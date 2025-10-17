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
    text-decoration: none;" title="Refrescar pesta&ntilde;a">PQRSD</a></h1>
          </div>
          <script type="text/javascript">
            function refresh() {              
                window.location.reload();
                }
          </script>
          <div class="col-sm-6">
            <div class="breadcrumb float-sm-right">
              <h1 id="botonA">
                  
                  <button class="btn btn-info" onclick="getParams()"><i class="fas fa-sync-alt"></i> Actualizar parámetros</button>
                  
                  <a href="pqr.php"><button class="btn btn-danger"><i class="fa fa-arrow-circle-left"></i> Volver</button></a> </h1>
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
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Formulario PQRs</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <!-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                  </button> -->
                </div>
              </div>
              <div class="card-body">                        
                <div class="panel-body"  id="formularioregistros">

                  <nav class="form">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                      <a class="nav-item nav-link active" id="nav-idbte-tab" data-toggle="tab" href="#nav-idbte" role="tab" aria-controls="nav-idbte" aria-selected="true">ID BTE</a>
                      <a class="nav-item nav-link" id="nav-dates-tab" data-toggle="tab" href="#nav-dates" role="tab" aria-controls="nav-dates" aria-selected="false">Rango de fechas</a>
                    </div>
                  </nav>

                  <div class="tab-content form" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-idbte" role="tabpanel" aria-labelledby="nav-idbte-tab">

                      <div class="row" style="margin-bottom: 15px;margin-top: 20px;">
                        <div class="col-4 ident">
                          <label>ID Bogotá Te Escucha:</label>
                          <input type="text" class="form-control" id="id_bte"  maxlength="50" placeholder="ID BTE">
                        </div>
                      </div>
                      <button class="btn btn-primary" onclick="filtrobte_reque($('#id_bte').val());" id="btnGuardarfrom"><i class="fa fa-save"></i> Agregar</button>
                      <a href="pqr.php"><button class="btn btn-danger" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button></a>
                    </div>

                          
                    <div class="tab-pane fade show" id="nav-dates" role="tabpanel" aria-labelledby="nav-dates-tab">
                      <div class="row" style="margin-bottom: 15px;margin-top: 20px;">
                        <div class="col-4 ident">
                          <label>Fecha inicio:</label>
                          <input type="date" class="form-control" id="fecha_ini" placeholder="ID BTE">
                        </div>
                        <div class="col-4 ident">
                          <label>Fecha fin:</label>
                          <input type="date" class="form-control" id="fecha_fin" placeholder="ID BTE">
                        </div>
                      </div>
                      <button class="btn btn-primary" onclick="filtrobte_dates($('#fecha_ini').val(), $('#fecha_fin').val());" id="btnGuardarfrom"><i class="fa fa-save"></i> Agregar</button>
                      <a href="pqr.php"><button class="btn btn-danger" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button></a>
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
    <div class="modal fade" id="loading_modal">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-body">
              <img id="gif" src="pages/gif.gif" width="500">
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

  </div>
  <script type="text/javascript">
    const store = <?php echo json_encode($_SESSION['JWT']);?>;
  </script>
  <script type="text/javascript" src="scripts/bte.js"></script>
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