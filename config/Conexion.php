<?php 
require_once "global.php";

$conexion = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);

mysqli_query( $conexion, 'SET NAMES "'.DB_ENCODE.'"');

//Si tenemos un posible error en la conexión lo mostramos
if (mysqli_connect_errno())
{
	printf("Falló conexión a la base de datos: %s\n",mysqli_connect_error());
	exit();
}

if (!function_exists('ejecutarConsulta'))
{
	function ejecutarConsulta($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);
		return $query;
	}

	function ejecutarConsultaSimpleFila($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);		
		$row = $query->fetch_assoc();
		return $row;
	}

	function ejecutarConsulta_retornarID($sql)
	{
		global $conexion;
		$query = $conexion->query($sql);		
		return $conexion->insert_id;			
	}

	function limpiarCadena($str)
	{
		global $conexion;
		$str = mysqli_real_escape_string($conexion,trim($str));
		return htmlspecialchars($str);
	}




	

	function ejecutarConsultaU($stmt)
    {
        $flag;
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $flag = $result;
        } else {
            $flag = false;
            echo "Error: " . $stmt->error;
        }

 

        $stmt->close();
        return $flag;
    }


    function insert_and_update($stmt)
	{
		$flag;
		if ($stmt->execute()) {
			$flag = true;
		} else {
			$flag = false;
		    echo "Error: " . $stmt->error;
		}

		$stmt->close();
		return $flag;
	}


	function ejecutarConsultaReturn($stmt)
	{
		global $conexion;
		$flag;
		if ($stmt->execute()) {
			$flag = $conexion->insert_id;
		} else {
			$flag = false;
		    echo "Error: " . $stmt->error;
		}

		$stmt->close();			
		return $flag;
	}
	
	
}
?>