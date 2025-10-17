<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"]))
{
  header("Location: login.php");
}
else
{
  require 'header.php';
if ($_SESSION['Admin']==1 OR $_SESSION['Usuario']==1)
{

?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><a href="" onclick="refresh()" style=" color: black;
    text-decoration: none;" title="Refrescar pesta&ntilde;a">Dashboard PQRS Metro Bogotá</a></h1>
          </div>
          <script type="text/javascript">
            function refresh() {              
                window.location.reload();
                }
          </script>
          <div class="col-sm-6">
            <div class="breadcrumb float-sm-right">
              
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Default box -->
            <div class="card collapsed-card">
              <div class="card-header" data-card-widget="collapse" style="cursor: pointer;">
                <h3 class="card-title">Diagramas Informe:</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-plus"></i>
                  </button>
                  <!-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                  </button> -->
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                <select class="form-control" data-live-search="true" id="show">
                  <option value="1">Diagrama en Torta</option>
                  <option value="2">Diagrama en Barras</option>
                  <option value="3">Diagrama en Líneas</option>
                </select>
                <br>
                </div>
                <div id="print1" class="col-12">
                  <iframe id="frame1" width="100%" height="650" src="https://datastudio.google.com/embed/reporting/9a63d724-f0d3-40b2-836b-0681b8ff3b1e/page/4VDGB" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
                <!-- <div id="print2" class="col-12">
                  <iframe width="100%" height="650" src="https://datastudio.google.com/embed/reporting/9a63d724-f0d3-40b2-836b-0681b8ff3b1e/page/p_an7bo65gpc" frameborder="0" style="border:0" allowfullscreen></iframe>
               </div>
               <div id="print3" class="col-12">
                  <iframe width="100%" height="650" src="https://datastudio.google.com/embed/reporting/9a63d724-f0d3-40b2-836b-0681b8ff3b1e/page/p_mcinj68hpc" frameborder="0" style="border:0" allowfullscreen></iframe>
               </div> -->


                 </div>
                 <script type="text/javascript">
                $('#show').change(function(){
                  if (this.value==2) {
                    // $('#print1').hide();
                    // $('#print3').hide();
                    // $('#print2').show();
                    $("#frame1").attr("src", "https://datastudio.google.com/embed/reporting/9a63d724-f0d3-40b2-836b-0681b8ff3b1e/page/p_an7bo65gpc");
                  } else if (this.value==1) {
                    // $('#print2').hide();
                    // $('#print3').hide();
                    // $('#print1').show();
                    $("#frame1").attr("src", "https://datastudio.google.com/embed/reporting/9a63d724-f0d3-40b2-836b-0681b8ff3b1e/page/4VDGB");
                  } else {
                    // $('#print2').hide();
                    // $('#print3').show();
                    // $('#print1').hide();
                    $("#frame1").attr("src", "https://datastudio.google.com/embed/reporting/9a63d724-f0d3-40b2-836b-0681b8ff3b1e/page/p_mcinj68hpc");
                  }
                });
              </script>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div class="col-12">
          <!-- /.card -->
            <div class="card collapsed-card">
              <div class="card-header" data-card-widget="collapse" style="cursor: pointer;">
                <h3 class="card-title">PQRS pendientes por cerrar:</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-plus"></i>
                  </button>
                  <!-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                  </button> -->
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                <div class="col-12">
                  <iframe id="tabla" width="100%" height="750" src="https://datastudio.google.com/embed/reporting/9a63d724-f0d3-40b2-836b-0681b8ff3b1e/page/p_207rh64hpc" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
                 </div>
            </div>
          </div>
          <!-- /.card -->
        </div>


        <div class="col-12">
          <!-- /.card -->
            <div class="card collapsed-card">
              <div class="card-header" data-card-widget="collapse" style="cursor: pointer;">
                <h3 class="card-title">Tabla por tipo de solicitud:</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-plus"></i>
                  </button>
                  <!-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                  </button> -->
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                <div class="col-12">
                  <iframe width="100%" height="750" src="https://datastudio.google.com/embed/reporting/9a63d724-f0d3-40b2-836b-0681b8ff3b1e/page/p_anhc5y7hpc" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
                 </div>
            </div>
          </div>
          <!-- /.card -->
        </div>


      </div>
    </section>
    <!-- /.content -->
  </div>
  <script type="text/javascript" src="scripts/pqr.js"></script>
<?php
}
else
{
  require 'noacceso.php';
}
require 'footer.php';
?>
<?php 
}
ob_end_flush();
?>