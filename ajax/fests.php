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
// Load config for JWT secret
require_once __DIR__ . "/../config/global.php";

// Extract token from Authorization header or request
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

$secretKey  = defined('JWT_SECRET_KEY') ? JWT_SECRET_KEY : ';[wR77BmCt"y~jXL7M:wu{cPV|-*IwtA2!d%_K..4t`i0;:&SjPtqFSeiW#`7?d';
try {
	$token = JWT::decode($jwt, $secretKey, ['HS512']);
	unset($secretKey);
} catch (Exception $e) {
	error_log('[fests.php] JWT decode error: ' . $e->getMessage());
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

		require_once "../modelo/Fests.php";

		$fests=new Fests();

		$id=isset($_POST["id"])? limpiarCadena($_POST["id"]):"";
		$fechas=isset($_POST["fechas"])? limpiarCadena($_POST["fechas"]):"";
		$fecha_fest=isset($_POST["fecha_fest"])? limpiarCadena($_POST["fecha_fest"]):"";



		switch ($_GET["op"]){
			case 'guardaryeditar':
					$rspta=$fests->insertar($fechas);
					echo $rspta ? json_encode(array("result"=>"Día registrado")) : json_encode(array("result"=>"El día no se pudo registar"));
			break;

			case 'editar':
					$rspta=$fests->editar($fecha_fest,$id);
					echo $rspta ? json_encode(array("result"=>"Día actualizado")) : json_encode(array("result"=>"El día no se pudo actualizar"));
			break;

			

			case 'mostrar':
				$rspta=$fests->mostrar($id);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;

			case 'listar':
				$anio = $_REQUEST['anio'];
				$rspta=$fests->listar($anio);
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				
		 				"0"=>$reg->date_fest,
		 				"1"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->id_fest .')"><i class="fas fa-pencil-alt"></i></button>'
		 				);
		 		}
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
		 			"aaData"=>$data);
		 		echo json_encode($results);

			break;

			case 'listar_bi':
				$rspta=$fests->listar_bi();
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				
		 				"0"=>$reg->tipo,
		 				"1"=>$reg->id_row,
		 				"2"=>$reg->tabla,
		 				"3"=>$reg->valor_alterado,
		 				"4"=>$reg->fecha,
		 				"5"=>$reg->nombre,
		 			);
		 		}
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
		 			"aaData"=>$data);
		 		echo json_encode($results);

			break;
		}
	}
}
?>