<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Firebase\JWT\JWT;

// Prevent vendor deprecation notices from being printed to responses
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);

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


		require_once "../modelo/Usuarios.php";

		$usuarios=new Usuarios($conexion);

		$id=isset($_POST["id"])? limpiarCadena($_POST["id"]):"";
		$documento=isset($_POST["documento"])? limpiarCadena($_POST["documento"]):"";
		$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
		$cargo=isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):"";
		$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
		$password=isset($_POST["password"])? limpiarCadena($_POST["password"]):"";
		$area=isset($_POST["area"])? limpiarCadena($_POST["area"]):"";


		$correo=isset($_POST["correo"])? limpiarCadena($_POST["correo"]):"";




		switch ($_GET["op"]){

			case 'guardaryeditar':

		$clavehash=hash("SHA256",$password);
				
			if (empty($id)){
					$rspta=$usuarios->insertar($documento,$nombre,$cargo,$email,$clavehash,$_POST['permiso'],$area);
					echo $rspta ? json_encode(array("result"=>"Registrado")) : json_encode(array("result"=>"No se pudo registrar el usuario"));
				}
				else {
					$rspta=$usuarios->editar($id,$documento,$nombre,$cargo,$email,$clavehash,$_POST['permiso'],$area);
					echo $rspta ? json_encode(array("result"=>"Usuario actualizado")) : json_encode(array("result"=>"El usuario no se pudo actualizar"));
				}
		   
			break;

			case 'mostrar':
				$rspta=$usuarios->mostrar($id);
		 		//Codificar el resultado utilizando json
		 		echo json_encode($rspta);
			break;


			case 'send_email':
				$sql = "SELECT * FROM `usuarios` WHERE `email` = '$correo' limit 1";
				$result = ejecutarConsultaSimpleFila($sql);

				if (isset($result['id'])) {
					require_once "/home/pqrsmetro/public_html/PHPMailer/PHPMailerAutoload.php";
					$mail = new PHPMailer;
					$mail->CharSet = 'UTF-8';
					$mail ->Host = 'smtp.gmail.com';
					$mail -> isMail();
					$mail -> setFrom('sgd@mab.com.co','MAB Ingeniería de Valor');
					$mail ->Subject ="Recuperación de contraseña PQRSD.";
					$mail -> addAddress('jsanchezr@mab.com.co');
					$mail -> addAddress($result['email']);

					$mail -> msgHTML('<html lang="es">
						<head>
						<meta charset="UTF-8">
						<title>MAB | Ticket</title>
						</head>
						<body>
						<div style="width:100%; background:#f6f6f6; position:relative; font-family:sans-serif; padding-bottom:40px">
						<div style="position:relative; margin:auto; width:600px; background:white; padding:20px">
						<center>
						<img style="padding:60px; width:35%" src="https://www.mab.com.co/wp-content/uploads/2021/02/Logo-MAB-1.png">

						
						<h3 style="font-weight:100; color:#000000 font-size:12px;">Recuperación de contraseña.</h3>
						</h4>

						<hr style="border:1px solid #000000; width:80%">

						<h3 style="font-weight:80; color:#000000 font-size:12px;">'.$result['nombre'].'</h3>
						</h4>

						<br>
						<a href="https://pqrsmetro.mab.com.co/vistas/send_email.php?op=send_email&user='.$result['id'].'"><button type="button" class="btn btn-warning">Cambiar contraseña</button></a>

						<hr style="border:1px solid #ccc; width:80%">

						</center>
						
						</div>

						</div>

						</body>
						</html>');
					$mail -> Send();
					echo json_encode(array("result"=>"Se ha enviado un correo con el enlace para cambiar la contraseña"));
				}else{
					echo json_encode(array("result"=>"El correo no se encuentra registrado en el sistema."));
				}
			break;


			case 'change_pass':

				$clavehash=hash("SHA256",$password);

				$rspta=$usuarios->change_pass($clavehash,$id);

				echo $rspta ? json_encode(array("result"=>"Contraseña actualizada")) : json_encode(array("result"=>"La contraseña no se pudo actualizar"));
			break;


			case 'listar':
				$rspta=$usuarios->listar();
		 		//Vamos a declarar un array
		 		$data= Array();

		 		while ($reg=$rspta->fetch_object()){
		 			$data[]=array(
		 				
		 				"0"=>$reg->documento,
		 				"1"=>$reg->nombre,
		 				"2"=>$reg->cargo,
		 				"3"=>$reg->email,
		 				"4"=>$reg->depend,
		 				"5"=>($reg->estado==1)?'<span class="label bg-green">Activo</span>':
		 				'<span class="label bg-red">Suspendido</span>',
		 				"6"=>($reg->estado==1)?'<button class="btn btn-warning" onclick="mostrar('.$reg->id.')"><i class="fas fa-pencil-alt"></i></button>'.'<button class="btn btn-danger" onclick="desactivarUsu('.$reg->id.')"><i class="fas fa-times"></i></button>':'<button class="btn btn-warning" onclick="mostrar('.$reg->id.')"><i class="fas fa-pencil-alt"></i></button>'.'<button class="btn btn-primary" onclick="activarUsu('.$reg->id.')"><i class="fas fa-check"></i></button>'
		 				
		 				
		 				);
		 		}
		 		$results = array(
		 			"sEcho"=>1, //Información para el datatables
		 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
		 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
		 			"aaData"=>$data);
		 		echo json_encode($results);					

			break;

			case 'desactivarUsu':
				$rspta=$usuarios->desactivarUsu($id);
				echo $rspta ? json_encode(array("result"=>"Usuario Suspendido")) : json_encode(array("result"=>"No se puede desactivar"));
			break;

			case 'activarUsu':
				$rspta=$usuarios->activarUsu($id);
				echo $rspta ? json_encode(array("result"=>"Usuario Activado")) : json_encode(array("result"=>"No se puede activar"));
			break;

			case "area":
				$rspta = $usuarios->area();
				$html = '<option value="" selected>Seleccione una opción</option>';
				while ($reg = $rspta->fetch_object())
						{
							$html .= '<option value=' . $reg->id_dep . '>' . $reg->depend .'</option>';
						}
				echo json_encode(array("result"=>$html));
			break;

			case 'permisos':
				//Obtenemos todos los permisos de la tabla permisos
				require_once "../modelo/Permiso.php";
				$permiso = new Permiso();
				$rspta = $permiso->listar();
				//Obtener los permisos asignados al usuario
				$id=$_GET['id'];
				$marcados = $usuarios->listarmarcados($id);
				//Declaramos el array para almacenar todos los permisos marcados
				$valores=array();

				//Almacenar los permisos asignados al usuario en el array
				while ($per = $marcados->fetch_object())
		        {
		          array_push($valores, $per->id_permiso);
		         
		        }
		        $html = '';
				//Mostramos la lista de permisos en la vista y si están o no marcados
				while ($reg = $rspta->fetch_object())
						{
							$sw=in_array($reg->id,$valores)?'checked':'';
							$html .= '<li> <input class="custom-control-input" type="radio" '.$sw.'  name="permiso[]" value="'.$reg->id.'" required>'.$reg->nombre.'</li>';
						}
						echo json_encode(array("result"=>$html));
			break;

			case 'verificar':
				$logina=$_POST['logina'];
			    $clavea=$_POST['clavea'];

			    //Hash SHA256 en la contraseña
				$clavehash=hash("SHA256",$clavea);

				$rspta=$usuarios->verificar($logina, $clavehash);

				$fetch=$rspta->fetch_object();
				
				if (isset($fetch))
		      {
		          //Declaramos las variables de sesión
		          $_SESSION['id']=$fetch->id;
		          $_SESSION['nombre']=$fetch->nombre;
		          $_SESSION['imagen']=$fetch->imagen;
		          $_SESSION['tipo']=$fetch->tipo;
		          $_SESSION['cargo']=$fetch->cargo;
		          $_SESSION['email']=$fetch->email;
		        
		          
		           //Obtenemos los permisos del usuario
		        $marcados = $usuarios->listarmarcados($fetch->id);

		        //Declaramos el array para almacenar todos los permisos marcados
		      $valores=array();

		      //Almacenamos los permisos marcados en el array
		      while ($per = $marcados->fetch_object())
		        {
		          array_push($valores, $per->id_permiso);
		         
		        }

		        //Determinamos los accesos del usuario
		      in_array(1,$valores)?$_SESSION['Usuario']=1:$_SESSION['Usuario']=0;
		      in_array(2,$valores)?$_SESSION['Admin']=1:$_SESSION['Admin']=0;
		      $_SESSION['JWT']=json_decode($_POST['token']);



		      }
			    echo json_encode($fetch);
			break;
		}

	}
}
?>