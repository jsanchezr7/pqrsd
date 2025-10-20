<?php
// declare(strict_types=1);


// Prevent vendor deprecation notices from being printed to responses
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);

require_once "../extensiones/vendor/autoload.php";

// print_r($_SERVER['HTTP_REFERER']);
use Firebase\JWT\JWT;

if (isset($_SERVER['HTTP_REFERER'])){
	if ($_SERVER['HTTP_REFERER']==('https://aplicaciones.mab.com.co/pqr/vistas/login.php')||('http://52.232.166.142/vistas/login.php')){
		$secretKey  = ';[wR77BmCt"y~jXL7M:wu{cPV|-*IwtA2!d%_K..4t`i0;:&SjPtqFSeiW#`7?d';
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

		// Encode the array to a JWT string.
	    echo JWT::encode(
	        $data,
	        $secretKey,
	        'HS512'
	    );
	    
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
