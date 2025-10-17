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


		require_once "../modelo/Select.php";

		$select=new Select();

		$localidad=isset($_POST["localidad"])? limpiarCadena($_POST["localidad"]):"";
		$codigoUpz=isset($_POST["codigoUpz"])? limpiarCadena($_POST["codigoUpz"]):"";
		$categoria=isset($_POST["categoria"])? limpiarCadena($_POST["categoria"]):"";
		$tipoPersonaJuridica=isset($_POST["tipoPersonaJuridica"])? limpiarCadena($_POST["tipoPersonaJuridica"]):"";
		$area=isset($_POST["area"])? limpiarCadena($_POST["area"]):"";

		switch ($_GET["op"]){

			case 'sel_canal_rec':
				$rspta = $select->sel_canal_rec();
				$html = '<option value="">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_canal . '>' . $reg->name_canal .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_upz':
				$rspta = $select->sel_upz($localidad);
				$html = '<option value="">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_upz . '>' . $reg->upz .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_barrio':
				$rspta = $select->sel_barrio($codigoUpz);
				$html = '<option value="">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_bar . '>' . $reg->barrio .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_subtema':
				$rspta = $select->sel_subtema($categoria);
				$html = '<option value="">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_sub . '>' . $reg->id_sub . ' - ' . $reg->subtema .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_area':
				$rspta = $select->sel_area();
				$html = '<option value="0">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_dep . '>' . $reg->depend .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_usu':
				$rspta = $select->sel_usu();
				$html = '<option value="0">Seleccione una opción</option><option value="all">No Asignado</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id . '>' . $reg->nombre .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_tipoid':
				$rspta = $select->sel_tipoid();
				$html = '<option value="">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_id . '>' . $reg->abreviatura .' - '. $reg->descripcion .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_genero':
				$rspta = $select->sel_genero();
				$html = '<option value="4">No brinda información</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_gen . '>' . $reg->genero .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_aten_pref':
				$rspta = $select->sel_aten_pref();
				$html = '';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id . '>' . $reg->nombre .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_juridica':
				$rspta = $select->sel_juridica();
				$html = '<option value="">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_jur . '>' . $reg->tipo_jur .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_user':
				$rspta = $select->sel_user($area);
				$html = '<option value="0">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id . '>' . $reg->nombre .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_categoria':
				$rspta = $select->sel_categoria();
				$html = '<option value="">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_cat . '>' . $reg->categoria .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_estado':
				$rspta = $select->sel_estado();
				$html = '<option value="0">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_estado . '>' . $reg->nombre_est .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_estado_tra':
				$rspta = $select->sel_estado_tra();
				$html = '<option value="0">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_estado . '>' . $reg->nombre_est .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_estado_int':
				$rspta = $select->sel_estado_int();
				$html = '<option value="0">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_estado . '>' . $reg->nombre_est .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_opcion_pet':
				$rspta = $select->sel_opcion_pet();
				$html = '';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_op . '>' . $reg->opcion .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_localidad':
				$rspta = $select->sel_localidad();
				$html = '<option value="">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_local . '>' . $reg->localidad .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_tipo_soli':
				$rspta = $select->sel_tipo_soli();
				$html = '<option value="">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_sol . '>' . $reg->nombre_sol .' - '. $reg->days .' días </option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_dependencia':
				$rspta = $select->sel_dependencia();
				$html = '<option value="">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_dep . '>' . $reg->depend .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_entidad_dist':
				$rspta = $select->sel_entidad_dist();
				$html = '<option value="">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id . '>' . $reg->entidad .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_tipoPersonaJuridica':
				$rspta = $select->sel_tipoPersonaJuridica($tipoPersonaJuridica);
				$html = '<option value="">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_ent . '>' . $reg->nombre .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_etnia':
				$rspta = $select->sel_etnia();
				$html = '';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_etnia . '>' . $reg->etnia .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'sel_idDiscapacidad':
				$rspta = $select->sel_idDiscapacidad();
				$html = '';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id . '>' . $reg->nombre .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;









			

			case 'sel_estado_seg':
				$rspta = $select->sel_estado_seg();
				$html = '<option value="0">Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_estado . '>' . $reg->nombre_est .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;




			

			
		}

	}
}
?>