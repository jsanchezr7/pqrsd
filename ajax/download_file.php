<?php 
ob_start();
session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

use Firebase\JWT\JWT;

require_once "../extensiones/vendor/autoload.php";
// echo json_encode($_SERVER['HTTP_AUTHORIZATION']);

$jwt = json_decode(json_encode($_SESSION['JWT']), true);

$secretKey = null;
if (defined('JWT_SECRET_KEY') && JWT_SECRET_KEY) {
	$secretKey = JWT_SECRET_KEY;
} elseif (getenv('JWT_SECRET') !== false) {
	$secretKey = getenv('JWT_SECRET');
}
try {
	$token = JWT::decode($jwt['JWT'], $secretKey, ['HS512']);
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
    		$file_name = rawurlencode($_GET['link']);
    		switch ($_GET['type']) {
    			case '1':
    				$contenedor = 'entrega';
    				break;

    			case '2':
    				$contenedor = 'pqr';
    				break;

    			case '3':
    				$contenedor = 'seguimiento';
    				break;
    		}
			// Try to download via SDK using AZURE_CONNECTION_STRING from config/global.php or env
			if (defined('AZURE_CONNECTION_STRING') && AZURE_CONNECTION_STRING) {
				try {
					$blobRestProxy = ServicesBuilder::getInstance()->createBlobService(AZURE_CONNECTION_STRING);
					$result = $blobRestProxy->getBlob($contenedor, $_GET['link']);
					$stream = $result->getContentStream();
					$content = stream_get_contents($stream);
				} catch (Exception $e) {
					error_log('Failed to download blob via SDK: ' . $e->getMessage());
					header('HTTP/1.1 500 Internal Server Error');
					exit;
				}
			} else {
				// Fall back to building a blob URL with SAS token (if available in config/global.php or env)
				if (defined('AZURE_BLOB_URL') && AZURE_BLOB_URL && defined('AZURE_SAS_TOKEN') && AZURE_SAS_TOKEN) {
					$url = rtrim(AZURE_BLOB_URL, '/') . '/' . $contenedor . '/' . $file_name . '?' . AZURE_SAS_TOKEN;
					$content = file_get_contents($url);
				} else {
					$blobUrl = getenv('AZURE_BLOB_URL');
					$sas = getenv('AZURE_SAS_TOKEN');
					if ($blobUrl && $sas) {
						$url = rtrim($blobUrl, '/') . '/' . $contenedor . '/' . $file_name . '?' . $sas;
						$content = file_get_contents($url);
					} else {
						error_log('No Azure configuration available to fetch blob');
						header('HTTP/1.1 500 Internal Server Error');
						exit;
					}
				}
			}
    		// echo 'https://almacenamientopqrsd.blob.core.windows.net/pqr/'.$file_name.'?sv=2021-12-02&ss=bfqt&srt=sco&sp=rwdlacupiytfx&se=2050-03-16T04:34:39Z&st=2023-03-15T20:34:39Z&spr=https&sig=z%2FlRkR1WNmrJ2DeND2mnG%2BClbTqctWcCViKNbWktRfg%3D';



			$link = '../Files/'.$file_name;
			file_put_contents($link, $content);

    	
    	if (file_exists($link)) {
	      	header('Content-Description: File Transfer');
	      	header('Content-Type: application/octet-stream');
	      	header('Content-Disposition: attachment; filename='.basename($link));
	      	header('Content-Transfer-Encoding: binary');
	      	header('Expires: 0');
	      	header('Cache-Control: must-revalidate');
	      	header('Pragma: public');
	      	header('Content-Length: ' . filesize($link));
	      	ob_clean();
	      	flush();
	      	readfile($link);
	      	unlink($link);

      	exit;
    	}

	}	
}
?>
