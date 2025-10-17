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
<script src="https://cdn.jsdelivr.net/npm/jquery.soap@1.7.3/jquery.soap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/x2js/1.2.0/xml2json.min.js" integrity="sha512-HX+/SvM7094YZEKOCtG9EyjRYvK8dKlFhdYAnVCGNxMkA59BZNSZTZrqdDlLXp0O6/NjDb1uKnmutUeuzHb3iQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/cors-anywhere@0.4.4/lib/cors-anywhere.min.js"></script> -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">

          <div class="col-sm-6">
            <h1><a href="" onclick="refresh()" style=" color: black;
    text-decoration: none;" title="Refrescar pesta&ntilde;a">Fechas</a></h1>
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
                <h3 class="card-title">Formulario Fechas</h3>

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
                    <div class="form-group col-md-3 col-sm-6 col-xs-12">
                      <label>Año:</label>
                      <input type="number" class="form-control" id="anio" />
                    </div>

                    <div class="col-2">
                      <button type="button" class="btn btn-info" style="position: absolute;bottom: 15px;" onclick="listar();">Filtrar</button>
                    </div>
                  </div>
                      <table id="tbllistado" class="table table-bordered table-hover">
                          <thead>
                          <th>Festivos</th>
                           <th>Opciones</th>
                          </thead>
                          <tbody>

                            
                          </tbody>
                          <tfoot>
                          <th>Festivos</th>
                           <th>Opciones</th>
                          </tfoot>
                        </table>
                      </div>
                        
                          <div class="panel-body"  id="formularioregistros">
                            <form name="formulario" id="formulario" method="POST">
                            <div class="row" >
                            <div class="col-3">
                            <label>Fecha Festivo:</label>
                            <input type="date" class="form-control" id="fecha_fest">
                          </div>
                          <div class="col-12" style="margin-top:10px;">
                <table class="table table-responsive">
                <thead>
                <tr><th>Fechas seleccionadas</th><th>Eliminar</th></tr>
              </thead>
              <tbody id="list_ad">
                
              </tbody>
              </table>
              </div>

                <script type="text/javascript">
                var fechas = [];

                if ($('#id').val()=="") {
                  console.log('hi')
                }
                
                $('#fecha_fest').change(function(){
                  $("#list_ad").html("");
                fechas.push(this.value);
                tbl_ad();
                console.log(fechas);
              });
                
              </script>

                        </div>
                         

                <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                     
              </form>
            </div>




            <div id="editar">
              <!-- <button id="post" class="btn btn-primary" type="button" onclick="soap();"> SOAP</button> -->
              <form name="formularioedit" id="formularioedit" method="POST">
                        <div class="row" >
                          <div class="col-3">
                            <label>Fecha Festivo:</label>
                            <input type="hidden" name="id" id="id">
                            <input type="date" class="form-control" name="fecha_fest" id="fecha_fest2">
                          </div>
                        </div>
                         

                <button class="btn btn-primary" type="submit" id="btnEditar"><i class="fa fa-save"></i> Guardar</button>
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
  <script type="text/javascript">
    const store = <?php echo json_encode($_SESSION['JWT']);?>;
    $('#anio').val(<?php echo date("Y"); ?>);

  </script>

  <script type="text/javascript" src="scripts/fests.js"></script>
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