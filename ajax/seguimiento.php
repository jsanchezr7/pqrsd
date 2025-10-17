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

		require_once "../modelo/Seguimiento.php";
		require_once "upload.php";

		$seguimiento = new Seguimiento();

		
		$id = !empty($_POST["id"])? limpiarCadena($_POST["id"]):"";
		$id_pqr = !empty($_POST["id_pqr"])? limpiarCadena($_POST["id_pqr"]):"";
		$estado = !empty($_POST["estado"])? limpiarCadena($_POST["estado"]):0;
		$notas = !empty($_POST["notas"])? limpiarCadena($_POST["notas"]):"";
		$fecha = !empty($_POST["fecha"])? limpiarCadena($_POST["fecha"]):"";
		$filename = !empty($_POST["filename"])? limpiarCadena($_POST["filename"]):"";



		switch ($_GET["op"]){
			case 'guardaryeditar':
				$allFiles = array();

				if (empty($id)) {
					$id = $seguimiento->insertar($id_pqr, $estado, $notas, $fecha);
					$rspta = $id > 0 ? array("result"=>"Registro realizado") : array("result"=>"No se pudo registar");

				}else{
					$editar = $seguimiento->editar($id, $estado, $notas, $fecha);
					$rspta = !empty($editar) ? array("result"=>"Registro actualizado") : array("result"=>"No se pudo actualizar");
					if ($editar['archivos'] != '') {
						$allFiles = explode(", ", $editar['archivos']);
					}
				}

				if (count($_FILES) > 0) {
					
					foreach (array_keys($_FILES) as $field){
						$rspta_c = $seguimiento->count_files($id);
						$tmp_name = $_FILES[$field]["tmp_name"];

						$i = count(explode(", ", $rspta_c['archivos'])) + 1;
						$name_save = $id.'_'.$i.$_FILES[$field]["name"];

						$upload_file($tmp_name, $name_save, 'seguimiento');
						// echo $get_files_list();
						
						array_push($allFiles, $name_save);
					}

					$files = $seguimiento->save_files($allFiles, $id);
					$rspta['result'] .= $files ? '' : ' | No se pudieron guardar los archivos';
				}

					echo json_encode($rspta);

			break;


			case 'mostrar':
				$rspta = $seguimiento->mostrar($id);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;

			
			case 'listar':
				$estados_filtro = $_REQUEST['estados_filtro'];
				$fecha_crea = $_REQUEST['fecha_crea'];
				$fecha_ini = $_REQUEST['fecha_ini'];
				$fecha_fin = $_REQUEST['fecha_fin'];
				$ori_pqr = $_REQUEST['ori_pqr'];
				$sel_canal = $_REQUEST['sel_canal'];

				$rspta = $seguimiento->listar($estados_filtro,$fecha_crea,$fecha_ini,$fecha_fin,$ori_pqr,$sel_canal);
		 		//Vamos a declarar un array
		 		$data = Array();

		 		while ($reg = $rspta->fetch_object()){
		 			if ($_SESSION['Admin'] == 1){
			 			$data[] = array(
			 				
			 				"0"=>$reg->id_pqr2,
			 				"1"=>$reg->id_emb,
			 				"2"=>($reg->id_bte==0 || $reg->id_bte=='')?'<span class="label bg-red">No Guardado en BTE</span>':$reg->id_bte,
			 				"3"=>$reg->observaciones,
			 				"4"=>$reg->ori_pqr,
			 				"5"=>$reg->asunto_obs,
			 				"6"=>$reg->fecha_crea,
			 				"7"=>$reg->fecha_ley,
			 				"8"=>($reg->nombre_est=='Terminado')?$reg->nombre_est.' - '.$reg->fecha_cierre:$reg->nombre_est,
			 				"9"=>$reg->est_tra,
			 				"10"=>$reg->numeroDocumento,
			 				"11"=>$reg->primerNombre,
			 				"12"=>($reg->asign==1)?'<span class="label bg-green">Asignado a '.$reg->nombre.'</span>':'<span class="label bg-red">No Asignado</span>'
			 			);
		 			}else{
		 				$data[] = array(
			 				
			 				"0"=>$reg->id_pqr,
			 				"1"=>$reg->id_emb,
			 				"2"=>$reg->id_bte==0?'<span class="label bg-red">No Guardado en BTE</span>':$reg->id_bte,
			 				"3"=>$reg->observaciones,
			 				"4"=>$reg->ori_pqr,
			 				"5"=>$reg->asunto_obs,
			 				"6"=>$reg->fecha_crea,
			 				"7"=>$reg->fecha_ley,
			 				"8"=>($reg->nombre_est=='Terminado')?$reg->nombre_est.' - '.$reg->fecha_cierre:$reg->nombre_est,
			 				"9"=>$reg->est_tra,
			 				"10"=>$reg->numeroDocumento,
			 				"11"=>$reg->primerNombre,
			 				"12"=>($reg->asign==1)?'<span class="label bg-green">Asignado</span>':'<span class="label bg-red">No Asignado</span>'
			 			);
		 			}
		 		}
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
		 			"aaData"=>$data);
		 		echo json_encode($results);
			break;




			case 'listarHistorial':
				$id = $_REQUEST['id'];
				$rspta = $seguimiento->listarHistorial($id);
		 		//Vamos a declarar un array
		 		$data = Array();

		 		while ($reg = $rspta->fetch_object()){
		 			$archivos = explode(", ", $reg->archivos);

		 			$archivosField = "";

		 			for ($i=0; $i < count($archivos); $i++) { 
		 				$archivosField .= "<a href='../ajax/download_file.php?link=$archivos[$i]&type=3' target='_blank'>$archivos[$i]</a> ";
		 				if ($i+1 < count($archivos)) {
		 					$archivosField .= ' | ';
		 				}
		 			}

		 			$data[] = array(
		 				
		 				"0"=>$reg->nombre_est,
		 				"1"=>$archivosField,
		 				"2"=>$reg->notas,
		 				"3"=>$reg->fecha,
		 				"4"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->id.');" data-toggle="modal" data-target="#form_modal" title="Editar seguimiento"><i class="fas fa-pencil-alt"></i></button>'.'<button class="btn btn-danger" onclick="deleteSeg('.$reg->id.');" title="Eliminar seguimiento"><i class="fas fa-times"></i></button>'
		 			);
		 		}
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
		 			"aaData"=>$data);
		 		echo json_encode($results);

			break;




			case 'eliminarFile':

				try{
					echo $delete_file($filename, 'seguimiento');
					// echo $get_files_list();
				}catch(Error $e){
					echo $e;
				}

				$rspta = $seguimiento->eliminarFile($filename, $id);
				echo $rspta ? json_encode(array("result"=>"Archivo eliminado")) : json_encode(array("result"=>"No se pudo eliminar"));
			break;




			case 'deleteSeg':

				$rspta = $seguimiento->mostrar($id);
		 		$archivos = explode(", ", $rspta['archivos']);

		 		for ($i=0; $i < count($archivos); $i++) { 
					try{
						echo $delete_file($archivos[$i], 'seguimiento');
					}catch(Error $e){
						echo $e;
					}
				}

				$rspta = $seguimiento->deleteSeg($id);
				echo $rspta ? json_encode(array("result"=>"Seguimiento eliminado")) : json_encode(array("result"=>"No se pudo eliminar"));
			break;


		}
	}
}
?>