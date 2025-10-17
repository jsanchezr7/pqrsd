<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

use Firebase\JWT\JWT;

require_once "../extensiones/vendor/autoload.php";
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

$secretKey  = ';[wR77BmCt"y~jXL7M:wu{cPV|-*IwtA2!d%_K..4t`i0;:&SjPtqFSeiW#`7?d';
try {
	$token = JWT::decode($jwt, $secretKey, ['HS512']);
		unset($secretKey); 
}catch(Exception $e){
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