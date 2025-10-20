<?php 
session_write_close();
ignore_user_abort(false);
set_time_limit(1000);
ini_set('max_execution_time', 1300);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
	error_log('[bte.php] JWT decode error: ' . $e->getMessage());
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
		$fecha_ini=isset($_POST["fecha_ini"])? limpiarCadena($_POST["fecha_ini"]):"";
		$fecha_fin=isset($_POST["fecha_fin"])? limpiarCadena($_POST["fecha_fin"]):"";


		$log_user=1;
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

		$save_ciu = function($tipo_doc, $doc) use ($soapclient, $log_user, $conexion){

			$id_rspta=0;
			$anonim=0;

			try {
				$parameters_ciu = array('consultarPeticionario' => array('codigoTipoIdentificacion'=>$tipo_doc,'numeroDocumento'=>$doc));
				$response_ciu =$soapclient->consultarPeticionario($parameters_ciu);
				$array_ciu = json_decode(json_encode($response_ciu), true);  
			} catch (Exception $e) {
			   	// echo 'error_ciu '.$e;   
			   	return array("id_rspta"=>$id_rspta, "anonim"=>1);
			}	
			if ($array_ciu['return']['codigoError']>0) {
				// echo 'error_ciu '.$tipo_doc.' - '. $doc;   
				// echo json_encode($array_ciu['return']);
				return array("id_rspta"=>$id_rspta, "anonim"=>1);
			}

			$array2_ciu = json_decode(json_encode($array_ciu['return']['peticionarios']), true);

			if ($array2_ciu!=null) {

				$sql_ciu = "SELECT `id_ciu` FROM `ciudadano` WHERE `numeroDocumento`='$doc'";
				$result = ejecutarConsultaSimpleFila($sql_ciu);

				if (empty($result['id_ciu'])) {



					$comparison = array(
						"tipoIdentificacion"=>"codigoTipoIdentificacion",
						"numeroIdentificacion"=>"numeroDocumento",
						"genero"=>"idGenero",
						"atencionPreferencial"=>"idAtencionPreferencial",
						"cargo"=>"cargoContacto",
						"barrio"=>"idBarrio",
						"tipoPersona"=>"idTipoPersona",
						"tipoEntidadPersonaJuridica"=>"tipoPersonaJuridica",
						"estrato"=>"idEstrato",
						"id"=>"id_per_bte",
						"entidad"=>"codigoTipoEntidad",
						"tipoIdentificacion"=>"codigoTipoIdentificacion",
					);
					$ar_c = array();

					foreach ($array2_ciu as $key => $value) {
						$type = gettype($value);
						$key_com = isset($comparison[$key])?$comparison[$key]:$key;
						if ($type=="array") {
							$ar_c[$key_com] = isset($value['id'])?$value['id']:null;
						}else{			
							$ar_c[$key_com] = $value;
						}
					}

					$ar_c['notificacionElectronica'] = isset($ar_c['notificacionElectronica'])? ($ar_c['notificacionElectronica']==true?1:0):0;
					$ar_c['notificacionFisica'] = isset($ar_c['notificacionFisica'])? ($ar_c['notificacionFisica']==true?1:0):0;

					if (isset($ar_c['fechaNacimiento'])) {
						$ar_c['fechaNacimiento'] = $ar_c['fechaNacimiento']==''?null:strtok($ar_c['fechaNacimiento'], 'T');
					}

					unset($ar_c['usuario'],$ar_c['ciudad'],$ar_c['notificacionCelular'],$ar_c['animoDeLucro'],$ar_c['0'],$ar_c['1']);


					    $into = array_keys($ar_c);
						$values = array_values($ar_c);

						$sql = "INSERT INTO `ciudadano`(`" . join("`, `", $into) . "`, `log_user`) VALUES (" . str_repeat('?,', count($values) - 1) . "?, ?)";

						try {
						    // Prepare the SQL statement
						    $stmt = $conexion->prepare($sql);

						    // Add the additional value to the end of your values array
						    array_push($values, $log_user);

						    // Dynamically generate the types for bind_param
						    $types = '';
						    foreach ($values as $value) {
						        // Assuming 'i' for integer and 's' for other types
						        $types .= is_int($value) ? 'i' : 's';
						    }

						    // Add the types and values to an array
						    $params = array($types);
						    foreach ($values as &$value) {
						        $params[] = &$value;
						    }

						    // Call bind_param with parameter array
						    call_user_func_array(array($stmt, 'bind_param'), $params);

						    // Execute the statement
						    $stmt->execute();

						    // Get the last inserted ID
						    $id_rspta = $stmt->insert_id;
						} catch (Exception $e) {
						    echo $e;
						    $id_rspta = 0;
						    $anonim = 1;
						}




					
				}else{
					// echo 'Ya'.$doc;
					$id_rspta=$result['id_ciu'];
				}

			}else{
				$anonim=1;
			}

			return array("id_rspta"=>$id_rspta, "anonim"=>$anonim);
		};









		$save_params = function($id_bte, $id_rspta) use ($pqr, $soapclient, $get_files_list, $upload_file64){

			$parameters = array('codigoRequerimiento' => array('codigoRequerimiento'=>$id_bte));
			try {
				$response = $soapclient->consultarRequerimiento($parameters);
				$array0 = json_decode(json_encode($response), true);
				$array = json_decode(json_encode($array0['return']['requerimiento']), true);
			} catch (Exception $e) {
			   	echo $e;  
			}

			$rspta = 5;
			$res_pod = 5;
			$res_rep = 5;

			$num_docs = !isset($array['documentos'])?0:(!isset($array['documentos'][0])?1:count($array['documentos']));

			for ($i=0; $i < $num_docs; $i++) { 
				// echo count($array['documentos']);
				if (!isset($array['documentos'][$i])) {
					$nombre = $array['documentos']['nombreArchivo'];
					$file = $array['documentos']['contenidoDocumento'];
				}else{
					$nombre = $array['documentos'][$i]['nombreArchivo'];
					$file = $array['documentos'][$i]['contenidoDocumento'];
				}
				
			 	$rspta_c = $pqr->count_files($id_rspta);
				$j = $rspta_c['num'] + 1;
				$name_save = $id_rspta.'_'.$j.$nombre;

				//echo $get_files_list();

				//$upload_file64(base64_decode($file), $name_save, 'entrega');
				// $upload_file64(base64_decode($file), $name_save, 'pqr');


				//echo $get_files_list();

				$ver = 2;
				$rspta=$pqr->entrega_con($id_rspta,$name_save,$ver);

			} 

			$num_pod = !isset($array['poderdantes'])?0:(!isset($array['poderdantes'][0])?1:count($array['poderdantes']));
			for ($i=0; $i < $num_pod; $i++) {
			 	if (!isset($array['poderdantes'][$i])) {
					$codigoTipoIdentificacionPod = $array['poderdantes']['codigoTipoIdentificacionPod'];
					$numeroIdentificacionPod = $array['poderdantes']['numeroIdentificacionPod'];
					$telefonoPod = isset($array['poderdantes']['telefonoPod'])?$array['poderdantes']['telefonoPod']:0;
					$primerApellidoPod = $array['poderdantes']['primerApellidoPod'];
					$segundoApellidoPod = isset($array['poderdantes']['segundoApellidoPod'])?$array['poderdantes']['segundoApellidoPod']:'';
					$primerNombrePod = $array['poderdantes']['primerNombrePod'];
					$segundoNombrePod = isset($array['poderdantes']['segundoNombrePod'])?$array['poderdantes']['segundoNombrePod']:'';
				}else{
					$codigoTipoIdentificacionPod = $array['poderdantes'][$i]['codigoTipoIdentificacionPod'];
					$numeroIdentificacionPod = $array['poderdantes'][$i]['numeroIdentificacionPod'];
					$telefonoPod = $array['poderdantes'][$i]['telefonoPod'];
					$primerApellidoPod = $array['poderdantes'][$i]['primerApellidoPod'];
					$segundoApellidoPod = $array['poderdantes'][$i]['segundoApellidoPod'];
					$primerNombrePod = $array['poderdantes'][$i]['primerNombrePod'];
					$segundoNombrePod = $array['poderdantes'][$i]['segundoNombrePod'];
				}

				$sql_pod = "INSERT INTO `poderdante` (`id_pqr`,`codigoTipoIdentificacionPod`,`numeroIdentificacionPod`,`telefonoPod`,`primerApellidoPod`,`segundoApellidoPod`,`primerNombrePod`,`segundoNombrePod`) VALUES ('$id_rspta','$codigoTipoIdentificacionPod','$numeroIdentificacionPod','$telefonoPod','$primerApellidoPod','$segundoApellidoPod','$primerNombrePod','$segundoNombrePod')";
				$res_pod = ejecutarConsulta($sql_pod);
			}


			$num_rep = !isset($array['representados'])?0:(!isset($array['representados'][0])?1:count($array['representados']));
			for ($i=0; $i < $num_rep; $i++) {
			 	if (!isset($array['representados'][$i])) {
					$codigoTipoIdentificacionRep = $array['representados']['codigoTipoIdentificacionRep'];
					$numeroIdentificacionRep = $array['representados']['numeroIdentificacionRep'];
					$telefonoRep = $array['representados']['telefonoRep'];
					$primerApellidoRep = $array['representados']['primerApellidoRep'];
					$segundoApellidoRep = $array['representados']['segundoApellidoRep'];
					$primerNombreRep = $array['representados']['primerNombreRep'];
					$segundoNombreRep = $array['representados']['segundoNombreRep'];
				}else{
					$codigoTipoIdentificacionRep = $array['representados'][$i]['codigoTipoIdentificacionRep'];
					$numeroIdentificacionRep = $array['representados'][$i]['numeroIdentificacionRep'];
					$telefonoRep = $array['representados'][$i]['telefonoRep'];
					$primerApellidoRep = $array['representados'][$i]['primerApellidoRep'];
					$segundoApellidoRep = $array['representados'][$i]['segundoApellidoRep'];
					$primerNombreRep = $array['representados'][$i]['primerNombreRep'];
					$segundoNombreRep = $array['representados'][$i]['segundoNombreRep'];
				}

				$sql_rep = "INSERT INTO `representado` (`id_pqr`,`codigoTipoIdentificacionRep`,`numeroIdentificacionRep`,`telefonoRep`,`primerApellidoRep`,`segundoApellidoRep`,`primerNombreRep`,`segundoNombreRep`) VALUES ('$id_rspta','$codigoTipoIdentificacionRep','$numeroIdentificacionRep','$telefonoRep','$primerApellidoRep','$segundoApellidoRep','$primerNombreRep','$segundoNombreRep')";
				$res_rep = ejecutarConsulta($sql_rep);
			}


			return array("documentos"=>$rspta, "poderdantes"=>$res_pod, "representados"=>$res_rep);
		};






		switch ($_GET["op"]){

			case 'filtrobte_reque':

				$sql_pqr = "SELECT `id_pqr` FROM `pqr` WHERE `id_bte` = '$id_bte'";
				$result_pqr = ejecutarConsultaSimpleFila($sql_pqr);

				if (!isset($result_pqr['id_pqr'])) {
					
					$parameters = array('codigoRequerimiento' => array('codigoRequerimiento'=>$id_bte));
					$response = 0;
					$response_ciu = 0;

					$doc=0;
					$tipo_doc=0;
					$array2_ciu=0;

					try {
						$response =$soapclient->consultarRequerimiento($parameters);
						$array = json_decode(json_encode($response), true);
						$array2 = json_decode(json_encode($array['return']['requerimiento']), true);
						$doc = $array['return']['requerimiento']['numeroDocumento'];
						$tipo_doc = $array['return']['requerimiento']['codigoTipoIdentificacion'];
					} catch (Exception $e) {
					   	echo $e;   
				   		break;  
					}

					$rspta=$save_ciu($tipo_doc, $doc);

					$id_rspta=$rspta['id_rspta'];
					$anonim=$rspta['anonim'];


					$comparison = array(
						"asunto"=>"asunto_obs",
						"tipoAtencionPreferencial"=>"idAtencionPreferencial"
					);
					$ar_p = array();

					foreach ($array2 as $key => $value) {
						$type = gettype($value);
						$key_com = isset($comparison[$key])?$comparison[$key]:$key;
						if ($type=="array") {
							$ar_p[$key_com] = isset($value['id'])?$value['id']:null;
						}else{			
							$ar_p[$key_com] = $value;
						}
					}

					$ar_p['fecha_crea'] = $ar_p['fechaRadicado'];
					$ar_p['id_emb'] = $ar_p['numeroRadicado'];
					$ar_p['notificacionElectronica'] = $ar_p['notificacionElectronica']==true?1:0;
					$ar_p['notificacionFisica'] = $ar_p['notificacionFisica']==true?1:0;
					
					unset($ar_p['codigoSector'],$ar_p['codigoTema'],$ar_p['codigoCiudad'],$ar_p['codigoDepartamento'],$ar_p['codigoPais'],$ar_p['documentos'],$ar_p['email'],$ar_p['entidadQueIngresaRequerimiento'],$ar_p['codigoTipoIdentificacion'],$ar_p['numeroDocumento'],$ar_p['telefonoCasa'],$ar_p['telefonoCelular'],$ar_p['telefonoOficina'],$ar_p['idPuntoAtencion'],$ar_p['codigoEntidad'],$ar_p['codigoTipoTramite'],$ar_p['codigoPostal'],$ar_p['idFuncionario']);

					$into_p = array_keys($ar_p);
					$values_p = array_values($ar_p);

					$sql="INSERT INTO `pqr`(`id_bte`, `ciudadano`, `estado`, `estado_tra`, `anonimo`, `log_user`, `".join("`, `",$into_p)."`) VALUES ($id_bte, $id_rspta, 1, 9, $anonim, $log_user, '".join("','",$values_p)."')";
					$id_rspta = ejecutarConsulta_retornarID($sql);


					$res_p = $save_params($id_bte, $id_rspta);


					$data = array(
				      'pqr' => $array2,
				      'ciudadano' => $array2_ciu
					);

					// echo json_encode($data);
					echo $id_rspta!=0 ? json_encode(array("result"=>"PQRSD registrada")) : json_encode(array("result"=>"No se pudo registrar la pqr"));
				}else{
					echo json_encode(array("result"=>"Ya se encuentra registrada esta petición."));
				}
		

				// echo $get_files_list();

			break;



















			case 'filtrobte_dates':
				global $conexion;
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

				$message_rspta = '';

			  	$pageToken = 1;
					 while ($pageToken != null){
						$parameters = array('consultarRequerimientosRangoFechas' => array('codigoEntidad'=>2212, 'fechaInicio'=>$fecha_ini, 'fechaFin'=>$fecha_fin, 'pagina'=>$pageToken, 'numeroRegistros'=>1000));
						try {
							$soapclient = new SoapClient($WSDL, $options);
							$response =$soapclient->consultarRequerimientosRangoFechas($parameters);
											
							// echo json_encode($response);
							$array = json_decode(json_encode($response), true);
						} catch (Exception $e) {
						   echo $e; 
						   break;
						}


// $xml = simplexml_load_string('');
// $json = json_encode($xml);
// $array = json_decode($json,TRUE);
// echo $json;


						if ($array['return']['registros']>1) {
							for ($i=0; $i < $array['return']['registros']; $i++) {

								$id_bte = $array['return']['peticiones'][$i]['numero'];
								$sql_pqr = "SELECT `id_pqr` FROM `pqr` WHERE `id_bte` = '$id_bte'";
								$result_pqr = ejecutarConsultaSimpleFila($sql_pqr);

								if (empty($result_pqr['id_pqr'])) {

									$array2 = json_decode(json_encode($array['return']['peticiones'][$i]), true);
									$doc = isset($array['return']['peticiones'][$i]['numeroIdentificacion'])?$array['return']['peticiones'][$i]['numeroIdentificacion']:0;
									$tipo_doc = isset($array['return']['peticiones'][$i]['tipoIdentificacion']['id'])?$array['return']['peticiones'][$i]['tipoIdentificacion']['id']:1;

									// echo 'info - '.$tipo_doc.' - '.$doc;
									if ($doc == 0 && $tipo_doc == 1) {
										$nombre = isset($array['return']['peticiones'][$i]['peticionario']['nombre'])?$array['return']['peticiones'][$i]['peticionario']['nombre']:false;
										$email = isset($array['return']['peticiones'][$i]['peticionario']['correoElectronico'])?$array['return']['peticiones'][$i]['peticionario']['correoElectronico']:null;
										if ($nombre) {
											$sql="INSERT INTO `ciudadano` (`primerNombre`, `correoElectronico`, `log_user`) VALUES (?, ?, ?)";
											$stmt = $conexion->prepare($sql);
											$stmt->bind_param("sss", $nombre, $email, $log_user);
											$id_rspta = ejecutarConsultaReturn($stmt);
											$anonim = 0;
										}else{
											$id_rspta = 0;
											$anonim = 1;
										}
									}else{
										$rspta = $save_ciu($tipo_doc, $doc);
										$id_rspta = $rspta['id_rspta'];
										$anonim = $rspta['anonim'];
									}





									$comparison = array(
										"asunto"=>"asunto_obs",
										"atencionPreferencial"=>"idAtencionPreferencial",
										"barrio"=>"codigoBarrioPeticion",
										"canal"=>"codigoCanal",
										"dependencia"=>"codigoDependencia",
										"opcion"=>"codigoOpcion",
										"tipo"=>"codigoTipoRequerimiento",
										"estrato"=>"codigoEstratoPeticion",
										"procesoCalidad"=>"codigoProcesoCalidad",
										"redSocial"=>"codigoRedSocial"
									);
									$ar_p = array();

									foreach ($array2 as $key => $value) {
										$type = gettype($value);
										$key_com = isset($comparison[$key])?$comparison[$key]:$key;
										if ($type=="array") {
											$ar_p[$key_com] = isset($value['id'])?$value['id']:null;
										}else{			
											$ar_p[$key_com] = $value;
										}
									}

									$ar_p['fecha_crea'] = strtok($ar_p['fechaIngreso'], 'T');
									$ar_p['id_emb'] = isset($ar_p['numeroRadicado'])?$ar_p['numeroRadicado']:0;
									$ar_p['id_bte'] = $ar_p['numero'];
									$ar_p['estado'] = isset($ar_p['fechaSolucion'])?3:1;
									$ar_p['notificacionElectronica'] = isset($ar_p['notificacionElectronica'])?($ar_p['notificacionElectronica']==true?1:0):0;
									$ar_p['notificacionFisica'] = isset($ar_p['notificacionFisica'])?($ar_p['notificacionFisica']==true?1:0):0;
									
									unset($ar_p['ciudad'],$ar_p['ciudadContacto'],$ar_p['correoElectronico'],$ar_p['entidad'],$ar_p['entidadFuente'],$ar_p['entidadIngresa'],$ar_p['entidadProcedencia'],$ar_p['entidadResponsable'],$ar_p['esMenorEdad'],$ar_p['esPublicada'],$ar_p['esSuscripcion'],$ar_p['esUnificada'],$ar_p['fechaRegistro'],$ar_p['fechaRadicado'],$ar_p['motivo'],$ar_p['numero'],$ar_p['puntoAtencion'],$ar_p['subtema'],$ar_p['tema'],$ar_p['tipoFormulario'],$ar_p['tipoIdentificacion'],$ar_p['numeroIdentificacion'],$ar_p['tipoIdentificacionRep'],$ar_p['tipoTramite'],$ar_p['fechaIngreso'],$ar_p['fechaSolucion'],$ar_p['funcionario'],$ar_p['numeroPeticionEntidad'],$ar_p['peticionario'],$ar_p['notificacionCelular'],$ar_p['palabraClave'],$ar_p['radicadoProcedencia'],$ar_p['representanteLegal'],$ar_p['resumen']);

									$into_p = array_keys($ar_p);
									$values_p = array_values($ar_p);


								    // Prepare the SQL statement with placeholders
								    $sql = "INSERT INTO `pqr`(`ciudadano`, `estado_tra`, `anonimo`, `log_user`, `" . join("`, `", $into_p) . "`) VALUES (?, ?, ?, ?, " . str_repeat('?,', count($values_p) - 1) . "?)";

								    try {
								        // Prepare the SQL statement
								        $stmt = $conexion->prepare($sql);

								        // Add the additional values to the beginning of your values array
								        array_unshift($values_p, $id_rspta, 9, $anonim, $log_user);

								        // Dynamically generate the types for bind_param
								        $types = '';
								        foreach ($values_p as $value) {
								            // Assuming 'i' for integer and 's' for other types
								            $types .= is_int($value) ? 'i' : 's';
								        }


								        $params = array_merge(array($types), $values_p);

										// Create an array of references
										$refs = array();
										foreach($params as $key => $value) {
										    $refs[$key] = &$params[$key];
										}

										// Call bind_param with parameter array
										call_user_func_array(array($stmt, 'bind_param'), $refs);


								        // Execute the statement
								        $stmt->execute();

								        // Get the last inserted ID
								        $id_rspta = $stmt->insert_id;
								    } catch (Exception $e) {
								        echo $e;
								    }

									

									$id_pqr = $ar_p['id_bte'];
									// $res_p = $save_params($id_pqr, $id_rspta);

									// echo json_encode($array);
									// echo json_encode($ar_p);
									$message_rspta .= $id_rspta ? "$id_bte - Registrado Correctamente | " : "$id_bte - No se pudo registrar | $sql";
								}else{

									if (!empty($array['return']['peticiones'][$i]['fechaSolucion'])) {
										$sqlUpdate = "UPDATE `pqr` SET `estado` = 3 WHERE `id_bte` = $id_bte";
										ejecutarConsulta($sqlUpdate);
									}

									$message_rspta .= "$id_bte - Ya se encuentra registrado | ";
								}
							}
							$pageToken++;
						}else{
							$pageToken = null;
						}

					};				

					echo json_encode(array("result"=>$message_rspta));
			break;













































			
			
			case 'getParams':

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
			// _______________________________________________________________________________________consultarTiposAtencionPreferencial_________________________________________________________________________________________

				try{
				  	$soapclient = new SoapClient($WSDL, $options);
				    $response =$soapclient->consultarTiposAtencionPreferencial();
			
				    $array = json_decode(json_encode($response), true);
				    $array2 = json_decode(json_encode($array['return']['list']), true);

			      	// foreach($array as $item) {
				    //     echo '<pre>'; var_dump($item);
			      	// }  
				    
				    foreach($array2 as $item) {
				      	// print_r('<br>'.$item['id'].' __ '.$item['nombre'].' __ ');
				      	$id = $item['id'];
				      	$nombre = $item['nombre'];
			      		$sql = "INSERT IGNORE INTO `aten_pref` (`id`, `nombre`) VALUES ('$id','$nombre')";
				      	ejecutarConsulta($sql);
				    } 

				}catch(Exception $e){
				  echo $e->getMessage();
				}
			// _______________________________________________________________________________________consultarLocalidad - UPZ - Barrio_________________________________________________________________________________________
				  	
				try{
				  	$soapclient = new SoapClient($WSDL, $options);
				  	$parametersL = array('codigoCiudad' => array('codigoCiudad'=>11001));
				    $responseL =$soapclient->consultarLocalidad($parametersL);
			
				    $arrayL = json_decode(json_encode($responseL), true);
				    $arrayL2 = json_decode(json_encode($arrayL['return']['list']), true);

				    foreach($arrayL2 as $itemL) {
				      	$idL = $itemL['id'];
				      	$nombreL = $itemL['nombre'];

				      	$sqlL = "INSERT IGNORE INTO `localidades` (`id_local`, `localidad`) VALUES ('$idL','$nombreL')";
				      	ejecutarConsulta($sqlL);

				      	$parametersU = array('codigoLocalidad' => array('codigoLocalidad'=>$idL));
					    $responseU =$soapclient->consultarUpz($parametersU);
				
					    $arrayU = json_decode(json_encode($responseU), true);
					    $arrayU2 = json_decode(json_encode($arrayU['return']['list'] ?? []), true);

					    if (isset($arrayU2['id'])) {
					    	$arrayU3 = array();
					    	array_push($arrayU3, $arrayU2);
					    }else{
					    	$arrayU3 = $arrayU2;
					    }

					    foreach($arrayU3 as $itemU) {
					      	$idU = $itemU['id'];
					      	$idLocalidadU = $itemU['idLocalidad'];
					      	$nombreU = $itemU['nombre'];

					      	$sqlU = "INSERT IGNORE INTO `upz` (`id_upz`, `id_local`, `upz`) VALUES ('$idU', '$idLocalidadU', '$nombreU')";
					      	ejecutarConsulta($sqlU);

					      	$parametersB = array('codigoUpz' => array('codigoUpz'=>$idU));
						    $responseB =$soapclient->consultarBarrio($parametersB);
						    $arrayB = json_decode(json_encode($responseB), true);
						    $arrayB2 = json_decode(json_encode($arrayB['return']['list'] ?? []), true);

						    if (isset($arrayB2['id'])) {
						    	$arrayB3 = array();
						    	array_push($arrayB3, $arrayB2);
						    }else{
						    	$arrayB3 = $arrayB2;
						    }

						    foreach($arrayB3 as $itemB) {
						      	$idB = $itemB['id'];
						      	$idUpzB = $itemB['idUpz'];
						      	$nombreB = $itemB['nombre'];
						      	$codigoCatastroB = $itemB['codigoCatastro'] ?? 0;

						      	$sqlB = "INSERT IGNORE INTO `barrios` (`id_bar`, `id_upz`, `barrio`, `codigoCatastro`) VALUES ('$idB', '$idUpzB', '$nombreB', '$codigoCatastroB')";
						      	ejecutarConsulta($sqlB);
						    } 
					    } 
				    } 

				}catch(Exception $e){
				  echo $e->getMessage();
				}
			// _______________________________________________________________________________________consultarCanales_________________________________________________________________________________________

				try{
				  	$soapclient = new SoapClient($WSDL, $options);
				    $response =$soapclient->consultarCanales();
			
				    $array = json_decode(json_encode($response), true);
				    $array2 = json_decode(json_encode($array['return']['canalArray']), true);

				    foreach($array2 as $item) {
				      	$id = $item['codigoCanal'];
				      	$nombre = $item['nombre'];
			      		$sql = "INSERT IGNORE INTO `canal_rec` (`id_canal`, `name_canal`) VALUES ('$id','$nombre')";
				      	ejecutarConsulta($sql);
				    } 

				}catch(Exception $e){
				  echo $e->getMessage();
				}
			// _______________________________________________________________________________________consultarCategorias_________________________________________________________________________________________

				try{
				  	$soapclient = new SoapClient($WSDL, $options);
				  	$parameters = array('codigoEntidad' => array('codigoEntidad'=>2212));
				    $response =$soapclient->consultarCategorias($parameters);
			
				    $array = json_decode(json_encode($response), true);
				    $array2 = json_decode(json_encode($array['return']['list']), true);

				    foreach($array2 as $item) {
				      	$id = $item['id'];
				      	$nombre = $item['nombre'];
			      		$sql = "INSERT IGNORE INTO `categorias` (`id_cat`, `categoria`) VALUES ('$id','$nombre')";
				      	ejecutarConsulta($sql);
				    } 

				}catch(Exception $e){
				  echo $e->getMessage();
				}
			// _______________________________________________________________________________________consultarDependencia_________________________________________________________________________________________

				try{
				  	$soapclient = new SoapClient($WSDL, $options);
				  	$parameters = array('codigoEntidad' => array('codigoEntidad'=>2212));
				    $response =$soapclient->consultarDependencia($parameters);
			
				    $array = json_decode(json_encode($response), true);
				    $array2 = json_decode(json_encode($array['return']['list']), true);

				    foreach($array2 as $item) {
				      	$id = $item['id'];
				      	$nombre = $item['nombre'];
				      	$correoElectContacto = $item['correoElectContacto'];
			      		$sql = "INSERT IGNORE INTO `dependencias` (`id_dep`, `depend`, `email`) VALUES ('$id', '$nombre', '$correoElectContacto')";
				      	ejecutarConsulta($sql);
				    } 

				}catch(Exception $e){
				  echo $e->getMessage();
				}
			// _______________________________________________________________________________________consultarDiscapacidad_________________________________________________________________________________________

				try{
				  	$soapclient = new SoapClient($WSDL, $options);
				    $response =$soapclient->consultarDiscapacidad();
			
				    $array = json_decode(json_encode($response), true);
				    $array2 = json_decode(json_encode($array['return']['list']), true);

				    foreach($array2 as $item) {
				      	$id = $item['id'];
				      	$nombre = $item['nombre'];
			      		$sql = "INSERT IGNORE INTO `discapacidad` (`id`, `nombre`) VALUES ('$id', '$nombre')";
				      	ejecutarConsulta($sql);
				    } 

				}catch(Exception $e){
				  echo $e->getMessage();
				}
			// _______________________________________________________________________________________consultarTipoArchivo_________________________________________________________________________________________

				try{
				  	$soapclient = new SoapClient($WSDL, $options);
				  	$parameters = array('codigoCategoria' => "");
				    $response =$soapclient->consultarTipoArchivo($parameters);
			
				    $array = json_decode(json_encode($response), true);
				    $array2 = json_decode(json_encode($array['return']['list']), true);

				    foreach($array2 as $item) {
				      	$id = $item['id'];
				      	$nombre = $item['nombre'];
			      		$sql = "INSERT IGNORE INTO `doctype` (`id_doc`, `nombre`) VALUES ('$id', '$nombre')";
				      	ejecutarConsulta($sql);
				    } 

				}catch(Exception $e){
				  echo $e->getMessage();
				}
			// _______________________________________________________________________________________consultarEntidadesDistritales_________________________________________________________________________________________

				try{
				  	$soapclient = new SoapClient($WSDL, $options);
				    $response =$soapclient->consultarEntidadesDistritales();
			
				    $array = json_decode(json_encode($response), true);
				    $array2 = json_decode(json_encode($array['return']['list']), true);
				    
				    foreach($array2 as $item) {
				      	$id = $item['id'];
				      	$nombre = $item['nombre'];
			      		$sql = "INSERT IGNORE INTO `entidades_dist` (`id`, `entidad`) VALUES ('$id','$nombre')";
				      	ejecutarConsulta($sql);
				    } 

				}catch(Exception $e){
				  echo $e->getMessage();
				}
			// _______________________________________________________________________________________consultarEtnia_________________________________________________________________________________________

				try{
				  	$soapclient = new SoapClient($WSDL, $options);
				    $response =$soapclient->consultarEtnia();
			
				    $array = json_decode(json_encode($response), true);
				    $array2 = json_decode(json_encode($array['return']['list']), true);
				    
				    foreach($array2 as $item) {
				      	$id = $item['id'];
				      	$nombre = $item['nombre'];
			      		$sql = "INSERT IGNORE INTO `etnias` (`id_etnia`, `etnia`) VALUES ('$id','$nombre')";
				      	ejecutarConsulta($sql);
				    } 

				}catch(Exception $e){
				  echo $e->getMessage();
				}
			// _______________________________________________________________________________________consultarGeneros_________________________________________________________________________________________

				try{
				  	$soapclient = new SoapClient($WSDL, $options);
				    $response =$soapclient->consultarGeneros();
			
				    $array = json_decode(json_encode($response), true);
				    $array2 = json_decode(json_encode($array['return']['list']), true);
				    
				    foreach($array2 as $item) {
				      	$id = $item['id'];
				      	$nombre = $item['nombre'];
			      		$sql = "INSERT IGNORE INTO `generos` (`id_gen`, `genero`) VALUES ('$id','$nombre')";
				      	ejecutarConsulta($sql);
				    } 

				}catch(Exception $e){
				  echo $e->getMessage();
				}
			// _______________________________________________________________________________________consultarSectores_________________________________________________________________________________________

				try{
				  	$soapclient = new SoapClient($WSDL, $options);
				    $response =$soapclient->consultarSectores();
			
				    $array = json_decode(json_encode($response), true);
				    $array2 = json_decode(json_encode($array['return']['list']), true);
				    
				    foreach($array2 as $item) {
				      	$id = $item['codigoSector'];
				      	$nombre = $item['nombre'];
			      		$sql = "INSERT IGNORE INTO `sectores` (`id_sec`, `sector`) VALUES ('$id','$nombre')";
				      	ejecutarConsulta($sql);
				    } 

				}catch(Exception $e){
				  echo $e->getMessage();
				}
			// _______________________________________________________________________________________consultarTipoArchivo_________________________________________________________________________________________

				try{
				  	$soapclient = new SoapClient($WSDL, $options);
				  	$parameters = array('codigoCategoria' => "");
				    $response =$soapclient->consultarTipoArchivo($parameters);
			
				    $array = json_decode(json_encode($response), true);
				    $array2 = json_decode(json_encode($array['return']['list']), true);

				    foreach($array2 as $item) {
				      	$id = $item['id'];
				      	$nombre = $item['nombre'];
			      		$sql = "INSERT IGNORE INTO `subtema` (`id_sub`, `id_cat`, `subtema`) VALUES ('$id', '$nombre', '$nombre')";
				      	ejecutarConsulta($sql);
				    } 

				}catch(Exception $e){
				  echo $e->getMessage();
				}
			// _______________________________________________________________________________________consultarSubtema_________________________________________________________________________________________

				try{
				  	$soapclient = new SoapClient($WSDL, $options);
				  	$parameters = array('codigoEntidadTema' => array('codigoEntidadTema'=>1835));
				    $response =$soapclient->consultarSubtema($parameters);
			
				    $array = json_decode(json_encode($response), true);
				    $array2 = json_decode(json_encode($array['return']['list']), true);

				    foreach($array2 as $item) {
				      	$id = $item['id'];
				      	$idCategoria = $item['idCategoria'];
				      	$nombre = $item['nombre'];
			      		$sql = "INSERT IGNORE INTO `subtema` (`id_sub`, `id_cat`, `subtema`) VALUES ('$id', '$idCategoria', '$nombre')";
				      	ejecutarConsulta($sql);
				    } 

				}catch(Exception $e){
				  echo $e->getMessage();
				}
			// _______________________________________________________________________________________consultarPorIdTipoPersonaJuridica_________________________________________________________________________________________

				try{
				  	$soapclient = new SoapClient($WSDL, $options);
				  	$parameters = array('idTipoPersonaJuridica' => array('codigoPersona'=>""));
				    $response =$soapclient->consultarPorIdTipoPersonaJuridica($parameters);
			
				    $array = json_decode(json_encode($response), true);
				    $array2 = json_decode(json_encode($array['return']['list']), true);

				    foreach($array2 as $item) {
				      	$id = $item['id'];
				      	$nombre = $item['nombre'];
			      		$sql = "INSERT IGNORE INTO `per_juridica` (`id_jur`, `tipo_jur`) VALUES ('$id', '$nombre')";
				      	ejecutarConsulta($sql);


				      	$parametersB = array('idTipoPersonaJuridica' => array('codigoPersona'=>$id));
					    $responseB =$soapclient->consultarTiposEntidadPersonaJuridica($parametersB);
					    $arrayB = json_decode(json_encode($responseB), true);
					    $arrayB2 = json_decode(json_encode($arrayB['return']['list'] ?? []), true);

					    if (isset($arrayB2['id'])) {
					    	$arrayB3 = array();
					    	array_push($arrayB3, $arrayB2);
					    }else{
					    	$arrayB3 = $arrayB2;
					    }

					    foreach($arrayB3 as $itemB) {
					      	$idB = $itemB['id'];
					      	$nombreB = $itemB['nombre'];

					      	$sqlB = "INSERT IGNORE INTO `TiposEntidadPJ` (`id_ent`, `id_pj`, `nombre`) VALUES ('$idB', '$id', '$nombreB')";
					      	ejecutarConsulta($sqlB);
					    } 
				    } 

				}catch(Exception $e){
				  echo $e->getMessage();
				}
				
				echo json_encode(array("result"=>"Actualizado Correctamente"));  	
				  	
				
				
			break;
	

		}

	
	}
}
?>
