<?php
require "../config/Conexion.php";
require "/home/pqrsmetro/public_html/PHPMailer/PHPMailerAutoload.php";

$sql="SELECT u.email,u.id,u.nombre,p.id_pqr, `id_bte`, `id_emb`, `canal_rec`, p.estado, `ciudadano`, `asunto_obs`, `temas`, `ori_pqr`, `fecha_crea`, `punto_ciu`, `tipo_soli`, `fecha_asigna`, `fecha_ley`, IFNULL(c.documento,'Anónimo') as documento, IFNULL(`name`,'Anónimo') as name, `asign`,`nombre_est`, `anonimo`, (CURDATE() - fecha_ley) as dif FROM pqr p LEFT JOIN ciudadano c ON c.id_ciu=p.ciudadano INNER JOIN estados e ON e.id_estado=p.estado LEFT JOIN asignacion a ON a.id_pqr=p.id_pqr LEFT JOIN usuarios u ON u.id=a.id_user WHERE p.estado!=3 AND `fecha_ley` BETWEEN (CURDATE() - INTERVAL 7 DAY) AND CURDATE() limit 1";
	$result = ejecutarConsulta($sql);


		while($row = mysqli_fetch_array($result))
    {
    	$email=$row['email'];
    	$nombre=$row['nombre'];
    	$asunto=$row['asunto_obs'];
    	$tema=$row['temas'];
    	$id_pqr=$row['id_pqr'];
    	$id_bte=$row['id_bte'];
    	$id_emb=$row['id_emb'];
    	$dif=$row['dif'];

    	try {
    //Server settings
  $dominio='https://mantenimiento.sersia.co';
	$mail = new PHPMailer;
	$mail->CharSet = 'UTF-8';
	$mail ->Host = 'smtp.gmail.com';
	$mail -> isMail();
	$mail -> setFrom('jlozano@mab.com.co','MAB Ingeniería de Valor');
	$mail ->Subject ="Correo de compartido.";
	$mail -> addAddress($email);  
	// $mail -> addAddress('jsanchezr@mab.com.co');  
	$mail->Port       = 25;   //Add a recipient
                                    //Set email format to HTML
    $mail->Subject = 'Periodo de vencimiento de PQR';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail -> msgHTML('<html lang="es">

<style>
.clase_table {
    border-collapse: separate;
    border-spacing: 10;
    border: 1px solid black;
    border-radius: 15px;
    -moz-border-radius: 20px;
    padding: 40px;

}
</style>

	<font face="Arial,verdana">
	<head>
		<meta charset="UTF-8">
		<link rel="shortcut icon" href="../public/img/favicon.png">
		<title>Asignación PQR</title>
	</head> 
 
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="http://informesagiles.mab.com.co/pdf.js/public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="http://informesagiles.mab.com.co/pdf.js/public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="http://informesagiles.mab.com.co/pdf.js/public/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="http://informesagiles.mab.com.co/pdf.js/public/dist/css/mab.css">
  <link rel="stylesheet" href="http://informesagiles.mab.com.co/pdf.js/public/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css">

	<body >	

	<div class="row">
			<div class="col-12" style="
			background-color:#0E4D83;
			width:100%;
			height:100px;
			border-radius: 1px;
			">

			<img src="https://www.mab.com.co/wp-content/uploads/2021/02/Logo-MAB-1.png" style="position: relative;padding:15px; " >
			</div>
			
			<table>
			<tr>	
			<td><h5 style="font-size:25px;">Buen día '.$nombre.'.</h5></td>
            </tr>
            <tr>	
			<td><p style="position:absolute;font-size:15px; text-align: justify; top:10px;">El PQR con tema: "'.$tema.'" vencerá en "'.$dif.'" días. </p>
            </td>
            </tr>
            <tr>
            <td><p style="position:absolute;font-size:15px; text-align: justify; top:10px;">Asunto del PQR: "'.$asunto.'". </p>
            </td>
            </tr>

			</table>

				<table class="clase_table" align="center">
		  <td><img style="; width:40%;" src="http://informesagiles.mab.com.co/pdf.js/public/dist/img/interventoria.jpg"></td>
		  <td style="font-size:15px;">Información para su consulta: <br>
          <b>ID PQR : '.$id_pqr. '<br>
		  <b>ID Bogotá te escucha: '.$id_bte.'<br>
		  <b>ID EMB: '.$id_emb.'<br>
		  <a href="https://pqrsmetro.mab.com.co/vistas/pqr.php" target="_blank" style="position:absolute; left:10px;"> <button style="transition: background-color .3s, box-shadow .3s;
    
  padding: 12px 68px 12px 68px;
  border: none;
  border-radius: 3px;
  box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 1px 1px rgba(0, 0, 0, .25);
  
  color: #FFFFFF !important;
  font-size: 14px;
  font-weight: 500;
  
  background-color: #0E4D83;">Click aqui para ir a la página.</button></a>
		  </td>
		  <td>
		 
		  </td>
		   <td>
		  
		  </td>

		</table>
				
	
			<div class="col-12" style="
			margin-top:90px;
			background-color:#0E4D83;
			width:100%;
			height:100px;
			border-radius: 10px;">
			</div>


			</div>

	</body>
	</html>');

    $mail->send();
    // echo 'Se envio el correo'.$email;
} catch (Exception $e) {
    echo " {$mail->ErrorInfo}";
}

    }


?>