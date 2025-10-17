<?php 

require "../config/Conexion.php";


Class Select
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	public function sel_canal_rec(){
	$sql="SELECT * FROM `canal_rec`";
	return ejecutarConsulta($sql);
	}

	public function sel_upz($localidad){
	$sql="SELECT * FROM `upz` WHERE `id_local` = '$localidad'";
	return ejecutarConsulta($sql);
	}

	public function sel_barrio($codigoUpz){
	$sql="SELECT * FROM `barrios` WHERE `id_upz` = '$codigoUpz'";
	return ejecutarConsulta($sql);
	}

	public function sel_subtema($categoria){
	$sql="SELECT * FROM `subtema` WHERE `id_cat` = '$categoria'";
	return ejecutarConsulta($sql);
	}

	public function sel_area(){
	$sql="SELECT * FROM `dependencias`";
	return ejecutarConsulta($sql);
	}

	public function sel_usu(){
	$sql="SELECT id,nombre FROM `usuarios`";
	return ejecutarConsulta($sql);
	}

	public function sel_tipoid(){
	$sql="SELECT `id_id`, `abreviatura`, `descripcion` FROM `tipo_identificacion`";
	return ejecutarConsulta($sql);
	}

	public function sel_genero(){
	$sql="SELECT `id_gen`, `genero` FROM `generos`";
	return ejecutarConsulta($sql);
	}

	public function sel_aten_pref(){
	$sql="SELECT `id`, `nombre` FROM `aten_pref` ORDER BY `id` DESC";
	return ejecutarConsulta($sql);
	}

	public function sel_juridica(){
	$sql="SELECT `id_jur`, `tipo_jur` FROM `per_juridica`";
	return ejecutarConsulta($sql);
	}

	public function sel_user($area){
	$sql="SELECT `id`, `nombre` FROM usuarios WHERE area = '$area' AND id not in (SELECT id_user FROM u_permiso WHERE id_permiso=2)";
	return ejecutarConsulta($sql);
	}

	public function sel_categoria(){
	$sql="SELECT * FROM `categorias`";
	return ejecutarConsulta($sql);
	}

	public function sel_estado(){
	$sql="SELECT * FROM `estados` WHERE `tipo_es`=1";
	return ejecutarConsulta($sql);
	}

	public function sel_estado_tra(){
	$sql="SELECT * FROM `estados` WHERE `tipo_es`=2";
	return ejecutarConsulta($sql);
	}

	public function sel_estado_int(){
	$sql="SELECT * FROM `estados` WHERE `id_estado` IN (3,4)";
	return ejecutarConsulta($sql);
	}

	public function sel_opcion_pet(){
	$sql="SELECT * FROM `opcion_pet`";
	return ejecutarConsulta($sql);
	}

	public function sel_localidad(){
		$sql="SELECT * FROM `localidades`";
		return ejecutarConsulta($sql);
	}

	public function sel_tipo_soli(){
		$sql="SELECT * FROM `tipo_sol`";
		return ejecutarConsulta($sql);
	}

	public function sel_dependencia(){
		$sql="SELECT * FROM `dependencias`";
		return ejecutarConsulta($sql);
	}

	public function sel_entidad_dist(){
		$sql="SELECT * FROM `entidades_dist`";
		return ejecutarConsulta($sql);
	}

	public function sel_tipoPersonaJuridica($tipoPersonaJuridica){
		$sql="SELECT * FROM `TiposEntidadPJ` WHERE `id_pj` = '$tipoPersonaJuridica'";
		return ejecutarConsulta($sql);
	}

	public function sel_etnia(){
		$sql="SELECT * FROM `etnias` ORDER BY `id_etnia` DESC";
		return ejecutarConsulta($sql);
	}

	public function sel_idDiscapacidad(){
		$sql="SELECT * FROM `discapacidad` ORDER BY `id` DESC";
		return ejecutarConsulta($sql);
	}













	public function sel_estado_seg(){
	$sql="SELECT * FROM `estados` WHERE `id_estado` IN (1, 13, 3)";
	return ejecutarConsulta($sql);
	}

}

?>