<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

Class Usuarios
{
	//Implementamos nuestro constructor
	
	
	private $conexion;
    //Implementamos nuestro constructor
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

	//Implementamos un método para insertar registros
	public function insertar($documento,$nombre,$cargo,$email,$clavehash,$permisos,$area)
	{
		$log_user=$_SESSION['id'];
		$sql="INSERT INTO `usuarios`(`documento`, `nombre`, `cargo`, `email`, `password`, `area`, `log_user`) VALUES ('$documento','$nombre','$cargo','$email','$clavehash','$area','$log_user')";
		echo $sql;
		$id= ejecutarConsulta_retornarID($sql);


		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($permisos))
		{
			$sql_detalle = "INSERT INTO u_permiso(id_user, id_permiso) VALUES('$id', '$permisos[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos=$num_elementos + 1;
		}
		
		// return $sw;
	}

	//Implementamos un método para editar registros
	public function editar($id,$documento,$nombre,$cargo,$email,$clavehash,$permisos,$area)
	{
		$log_user=$_SESSION['id'];
		
		$sql="UPDATE usuarios SET `documento`='$documento',`nombre`='$nombre',`cargo`='$cargo',`email`='$email',`password`='$clavehash',`area`='$area',`log_user`='$log_user' WHERE id='$id'";
		ejecutarConsulta($sql);

		$sqldel="DELETE FROM u_permiso WHERE id_user='$id'";
		ejecutarConsulta($sqldel);

		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($permisos))
		{
			$sql_detalle = "INSERT INTO u_permiso(id_user, id_permiso) VALUES('$id', '$permisos[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos=$num_elementos + 1;
		}

		return $sw;

	}

	
	public function mostrar($id)
	{
		$sql="SELECT * FROM usuarios WHERE id='$id'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT `id`, `documento`, `nombre`, `cargo`, u.`email`, `password`, `estado`, `imagen`, `fecha_registro`, `tipo`, `area`, IFNULL(`depend`,'Usuario') as depend FROM usuarios u LEFT JOIN dependencias d ON d.id_dep=u.area";
		return ejecutarConsulta($sql);		
	}

	public function area(){

	$sql="SELECT id_dep,depend FROM `dependencias`";
	return ejecutarConsulta($sql);
	}
	

	public function desactivarUsu($id)
	{
		$sql="UPDATE `usuarios` SET estado=0 WHERE id='$id'";
		return ejecutarConsulta($sql);
	}

	public function activarUsu($id)
	{
		$sql="UPDATE `usuarios` SET estado=1 WHERE id='$id'";
		return ejecutarConsulta($sql);
	}

	public function change_pass($clavehash,$id)
	{
		$sql="UPDATE `usuarios` SET `password`='$clavehash' WHERE `id`='$id'";
		return ejecutarConsulta($sql);
	}

	public function listarmarcados($id)
	{
		$sql="SELECT id,id_user,id_permiso FROM u_permiso WHERE id_user='$id'";
		return ejecutarConsulta($sql);
	}



    public function verificar($login,$clave)
    {

        $sql="SELECT id, documento, nombre, cargo, email, password, estado, imagen, tipo FROM usuarios WHERE email=? AND password=? AND estado='1'"; 

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param("ss", $login, $clave);

        return ejecutarConsultaU($stmt);  
    }

}

?>