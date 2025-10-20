<?php
// declare(strict_types=1);


// Prevent vendor deprecation notices from being printed to responses
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);

require_once "../extensiones/vendor/autoload.php";
use Firebase\JWT\JWT;

// Load config which defines JWT_SECRET_KEY
require_once __DIR__ . "/../config/global.php";

if (! isset($_SERVER['HTTP_REFERER'])){
	header('HTTP/1.1 401 Unauthorized');
	echo json_encode(array('error' => 'Unauthorized'));
	exit;
}

$allowed_refs = array('https://aplicaciones.mab.com.co/pqr/vistas/login.php', 'http://52.232.166.142/vistas/login.php');
if (! in_array($_SERVER['HTTP_REFERER'], $allowed_refs)){
	header('HTTP/1.1 401 Unauthorized');
	echo json_encode(array('error' => 'Unauthorized'));
	exit;
}

// Use configured secret key (fallback to legacy literal if missing)
$secretKey = defined('JWT_SECRET_KEY') ? JWT_SECRET_KEY : ';[wR77BmCt"y~jXL7M:wu{cPV|-*IwtA2!d%_K..4t`i0;:&SjPtqFSeiW#`7?d';
$issuedAt   = new DateTimeImmutable();
$expire     = $issuedAt->modify('+60 minutes')->getTimestamp();
$serverName = "http://localhost/";
$username   = "username"; // Retrieved from filtered POST data

$data = [
	'iat'  => $issuedAt->getTimestamp(),
	'iss'  => $serverName,
	'nbf'  => $issuedAt->getTimestamp(),
	'exp'  => $expire,
	'userName' => $username
];

// Encode the array to a JWT string and return only the token
echo JWT::encode($data, $secretKey, 'HS512');
unset($secretKey);
?>
