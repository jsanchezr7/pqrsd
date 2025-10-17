<?php
require "../config/Conexion.php";
if (strlen(session_id()) < 1) 
  session_start();  
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="apple-touch-icon" sizes="180x180" href="pages/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="pages/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="pages/favicon/favicon-16x16.png">
   <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Metro Bogotá PQRSD</title>
  <!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>

  <!-- Bootstrap 4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="plugins/moment/moment.min.js"></script>
<!-- jquery-validation -->
<script src="plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="plugins/jquery-validation/additional-methods.min.js"></script>
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- DATATABLES -->
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="dist/css/mab.css">
  <link rel="stylesheet" href="dist/css/yearpicker.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- colorpicker -->
  <link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="plugins/dropzone/dropzone.css">




<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- overlayScrollbars -->

<!-- daterangepicker -->
  <script src="plugins/moment/moment.min.js"></script>
  <script src="plugins/daterangepicker/daterangepicker.js"></script>

<!-- Summernote -->
  <script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- alertas js 
  <script src="../public/js/bootbox.min.js"></script> -->
  <!-- Toastr -->
  <script src="plugins/toastr/toastr.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
  <!-- color picker-->
  <script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
  <!-- dropzonejs -->
  <script src="plugins/dropzone/dropzone.js"></script>
  <!-- alertas js -->
  <script src="plugins/js/bootbox.min.js"></script>
  <!-- Select -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
  <script src="dist/js/yearpicker.js"></script>




  <script src="https://cdn.jsdelivr.net/npm/jquery.soap@1.7.3/jquery.soap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/x2js/1.2.0/xml2json.min.js" integrity="sha512-HX+/SvM7094YZEKOCtG9EyjRYvK8dKlFhdYAnVCGNxMkA59BZNSZTZrqdDlLXp0O6/NjDb1uKnmutUeuzHb3iQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



</head>
<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">

<!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="pages/logoM.png" alt="AdminLTELogo" width="300">
  </div>

 <!--  Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
   
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="pqr.php" class="nav-link">Inicio</a>
      </li>
    </ul>

   
    <ul class="navbar-nav ml-auto">

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" style="color:red;" onClick="logout()" role="button">
          <i class="fas fa-sign-out-alt" data-widget="iframe-close"> Cerrar Sesión </i>
        </a>
      </li>
    </ul>
  </nav>
  <script type="text/javascript">
    function logout(){
        location.href='logOut.php';
    }

</script>
 


  <aside class="main-sidebar main-sidebar-custom sidebar-dark-primary elevation-4" >
    
    <a href="index.php" class="brand-link mab-back" style="text-align: center;">
      <img src="pages/logoM.png" alt="MAB Logo" style="opacity: .8; max-height: 80px;">
    </a>


    <div class="sidebar" >
    
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <?php if (isset($_SESSION['imagen']))
            { ?>
          <img src="<?php  echo $_SESSION['imagen']; ?>" class="img-circle elevation-2" style="width: 2.3rem;">
        <?php } ?>
        </div>
        <div class="info">
          <a class="d-block"><?php echo $_SESSION['nombre']; ?></a>
        </div>
      </div>

      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Buscar" aria-label="Buscar">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <nav class="mt-2" style="padding-bottom: 120px !important;">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">PQRs</li>
          <li class="nav-item">
            <a href="pqr.php" class="nav-link">
              <i class="nav-icon fas fa-file"></i>
              <p>
                Formulario
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="seguimiento.php" class="nav-link">
              <i class="nav-icon fas fa-search"></i>
              <p>
                Seguimiento
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="datastudio.php" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Estadísticas
              </p>
            </a>
          </li>
          <li class="linea nav-header"></li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tools" style="color:#ffe79e;"></i>
              <p>
                Admin
                <i class="fas fa-angle-left right "></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="fests.php" class="nav-link">
                  <i class="far fa-calendar-times nav-icon"></i>
                  <p>Días Festívos</p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="places.php" class="nav-link">
                  <i class="fas fa-globe-americas nav-icon"></i>
                  <p>Localidades / Barrios</p>
                </a>
              </li> -->
              <li class="nav-item">
                <a href="bitacora.php" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Bitácora</p>
                </a>
              </li>
              <?php
            if ($_SESSION['Admin']==1)
            {
              ?>
              <li class="nav-item">
                <a href="usuarios.php" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Usuarios</p>
                </a>
              </li>
              
              <?php
            }
            ?>
            </ul>
          </li>
        
        
        </ul>
      </nav>
      <div class="user-panel" style="border-top: 1px solid #4f5962; border-bottom: 0px; padding-bottom: 0px;padding-top: 10px;background-color: #5a6570;position: fixed;bottom: 0px; width: 235px;height: 70px;" >
        <div class="info" style="width:100%;text-align: center;padding-left: 0px;cursor: pointer;" onclick="window.location.href = 'http://www.mabtec.com.co';">
          <p style="color:white;line-height: 5px;margin-bottom: 5px;">Un producto de:</p>
          <img src="pages/mabtec.svg" alt="MAB Logo" style="opacity: .8;width:30% !important;margin: auto;">
        </div>
      </div>
    </div>


  </aside>
 

  
