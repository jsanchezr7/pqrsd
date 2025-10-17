<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
session_start();
Class Seguimiento
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($id_pqr, $estado, $notas, $fecha)
	{
		$sql="INSERT INTO `seguimiento` (`id_pqr`, `estado`, `notas`, `fecha`) VALUES ($id_pqr, $estado, '$notas', '$fecha')";
		return ejecutarConsulta_retornarID($sql);
	}

	//Implementamos un método para editar registros
	public function editar($id, $estado, $notas, $fecha)
	{
		$sql="UPDATE `seguimiento` SET `estado`='$estado', `notas`='$notas', `fecha`='$fecha' WHERE `id`='$id'";
		ejecutarConsulta($sql);

		$sql="SELECT * FROM `seguimiento` WHERE `id` = $id";
		return ejecutarConsultaSimpleFila($sql);
	}


	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($id)
	{
		$sql="SELECT * FROM `seguimiento` WHERE `id` = $id";
		return ejecutarConsultaSimpleFila($sql);
	}




	public function listar($estados_filtro,$fecha_crea,$fecha_ini,$fecha_fin,$ori_pqr,$sel_canal)
	{
		$id_user=$_SESSION['id'];
		if ($_SESSION['Usuario']==1) {
			$sql="SELECT u.id,u.nombre,p.id_pqr, p.id_emb, `id_bte`, `codigoCanal`, p.estado, `ciudadano`, CONCAT(LEFT(asunto_obs, 50), IF(LENGTH(asunto_obs)>50, '…', '')) as asunto_obs, CONCAT(LEFT(observaciones, 50), IF(LENGTH(observaciones)>50, '…', '')) as observaciones, `ori_pqr`, `fecha_crea`, `fecha_cierre`, `codigoTipoRequerimiento`, `fecha_asigna`, `fecha_ley`, IFNULL(c.numeroDocumento,'Anónimo') as numeroDocumento, IFNULL(CONCAT(c.primerNombre,' ',c.segundoNombre,' ',c.primerApellido,' ',c.segundoApellido),'Anónimo') as primerNombre, `asign`,e.`nombre_est`,IFNULL(es.nombre_est, '') as est_tra, `anonimo`, vigente, confirm FROM pqr p LEFT JOIN ciudadano c ON c.id_ciu=p.ciudadano INNER JOIN estados e ON e.id_estado=p.estado LEFT JOIN estados es ON es.id_estado=p.estado_tra LEFT JOIN asignacion a ON a.id_pqr=p.id_pqr LEFT JOIN usuarios u ON u.id=a.id_user WHERE p.id_pqr IS NOT NULL and a.id_user='$id_user' and vigente = 1 and status = 1 and seguimiento = 1";
		}else{
			$sql="SELECT p.id_pqr as id_pqr2, p.id_emb, (SELECT GROUP_CONCAT(d.depend SEPARATOR ', ') FROM asignacion a INNER JOIN usuarios u ON u.id=a.id_user INNER JOIN dependencias d ON d.id_dep=u.area WHERE a.id_pqr=id_pqr2 AND vigente=1) as nombre, `id_bte`, `codigoCanal`, p.estado, `ciudadano`, CONCAT(LEFT(asunto_obs, 50), IF(LENGTH(asunto_obs)>50, '…', '')) as asunto_obs, CONCAT(LEFT(observaciones, 50), IF(LENGTH(observaciones)>50, '…', '')) as observaciones, `ori_pqr`, `fecha_crea`, `fecha_cierre`, `codigoTipoRequerimiento`, `fecha_asigna`, `fecha_ley`, IFNULL(c.numeroDocumento,'Anónimo') as numeroDocumento, IFNULL(CONCAT(c.primerNombre,' ',c.segundoNombre,' ',c.primerApellido,' ',c.segundoApellido),'Anónimo') as primerNombre, `asign`,e.`nombre_est`,IFNULL(es.nombre_est, '') as est_tra, `anonimo`, status FROM pqr p LEFT JOIN ciudadano c ON c.id_ciu=p.ciudadano INNER JOIN estados e ON e.id_estado=p.estado LEFT JOIN estados es ON es.id_estado=p.estado_tra WHERE p.id_pqr IS NOT NULL and seguimiento = 1";
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



	//Implementar un método para listar los registros
	public function listarHistorial($id)
	{
		$sql="SELECT s.*, `nombre_est` FROM `seguimiento` s INNER JOIN `estados` e ON e.id_estado = s.estado WHERE `id_pqr` = $id";
		return ejecutarConsulta($sql);		
	}



	public function save_files($allFiles, $id)
	{
		$archivos = implode(", ", $allFiles);
		$sql="UPDATE `seguimiento` SET `archivos` = '$archivos' WHERE `id` = $id";
		return ejecutarConsulta($sql);		
	}




	public function count_files($id)
	{
		$sql="SELECT * FROM `seguimiento` WHERE `id` = $id";
		return ejecutarConsultaSimpleFila($sql);		
	}





	public function eliminarFile($filename, $id)
	{
		$sql="SELECT * FROM `seguimiento` WHERE `id` = $id";
		$result = ejecutarConsultaSimpleFila($sql);

		$result = explode(", ", $result['archivos']);
		$newArray = array_diff($result, [$filename]);	

		$archivos = implode(", ", $newArray);
		$sql="UPDATE `seguimiento` SET `archivos` = '$archivos' WHERE `id` = $id";
		return ejecutarConsulta($sql);		

	}




	public function deleteSeg($id)
	{
		$sql="DELETE FROM `seguimiento` WHERE `id` = $id";
		return ejecutarConsulta($sql);		
	}





	
	
}

?>