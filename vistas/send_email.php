<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Metro Bogotá PQRSD | Olvidé mi contraseña</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <script src="https://code.jquery.com/jquery-3.6.1.slim.js" integrity="sha256-tXm+sa1uzsbFnbXt8GJqsgi2Tw+m4BLGDof6eUPjbtk=" crossorigin="anonymous"></script>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <div class="login-logo">
       <img style="width:90%;" class="logop" src="pages/Logo.png" >
      </div>
  </div>
  <!-- /.login-logo -->

  <?php   
  $option = $_GET["op"];
  $id = isset($_GET["user"])?$_GET["user"]:0;

  if ($option=='forgot') {
?>

  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Ingrese su correo electrónico</p>

      <form method="post" id="form_correo">
        <div class="input-group mb-3">
          <input type="text" id="correo" name="correo" class="form-control" placeholder="E-mail">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div style="text-align: center;" class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Reestablecer contraseña</button>
            <a href="login.php"><button type="button" class="btn btn-danger btn-block" style="margin-top: 15px;">Volver</button></a>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- /.social-auth-links -->
    </div>
    <!-- /.login-card-body -->
  </div>

<?php 
  }else{
?>

  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Ingrese su contraseña</p>

      <form method="post" id="form_pass">
        <div class="input-group mb-3">
          <input type="password" id="clavea" class="form-control" placeholder="Contraseña">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
          <label>Repita su contraseña</label>
        <div class="input-group mb-3">
          <input type="password" id="clavea2" class="form-control" placeholder="Contraseña">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div style="text-align: center;" class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Cambiar contraseña</button>
            <a href="login.php"><button type="button" style="margin-top: 15px;" class="btn btn-danger btn-block">Volver</button></a>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- /.social-auth-links -->
    </div>
    <!-- /.login-card-body -->
  </div>

<?php 
  }
?>
  
<script type="text/javascript">
  $("#form_pass").on('submit',function(e)
{
    e.preventDefault();
    clavea=$("#clavea").val();
    clavea2=$("#clavea2").val();
    id=<?php echo $id;?>;

    if (clavea==clavea2) {
        $.post("../ajax/usuarios.php?op=change_pass",
            {"password":clavea, "id":id},
            function(data)
        {
          bootbox.alert(data.result);
        });
    }else{
        bootbox.alert('Las contraseñas no coinciden');
    }

});
</script>







</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script type="text/javascript" src="scripts/login.js"></script>
</body>
</html>


