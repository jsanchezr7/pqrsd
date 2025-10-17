<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
session_start();
Class Pqr
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar_ciu($id_per_bte,$primerNombre,$segundoNombre,$primerApellido,$segundoApellido,$direccionResidencia,$codigoTipoIdentificacion,$numeroDocumento,$idAtencionPreferencial,$telefonoFijo,$celular,$nombreCompletoContacto,$idTipoPersona,$correoElectronico,$fechaNacimiento,$idGenero,$entidadDistrital,$localidad,$codigoUpz,$idBarrio,$idEstrato,$pbx,$direccionResidenciaContacto,$correoElectronicoContacto,$telefonoFijoContacto,$celularContacto,$cargoContacto,$notificacionFisica,$notificacionElectronica,$codigoPostal,$tipoPersonaJuridica,$codigoEntidadPrivada,$codigoTipoEntidad,$idEtnia,$idDiscapacidad,$idOrientacionSexual,$idEmbarazo,$adultoMayor,$digitoVerificacion)
	{
		$id_per_bte = ($id_per_bte=='' || $id_per_bte==0)?0:$id_per_bte;
		$log_user=$_SESSION['id'];
		$sqlc="INSERT INTO `ciudadano`(`codigoTipoIdentificacion`, `numeroDocumento`, `primerNombre`, `segundoNombre`, `primerApellido`, `segundoApellido`, `idGenero`, `fechaNacimiento`, `idAtencionPreferencial`, `telefonoFijo`, `pbx`, `nombreCompletoContacto`, `direccionResidenciaContacto`, `correoElectronicoContacto`, `telefonoFijoContacto`, `celularContacto`, `cargoContacto`, `correoElectronico`, `idBarrio`, `codigoUpz`, `localidad`, `direccionResidencia`, `codigoPostal`, `celular`, `idTipoPersona`, `tipoPersonaJuridica`, `entidadDistrital`, `codigoEntidadPrivada`, `idEstrato`, `notificacionFisica`, `notificacionElectronica`, `id_per_bte`, `codigoTipoEntidad`, `idEtnia`, `idDiscapacidad`, `idOrientacionSexual`, `idEmbarazo`, `adultoMayor`, `digitoVerificacion`, `log_user`) VALUES ('$codigoTipoIdentificacion','$numeroDocumento','$primerNombre','$segundoNombre','$primerApellido','$segundoApellido','$idGenero','$fechaNacimiento','$idAtencionPreferencial','$telefonoFijo','$pbx','$nombreCompletoContacto','$direccionResidenciaContacto','$correoElectronicoContacto','$telefonoFijoContacto','$celularContacto','$cargoContacto','$correoElectronico','$idBarrio','$codigoUpz','$localidad','$direccionResidencia','$codigoPostal','$celular','$idTipoPersona','$tipoPersonaJuridica','$entidadDistrital','$codigoEntidadPrivada','$idEstrato','$notificacionFisica','$notificacionElectronica','$id_per_bte','$codigoTipoEntidad','$idEtnia','$idDiscapacidad','$idOrientacionSexual','$idEmbarazo','$adultoMayor','$digitoVerificacion','$log_user')";
			//  echo $sqlc;
		return ejecutarConsulta_retornarID($sqlc);
	}

	public function editar_ciu($id_ciu,$id_per_bte,$primerNombre,$segundoNombre,$primerApellido,$segundoApellido,$direccionResidencia,$codigoTipoIdentificacion,$numeroDocumento,$idAtencionPreferencial,$telefonoFijo,$celular,$nombreCompletoContacto,$idTipoPersona,$correoElectronico,$fechaNacimiento,$idGenero,$entidadDistrital,$localidad,$codigoUpz,$idBarrio,$idEstrato,$pbx,$direccionResidenciaContacto,$correoElectronicoContacto,$telefonoFijoContacto,$celularContacto,$cargoContacto,$notificacionFisica,$notificacionElectronica,$codigoPostal,$tipoPersonaJuridica,$codigoEntidadPrivada,$codigoTipoEntidad,$idEtnia,$idDiscapacidad,$idOrientacionSexual,$idEmbarazo,$adultoMayor,$digitoVerificacion)
	{
		$log_user=$_SESSION['id'];
		$sqlc="UPDATE `ciudadano` SET `codigoTipoIdentificacion`='$codigoTipoIdentificacion', `numeroDocumento`='$numeroDocumento', `primerNombre`='$primerNombre', `segundoNombre`='$segundoNombre', `primerApellido`='$primerApellido', `segundoApellido`='$segundoApellido', `idGenero`='$idGenero', `fechaNacimiento`='$fechaNacimiento', `idAtencionPreferencial`='$idAtencionPreferencial', `telefonoFijo`='$telefonoFijo', `pbx`='$pbx', `nombreCompletoContacto`='$nombreCompletoContacto', `direccionResidenciaContacto`='$direccionResidenciaContacto', `correoElectronicoContacto`='$correoElectronicoContacto', `telefonoFijoContacto`='$telefonoFijoContacto', `celularContacto`='$celularContacto', `cargoContacto`='$cargoContacto', `correoElectronico`='$correoElectronico', `idBarrio`='$idBarrio', `codigoUpz`='$codigoUpz', `localidad`='$localidad', `direccionResidencia`='$direccionResidencia', `codigoPostal`='$codigoPostal', `celular`='$celular', `idTipoPersona`='$idTipoPersona', `tipoPersonaJuridica`='$tipoPersonaJuridica', `entidadDistrital`='$entidadDistrital', `codigoEntidadPrivada`='$codigoEntidadPrivada', `idEstrato`='$idEstrato', `notificacionFisica`='$notificacionFisica', `notificacionElectronica`='$notificacionElectronica', `id_per_bte`='$id_per_bte', `codigoTipoEntidad`='$codigoTipoEntidad', `idEtnia`='$idEtnia', `idDiscapacidad`='$idDiscapacidad', `idOrientacionSexual`='$idOrientacionSexual', `idEmbarazo`='$idEmbarazo', `adultoMayor`='$adultoMayor', `digitoVerificacion`='$digitoVerificacion', `log_user`='$log_user' WHERE `id_ciu`='$id_ciu'";
			// echo $sqlc;
		return ejecutarConsulta($sqlc);
	}



	public function insertar($id_bte_res,$id_emb,$id_ciu,$codigoOpcion,$codigoTipoRequerimiento,$tieneProcedencia,$esCopia,$asunto_obs,$codigoDependencia,$codigoProcesoCalidad,$codigoCanal,$codigoRedSocial,$numeroRadicado,$fechaRadicado,$numeroFolios,$observaciones,$codigoLocalidadPeticion,$codigoUpzPeticion,$codigoBarrioPeticion,$codigoEstratoPeticion,$direccionHechos,$ubicacionAproximada,$fecha_crea,$group_int,$ori_pqr,$estado,$estado_tra,$fecha_asigna,$fecha_ley,$notificacionFisica,$notificacionElectronica,$anonim,$codigoTipoIdentificacionRep,$numeroIdentificacionRep,$telefonoRep,$primerApellidoRep,$segundoApellidoRep,$primerNombreRep,$segundoNombreRep,$codigoTipoIdentificacionPod,$numeroIdentificacionPod,$telefonoPod,$primerApellidoPod,$segundoApellidoPod,$primerNombrePod,$segundoNombrePod,$idAtencionPreferencial,$correoElectronicoContacto,$nombreCompletoContacto,$telefonoFijoContacto,$celularContacto,$direccionResidenciaContacto)
	{
		$id_bte_res = ($id_bte_res=='' || $id_bte_res==0)?0:$id_bte_res;
		$log_user=$_SESSION['id'];
			
		$sql="INSERT INTO `pqr`(`id_bte`, `id_emb`, `ciudadano`, `codigoOpcion`, `codigoTipoRequerimiento`, `tieneProcedencia`, `esCopia`, `asunto_obs`, `codigoDependencia`, `codigoProcesoCalidad`, `codigoCanal`, `codigoRedSocial`, `numeroRadicado`, `fechaRadicado`, `numeroFolios`, `observaciones`, `codigoLocalidadPeticion`, `codigoUpzPeticion`, `codigoBarrioPeticion`, `codigoEstratoPeticion`, `direccionHechos`, `ubicacionAproximada`, `fecha_crea`, `group_int`, `ori_pqr`, `estado`, `estado_tra`, `fecha_asigna`, `fecha_ley`, `notificacionFisica`, `notificacionElectronica`, `anonimo`, `idAtencionPreferencial`, `correoElectronicoContacto`, `nombreCompletoContacto`, `telefonoFijoContacto`, `celularContacto`, `direccionResidenciaContacto`, `log_user`) VALUES ('$id_bte_res','$id_emb','$id_ciu','$codigoOpcion','$codigoTipoRequerimiento','$tieneProcedencia','$esCopia','$asunto_obs','$codigoDependencia','$codigoProcesoCalidad','$codigoCanal','$codigoRedSocial','$numeroRadicado','$fechaRadicado','$numeroFolios','$observaciones','$codigoLocalidadPeticion','$codigoUpzPeticion','$codigoBarrioPeticion','$codigoEstratoPeticion','$direccionHechos','$ubicacionAproximada','$fecha_crea','$group_int','$ori_pqr',1,'$estado_tra','$fecha_asigna','$fecha_ley','$notificacionFisica','$notificacionElectronica','$anonim','$idAtencionPreferencial','$correoElectronicoContacto','$nombreCompletoContacto','$telefonoFijoContacto','$celularContacto','$direccionResidenciaContacto','$log_user')";
			// echo $sql;
		$id_pqr = ejecutarConsulta_retornarID($sql);

		for ($i=0; $i < count($numeroIdentificacionRep); $i++) { 
			$sql_rep = "INSERT INTO `representado` (`id_pqr`,`codigoTipoIdentificacionRep`,`numeroIdentificacionRep`,`telefonoRep`,`primerApellidoRep`,`segundoApellidoRep`,`primerNombreRep`,`segundoNombreRep`) VALUES ('$id_pqr','$codigoTipoIdentificacionRep[$i]','$numeroIdentificacionRep[$i]','$telefonoRep[$i]','$primerApellidoRep[$i]','$segundoApellidoRep[$i]','$primerNombreRep[$i]','$segundoNombreRep[$i]')";
			ejecutarConsulta($sql_rep);
		}

		for ($i=0; $i < count($numeroIdentificacionPod); $i++) { 
			$sql_pod = "INSERT INTO `poderdante` (`id_pqr`,`codigoTipoIdentificacionPod`,`numeroIdentificacionPod`,`telefonoPod`,`primerApellidoPod`,`segundoApellidoPod`,`primerNombrePod`,`segundoNombrePod`) VALUES ('$id_pqr','$codigoTipoIdentificacionPod[$i]','$numeroIdentificacionPod[$i]','$telefonoPod[$i]','$primerApellidoPod[$i]','$segundoApellidoPod[$i]','$primerNombrePod[$i]','$segundoNombrePod[$i]')";
			ejecutarConsulta($sql_pod);
		}

		if ($_SESSION['Admin']==0) {
			$sql_asig="INSERT INTO `asignacion` (`id_pqr`,`asigna`,`id_user`,`observ`,`vigente`,`confirm`,`fecha_asign`) VALUES ($id_pqr,$log_user,$log_user,'Asignación Automática',1,1,CURRENT_TIMESTAMP())";
			ejecutarConsulta($sql_asig);
			$sql_a="UPDATE `pqr` SET `asign`=1 WHERE `id_pqr`=$id_pqr";
			ejecutarConsulta($sql_a);
		}

		return $id_pqr;

	}

	//Implementamos un método para editar registros
	public function editar($id,$id_bte,$id_emb,$id_ciu,$codigoOpcion,$codigoTipoRequerimiento,$tieneProcedencia,$esCopia,$asunto_obs,$codigoDependencia,$codigoProcesoCalidad,$codigoCanal,$codigoRedSocial,$numeroRadicado,$fechaRadicado,$numeroFolios,$observaciones,$codigoLocalidadPeticion,$codigoUpzPeticion,$codigoBarrioPeticion,$codigoEstratoPeticion,$direccionHechos,$ubicacionAproximada,$fecha_crea,$group_int,$ori_pqr,$estado,$estado_tra,$fecha_asigna,$fecha_ley,$notificacionFisica,$notificacionElectronica,$anonim,$id_pod,$id_rep,$codigoTipoIdentificacionRep,$numeroIdentificacionRep,$telefonoRep,$primerApellidoRep,$segundoApellidoRep,$primerNombreRep,$segundoNombreRep,$codigoTipoIdentificacionPod,$numeroIdentificacionPod,$telefonoPod,$primerApellidoPod,$segundoApellidoPod,$primerNombrePod,$segundoNombrePod,$idAtencionPreferencial,$correoElectronicoContacto,$nombreCompletoContacto,$telefonoFijoContacto,$celularContacto,$direccionResidenciaContacto)
	{
		$log_user=$_SESSION['id'];
		
		$sql="UPDATE `pqr` SET `id_emb`='$id_emb', `id_bte`='$id_bte', `codigoOpcion`='$codigoOpcion',`codigoTipoRequerimiento`='$codigoTipoRequerimiento',`tieneProcedencia`='$tieneProcedencia',`esCopia`='$esCopia',`asunto_obs`='$asunto_obs',`codigoDependencia`='$codigoDependencia',`codigoProcesoCalidad`='$codigoProcesoCalidad',`codigoCanal`='$codigoCanal',`codigoRedSocial`='$codigoRedSocial',`numeroRadicado`='$numeroRadicado',`fechaRadicado`='$fechaRadicado',`numeroFolios`='$numeroFolios',`observaciones`='$observaciones',`codigoLocalidadPeticion`='$codigoLocalidadPeticion',`codigoUpzPeticion`='$codigoUpzPeticion',`codigoBarrioPeticion`='$codigoBarrioPeticion',`codigoEstratoPeticion`='$codigoEstratoPeticion',`direccionHechos`='$direccionHechos',`ubicacionAproximada`='$ubicacionAproximada',`fecha_crea`='$fecha_crea',`group_int`='$group_int',`ori_pqr`='$ori_pqr',`estado`='$estado',`estado_tra`='$estado_tra',`fecha_asigna`='$fecha_asigna',`fecha_ley`='$fecha_ley',`notificacionFisica`='$notificacionFisica',`notificacionElectronica`='$notificacionElectronica',`idAtencionPreferencial`='$idAtencionPreferencial',`correoElectronicoContacto`='$correoElectronicoContacto',`nombreCompletoContacto`='$nombreCompletoContacto',`telefonoFijoContacto`='$telefonoFijoContacto',`celularContacto`='$celularContacto',`direccionResidenciaContacto`='$direccionResidenciaContacto',`log_user`='$log_user',`anonimo`='$anonim' WHERE `id_pqr`='$id'";
			
		// echo $sql;

		for ($i=0; $i < count($id_rep); $i++) { 
			if (intval($id_rep[$i])!=0) {
				$sql_rep = "UPDATE `representado` SET `codigoTipoIdentificacionRep`='$codigoTipoIdentificacionRep[$i]', `numeroIdentificacionRep`='$numeroIdentificacionRep[$i]', `telefonoRep`='$telefonoRep[$i]', `primerApellidoRep`='$primerApellidoRep[$i]', `segundoApellidoRep`='$segundoApellidoRep[$i]', `primerNombreRep`='$primerNombreRep[$i]', `segundoNombreRep`='$segundoNombreRep[$i]' WHERE `id_rep` = '$id_rep[$i]'";
			}else{
				$sql_rep = "INSERT INTO `representado` (`id_pqr`,`codigoTipoIdentificacionRep`,`numeroIdentificacionRep`,`telefonoRep`,`primerApellidoRep`,`segundoApellidoRep`,`primerNombreRep`,`segundoNombreRep`) VALUES ('$id','$codigoTipoIdentificacionRep[$i]','$numeroIdentificacionRep[$i]','$telefonoRep[$i]','$primerApellidoRep[$i]','$segundoApellidoRep[$i]','$primerNombreRep[$i]','$segundoNombreRep[$i]')";
			}
			ejecutarConsulta($sql_rep);
			// echo $sql_rep;
		}

		for ($i=0; $i < count($id_pod); $i++) { 
			if (intval($id_pod[$i])!=0) {
				$sql_pod = "UPDATE `poderdante` SET `codigoTipoIdentificacionPod`='$codigoTipoIdentificacionPod[$i]', `numeroIdentificacionPod`='$numeroIdentificacionPod[$i]', `telefonoPod`='$telefonoPod[$i]', `primerApellidoPod`='$primerApellidoPod[$i]', `segundoApellidoPod`='$segundoApellidoPod[$i]', `primerNombrePod`='$primerNombrePod[$i]', `segundoNombrePod`='$segundoNombrePod[$i]' WHERE `id_pod` = '$id_pod[$i]'";
			}else{
				$sql_pod = "INSERT INTO `poderdante` (`id_pqr`,`codigoTipoIdentificacionPod`,`numeroIdentificacionPod`,`telefonoPod`,`primerApellidoPod`,`segundoApellidoPod`,`primerNombrePod`,`segundoNombrePod`) VALUES ('$id','$codigoTipoIdentificacionPod[$i]','$numeroIdentificacionPod[$i]','$telefonoPod[$i]','$primerApellidoPod[$i]','$segundoApellidoPod[$i]','$primerNombrePod[$i]','$segundoNombrePod[$i]')";
			}
			ejecutarConsulta($sql_pod);
			// echo $sql_pod;
		}

		return ejecutarConsulta($sql);;
	}


	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($id)
	{
		$sql="SELECT `id_pqr`, `id_bte`, `id_emb`, `ciudadano`, `codigoOpcion`, `codigoTipoRequerimiento`, `tieneProcedencia`, `esCopia`, `asunto_obs`, `codigoDependencia`, `codigoProcesoCalidad`, `codigoCanal`, `codigoRedSocial`, `numeroRadicado`, `fechaRadicado`, `numeroFolios`, `observaciones`, `codigoLocalidadPeticion`, `codigoUpzPeticion`, `codigoBarrioPeticion`, `codigoEstratoPeticion`, `direccionHechos`, `ubicacionAproximada`, p.`notificacionFisica` as notificacionFisica_anonim, p.`notificacionElectronica` as notificacionElectronica_anonim, `fecha_crea`, `group_int`, `ori_pqr`, `estado`, `estado_tra`, `fecha_asigna`, `fecha_ley`, p.`idAtencionPreferencial` as idAtencionPreferencial_anonim, p.`nombreCompletoContacto` as nombreCompletoContacto_anonim, p.`telefonoFijoContacto` as telefonoFijoContacto_anonim, p.`celularContacto` as celularContacto_anonim, p.`direccionResidenciaContacto` as direccionResidenciaContacto_anonim, p.`correoElectronicoContacto` as correoElectronicoContacto_anonim, `asign`, `anonimo`, `categoria`, `subtema`, `fecha_cierre`, `rad_cierre`, `status`, `id_ciu`, `codigoTipoIdentificacion`, `numeroDocumento`, `primerNombre`, `segundoNombre`, `primerApellido`, `segundoApellido`, `idGenero`, `fechaNacimiento`, c.`idAtencionPreferencial` as idAtencionPreferencial, `idEtnia`, `idDiscapacidad`, `idOrientacionSexual`, `idEmbarazo`, `adultoMayor`, `digitoVerificacion`, `telefonoFijo`, `pbx`, c.`nombreCompletoContacto` as nombreCompletoContacto, c.`direccionResidenciaContacto` as direccionResidenciaContacto, c.`correoElectronicoContacto` as correoElectronicoContacto, c.`telefonoFijoContacto` as telefonoFijoContacto, c.`celularContacto` as celularContacto, `cargoContacto`, `correoElectronico`, `idBarrio`, `codigoUpz`, `localidad`, `direccionResidencia`, `codigoPostal`, `celular`, `idTipoPersona`, `tipoPersonaJuridica`, `entidadDistrital`, `codigoTipoEntidad`, `codigoEntidadPrivada`, `idEstrato`, c.`notificacionFisica` as notificacionFisica, c.`notificacionElectronica` as notificacionElectronica, `id_per_bte` FROM `pqr` p LEFT JOIN ciudadano c ON c.id_ciu=p.ciudadano WHERE `id_pqr`='$id'";
		return ejecutarConsultaSimpleFila($sql);
	}

	public function filtro($doc)
	{
		$sql="SELECT * FROM `ciudadano`WHERE `numeroDocumento` = '$doc'";
		// echo $sql;
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	

    public function listar($estados_filtro,$fecha_crea,$fecha_ini,$fecha_fin,$ori_pqr,$sel_canal)
	{
		$id_user=$_SESSION['id'];
		if ($_SESSION['Usuario']==1) {
			$sql="SELECT u.id,u.nombre,p.id_pqr, p.id_emb, `id_bte`, `codigoCanal`, p.estado, `ciudadano`, CONCAT(LEFT(asunto_obs, 50), IF(LENGTH(asunto_obs)>50, '…', '')) as asunto_obs, CONCAT(LEFT(observaciones, 50), IF(LENGTH(observaciones)>50, '…', '')) as observaciones, `ori_pqr`, `fecha_crea`, `fecha_cierre`, `codigoTipoRequerimiento`, `fecha_asigna`, `fecha_ley`, IFNULL(c.numeroDocumento,'Anónimo') as numeroDocumento, IFNULL(CONCAT(c.primerNombre,' ',c.segundoNombre,' ',c.primerApellido,' ',c.segundoApellido),'Anónimo') as primerNombre, `asign`,e.`nombre_est`,IFNULL(es.nombre_est, '') as est_tra, `anonimo`, vigente, confirm FROM pqr p LEFT JOIN ciudadano c ON c.id_ciu=p.ciudadano INNER JOIN estados e ON e.id_estado=p.estado LEFT JOIN estados es ON es.id_estado=p.estado_tra LEFT JOIN asignacion a ON a.id_pqr=p.id_pqr LEFT JOIN usuarios u ON u.id=a.id_user WHERE p.id_pqr IS NOT NULL and a.id_user='$id_user' and vigente = 1 and status = 1";
		}else{
			$sql="SELECT p.id_pqr as id_pqr2, p.id_emb, (SELECT GROUP_CONCAT(d.depend SEPARATOR ', ') FROM asignacion a INNER JOIN usuarios u ON u.id=a.id_user INNER JOIN dependencias d ON d.id_dep=u.area WHERE a.id_pqr=id_pqr2 AND vigente=1) as nombre, `id_bte`, `codigoCanal`, p.estado, `ciudadano`, CONCAT(LEFT(asunto_obs, 50), IF(LENGTH(asunto_obs)>50, '…', '')) as asunto_obs, CONCAT(LEFT(observaciones, 50), IF(LENGTH(observaciones)>50, '…', '')) as observaciones, `ori_pqr`, `fecha_crea`, `fecha_cierre`, `codigoTipoRequerimiento`, `fecha_asigna`, `fecha_ley`, IFNULL(c.numeroDocumento,'Anónimo') as numeroDocumento, IFNULL(CONCAT(c.primerNombre,' ',c.segundoNombre,' ',c.primerApellido,' ',c.segundoApellido),'Anónimo') as primerNombre, `asign`,e.`nombre_est`,IFNULL(es.nombre_est, '') as est_tra, `anonimo`, status FROM pqr p LEFT JOIN ciudadano c ON c.id_ciu=p.ciudadano INNER JOIN estados e ON e.id_estado=p.estado LEFT JOIN estados es ON es.id_estado=p.estado_tra WHERE p.id_pqr IS NOT NULL";
		}

		if ($estados_filtro>0) {
			$sql .=" AND p.estado = '$estados_filtro'";
		}
		if ($fecha_ini>0 && $fecha_fin>0) {
			$sql .=" AND `fecha_ley` BETWEEN '$fecha_ini' AND '$fecha_fin'";
		}
		
		if ($fecha_crea>0) {
            $sql .=" AND `fecha_crea` = '$fecha_crea'";
        }
		
		if (!empty($ori_pqr)) {
			$sql .=" AND `ori_pqr` = '$ori_pqr'";
		}
		if ($sel_canal>0) {
			$sql .=" AND `codigoCanal` = '$sel_canal'";
		}
			//$sql .=" GROUP BY id_pqr ORDER BY id_pqr DESC";
			$sql .=" ORDER BY id_pqr DESC";
			//echo $sql;
		return ejecutarConsulta($sql);		
	}


	public function confirmar($id_pqr){
	$sql="UPDATE `asignacion` SET `confirm`=1 WHERE `id_pqr`='$id_pqr'";
	return ejecutarConsulta($sql);
	}



	public function entrega_con($id_pqr,$nombre,$ver){
		$id_user=$_SESSION['id'];
		$sql="INSERT INTO `entregas`(`archivo`, `perfil_en`, `id_usu`, `id_pqr`, `date_ent`) VALUES ('$nombre','$ver','$id_user','$id_pqr',NOW())";
		// echo $sql;
		return ejecutarConsulta($sql);
	}

	public function contra($id,$con)
	{
		$sql="SELECT `id_entrega`, `archivo` FROM `entregas` WHERE `id_pqr`='$id' AND `perfil_en`='$con'";
		
		$result = ejecutarConsulta($sql);


		$id_entrega = array();
		$archivo = array();
		// $link = array();
		$data = array();

		while($row = mysqli_fetch_array($result))
    {
        array_push($id_entrega,$row['id_entrega']);
        array_push($archivo,$row['archivo']);
        // array_push($link,$row['link']);
    }

    $data['id_entrega'] = $id_entrega;
    $data['archivo'] = $archivo;
    // $data['link'] = $link;

	
    return $data;

	}


	public function entrega_view($id_pqr2,$observa_view,$estado_view,$id_ent,$date_respc,$rad_respc,$categoria,$subtema){

		$id_user=$_SESSION['id'];
		$sqlu="UPDATE `pqr` SET `estado`='$estado_view',`fecha_cierre`='$date_respc',`rad_cierre`='$rad_respc',`categoria`='$categoria',`subtema`='$subtema' WHERE `id_pqr`='$id_pqr2'";
		ejecutarConsulta($sqlu);

		// $sql="UPDATE `entregas` SET `obs_ev`='$observa_view' WHERE `id_entrega`='$id_ent'";
		// ejecutarConsulta($sql);

		$sqlobs="INSERT INTO `observaciones` (`observacion`, `id_pqr`, `fecha_obs`, `id_user`) VALUES ('$observa_view','$id_pqr2',CURDATE(),'$id_user')";
		return ejecutarConsulta($sqlobs);
	}

	

	public function fechas($codigoTipoRequerimiento){
		$sql="SELECT `days` FROM `tipo_sol` WHERE `id_sol`='$codigoTipoRequerimiento'";
		$re=ejecutarConsultaSimpleFila($sql);

		$sqlf="SELECT date_fest FROM fests WHERE year(date_fest)=year(CURDATE())";
		$result=ejecutarConsulta($sqlf);

		$data = array();
		$fests = array();

		while($row = mysqli_fetch_array($result))
    {
        array_push($fests,$row['date_fest']);
    }

    	$data['fests'] = $fests;
    	$data['n_days'] = $re['days'];

		return $data;
	}

	public function mostrarobs($id)
	{
		$sql="SELECT `id_observa`, if(`observacion`='','Sin observación', `observacion`) as observacion, `fecha_obs` FROM `observaciones` WHERE `id_pqr`='$id'";
		$result=ejecutarConsulta($sql);

		$data = array();
		$observa = array();
		$id_obs = array();
		$date_obs = array();

		while($row = mysqli_fetch_array($result))
    {
        array_push($observa,$row['observacion']);
        array_push($id_obs,$row['id_observa']);
        array_push($date_obs,$row['fecha_obs']);
    }

    $sqla="SELECT `id_asigna`, `id_pqr`, `asigna`, `id_user`, IFNULL(`observ`,'Asignado por Interventoría') as observ, `vigente`, `confirm`, `fecha_asign`, u.nombre as nom_as, u2.nombre FROM asignacion a LEFT JOIN usuarios u ON a.asigna=u.id LEFT JOIN usuarios u2 ON u2.id=a.id_user WHERE `id_pqr`='$id'";
		$resulta=ejecutarConsulta($sqla);

		$observ = array();
		$nom_as = array();
		$nombre = array();
		$fecha_asign = array();

		while($row = mysqli_fetch_array($resulta))
    {
        array_push($observ,$row['observ']);
        array_push($nom_as,$row['nom_as']);
        array_push($nombre,$row['nombre']);
        array_push($fecha_asign,$row['fecha_asign']);
    }

    	$data['id_obs'] = $id_obs;
    	$data['observa'] = $observa;
    	$data['date_obs'] = $date_obs;

    	$data['observ'] = $observ;
    	$data['nom_as'] = $nom_as;
    	$data['nombre'] = $nombre;
    	$data['fecha_asign'] = $fecha_asign;


		return $data;

	}


	public function eliminar($id_ent){

		$log_user=$_SESSION['id'];

		$sqlu="UPDATE `entregas` SET `log_user` = '$log_user' WHERE `id_entrega` = '$id_ent'";
		ejecutarConsulta($sqlu);

		$sql="DELETE FROM `entregas` WHERE `id_entrega` = '$id_ent'";
		
		return ejecutarConsulta($sql);
	}



	public function asignacion($id_pqrm,$people,$observa_asign){

		$id_user=$_SESSION['id'];
		$people = json_decode($people);
		$ids = array();
		$correo = array();
		for ($i=0; $i < count($people); $i++) { 
			$sql = "SELECT * FROM `asignacion` WHERE `id_pqr`='$id_pqrm' AND `id_user`='$people[$i]' AND `vigente` = 1";
			$re_sql = ejecutarConsultaSimpleFila($sql);

			if (!isset($re_sql)) {
				$sql_i="INSERT INTO `asignacion` (`id_pqr`,`asigna`,`id_user`,`observ`,`vigente`,`fecha_asign`) VALUES ('$id_pqrm','$id_user','$people[$i]','$observa_asign',1,CURRENT_TIMESTAMP())";
				$sql_id = ejecutarConsulta_retornarID($sql_i);
				array_push($ids, $sql_id);
				array_push($correo, $sql_id);
			}else{
				array_push($ids, $re_sql['id_asigna']);
			}
		}
		$ids = json_encode($ids);

		$ids = str_replace('[', '(', $ids);
		$ids = str_replace(']', ')', $ids);

		$sql_u="UPDATE `asignacion` SET `vigente`=0 WHERE `id_pqr`='$id_pqrm' AND `id_asigna` not in $ids";
		// echo $sql_u;
		ejecutarConsulta($sql_u);		

		$sql_a="UPDATE `pqr` SET `asign`=1 WHERE `id_pqr`='$id_pqrm'";
		ejecutarConsulta($sql_a);

		return $correo;
	}




	public function show_asign($id_pqr){
		$sql="SELECT * FROM `asignacion` WHERE `id_pqr`='$id_pqr' AND `vigente`=1";
		return ejecutarConsultaSimpleFila($sql);
	}


	public function show_cierre($id_pqr){
		$sql="SELECT `estado`,`fecha_cierre`,`rad_cierre`,`categoria`,`subtema`,`id_bte` FROM `pqr` WHERE `id_pqr`='$id_pqr'";
		return ejecutarConsultaSimpleFila($sql);
	}

	
	public function data_ciu($id_ciu)
	{
		$sql="SELECT * FROM `ciudadano` WHERE `id_ciu`='$id_ciu' ";
		return ejecutarConsultaSimpleFila($sql);
	}


	public function poderdantes($id_pqr){

		$sql="SELECT * FROM `poderdante` WHERE `id_pqr`='$id_pqr'";
		$result = ejecutarConsulta($sql);

		$data = array();

		$id_pod = array();
		$codigoTipoIdentificacionPod = array();
		$numeroIdentificacionPod = array();
		$telefonoPod = array();
		$primerApellidoPod = array();
		$segundoApellidoPod = array();
		$primerNombrePod = array();
		$segundoNombrePod = array();

		while ($row = mysqli_fetch_array($result)) {
			array_push($id_pod,$row['id_pod']);
			array_push($codigoTipoIdentificacionPod,$row['codigoTipoIdentificacionPod']);
			array_push($numeroIdentificacionPod,$row['numeroIdentificacionPod']);
			array_push($telefonoPod,$row['telefonoPod']);
			array_push($primerApellidoPod,$row['primerApellidoPod']);
			array_push($segundoApellidoPod,$row['segundoApellidoPod']);
			array_push($primerNombrePod,$row['primerNombrePod']);
			array_push($segundoNombrePod,$row['segundoNombrePod']);
		}

		$data['id_pod'] = $id_pod;
		$data['codigoTipoIdentificacionPod'] = $codigoTipoIdentificacionPod;
		$data['numeroIdentificacionPod'] = $numeroIdentificacionPod;
		$data['telefonoPod'] = $telefonoPod;
		$data['primerApellidoPod'] = $primerApellidoPod;
		$data['segundoApellidoPod'] = $segundoApellidoPod;
		$data['primerNombrePod'] = $primerNombrePod;
		$data['segundoNombrePod'] = $segundoNombrePod;

		return $data;
	}


	public function representados($id_pqr){

		$sql="SELECT * FROM `representado` WHERE `id_pqr`='$id_pqr'";
		$result = ejecutarConsulta($sql);

		$data = array();

		$id_rep = array();
		$codigoTipoIdentificacionRep = array();
		$numeroIdentificacionRep = array();
		$telefonoRep = array();
		$primerApellidoRep = array();
		$segundoApellidoRep = array();
		$primerNombreRep = array();
		$segundoNombreRep = array();

		while ($row = mysqli_fetch_array($result)) {
			array_push($id_rep,$row['id_rep']);
			array_push($codigoTipoIdentificacionRep,$row['codigoTipoIdentificacionRep']);
			array_push($numeroIdentificacionRep,$row['numeroIdentificacionRep']);
			array_push($telefonoRep,$row['telefonoRep']);
			array_push($primerApellidoRep,$row['primerApellidoRep']);
			array_push($segundoApellidoRep,$row['segundoApellidoRep']);
			array_push($primerNombreRep,$row['primerNombreRep']);
			array_push($segundoNombreRep,$row['segundoNombreRep']);
		}

		$data['id_rep'] = $id_rep;
		$data['codigoTipoIdentificacionRep'] = $codigoTipoIdentificacionRep;
		$data['numeroIdentificacionRep'] = $numeroIdentificacionRep;
		$data['telefonoRep'] = $telefonoRep;
		$data['primerApellidoRep'] = $primerApellidoRep;
		$data['segundoApellidoRep'] = $segundoApellidoRep;
		$data['primerNombreRep'] = $primerNombreRep;
		$data['segundoNombreRep'] = $segundoNombreRep;

		return $data;
	}

	public function remove_pond($id_pod){
		$sql="DELETE FROM `poderdante` WHERE `id_pod` = '$id_pod'";
		return ejecutarConsulta($sql);
	}


	public function remove_rep($id_rep){
		$sql="DELETE FROM `representado` WHERE `id_rep` = '$id_rep'";
		return ejecutarConsulta($sql);
	}

	public function count_files($id)
	{
		$sql="SELECT count(id_entrega) as num FROM entregas WHERE `id_pqr`='$id' AND `perfil_en`=2";
		return ejecutarConsultaSimpleFila($sql);
	}


	public function activar_pqr($id)
	{
		$sql="UPDATE `pqr` SET `status`=1 WHERE `id_pqr`='$id'";
		return ejecutarConsulta($sql);
	}

	public function desactivar_pqr($id)
	{
		$sql="UPDATE `pqr` SET `status`=0 WHERE `id_pqr`='$id'";
		return ejecutarConsulta($sql);
	}
	
	public function get_ext()
    {
        $sql="SELECT * FROM `doctype`";
        $res = ejecutarConsulta($sql);
        return mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

	public function seguimiento($id)
	{
		$sql="UPDATE `pqr` SET `seguimiento`= 1 WHERE `id_pqr`='$id'";
		return ejecutarConsulta($sql);
	}
	
	
}

?>