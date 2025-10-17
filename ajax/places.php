<?php 
require_once "../modelo/Places.php";

$place=new Places();

$id=isset($_POST["id"])? limpiarCadena($_POST["id"]):"";
$tipo=isset($_POST["tipo"])? limpiarCadena($_POST["tipo"]):"";
$localidad=isset($_POST["localidad"])? limpiarCadena($_POST["localidad"]):"";
$sel_local=isset($_POST["sel_local"])? limpiarCadena($_POST["sel_local"]):"";
$barrio=isset($_POST["barrio"])? limpiarCadena($_POST["barrio"]):"";


$id_local=isset($_POST["id_local"])? limpiarCadena($_POST["id_local"]):"";
$local_edit=isset($_POST["local_edit"])? limpiarCadena($_POST["local_edit"]):"";
$sel_local_edit=isset($_POST["sel_local_edit"])? limpiarCadena($_POST["sel_local_edit"]):"";
$id_barrio=isset($_POST["id_barrio"])? limpiarCadena($_POST["id_barrio"]):"";
$barrio_edit=isset($_POST["barrio_edit"])? limpiarCadena($_POST["barrio_edit"]):"";



switch ($_GET["op"]){
	case 'guardaryeditar':
			$rspta=$place->insertar($localidad);
			echo $rspta ? "Localidad registrado" : "La localidad no se pudo registar";
	break;

	case 'guardar':
			$rspta=$place->guardar($sel_local,$barrio);
			echo $rspta ? "Barrio registrado" : "El barrio no se pudo registar";
	break;

	case 'editar':
			$rspta=$place->editar($local_edit,$id_local);
			echo $rspta ? "Localidad actualizado" : "La localidad no se pudo actualizar";
	break;

	case 'editar2':
			$rspta=$place->editar2($barrio_edit,$id_barrio,$sel_local_edit);
			echo $rspta ? "Barrio actualizado" : "El barrio no se pudo actualizar";
	break;

	case "sel_local":
		$rspta = $place->sel_local();
		echo '<option value="0">Seleccione una opción</option>';
		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->id_local . '>' . $reg->localidad .'</option>';
				}
	break;

	

	case 'mostrar':
		$rspta=$place->mostrar($id,$tipo);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;



	case 'listar':
		$rspta=$place->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				
 				"0"=>$reg->localidad,
 				"1"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->id_local .',1)"><i class="fas fa-pencil-alt"></i></button>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	case 'listar2':
		$rspta=$place->listar2();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				
 				"0"=>$reg->localidad,
 				"1"=>$reg->barrio,
 				"2"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->id_bar.',2)"><i class="fas fa-pencil-alt"></i></button>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;
}
?>