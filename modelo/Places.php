<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Places
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($localidad)
	{
		$sql="INSERT INTO `localidades`(`localidad`) VALUES ('$localidad')";
		return ejecutarConsulta($sql);
	}

	public function guardar($sel_local,$barrio)
	{
		$sql="INSERT INTO `barrios`(`id_local`, `barrio`) VALUES ('$sel_local','$barrio')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($local_edit,$id_local)
	{
		$sql="UPDATE `localidades` SET `localidad`='$local_edit' WHERE `id_local`='$id_local'";
		return ejecutarConsulta($sql);
	}

	public function editar2($barrio_edit,$id_barrio,$sel_local_edit)
	{
		$sql="UPDATE `barrios` SET `barrio`='$barrio_edit', `id_local`='$sel_local_edit' WHERE `id_bar`='$id_barrio'";
		return ejecutarConsulta($sql);
	}


	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($id,$tipo)
	{
		if ($tipo==1) {
			$sql="SELECT * FROM `localidades` WHERE `id_local`='$id'";
			return ejecutarConsultaSimpleFila($sql);
		} else {
			$sql="SELECT * FROM `barrios` WHERE `id_bar`='$id'";
			return ejecutarConsultaSimpleFila($sql);
		}
	}

	public function sel_local()
	{
		$sql="SELECT * FROM `localidades`";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM `localidades`";
		return ejecutarConsulta($sql);		
	}

	public function listar2()
	{
		$sql="SELECT `id_bar`, `barrio`, `localidad` FROM barrios b INNER JOIN localidades l ON l.id_local=b.id_local";
		return ejecutarConsulta($sql);		
	}
	
}

?>