<?php
// declare(strict_types=1);


// Do not output PHP warnings/notices to the response body (they break API consumers)
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);

require_once "../extensiones/vendor/autoload.php";

// print_r($_SERVER['HTTP_REFERER']);
use Firebase\JWT\JWT;

if (isset($_SERVER['HTTP_REFERER'])){
	if ($_SERVER['HTTP_REFERER']==('https://aplicaciones.mab.com.co/pqr/vistas/login.php')||('http://52.232.166.142/vistas/login.php')){
		// Prefer secret from config/global.php if present
		if (defined('JWT_SECRET_KEY') && JWT_SECRET_KEY) {
			$secretKey = JWT_SECRET_KEY;
		} else {
			$secretKey  = ';[wR77BmCt"y~jXL7M:wu{cPV|-*IwtA2!d%_K..4t`i0;:&SjPtqFSeiW#`7?d';
		}
		$issuedAt   = new DateTimeImmutable();
		$expire     = $issuedAt->modify('+60 minutes')->getTimestamp();      // Add 60 seconds
		$serverName = "http://localhost/";
		$username   = "username";                                           // Retrieved from filtered POST data

		$data = [
		    'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
		    'iss'  => $serverName,                       // Issuer
		    'nbf'  => $issuedAt->getTimestamp(),         // Not before
		    'exp'  => $expire,                           // Expire
		    'userName' => $username                 // User name
		];

		// Encode the array to a JWT string and ensure only the token is output
		// Use output buffering to capture any accidental notices and discard them
		ob_start();
		$jwt = JWT::encode($data, $secretKey, 'HS512');
		// clean any buffered output (warnings/notices) so only the token is returned
		ob_end_clean();
		echo $jwt;
		unset($secretKey);
	}else{
		echo('401 Unauthorized');
	  exit;
	}
}else{
	echo('401 Unauthorized');
  exit;
}
?>
