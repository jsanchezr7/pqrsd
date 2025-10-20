<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

use Firebase\JWT\JWT;

// Prevent vendor deprecation notices from being printed to responses
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);

require_once "../extensiones/vendor/autoload.php";
// use MicrosoftAzure\Storage\Common\Internal\Resources;
// use MicrosoftAzure\Storage\File\FileSharedAccessSignatureHelper;

use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Common\ServiceException;

use WindowsAzure\Blob\Models\SetBlobPropertiesOptions;
use WindowsAzure\Blob\Models\ListBlobsOptions;

// use WindowsAzure\Common\ServicesBuilder;

// Load config for JWT secret
require_once __DIR__ . "/../config/global.php";

// Extract token from Authorization header or request body
$authHeader = null;
if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
	$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
} elseif (isset($_SERVER['Authorization'])) {
	$authHeader = $_SERVER['Authorization'];
} elseif (function_exists('getallheaders')) {
	$headers = getallheaders();
	if (isset($headers['Authorization'])) {
		$authHeader = $headers['Authorization'];
	} elseif (isset($headers['authorization'])) {
		$authHeader = $headers['authorization'];
	}
} elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
	$authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
}

$jwt = null;
if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
	$jwt = $matches[1];
}

if (! $jwt && isset($_REQUEST['token'])) {
	$jwt = $_REQUEST['token'];
}

if (! $jwt) {
	$raw = file_get_contents('php://input');
	$json = @json_decode($raw, true);
	if (is_array($json) && isset($json['token'])) {
		$jwt = $json['token'];
	}
}

if (! $jwt) {
	header('HTTP/1.0 400 Bad Request');
	echo json_encode(array('error' => 'Token not found in request'));
	exit;
}

$secretKey = defined('JWT_SECRET_KEY') ? JWT_SECRET_KEY : (getenv('JWT_SECRET') !== false ? getenv('JWT_SECRET') : null);
try {
	$token = JWT::decode($jwt, $secretKey, ['HS512']);
	unset($secretKey);
} catch (Exception $e) {
	error_log('[upload.php] JWT decode error: ' . $e->getMessage());
	header('HTTP/1.1 401 Unauthorized');
	echo json_encode(array('error' => 'Signature verification failed'));
	exit;
}

$now = new DateTimeImmutable();
$serverName = "http://localhost/";

if (isset($token)) {
	
    if ($token->iss !== $serverName ||
        $token->nbf > $now->getTimestamp() ||
        $token->exp < $now->getTimestamp())
    {
        header('HTTP/1.1 401 Unauthorized');
        exit;
    }else{




		$upload_file = function($file, $fileName, $containerName){

			 # Setup a specific instance of an Azure::Storage::Client
			// Use AZURE_CONNECTION_STRING from config/global.php or env if available
			if (defined('AZURE_CONNECTION_STRING') && AZURE_CONNECTION_STRING) {
				$connectionString = AZURE_CONNECTION_STRING;
			} else {
				$connectionString = getenv('AZURE_STORAGE_CONNECTION_STRING');
			}
			// Create blob client.
			$blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);

		    $handle = @fopen($file, "r");
		    if($handle)
		    {
		        try
		        {
		        	// $exists = $blobRestProxy->getBlobMetadata($containerName, $fileName);

		        	// if (!empty($exists)) {
		            // 	$baseName = substr($fileName, 0, strrpos($fileName, "."));
					// 	$extension = substr($fileName, strrpos($fileName, "."));
					// 	$i = 1;


					// 	while (!empty($exists)) {
					// 	    $fileName = $baseName . "_" . $i . $extension;
					// 	    $exists = $blobRestProxy->getBlobMetadata($containerName, $fileName);
					// 	    $i++;

					// 	    if (is_null($exists) || $exists === false) {
					// 	        break;
					// 	    }
					// 	}
	            	// }
					 
					$blobRestProxy->createBlockBlob($containerName, $fileName, $handle);
					
		        } catch ( Exception $e ) {
		            error_log("Failed to upload file '".$file."' to storage: ". $e);
		        }

		        // echo 'subido';
		        @fclose($handle);
		        return true;
		    } else {
		        error_log("Failed to open file '".$file."' to upload to storage.");
		        return false;
    		}


		};



		$upload_file64 = function($base64, $fileName, $containerName){

			 # Setup a specific instance of an Azure::Storage::Client
			if (defined('AZURE_CONNECTION_STRING') && AZURE_CONNECTION_STRING) {
				$connectionString = AZURE_CONNECTION_STRING;
			} else {
				$connectionString = getenv('AZURE_STORAGE_CONNECTION_STRING');
			}
			// Create blob client.
			$blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);

		   
		        try
		        {
		            $blobRestProxy->createBlockBlob($containerName, $fileName, $base64);
		        } catch ( Exception $e ) {
		            error_log("Failed to upload file '".$fileName."' to storage: ". $e);
		        }

		        return true;
		    


		};




		$get_files_list = function(){
			try {
			    $containerName = "seguimiento";
				if (defined('AZURE_CONNECTION_STRING') && AZURE_CONNECTION_STRING) {
					$connectionString = AZURE_CONNECTION_STRING;
				} else {
					$connectionString = getenv('AZURE_STORAGE_CONNECTION_STRING');
				}
				$blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);
			    $nextMarker = "";
			    $counter = 0;
			do {
			    $listBlobsOptions = new ListBlobsOptions();
			    $listBlobsOptions->setMarker($nextMarker);
			    $blob_list = $blobRestProxy->listBlobs($containerName, $listBlobsOptions);
			    $blobs = $blob_list->getBlobs();
			    $nextMarker = $blob_list->getNextMarker();
			    foreach($blobs as $blob) {
			        echo $blob->getUrl()."\n";
			        $counter = $counter + 1;
			    }
			    echo "NextMarker = ".$nextMarker."\n";
			    echo "Files Fetched = ".$counter."\n";
			} while ($nextMarker != "");
			echo "Total Files: ".$counter."\n";
			}
			catch(Exception $e){
			$code = $e->getCode();
			    $error_message = $e->getMessage();
			    echo $code.": ".$error_message."<br />";
			}  
		};




		$delete_file = function($fileName, $containerName){

			 # Setup a specific instance of an Azure::Storage::Client
			if (defined('AZURE_CONNECTION_STRING') && AZURE_CONNECTION_STRING) {
				$connectionString = AZURE_CONNECTION_STRING;
			} else {
				$connectionString = getenv('AZURE_STORAGE_CONNECTION_STRING');
			}
			// Create blob client.
			$blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);

		        try{
		            // $blobRestProxy->deleteBlob($containerName, $fileName);
		            $blobRestProxy->deleteBlob($containerName, $fileName);
		        } catch ( Exception $e ) {
		            error_log("Failed to delete file '".$fileName."' to storage: ". $e);
		        }
		};





		$get_file = function($fileName, $containerName){
			$file_name = rawurlencode($fileName);
			// Prefer using the Azure SDK (connection string). If not available, fall back to AZURE_BLOB_URL + SAS token.
			if (defined('AZURE_CONNECTION_STRING') && AZURE_CONNECTION_STRING) {
				try {
					$blobRestProxy = ServicesBuilder::getInstance()->createBlobService(AZURE_CONNECTION_STRING);
					$result = $blobRestProxy->getBlob($containerName, $fileName);
					$stream = $result->getContentStream();
					return stream_get_contents($stream);
				} catch (Exception $e) {
					error_log('Failed to get blob via SDK: ' . $e->getMessage());
					return false;
				}
			}

			// Build a URL using AZURE_BLOB_URL + AZURE_SAS_TOKEN if available
			if (defined('AZURE_BLOB_URL') && AZURE_BLOB_URL && defined('AZURE_SAS_TOKEN') && AZURE_SAS_TOKEN) {
				$url = rtrim(AZURE_BLOB_URL, '/') . '/' . $containerName . '/' . $file_name . '?' . AZURE_SAS_TOKEN;
				return file_get_contents($url);
			}

			// Last resort: try env vars
			$blobUrl = getenv('AZURE_BLOB_URL');
			$sas = getenv('AZURE_SAS_TOKEN');
			if ($blobUrl && $sas) {
				$url = rtrim($blobUrl, '/') . '/' . $containerName . '/' . $file_name . '?' . $sas;
				return file_get_contents($url);
			}

			error_log('No Azure configuration available to fetch blob');
			return false;
		};





	}	
}
?>
