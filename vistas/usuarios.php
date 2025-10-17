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
if ($_SESSION['Admin']==1)
{

?>
<meta charset="utf-8">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><a href="" onclick="refresh()" style=" color: black;
            text-decoration: none;" title="Refrescar pesta&ntilde;a">Usuarios</a></h1>
          </div>
          <script type="text/javascript">
            function refresh() {              
                window.location.reload();
                }
          </script>
          <div class="col-sm-6">
            <div class="breadcrumb float-sm-right">
              <h1 id="botonA"><span style="font-size:20px;">Nuevo Usuario</span>
                            <button id="btnagregar" class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button><button id="btnvolver" class="btn btn-danger" onclick="cancelarform()"><i class="fa fa-arrow-circle-left"></i> Volver</button></h1>
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
                <h3 class="card-title">Formulario Usuarios</h3>

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
                      <table id="tbllistado" class="table table-bordered table-hover">
                          <thead>
                          <th>Documento</th>
                          <th>Nombre</th>
                          <th>Cargo</th>
                          <th>Email</th>
                          <th>Área</th>
                          <th>Estado</th>
                           <th>Opciones</th>
                          </thead>
                          <tbody>

                            
                          </tbody>
                          <tfoot>
                          <th>Documento</th>
                          <th>Nombre</th>
                          <th>Cargo</th>
                          <th>Email</th>
                          <th>Área</th>
                          <th>Estado</th>
                           <th>Opciones</th>
                        
                         
                          </tfoot>
                        </table>
                      </div>
                        
                          <div class="panel-body"  id="formularioregistros">
                            <form name="formulario" id="formulario" method="POST">
                            <div class="row" >
                            <div class="col-4">
                            <label>Documento:</label>
                            <input type="hidden" name="id" id="id">
                            <input type="text" class="form-control" name="documento" id="documento" maxlength="15" placeholder="Documento">
                          </div>
                          <div class="col-4">
                            <label>Nombre: *</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
                          </div>
                          <div class="col-4">
                            <label>Cargo:</label>
                            <input type="text" class="form-control" name="cargo" id="cargo" maxlength="100" placeholder="Cargo" >
                          </div>
                          <div class="col-4">
                            <label>Email: *</label>
                            <input type="text" class="form-control" name="email" id="email" maxlength="50" placeholder="Email" required>
                          </div>
                          <div class="col-4">
                            <label>Contrase&ntilde;a: *</label>
                            <input type="password" class="form-control" name="password" id="password" maxlength="100" placeholder="Contrase&ntilde;a" required>
                          </div>
                          <div class="col-4">
                            <label>Área: *</label>
                            <select class="form-control" data-live-search="true" id="area" name="area" required></select>
                          </div>
                          <div class="col-12">
                            <label>Permisos:</label>
                            <ul style="list-style: none;" id="permisos">
                            </ul>
                          </div>
                        </div>
                         

                <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

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
  </script>
  <script type="text/javascript" src="scripts/usuarios.js"></script>
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