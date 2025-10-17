<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

use Firebase\JWT\JWT;

require_once "../extensiones/vendor/autoload.php";
// use MicrosoftAzure\Storage\Common\Internal\Resources;
// use MicrosoftAzure\Storage\File\FileSharedAccessSignatureHelper;

use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Common\ServiceException;

use WindowsAzure\Blob\Models\SetBlobPropertiesOptions;
use WindowsAzure\Blob\Models\ListBlobsOptions;

// use WindowsAzure\Common\ServicesBuilder;

// echo json_encode($_SERVER['HTTP_AUTHORIZATION']);
if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
    header('HTTP/1.0 400 Bad Request');
    echo 'Token not found in request';
    exit;
}

$jwt = $matches[1];
if (! $jwt) {
    // No token was able to be extracted from the authorization header
    header('HTTP/1.0 400 Bad Request');
    exit;
}


$secretKey = null;
// Prefer the value defined in config/global.php, otherwise fall back to env
if (defined('JWT_SECRET_KEY') && JWT_SECRET_KEY) {
	$secretKey = JWT_SECRET_KEY;
} elseif (getenv('JWT_SECRET') !== false) {
	$secretKey = getenv('JWT_SECRET');
}
try {
	$token = JWT::decode($jwt, $secretKey, ['HS512']);
	unset($secretKey);
} catch (Exception $e) {
	echo $e->getMessage();
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
