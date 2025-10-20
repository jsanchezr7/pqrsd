<?php 

ini_set("memory_limit", -1);


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Firebase\JWT\JWT;

// Prevent vendor deprecation notices from being printed to responses
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);

require_once "../extensiones/vendor/autoload.php";
// Load project config (contains JWT_SECRET_KEY)
require_once __DIR__ . "/../config/global.php";

// Robust extraction of Authorization header / token from multiple sources
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

// Fallbacks: token in request parameters or raw JSON body
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

// Prefer configured secret key; keep legacy literal as a last-resort fallback
$secretKey = defined('JWT_SECRET_KEY') ? JWT_SECRET_KEY : ';[wR77BmCt"y~jXL7M:wu{cPV|-*IwtA2!d%_K..4t`i0;:&SjPtqFSeiW#`7?d';
try {
	$token = JWT::decode($jwt, $secretKey, ['HS512']);
	unset($secretKey);
} catch (Exception $e) {
	// Log the detailed error server-side, but don't echo library messages to clients
	error_log("[pqr.php] JWT decode error: " . $e->getMessage());
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

		require_once "../modelo/Pqr.php";
		require_once "upload.php";
		$pqr=new Pqr();

		$id_bte=isset($_POST["id_bte"])? limpiarCadena($_POST["id_bte"]):"";
		$id_emb=isset($_POST["id_emb"])? limpiarCadena($_POST["id_emb"]):"";
		$id_ciu=isset($_POST["id_ciu"])? ($_POST["id_ciu"]==''?0:limpiarCadena($_POST["id_ciu"])):0;
		$anonim=isset($_POST["anonim"])? limpiarCadena($_POST["anonim"]):0;
		$codigoOpcion=isset($_POST["codigoOpcion"])? ($_POST["codigoOpcion"]==''?0:limpiarCadena($_POST["codigoOpcion"])):0;
		$codigoTipoRequerimiento=isset($_POST["codigoTipoRequerimiento"])? limpiarCadena($_POST["codigoTipoRequerimiento"]):"";
		$asunto_obs=isset($_POST["asunto_obs"])? limpiarCadena($_POST["asunto_obs"]):"";
		$codigoDependencia=isset($_POST["codigoDependencia"])? limpiarCadena($_POST["codigoDependencia"]):"";
		$codigoProcesoCalidad=isset($_POST["codigoProcesoCalidad"])? ($_POST["codigoProcesoCalidad"]==''?0:limpiarCadena($_POST["codigoProcesoCalidad"])):0;
		$codigoCanal=isset($_POST["codigoCanal"])? limpiarCadena($_POST["codigoCanal"]):"";
		$codigoRedSocial=isset($_POST["codigoRedSocial"])? ($_POST["codigoRedSocial"]==''?0:limpiarCadena($_POST["codigoRedSocial"])):0;
		$numeroRadicado=isset($_POST["numeroRadicado"])? limpiarCadena($_POST["numeroRadicado"]):"";
		$fechaRadicado=isset($_POST["fechaRadicado"])? ($_POST["fechaRadicado"]==''?"2000-01-01":limpiarCadena($_POST["fechaRadicado"])):"2000-01-01";
		$numeroFolios=isset($_POST["numeroFolios"])? ($_POST["numeroFolios"]==''?0:limpiarCadena($_POST["numeroFolios"])):0;
		$observaciones=isset($_POST["observaciones"])? limpiarCadena($_POST["observaciones"]):"";
		$codigoLocalidadPeticion=isset($_POST["codigoLocalidadPeticion"])? limpiarCadena($_POST["codigoLocalidadPeticion"]):"";
		$codigoUpzPeticion=isset($_POST["codigoUpzPeticion"])? limpiarCadena($_POST["codigoUpzPeticion"]):0;
		$codigoBarrioPeticion=isset($_POST["codigoBarrioPeticion"])? limpiarCadena($_POST["codigoBarrioPeticion"]):0;
		$codigoEstratoPeticion=isset($_POST["codigoEstratoPeticion"])? limpiarCadena($_POST["codigoEstratoPeticion"]):0;
		$direccionHechos=isset($_POST["direccionHechos"])? limpiarCadena($_POST["direccionHechos"]):"";
		$ubicacionAproximada=isset($_POST["ubicacionAproximada"])? limpiarCadena($_POST["ubicacionAproximada"]):"";
		$fecha_crea=isset($_POST["fecha_crea"])? ($_POST["fecha_crea"]==''?"2000-01-01":limpiarCadena($_POST["fecha_crea"])):"2000-01-01";
		$group_int=isset($_POST["group_int"])? limpiarCadena($_POST["group_int"]):"";
		$ori_pqr=isset($_POST["ori_pqr"])? limpiarCadena($_POST["ori_pqr"]):"";
		$estado=isset($_POST["estado"])? limpiarCadena($_POST["estado"]):"";
		$estado_tra=isset($_POST["estado_tra"])? limpiarCadena($_POST["estado_tra"]):"";
		$fecha_asigna=isset($_POST["fecha_asigna"])? ($_POST["fecha_asigna"]==''?"2000-01-01":limpiarCadena($_POST["fecha_asigna"])):"2000-01-01";
		$fecha_ley=isset($_POST["fecha_ley"])? ($_POST["fecha_ley"]==''?"2000-01-01":limpiarCadena($_POST["fecha_ley"])):"2000-01-01";
		$tieneProcedencia=isset($_POST["tieneProcedencia"])? limpiarCadena($_POST["tieneProcedencia"]):0;
		$esCopia=isset($_POST["esCopia"])? limpiarCadena($_POST["esCopia"]):0;




		$id_rep=isset($_POST["id_rep"])? $_POST["id_rep"]:[];
		$codigoTipoIdentificacionRep=isset($_POST["codigoTipoIdentificacionRep"])? $_POST["codigoTipoIdentificacionRep"]:[];
		$numeroIdentificacionRep=isset($_POST["numeroIdentificacionRep"])? $_POST["numeroIdentificacionRep"]:[];
		$telefonoRep=isset($_POST["telefonoRep"])? $_POST["telefonoRep"]:[];
		$primerApellidoRep=isset($_POST["primerApellidoRep"])? $_POST["primerApellidoRep"]:[];
		$segundoApellidoRep=isset($_POST["segundoApellidoRep"])? $_POST["segundoApellidoRep"]:[];
		$primerNombreRep=isset($_POST["primerNombreRep"])? $_POST["primerNombreRep"]:[];
		$segundoNombreRep=isset($_POST["segundoNombreRep"])? $_POST["segundoNombreRep"]:[];



		$id_pod=isset($_POST["id_pod"])? $_POST["id_pod"]:[];
		$codigoTipoIdentificacionPod=isset($_POST["codigoTipoIdentificacionPod"])? $_POST["codigoTipoIdentificacionPod"]:[];
		$numeroIdentificacionPod=isset($_POST["numeroIdentificacionPod"])? $_POST["numeroIdentificacionPod"]:[];
		$telefonoPod=isset($_POST["telefonoPod"])? $_POST["telefonoPod"]:[];
		$primerApellidoPod=isset($_POST["primerApellidoPod"])? $_POST["primerApellidoPod"]:[];
		$segundoApellidoPod=isset($_POST["segundoApellidoPod"])? $_POST["segundoApellidoPod"]:[];
		$primerNombrePod=isset($_POST["primerNombrePod"])? $_POST["primerNombrePod"]:[];
		$segundoNombrePod=isset($_POST["segundoNombrePod"])? $_POST["segundoNombrePod"]:[];




		$id=isset($_POST["id"])? limpiarCadena($_POST["id"]):"";
		$doc=isset($_POST["doc"])? limpiarCadena($_POST["doc"]):"";



		$primerNombre=isset($_POST["primerNombre"])? limpiarCadena($_POST["primerNombre"]):"";
		$segundoNombre=isset($_POST["segundoNombre"])? limpiarCadena($_POST["segundoNombre"]):"";
		$primerApellido=isset($_POST["primerApellido"])? limpiarCadena($_POST["primerApellido"]):"";
		$segundoApellido=isset($_POST["segundoApellido"])? limpiarCadena($_POST["segundoApellido"]):"";
		$direccionResidencia=isset($_POST["direccionResidencia"])? limpiarCadena($_POST["direccionResidencia"]):"";
		$codigoTipoIdentificacion=isset($_POST["codigoTipoIdentificacion"])? limpiarCadena($_POST["codigoTipoIdentificacion"]):"";
		$numeroDocumento=isset($_POST["numeroDocumento"])? limpiarCadena($_POST["numeroDocumento"]):"";
		$idAtencionPreferencial=isset($_POST["idAtencionPreferencial"])? limpiarCadena($_POST["idAtencionPreferencial"]):"";
		$telefonoFijo=isset($_POST["telefonoFijo"])? limpiarCadena($_POST["telefonoFijo"]):"";
		$celular=isset($_POST["celular"])? limpiarCadena($_POST["celular"]):"";
		$nombreCompletoContacto=isset($_POST["nombreCompletoContacto"])? limpiarCadena($_POST["nombreCompletoContacto"]):"";
		$idTipoPersona=isset($_POST["idTipoPersona"])? limpiarCadena($_POST["idTipoPersona"]):"";
		$correoElectronico=isset($_POST["correoElectronico"])? limpiarCadena($_POST["correoElectronico"]):"";
		$fechaNacimiento=isset($_POST["fechaNacimiento"])? ($_POST["fechaNacimiento"]==''?"2000-01-01":limpiarCadena($_POST["fechaNacimiento"])):"2000-01-01";
		$idGenero=isset($_POST["idGenero"])? limpiarCadena($_POST["idGenero"]):"";
		$entidadDistrital=isset($_POST["entidadDistrital"])? limpiarCadena($_POST["entidadDistrital"]):0;
		$localidad=isset($_POST["localidad"])? limpiarCadena($_POST["localidad"]):"";
		$codigoUpz=isset($_POST["codigoUpz"])? limpiarCadena($_POST["codigoUpz"]):"";
		$idBarrio=isset($_POST["idBarrio"])? limpiarCadena($_POST["idBarrio"]):"";
		$idEstrato=isset($_POST["idEstrato"])? limpiarCadena($_POST["idEstrato"]):"";
		$pbx=isset($_POST["pbx"])? limpiarCadena($_POST["pbx"]):"";
		$direccionResidenciaContacto=isset($_POST["direccionResidenciaContacto"])? limpiarCadena($_POST["direccionResidenciaContacto"]):"";
		$correoElectronicoContacto=isset($_POST["correoElectronicoContacto"])? limpiarCadena($_POST["correoElectronicoContacto"]):"";
		$telefonoFijoContacto=isset($_POST["telefonoFijoContacto"])? limpiarCadena($_POST["telefonoFijoContacto"]):"";
		$celularContacto=isset($_POST["celularContacto"])? limpiarCadena($_POST["celularContacto"]):"";
		$cargoContacto=isset($_POST["cargoContacto"])? limpiarCadena($_POST["cargoContacto"]):"";
		$notificacionFisica=isset($_POST["notificacionFisica"])? ($_POST["notificacionFisica"]==''?0:1):0;
		$notificacionElectronica=isset($_POST["notificacionElectronica"])? limpiarCadena($_POST["notificacionElectronica"]):0;
		$codigoPostal=isset($_POST["codigoPostal"])? limpiarCadena($_POST["codigoPostal"]):"";
		$tipoPersonaJuridica=isset($_POST["tipoPersonaJuridica"])? limpiarCadena($_POST["tipoPersonaJuridica"]):"";
		$codigoEntidadPrivada=isset($_POST["codigoEntidadPrivada"])? ($_POST["codigoEntidadPrivada"]==''?0:limpiarCadena($_POST["codigoEntidadPrivada"])):0;
		$codigoTipoEntidad=isset($_POST["codigoTipoEntidad"])? limpiarCadena($_POST["codigoTipoEntidad"]):0;
		$idEtnia=isset($_POST["idEtnia"])? limpiarCadena($_POST["idEtnia"]):0;
		$idDiscapacidad=isset($_POST["idDiscapacidad"])? ($_POST["idDiscapacidad"]==''?0:limpiarCadena($_POST["idDiscapacidad"])):0;
		$idOrientacionSexual=isset($_POST["idOrientacionSexual"])? ($_POST["idOrientacionSexual"]==''?0:limpiarCadena($_POST["idOrientacionSexual"])):0;
		$idEmbarazo=isset($_POST["idEmbarazo"])? ($_POST["idEmbarazo"]==''?0:limpiarCadena($_POST["idEmbarazo"])):0;
		$adultoMayor=isset($_POST["adultoMayor"])? limpiarCadena($_POST["adultoMayor"]):"";
		$digitoVerificacion=isset($_POST["digitoVerificacion"])? limpiarCadena($_POST["digitoVerificacion"]):"";
		$type_edit=isset($_POST["type_edit"])? limpiarCadena($_POST["type_edit"]):"";




		$id_pqr=isset($_POST["id_pqr"])? limpiarCadena($_POST["id_pqr"]):"";
		$date_respc=isset($_POST["date_respc"])? limpiarCadena($_POST["date_respc"]):"";
		$rad_respc=isset($_POST["rad_respc"])? limpiarCadena($_POST["rad_respc"]):"";
		$categoria=isset($_POST["categoria"])? limpiarCadena($_POST["categoria"]):0;
		$subtema=isset($_POST["subtema"])? limpiarCadena($_POST["subtema"]):0;
		$con=isset($_POST["con"])? limpiarCadena($_POST["con"]):"";

		$id_pqr2=isset($_POST["id_pqr2"])? limpiarCadena($_POST["id_pqr2"]):"";
		$observa_view=isset($_POST["observa_view"])? limpiarCadena($_POST["observa_view"]):"";
		$con2=isset($_POST["con2"])? limpiarCadena($_POST["con2"]):"";
		$estado_view=isset($_POST["estado_view"])? limpiarCadena($_POST["estado_view"]):"";
		$id_ent=isset($_POST["id_ent"])? limpiarCadena($_POST["id_ent"]):"";
		$type_sub=isset($_POST["type_sub"])? limpiarCadena($_POST["type_sub"]):"";

		$people=isset($_POST["people"])? $_POST["people"]:[];
		$area=isset($_POST["area"])? limpiarCadena($_POST["area"]):"";
		$id_pqrm=isset($_POST["id_pqrm"])? limpiarCadena($_POST["id_pqrm"]):"";
		$observa_asign=isset($_POST["observa_asign"])? limpiarCadena($_POST["observa_asign"]):"";




		$link=isset($_POST["link"])? limpiarCadena($_POST["link"]):"";




		switch ($_GET["op"]){

			case 'guardaryeditar_ciu':
			if ($anonim==0) {
				$id_per_bte = 0;
				
				if (empty($id_ciu)){
					$id_rspta=$pqr->insertar_ciu($id_per_bte,$primerNombre,$segundoNombre,$primerApellido,$segundoApellido,$direccionResidencia,$codigoTipoIdentificacion,$numeroDocumento,$idAtencionPreferencial,$telefonoFijo,$celular,$nombreCompletoContacto,$idTipoPersona,$correoElectronico,$fechaNacimiento,$idGenero,$entidadDistrital,$localidad,$codigoUpz,$idBarrio,$idEstrato,$pbx,$direccionResidenciaContacto,$correoElectronicoContacto,$telefonoFijoContacto,$celularContacto,$cargoContacto,$notificacionFisica,$notificacionElectronica,$codigoPostal,$tipoPersonaJuridica,$codigoEntidadPrivada,$codigoTipoEntidad,$idEtnia,$idDiscapacidad,$idOrientacionSexual,$idEmbarazo,$adultoMayor,$digitoVerificacion);
					
					echo $id_rspta!=0 ? json_encode(array('message'=>"Usuario registrado localmente",'type'=>1,"ciudadano"=>$id_rspta)) : json_encode(array('message'=>"No se pudo registrar el usuario localmente",'type'=>0));
				}else{
					$id_rspta=$pqr->editar_ciu($id_ciu,$id_per_bte,$primerNombre,$segundoNombre,$primerApellido,$segundoApellido,$direccionResidencia,$codigoTipoIdentificacion,$numeroDocumento,$idAtencionPreferencial,$telefonoFijo,$celular,$nombreCompletoContacto,$idTipoPersona,$correoElectronico,$fechaNacimiento,$idGenero,$entidadDistrital,$localidad,$codigoUpz,$idBarrio,$idEstrato,$pbx,$direccionResidenciaContacto,$correoElectronicoContacto,$telefonoFijoContacto,$celularContacto,$cargoContacto,$notificacionFisica,$notificacionElectronica,$codigoPostal,$tipoPersonaJuridica,$codigoEntidadPrivada,$codigoTipoEntidad,$idEtnia,$idDiscapacidad,$idOrientacionSexual,$idEmbarazo,$adultoMayor,$digitoVerificacion);
					echo $id_rspta!=0 ? json_encode(array('message'=>"Usuario actualizado correctamente en base de datos local",'type'=>1,"ciudadano"=>$id_ciu)) : json_encode(array('message'=>"No se pudo actualizar el usuario",'type'=>0));
				}


			}

			break;




			case 'guardaryeditar_pqr':

				$WSDL = 'https://sdqs.bogota.gov.co/sdqs/servicios/RadicacionPorCanalService?wsdl';
			  	$options = array(
			      	'login' => 'sweb79',
			      	'password' => 'Metro2022!',
			      	'cache_wsdl' => 0,
			      	'trace' => 1,
			      	'stream_context' => stream_context_create(array(
			        	'ssl' => array(
			          		'verify_peer' => false,
			          		'verify_peer_name' => false,
			          		'allow_self_signed' => true
		        		)
			      	))
			  	);
			  	$soapclient = new SoapClient($WSDL, $options);
				$id_bte_res=0;

				if (empty($id)){
					$response = 0;
					// echo $id_bte_res;

					$id_rspta=$pqr->insertar($id_bte_res,$id_emb,$id_ciu,$codigoOpcion,$codigoTipoRequerimiento,$tieneProcedencia,$esCopia,$asunto_obs,$codigoDependencia,$codigoProcesoCalidad,$codigoCanal,$codigoRedSocial,$numeroRadicado,$fechaRadicado,$numeroFolios,$observaciones,$codigoLocalidadPeticion,$codigoUpzPeticion,$codigoBarrioPeticion,$codigoEstratoPeticion,$direccionHechos,$ubicacionAproximada,$fecha_crea,$group_int,$ori_pqr,$estado,$estado_tra,$fecha_asigna,$fecha_ley,$notificacionFisica,$notificacionElectronica,$anonim,$codigoTipoIdentificacionRep,$numeroIdentificacionRep,$telefonoRep,$primerApellidoRep,$segundoApellidoRep,$primerNombreRep,$segundoNombreRep,$codigoTipoIdentificacionPod,$numeroIdentificacionPod,$telefonoPod,$primerApellidoPod,$segundoApellidoPod,$primerNombrePod,$segundoNombrePod,$idAtencionPreferencial,$correoElectronicoContacto,$nombreCompletoContacto,$telefonoFijoContacto,$celularContacto,$direccionResidenciaContacto);
						

					foreach (array_keys($_FILES) as $field){

						$rspta_c=$pqr->count_files($id_rspta);
						$i = $rspta_c['num']+1;
						// $_dir = '../Files/pqr';
						$tmp_name = $_FILES[$field]["tmp_name"];
						$name_save = $id_rspta.'_'.$i.$_FILES[$field]["name"];
						$ext = strtolower(pathinfo($_FILES[$field]["name"],PATHINFO_EXTENSION));

						$upload_file($tmp_name, $name_save, 'pqr');


						// if(move_uploaded_file($tmp_name, "$_dir/$name_save")){
						// 	$link = '../Files/pqr/'.$name_save;
						// }else{
						// 	echo '(No se pudo guardar el archivo) ';
						// }

						$ver = 2;
						$rspta=$pqr->entrega_con($id_rspta,$name_save,$ver);

					}

					echo $id_rspta!=0 ? json_encode(array('message'=>"Petición registrada localmente",'type'=>1,"id_pqr"=>$id_rspta)) : json_encode(array('message'=>"No se pudo registar la petición localmente",'type'=>0, 'error'=>''));

				}else {

					$message_rspta = array();

					if ($type_edit==1) {
						if ($anonim==1) {
							$parameters_pet = array('datos' => array(
							  'codigoTipoIdentificacionPeticionario'=>'',   
							  'numeroIdentificacionPeticionario'=>'',        
							  'asunto'=>$asunto_obs,
							  'idOpcion'=>1,
							  'idTema'=>12,           
							  'idTipoPeticion'=>$codigoTipoRequerimiento,	
							  'idCanal'=>$codigoCanal,
					  		  'numeroRadicado'=>$numeroRadicado,
							  'codigoCiudad'=>11001,
							  'codigoEntidad'=>2212,
					  		  'idTipoTramite'=>1951,
					  		  'codigoDependencia'=>$codigoDependencia,
					  		  'codigoProcesoCalidad'=>$codigoProcesoCalidad,
							  'idPuntoAtencion'=>816,
							  'idRedSocial'=>$codigoRedSocial,
					  		  'numeroRadicado'=>$numeroRadicado,
					  		  'fechaRadicado'=>$fechaRadicado,
					  		  'numeroFolios'=>$numeroFolios,
					  		  'tieneProcedencia'=>$tieneProcedencia,
					  		  'esCopia'=>$esCopia,
					  		  'observaciones'=>$observaciones,             
							  'codigoPais'=>169,
							  'codigoDepartamento'=>11,
							  'codigoCiudad'=>11001,   
					  		  'idBarrio'=>$codigoBarrioPeticion,
					  		  'idEstrato'=>$codigoEstratoPeticion,
					  		  'direccionHechos'=>$direccionHechos,
					  		  'ubicacionAproximada'=>$ubicacionAproximada,
					  		  'correoElectronicoContacto'=>$correoElectronicoContacto,
					  		  'idAtencionPreferencial'=>$idAtencionPreferencial,
					  		  'nombreCompletoContacto'=>$nombreCompletoContacto,
					  		  'telefonoFijoContacto'=>$telefonoFijoContacto,
					  		  'celularContacto'=>$celularContacto,
					  		  'notificacionElectronica'=>$notificacionElectronica,
					  		  'notificacionFisica'=>$notificacionFisica,
					  		  'direccionResidenciaContacto'=>$direccionResidenciaContacto

							));

							$notificacionElectronica==1?$parameters_pet['datos']['confirmacionEmail'] = 1:0;

							
							foreach (array_keys($_FILES) as $field){
								$rspta_c=$pqr->count_files($id);
								$i = $rspta_c['num']+1;
								// $_dir = '../Files/pqr';
								$tmp_name = $_FILES[$field]["tmp_name"];
								$name_save = $id.'_'.$i.$_FILES[$field]["name"];
								$ext = strtolower(pathinfo($_FILES[$field]["name"],PATHINFO_EXTENSION));

								$upload_file($tmp_name, $name_save, 'pqr');


								// if(move_uploaded_file($tmp_name, "$_dir/$name_save")){
								// 	$link = '../Files/pqr/'.$name_save;
								// }else{
								// 	echo '(No se pudo guardar el archivo) ';
								// }

								$ver = 2;
								$rspta=$pqr->entrega_con($id,$name_save,$ver);
							}

							$sql_files = "SELECT * FROM `entregas` WHERE `id_pqr`='$id' AND `perfil_en`=2";
							$result_files = ejecutarConsulta($sql_files);

							while($row = mysqli_fetch_array($result_files)){

						    	$ext = pathinfo($row['archivo'], PATHINFO_EXTENSION);
						        $path=$get_file($row['archivo'], 'pqr');
								$base64=base64_encode($path);
								$sqlext="SELECT * FROM `doctype` WHERE `nombre` LIKE '%$ext%'";
								echo $sqlext;
								$resultext=ejecutarConsultaSimpleFila($sqlext);
								$tipo_file = $resultext['id_doc'];
								$parameters_pet['datos']['documentos'][] =  array('contenidoDocumento'=>$base64,'codigoTipoArchivo'=>$tipo_file,'nombreArchivo'=>$row['archivo']);
						    }


							$response = $soapclient->registrarPeticion($parameters_pet);
							$array_pet = json_decode(json_encode($response), true);
							
							
							$id_bte = isset($array_pet['return']['numeroPeticion'])?json_decode(json_encode($array_pet['return']['numeroPeticion']), true):0;
							// echo json_encode($id_bte);
							// break;
						}else{

							$rspta_ciu=$pqr->data_ciu($id_ciu);
							$parameters_pet = array('datos' => array(
							  	'primerNombre'=>$rspta_ciu['primerNombre'],
							  	'segundoNombre'=>$rspta_ciu['segundoNombre'],
							  	'primerApellido'=>$rspta_ciu['primerApellido'],
							  	'segundoApellido'=>$rspta_ciu['segundoApellido'],
							 	'codigoTipoPersona'=>$rspta_ciu['idTipoPersona'],
							 	'codigoTipoIdentificacion'=>$rspta_ciu['codigoTipoIdentificacion'],
							 	'notificacionElectronica'=>$rspta_ciu['notificacionElectronica'],
							 	'numeroDocumento'=>$rspta_ciu['numeroDocumento'],
								'tipoAtencionPreferencial'=>$rspta_ciu['idAtencionPreferencial'],
					  			'nombreCompletoContacto'=>$rspta_ciu['nombreCompletoContacto'],
								'fechaNacimiento'=>$rspta_ciu['fechaNacimiento'],
								'codigoLocalidadPeticionario'=>$rspta_ciu['localidad'],
								'codigoUpzPeticionario'=>$rspta_ciu['codigoUpz'],
								'codigoBarrioPeticionario'=>$rspta_ciu['idBarrio'],
								'codigoEstratoPeticionario'=>$rspta_ciu['idEstrato'],
					  			'direccionResidenciaContacto'=>$rspta_ciu['direccionResidenciaContacto'],
					  			'telefonoFijoContacto'=>$rspta_ciu['telefonoFijoContacto'],
					  			'celularContacto'=>$rspta_ciu['celularContacto'],
					  			'cargo'=>$rspta_ciu['cargoContacto'],
					  			'codigoPostal'=>$rspta_ciu['codigoPostal'],
					  			'notificacionFisica'=>$rspta_ciu['notificacionFisica'],
					  			'tipoPersonaJuridica'=>$rspta_ciu['tipoPersonaJuridica'],
					  			'codigoEntidadPrivada'=>$rspta_ciu['codigoEntidadPrivada'],
					  			'idEtnia'=>$rspta_ciu['idEtnia'],
					  			'idDiscapacidad'=>$rspta_ciu['idDiscapacidad'],
					  			'idOrientacionSexual'=>$rspta_ciu['idOrientacionSexual'],
					  			'idEmbarazo'=>$rspta_ciu['idEmbarazo'],
					  			'adultoMayor'=>$rspta_ciu['adultoMayor'],
					  			'digitoVerificacion'=>$rspta_ciu['digitoVerificacion'],
					  			'pbx'=>$rspta_ciu['pbx'],                      
							  	'codigoGenero'=>$rspta_ciu['idGenero'],
							  	'codigoOpcion'=>$codigoOpcion,
							  	'codigoSector'=>11,
							  	'codigoTipoRequerimiento'=>$codigoTipoRequerimiento, 
							  	'asunto'=>$asunto_obs,
							  	'codigoDependencia'=>$codigoDependencia,
							  	'codigoProcesoCalidad'=>$codigoProcesoCalidad,
					  			'correoElectronicoContacto'=>$correoElectronicoContacto,
					  		  	'codigoCanal'=>$codigoCanal,
							  	'codigoRedSocial'=>$codigoRedSocial,
					  		  	'numeroRadicado'=>$numeroRadicado,
					  		  	'fechaRadicado'=>$fechaRadicado,
					  		  	'numeroFolios'=>$numeroFolios,
					  		  	'tieneProcedencia'=>$tieneProcedencia,
					  		  	'esCopia'=>$esCopia,
					  		  	'observaciones'=>$observaciones,
					  		  	'codigoLocalidadPeticion'=>$codigoLocalidadPeticion,
					  		  	'codigoUpzPeticion'=>$codigoUpzPeticion,
					  		  	'codigoBarrioPeticion'=>$codigoBarrioPeticion,
					  		  	'codigoEstratoPeticion'=>$codigoEstratoPeticion,
					  		  	'direccionHechos'=>$direccionHechos,
					  		  	'ubicacionAproximada'=>$ubicacionAproximada,
							  	'codigoTema'=>12,                       
							  	'codigoPais'=>169,
							  	'codigoDepartamento'=>11,
							  	'codigoCiudad'=>11001,       
							  	'terminosCondiciones'=>1,
							  	'entidadQueIngresaRequerimiento'=>2212,
							  	'idPuntoAtencion'=>816,
							  	'codigoTipoTramite'=>1951
							));



							$rspta_ciu['telefonoFijoContacto']!=0?$parameters_pet['datos']['telefonoCasa'] = $rspta_ciu['telefonoFijoContacto']:false;
							$rspta_ciu['celularContacto']!=0?$parameters_pet['datos']['telefonoCelular'] = $rspta_ciu['celularContacto']:false;
							$rspta_ciu['notificacionElectronica']==1?$parameters_pet['datos']['email'] = $rspta_ciu['correoElectronico']:false;
							$rspta_ciu['notificacionElectronica']==1?$parameters_pet['datos']['confirmacionEmail'] = 1:0;
							$rspta_ciu['notificacionFisica']==1?$parameters_pet['datos']['direccionResidencia'] = $rspta_ciu['direccionResidencia']:false;

							// echo json_encode($parameters_pet);

							foreach (array_keys($_FILES) as $field){
								$rspta_c=$pqr->count_files($id);
								$i = $rspta_c['num']+1;
								// $_dir = '../Files/pqr';
								$tmp_name = $_FILES[$field]["tmp_name"];
								$name_save = $id.'_'.$i.$_FILES[$field]["name"];
								$ext = strtolower(pathinfo($_FILES[$field]["name"],PATHINFO_EXTENSION));

								$upload_file($tmp_name, $name_save, 'pqr');


								// if(move_uploaded_file($tmp_name, "$_dir/$name_save")){
								// 	$link = '../Files/pqr/'.$name_save;
								// }else{
								// 	echo '(No se pudo guardar el archivo) ';
								// }

								$ver = 2;
								$rspta=$pqr->entrega_con($id,$name_save,$ver);
							}

							$sql_files = "SELECT * FROM `entregas` WHERE `id_pqr`='$id' AND `perfil_en`=2";
							$result_files = ejecutarConsulta($sql_files);

							while($row = mysqli_fetch_array($result_files))
							    {
							    	$ext = pathinfo($row['archivo'], PATHINFO_EXTENSION);
							        $path=$get_file($row['archivo'], 'pqr');
									$base64=base64_encode($path);
									$sqlext="SELECT * FROM `doctype` WHERE `nombre` LIKE '%$ext%'";
									$resultext=ejecutarConsultaSimpleFila($sqlext);
									$tipo_file = $resultext['id_doc'];
									$parameters_pet['datos']['documentos'][] =  array('contenidoDocumento'=>$base64,'codigoTipoArchivo'=>$tipo_file,'nombreArchivo'=>$row['archivo']);
							    }


							if (count($numeroIdentificacionRep)>0) {
								$parameters_pet['datos']['representados'] = array();
								for ($i=0; $i < count($numeroIdentificacionRep); $i++) { 
									$parameters_pet['datos']['representados'][] =  array('codigoTipoIdentificacionRep'=>$codigoTipoIdentificacionRep[$i],'numeroIdentificacionRep'=>$numeroIdentificacionRep[$i],'telefonoRep'=>$telefonoRep[$i],'primerApellidoRep'=>$primerApellidoRep[$i],'segundoApellidoRep'=>$segundoApellidoRep[$i],'primerNombreRep'=>$primerNombreRep[$i],'segundoNombreRep'=>$segundoNombreRep[$i]);
								}
							}


							if (count($numeroIdentificacionPod)>0) {
								$parameters_pet['datos']['poderdantes'] = array();
								for ($i=0; $i < count($numeroIdentificacionPod); $i++) { 
									$parameters_pet['datos']['poderdantes'][] =  array('codigoTipoIdentificacionPod'=>$codigoTipoIdentificacionPod[$i],'numeroIdentificacionPod'=>$numeroIdentificacionPod[$i],'telefonoPod'=>$telefonoPod[$i],'primerApellidoPod'=>$primerApellidoPod[$i],'segundoApellidoPod'=>$segundoApellidoPod[$i],'primerNombrePod'=>$primerNombrePod[$i],'segundoNombrePod'=>$segundoNombrePod[$i]);
								}
							}

							// echo json_encode($parameters_pet);
							// break;

							$response_pet = 0;

							$error_pet = '';

							try {
								$response_pet = $soapclient->registrarRequerimiento($parameters_pet);
								// echo json_encode($response_pet);
							} catch (Exception $e) { 
								$error_pet = $e['return']['error'].' - ';
							}

							$array_pet = json_decode(json_encode($response_pet), true);
							$id_bte = !isset($array_pet['return']['descripcion'])?json_decode(json_encode($array_pet['return']['codigoRequerimiento']), true):0;;
							// echo json_encode($array_pet);
							// break;
						
						}

						if (isset($id_bte) && $id_bte!=0) {
							$rspta_bte = json_encode($id_bte);
							array_push($message_rspta, array('message'=>"Petición guardada en BTE",'type'=>1,'id_bte'=>$rspta_bte));
						}else{
							//$error_pet .= json_encode($array_pet['return']['descripcion']);
							$error_pet .= json_encode($array_pet['return']);
							array_push($message_rspta, array('message'=>"No se pudo registrar la petición en BTE",'type'=>0, 'error'=>$error_pet));
						}
					}else{
						foreach (array_keys($_FILES) as $field){
							$rspta_c=$pqr->count_files($id);
							$i = $rspta_c['num']+1;
							// $_dir = '../Files/pqr';
							$tmp_name = $_FILES[$field]["tmp_name"];
							$name_save = $id.'_'.$i.$_FILES[$field]["name"];
							$ext = strtolower(pathinfo($_FILES[$field]["name"],PATHINFO_EXTENSION));

							$upload_file($tmp_name, $name_save, 'pqr');
							// echo $get_files_list();

							// if(move_uploaded_file($tmp_name, "$_dir/$name_save")){
							// 	$link = '../Files/pqr/'.$name_save;
							// }else{
							// 	echo '(No se pudo guardar el archivo) ';
							// }

							
							$ver = 2;
							$rspta=$pqr->entrega_con($id,$name_save,$ver);

							if ($id_bte!=0) {
								$filext = $_FILES[$field]['name'];
								$ext = pathinfo($filext, PATHINFO_EXTENSION);
								
								$sqlext="SELECT * FROM `doctype` WHERE `nombre` LIKE '%$ext%'";
								$resultext=ejecutarConsultaSimpleFila($sqlext);
								$tipo_file = $resultext['id_doc'];
									
								$path=$get_file($name_save, 'pqr');
								$base64=base64_encode($path);

								$parameters_ed = array('adjuntarArchivoEnRequerimiento' => array(
								  'codigoEntidad'=>2212,
								  'codigoRequerimiento'=>$id_bte,
								  'documentos'=>array('contenidoDocumento'=>$base64,'codigoTipoArchivo'=>$tipo_file,'nombreArchivo'=>$_FILES[$field]['name'])
								));
								$response =$soapclient->adjuntarArchivoEnRequerimiento($parameters_ed);
							}
						}
					}
					
							

					$rspta=$pqr->editar($id,$id_bte,$id_emb,$id_ciu,$codigoOpcion,$codigoTipoRequerimiento,$tieneProcedencia,$esCopia,$asunto_obs,$codigoDependencia,$codigoProcesoCalidad,$codigoCanal,$codigoRedSocial,$numeroRadicado,$fechaRadicado,$numeroFolios,$observaciones,$codigoLocalidadPeticion,$codigoUpzPeticion,$codigoBarrioPeticion,$codigoEstratoPeticion,$direccionHechos,$ubicacionAproximada,$fecha_crea,$group_int,$ori_pqr,$estado,$estado_tra,$fecha_asigna,$fecha_ley,$notificacionFisica,$notificacionElectronica,$anonim,$id_pod,$id_rep,$codigoTipoIdentificacionRep,$numeroIdentificacionRep,$telefonoRep,$primerApellidoRep,$segundoApellidoRep,$primerNombreRep,$segundoNombreRep,$codigoTipoIdentificacionPod,$numeroIdentificacionPod,$telefonoPod,$primerApellidoPod,$segundoApellidoPod,$primerNombrePod,$segundoNombrePod,$idAtencionPreferencial,$correoElectronicoContacto,$nombreCompletoContacto,$telefonoFijoContacto,$celularContacto,$direccionResidenciaContacto);

					$rspta ? array_push($message_rspta, array('message'=>"Petición actualizada localmente",'type'=>1)) : array_push($message_rspta, array('message'=>"No se pudo actualizar la petición localmente",'type'=>0));

					echo json_encode($message_rspta);

				}
		   
			break;

			case 'mostrar':
				$rspta=$pqr->mostrar($id);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;

			case 'show_cierre':
				$rspta=$pqr->show_cierre($id_pqr);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;

			case 'poderdantes':
				$rspta=$pqr->poderdantes($id_pqr);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;
			
			case 'representados':
				$rspta=$pqr->representados($id_pqr);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;

			case 'mostrarobs':
				$rspta=$pqr->mostrarobs($id);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;


			case 'filtro':
				$rspta=$pqr->filtro($doc);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;



			case 'filtrobte':
					
				$WSDL = 'https://sdqs.bogota.gov.co/sdqs/servicios/RadicacionPorCanalService?wsdl';
			  	$options = array(
			      	'login' => 'sweb79',
			      	'password' => 'Metro2022!',
			      	'cache_wsdl' => 0,
			      	'trace' => 1,
			      	'stream_context' => stream_context_create(array(
			        	'ssl' => array(
			          		'verify_peer' => false,
			          		'verify_peer_name' => false,
			          		'allow_self_signed' => true
		        		)
			      	))
			  	);
			  	$soapclient = new SoapClient($WSDL, $options);
				$parameters = array('consultarPeticionario' => array('codigoTipoIdentificacion'=>$codigoTipoIdentificacion,'numeroDocumento'=>$doc));
				$response = 0;

				try {
					$response =$soapclient->consultarPeticionario($parameters);
				} catch (Exception $e) {
				   // echo 'Error: ',  $e->getMessage(), "\n";   
				}

				// echo json_encode($response);
				// break;

				$array = json_decode(json_encode($response), true);
				$array2 = json_decode(json_encode($array['return']['peticionarios']), true);

				echo json_encode($array2);

			break;



			case 'listar':
			$estados_filtro=$_REQUEST['estados_filtro'];
			$fecha_crea=$_REQUEST['fecha_crea'];
			
			$fecha_ini=$_REQUEST['fecha_ini'];
			$fecha_fin=$_REQUEST['fecha_fin'];
			$ori_pqr=$_REQUEST['ori_pqr'];
			$sel_canal=$_REQUEST['sel_canal'];

				$rspta=$pqr->listar($estados_filtro,$fecha_crea,$fecha_ini,$fecha_fin,$ori_pqr,$sel_canal);
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			if ($_SESSION['Admin']==1){
			 			$data[]=array(
			 				
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
			 				"12"=>($reg->asign==1)?'<span class="label bg-green">Asignado a '.$reg->nombre.'</span>':'<span class="label bg-red">No Asignado</span>',
			 				"13"=>($reg->estado!=3)?'<button class="btn btn-success" onclick="asign('.$reg->id_pqr2.',1);" title="Asignar"><i class="fas fa-user"></i></button>'.'<button class="btn btn-info" onclick="seguimiento('.$reg->id_pqr2.');" title="Activar seguimiento"><i class="fas fa-search"></i></button>':'<button class="btn btn-info" onclick="seguimiento('.$reg->id_pqr2.');" title="Activar seguimiento"><i class="fas fa-search"></i></button>',
			 				"14"=>($reg->status==0)?'<span class="label bg-green" onclick="activar_pqr('.$reg->id_pqr2.')" style="cursor:pointer;">Habilitar</span>':'<span onclick="desactivar_pqr('.$reg->id_pqr2.')" style="cursor:pointer;" class="label bg-red">Inhabilitar</span>'
			 			);
		 			}else{
		 				$data[]=array(
			 				
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
			 				"12"=>($reg->asign==1)?'<span class="label bg-green">Asignado</span>':'<span class="label bg-red">No Asignado</span>',
			 				"13"=>($reg->confirm==1)?'<button class="btn btn-success" onclick="asign('.$reg->id_pqr.',2);" title="Reasignar"><i class="fas fa-user"></i></button>':'<button class="btn btn-success" onclick="confirm('.$reg->id_pqr.');" title="Confirmar asignación"><i class="fas fa-check"></i></button>'.'<button class="btn btn-info" onclick="seguimiento('.$reg->id_pqr2.');" title="Activar seguimiento"><i class="fas fa-search"></i></button>'
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

			


			case 'confirmar':
				$rspta=$pqr->confirmar($id_pqr);
				
				echo $rspta ? json_encode(array("result"=>"Ya puede realizar la respuesta a esta PQR")) : json_encode(array("result"=>"No se puede confirmar"));
			break;


			case 'contra':
				$rspta=$pqr->contra($id,$con);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;



			case 'entrega_view':

				foreach (array_keys($_FILES) as $field){

					$sql="SELECT count(id_entrega) as num FROM entregas WHERE `id_pqr`='$id_pqr2' AND `perfil_en` = 1";
					$entregas = ejecutarConsultaSimpleFila($sql);

					$i = $entregas['num']+1;
					// $_dir = '../Files/entrega';
					$tmp_name = $_FILES[$field]["tmp_name"];
					$name_save = $id_pqr2.'_'.$i.$_FILES[$field]["name"];
					$ext = strtolower(pathinfo($_FILES[$field]["name"],PATHINFO_EXTENSION));

					$upload_file($tmp_name, $name_save, 'entrega');


					// if(move_uploaded_file($tmp_name, "$_dir/$name_save")){
					// 	$link = '../Files/entrega/'.$name_save;
					// }else{
					// 	echo '(No se pudo guardar el archivo) ';
					// }
					$ver = 1;
					$rspta=$pqr->entrega_con($id_pqr2,$name_save,$ver);
				}

				$sql_file="SELECT `id_entrega`, `archivo`, `perfil_en`, `id_usu`, `id_pqr`, `obs_ev` FROM `entregas` WHERE `id_pqr`='$id_pqr2' AND `perfil_en`=1 ORDER BY `id_entrega` ASC";
				
				$arch = ejecutarConsulta($sql_file);

				$sql_pqr="SELECT `id_bte` FROM `pqr` WHERE `id_pqr`='$id_pqr2'";
				$id_bte_res = ejecutarConsultaSimpleFila($sql_pqr);

				$nombres = array();
				$id_bte = $id_bte_res['id_bte'];

				// echo $id_bte;

					while($row = mysqli_fetch_array($arch))
			    {
			        array_push($nombres,$row['archivo']);
			    }

				$rspta=$pqr->entrega_view($id_pqr2,$observa_view,$estado_view,$id_ent,$date_respc,$rad_respc,$categoria,$subtema);


				if ($rspta==1 && $type_sub==1) {
					$WSDL = 'https://sdqs.bogota.gov.co/sdqs/servicios/RadicacionPorCanalService?wsdl';
				    $options = array(
				      	'login' => 'sweb79',
				      	'password' => 'Metro2022!',
				      	'cache_wsdl' => 0,
				      	'trace' => 1,
				      	'stream_context' => stream_context_create(array(
				        	'ssl' => array(
				          		'verify_peer' => false,
				          		'verify_peer_name' => false,
				          		'allow_self_signed' => true
			        		)
				      	))
				  	);
				  	$soapclient = new SoapClient($WSDL, $options);
					$parameters_fun = array('consultarFuncionarioPeticion' => array(
					  'numeroPeticion'=>$id_bte
					));
					$response_fun =$soapclient->consultarFuncionarioPeticion($parameters_fun);
					$array_fun = json_decode(json_encode($response_fun), true);
					$documento_fun = json_decode(json_encode($array_fun['return']['list']['numeroIdentificacion']), true);
					// echo $documento_fun;


					$parameters = array('datos' => array(
					  'codigoRequerimiento'=>$id_bte,
					  'descripcionSolucion'=>$observa_view,
					  'fechaSolucion'=>$date_respc,
					  'idCategoria'=>$categoria,
					  'idSubTema'=>$subtema,
					  'numeroDocumentoFuncionario'=>$documento_fun,
					  'codigoProcesoCalidad'=>224,
					  'idTipoTramite'=>1951,
					  'idTema'=>1835,
					  'tipoSolucion'=>0,
					  'documentos'=>array()
					));


					for ($i=0; $i < count($nombres); $i++) { 
						$fileinfo = pathinfo($nombres[$i]);
						$content = $get_file($nombres[$i], 'entrega');
						$ext = $fileinfo['extension'];
						$sqlext="SELECT * FROM `doctype` WHERE `nombre` LIKE '%$ext%'";
						$resultext=ejecutarConsultaSimpleFila($sqlext);
						$tipo_file = $resultext['id_doc'];


				    	$base64=base64_encode($content);
				    	$parameters['datos']['documentos'][] =  array('contenidoDocumento'=>$base64,'codigoTipoArchivo'=>$tipo_file,'nombreArchivo'=>$nombres[$i]);
				    	// echo 'Archivo No.'.$i.' - '.$base64;
				    }

				    // echo json_encode($parameters);
				    // break;

					$response =$soapclient->cerrarRequerimiento($parameters);
					$array = json_decode(json_encode($response), true);
					$id_bte_res = json_decode(json_encode($array['return']), true);
					// echo json_encode($id_bte_res);
					
				}	
				echo $rspta ? json_encode(array("result"=>"Actualizado Correctamente")) : json_encode(array("result"=>"No se pudo actualizar"));

			break;





			case 'fechas':
				$rspta=$pqr->fechas($codigoTipoRequerimiento);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;

			case 'show_asign':
				$rspta=$pqr->show_asign($id_pqr);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;




			case 'eliminar':


				$sql="SELECT archivo, perfil_en FROM entregas WHERE id_entrega='$id_ent'";
				$res=ejecutarConsultaSimpleFila($sql);
				$archivo = $res['archivo'];
				$ver = $res['perfil_en']==2?'pqr':'entrega';

				try{
					echo $delete_file($archivo, $ver);
				}catch(Error $e){
					echo $e;
				}
				

				$rspta=$pqr->eliminar($id_ent);

				echo $rspta ? json_encode(array("result"=>"Eliminado")) : json_encode(array("result"=>"No se pudo eliminar"));

			break;



			

			case 'asignacion':
				$rspta=$pqr->asignacion($id_pqrm,$people,$observa_asign);

				for ($i=0; $i < count($rspta); $i++) { 
					$sqlu="SELECT nombre,email FROM usuarios WHERE id=(SELECT id_user FROM asignacion WHERE id_asigna = $rspta[$i])";
					$reu=ejecutarConsultaSimpleFila($sqlu);

					$sqla="SELECT nombre,email FROM usuarios u INNER JOIN u_permiso up ON up.id_user=u.id WHERE id_permiso=2";
					$rea=ejecutarConsulta($sqla);

					$sqlp="SELECT id_pqr,id_bte,id_emb,observaciones,asunto_obs, nombre_sol FROM pqr p LEFT JOIN tipo_sol t ON t.id_sol=p.codigoTipoRequerimiento WHERE id_pqr='$id_pqrm'";
					$rep=ejecutarConsultaSimpleFila($sqlp);

					$email=$reu['email'];
					$nombre=$reu['nombre'];
					$id_pqr=$rep['id_pqr'];
					$id_bte=$rep['id_bte'];
					$id_emb=$rep['id_emb'];
					$observaciones=$rep['observaciones'];
					$asunto=$rep['asunto_obs'];

					require_once "/home/site/wwwroot/PHPMailer/PHPMailerAutoload.php";

					try {
					    //Server settings
						$mail = new PHPMailer;
						$mail->CharSet = 'UTF-8';
						$mail ->Host = 'smtp.gmail.com';
						$mail -> isMail();
						$mail -> setFrom('sgd@mab.com.co','MAB Ingeniería de Valor');
						$mail -> addAddress($email);  
						$mail -> addAddress('jsanchezr@mab.com.co');  
						while($row = mysqli_fetch_array($rea)){
					        $mail -> addAddress($row['email']);  
					    }
						$mail->Port       = 25;   //Add a recipient
					                                    //Set email format to HTML
					    $mail->Subject = 'Nueva asignación de PQR';
					    $mail -> msgHTML('<html lang="es">

					<style>
					.clase_table {
					    border-collapse: separate;
					    border-spacing: 10;
					    border: 1px solid black;
					    border-radius: 15px;
					    -moz-border-radius: 20px;
					    padding: 40px;

					}
					</style>

						<font face="Arial,verdana">
						<head>
							<meta charset="UTF-8">
							<link rel="shortcut icon" href="../public/img/favicon.png">
							<title>Asignación PQR</title>
						</head> 
					 
					  <!-- Tempusdominus Bootstrap 4 -->
					  <link rel="stylesheet" href="http://informesagiles.mab.com.co/pdf.js/public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
					  <!-- iCheck -->
					  <link rel="stylesheet" href="http://informesagiles.mab.com.co/pdf.js/public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
					  <!-- Theme style -->
					  <link rel="stylesheet" href="http://informesagiles.mab.com.co/pdf.js/public/dist/css/adminlte.min.css">
					  <link rel="stylesheet" href="http://informesagiles.mab.com.co/pdf.js/public/dist/css/mab.css">
					  <link rel="stylesheet" href="http://informesagiles.mab.com.co/pdf.js/public/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css">

						<body >	

						<div class="row">
								<div class="col-12" style="
								background-color:#0E4D83;
								width:100%;
								height:100px;
								border-radius: 1px;
								">

								<img src="https://www.mab.com.co/wp-content/uploads/2021/02/Logo-MAB-1.png" style="position: relative;padding:15px; " >
								</div>
								
								<table>
								<tr>	
								<td><h5 style="font-size:25px;">Buen día '.$nombre.'.</h5></td>
					            </tr>
					            <tr>	
								<td><p style="position:absolute;font-size:15px; text-align: justify; top:10px;">Le ha sido asignado un PQR con Tipo de Solicitud: "'.$rep['nombre_sol'].'". </p>
					            </td>
					            </tr>
					            <tr>
					            <td><p style="position:absolute;font-size:15px; text-align: justify; top:10px;">Asunto: "'.$asunto.'". </p>
					            </td>
					            </tr>

								</table>

									<table class="clase_table" align="center">
							  <td style="font-size:15px;">Información para su consulta: <br>
					          <b>ID PQR : '.$id_pqr. '<br>
							  <b>ID Bogotá te escucha: '.$id_bte.'<br>
							  <b>ID EMB: '.$id_emb.'<br>
							  <a href="https://pqrsmetro.mab.com.co/vistas/pqr.php" target="_blank" style="position:absolute; left:10px; cursor:pointer;"> <button style="transition: background-color .3s, box-shadow .3s;
					    
					  padding: 12px 68px 12px 68px;
					  border: none;
					  border-radius: 3px;
					  box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 1px 1px rgba(0, 0, 0, .25);
					  
					  color: #FFFFFF !important;
					  font-size: 14px;
					  font-weight: 500;
					  
					  background-color: #0E4D83;">Click aqui para ir a la página.</button></a>
							  </td>
							  <td>
							 
							  </td>
							   <td>
							  
							  </td>

							</table>
									
						
								<div class="row">
								<div class="col-12" style="
								background-color:#0E4D83;
								width:100%;
								height:100px;
								border-radius: 1px;
								">

								</div>


								</div>

						</body>
						</html>');

					    $mail->send();
					    // echo 'Se envio el correo'.$email;
					} catch (Exception $e) {
					    echo $e;
					}
							
							}

				
				echo $rspta != 0 ? json_encode(array("result"=>"Asignado Correctamente")) : json_encode(array("result"=>"No se pudo asignar"));
				
				
				
				
				//echo $rspta ? "Asignado Correctamente" : "No se pudo asignar";
				//echo $sqlp;
			break;




			case 'remove_pond':
				$rspta=$pqr->remove_pond($id_pod);
				echo $rspta ? json_encode(array("result"=>"Poderdante Eliminado")) : json_encode(array("result"=>"No se pudo eliminar"));
			break;

			case 'remove_rep':
				$rspta=$pqr->remove_rep($id_rep);
				echo $rspta ? json_encode(array("result"=>"Representado Eliminado")) : json_encode(array("result"=>"No se pudo eliminar"));
			break;


			case 'activar_pqr':
				$rspta=$pqr->activar_pqr($id_pqr);
				echo $rspta ? json_encode(array("result"=>"PQRSD habilitada")) : json_encode(array("result"=>"No se pudo habilitar"));
			break;

			case 'desactivar_pqr':
				$rspta=$pqr->desactivar_pqr($id_pqr);
				echo $rspta ? json_encode(array("result"=>"PQRSD inhabilitada")) : json_encode(array("result"=>"No se pudo inhabilitar"));
			break;

            case 'get_ext':
                $rspta=$pqr->get_ext();
                 echo json_encode($rspta);
            break;

			case 'seguimiento':
				$rspta=$pqr->seguimiento($id_pqr);
				echo $rspta ? json_encode(array("result"=>"Seguimiento habilitado")) : json_encode(array("result"=>"No se pudo habilitar"));
			break;

		}

	}
}
?>
