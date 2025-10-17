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
    text-decoration: none;" title="Refrescar pesta&ntilde;a">Seguimiento</a></h1>
          </div>
          <script type="text/javascript">
            function refresh() {              
                window.location.reload();
                }
          </script>
          <div class="col-sm-6">
            <div class="breadcrumb float-sm-right">
              <h1 id="botonA"><span style="font-size:20px;">Nuevo Ingreso</span>
              <button id="btnvolver" class="btn btn-danger" onclick="cancelarform()"><i class="fa fa-arrow-circle-left"></i> Volver</button> </h1>
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
                <!-- <h3 class="card-title">Formulario Seguimiento</h3> -->

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
                  <div class="row" style="margin-bottom:30px;">
                    <div class="col-2">
                      <label>Origen PQRSD:</label>
                      <select class="form-control" id="origen_pqr">
                        <option value="">Seleccione una opción</option>
                        <option value="Metro Bogotá">Metro Bogotá</option>
                        <option value="Metro Línea 1">Metro Línea 1</option>
                        <option value="Línea 2">Línea 2</option>
                      </select>
                    </div>
                    <div class="col-2">
                      <label>Canal:</label>
                      <select class="form-control" data-live-search="true" id="sel_canal"></select>
                    </div>
                    <div class="col-2">
                      <label>Estados:</label>
                      <select class="form-control" data-live-search="true" id="estados_filtro"></select>
                    </div>
                    <div class="col-2">
                      <label>Fecha Ingreso:</label>
                      <input type="date" class="form-control" id="fecha_crea">
                    </div>
                    <div class="col-2">
                      <label>Fecha Inicio (vencimiento):</label>
                      <input type="date" class="form-control" id="fecha_ini">
                    </div>
                    <div class="col-2">
                      <label>Fecha Fin (vencimiento):</label>
                      <input type="date" class="form-control" id="fecha_fin">
                    </div>
                    <div class="col-2">
                      <button type="button" class="btn btn-info" style="margin-top: 15px" onclick="listar();">Filtrar</button>
                    </div>
                  </div>
                  <table id="tbllistado" class="table table-bordered table-hover">
                    <thead>
                      <th>ID PQRSD</th>
                      <th>ID EMB</th>
                      <th>ID BTE</th>
                      <th>Observaciones</th>
                      <th>Origen</th>
                      <th>Asunto</th>
                      <th>Fecha Ingreso</th>
                      <th>Fecha de Vencimiento Ley</th>
                      <th>Estado</th>
                      <th>Estado interno trámite</th>
                      <th>Documento Ciudadano</th>
                      <th>Nombre Ciudadano</th>
                      <th>Estado Asignación</th>
                    </thead>
                    <tbody>

                      
                    </tbody>
                    <!-- <tfoot>
                      <th>Festivos</th>
                      <th>Opciones</th>
                    </tfoot> -->
                  </table>
                </div>
                        
                <div class="panel-body"  id="formularioregistros">
                  
                    <div class="row">
                      <div class="col-3">
                        <button class="btn btn-success" data-toggle="modal" data-target="#form_modal"><i class="fa fa-plus-circle"></i> Registrar seguimiento</button>
                      </div>
                      <div class="col-12">
                        <input type="hidden" id="id_pqr">
                        <br>
                        <table id="tblHistorial" class="table table-bordered table-hover">
                          <thead>
                            <th>Estado</th>
                            <th>Archivos</th>
                            <th>Notas</th>
                            <th>Fecha</th>
                            <th>Opciones</th>
                          </thead>
                          <tbody>
                            
                          </tbody>
                          <tfoot>
                            <th>Estado</th>
                            <th>Archivos</th>
                            <th>Notas</th>
                            <th>Fecha</th>
                            <th>Opciones</th>
                          </tfoot>
                        </table>
                      </div>

                    </div>
                  
                </div>


                <div class="modal fade" id="form_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Formulario Seguimiento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form name="formulario" id="formulario" method="POST">
                        <div class="modal-body">
                          <div class="row">
                            
                            <div class="col-12">
                              <div id="hr_edit"></div>
                              <div class="row" id="entre_edit"></div>
                              <br>
                            </div>

                            <div class="col-4">
                              <label>Estado:</label>
                              <input type="hidden" class="form-control" style="width:20px;" name="id" id="id">
                              <select class="form-control" data-live-search="true" id="estado" name="estado"></select>
                            </div>
                            <div class="col-4">
                              <label>Observaciones / Notas:</label>
                              <input type="text" class="form-control" name="notas" id="notas">
                            </div>
                            <div class="col-4">
                              <label>Fecha:</label>
                              <input type="date" class="form-control" name="fecha" id="fecha">
                            </div>
                            <div class="col-4">
                              <label>Archivos:</label>
                              <input type="file" class="form-control multi" id="anexos" data-show-upload="false" data-show-caption="true" multiple>
                            </div>
                            <div class="col-12 anexo" style="margin-top:10px;">
                              <table class="table table-responsive">
                                <thead>
                                  <tr><th>Archivos seleccionados</th><th>Eliminar</th></tr>
                                </thead>
                                <tbody id="list_anexo">
                                  
                                </tbody>
                              </table>
                            </div>

                          </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success" type="submit" id="btn_confirm"><i class="fa fa-save"></i> Guardar</button>
                            <button class="btn btn-danger" type="button" data-dismiss="modal" aria-label="Close" id="btn_cancel"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                        </div>
                      </form>
                    </div>
                  </div>
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

    var anexos = [];

    $('#anexos').change(function(){
      for (var i = 0; i < this.files.length; i++){
        var file = this.files[i];
        anexos.push(file);
        tbl_an();
      }
      $("#anexos").val("");
    });
  </script>

  <script type="text/javascript" src="scripts/seguimiento.js"></script>
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