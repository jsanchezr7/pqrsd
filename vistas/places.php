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
    text-decoration: none;" title="Refrescar pesta&ntilde;a">Localidades y Barrios</a></h1>
          </div>
          <script type="text/javascript">
            function refresh() {              
                window.location.reload();
                }
          </script>
          <div class="col-sm-6">
            <div class="breadcrumb float-sm-right">
              <h1 id="botonA"><span style="font-size:20px;">Nuevo Ingreso</span>
                            <button id="btnagregar" class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button><button id="btnvolver" class="btn btn-danger" onclick="cancelarform()"><i class="fa fa-arrow-circle-left"></i> Volver</button> </h1>
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
                <h3 class="card-title">Formulario</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              
              <div class="card-body">
                <div id="listadoregistros">
                  <div class="row">
                    <div class="col-6">
                      <h4>Tabla Localidades</h4>
                      <hr>
                      <table id="tbllistado" class="table table-bordered table-hover">
                          <thead>
                          <th>Localidad</th>
                           <th>Opciones</th>
                          </thead>
                          <tbody>

                            
                          </tbody>
                          <tfoot>
                          <th>Localidad</th>
                           <th>Opciones</th>
                          </tfoot>
                        </table>
                        </div>
                        <div class="col-6">
                          <h4>Tabla Barrios</h4>
                          <hr>
                      <table id="tbllistado2" class="table table-bordered table-hover">
                          <thead>
                          <th>Localidad</th>
                          <th>Barrio</th>
                           <th>Opciones</th>
                          </thead>
                          <tbody>

                            
                          </tbody>
                          <tfoot>
                          <th>Localidad</th>
                          <th>Barrio</th>
                           <th>Opciones</th>
                          </tfoot>
                        </table>
                        </div>
                      </div>
                    </div>
                        
                          <div class="panel-body"  id="formularioregistros">

                            <nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Localidades</a>
    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Barrios</a>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
    <br>
    <form name="formulario" id="formulario" method="POST">
                        <div class="row" >
                          <div class="col-3">
                            <label>Localidad:</label>
                            <input type="text" class="form-control" name="localidad" id="localidad">
                          </div>
                        </div>
                         

                <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                     
              </form>
  </div>
  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
    <br>
    <form name="formulario2" id="formulario2" method="POST">
                            <div class="row" >
                              <div class="col-3">
                            <label>Localidad:</label>
                            <select class="form-control" data-live-search="true" id="sel_local" name="sel_local" required="required"></select>
                          </div>
                            <div class="col-3">
                            <label>Barrio:</label>
                            <input type="text" class="form-control" name="barrio" id="barrio">
                          </div>
                          
                        </div>
                         

                <button class="btn btn-primary" type="submit" id="btnGuardar2"><i class="fa fa-save"></i> Guardar</button>

                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                     
              </form>
  </div>
</div>

                            
            </div>

            <div id="editar">
              <form name="formularioedit" id="formularioedit" method="POST">
                        <div class="row" >
                          <div class="col-3">
                            <label>Localidad:</label>
                            <input type="hidden" name="id_local" id="id_local">
                            <input type="text" class="form-control" name="local_edit" id="local_edit">
                          </div>
                        </div>
                         

                <button class="btn btn-primary" type="submit" id="btnEditar"><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                     
              </form>
            </div>

            <div id="editar2">
              <form name="formularioedit2" id="formularioedit2" method="POST">
                        <div class="row">
                          <div class="col-3">
                            <label>Localidad:</label>
                            <select class="form-control" data-live-search="true" id="sel_local_edit" name="sel_local_edit"></select>
                          </div>
                          <div class="col-3">
                            <label>Barrio:</label>
                            <input type="hidden" name="id_barrio" id="id_barrio">
                            <input type="text" class="form-control" name="barrio_edit" id="barrio_edit">
                          </div>
                        </div>
                         

                <button class="btn btn-primary" type="submit" id="btnEditar2"><i class="fa fa-save"></i> Guardar</button>
                <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                     
              </form>
            </div>
              <!-- /.card-footer-->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <script type="text/javascript" src="scripts/places.js"></script>
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