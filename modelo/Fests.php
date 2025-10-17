<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
session_start();
Class Fests
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($fechas)
	{
		$date=explode(',', $fechas);
		for ($i=0; $i < count($date); $i++) { 
		$sql="INSERT INTO `fests`(`date_fest`) VALUES ('$date[$i]')";
		ejecutarConsulta($sql);
		}
		return true;
	}

	//Implementamos un método para editar registros
	public function editar($fecha_fest,$id)
	{
		$sql="UPDATE `fests` SET `date_fest`='$fecha_fest' WHERE `id_fest`='$id'";
		return ejecutarConsulta($sql);
	}


	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($id)
	{
		$sql="SELECT * FROM fests WHERE id_fest='$id'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar($anio)
	{
		$sql="SELECT * FROM `fests` WHERE YEAR(date_fest) = $anio";
		return ejecutarConsulta($sql);		
	}

	public function listar_bi()
	{
		if ($_SESSION['Admin']==1) {
			$sql="SELECT t.tipo,tabla,old,new,valor_alterado,fecha,nombre,id_row FROM tablalogs t INNER JOIN usuarios u ON u.id=t.login ORDER BY id_log DESC";
		}else{
			$id_user = $_SESSION['id'];
			$sql="SELECT t.tipo,tabla,old,new,valor_alterado,fecha,nombre,t.id_row FROM tablalogs t INNER JOIN usuarios u ON u.id=t.login INNER JOIN asignacion a ON a.id_pqr=t.id_row WHERE (t.tabla='pqr' OR t.tabla='entregas') AND a.id_user=$id_user ORDER BY id_log DESC";
		}
		// echo $sql;
		return ejecutarConsulta($sql);		
	}
	
}

?>