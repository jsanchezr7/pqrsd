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
            <h1><a href="" onclick="refresh()" style=" color: black;text-decoration: none;" title="Refrescar pesta&ntilde;a">Bitácora</a></h1>
          </div>
          <script type="text/javascript">
            function refresh() {              
                window.location.reload();
                }
          </script>
          <div class="col-sm-6">
            
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
                <!-- <h3 class="card-title">Tabla bitácora</h3> -->

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
                      <th>Tipo</th>
                      <th>ID PQR</th>
                      <th>Módulo</th>
                      <th>Cambio</th>
                      <th>Fecha</th>
                      <th>Usuario</th>
                    </thead>
                    <tbody>

                      
                    </tbody>
                    <tfoot>
                      <th>Tipo</th>
                      <th>ID PQR</th>
                      <th>Módulo</th>
                      <th>Cambio</th>
                      <th>Fecha</th>
                      <th>Usuario</th>
                    </tfoot>
                  </table>
                </div>
              <!-- /.card-footer-->
              </div>
            <!-- /.card -->
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <script>
    const store = <?php echo json_encode($_SESSION['JWT']);?>;
    $.ajaxSetup({
      headers: {
        'Authorization': `Bearer ${store.JWT}`
      },
      dataType    :"json", // all requests should respond with json string by default
      type        : "POST", // all request should POST by default
      error       : function(xhr, textStatus, errorThrown){
        console.log(xhr)
        if (xhr.responseText=='Expired token') {
          location.href='logOut.php';
        }
      }
    });

    var tabla;

    function listar()
    {
      tabla=$('#tbllistado').dataTable(
      {
        "aProcessing": true,//activamos el procesamiento del datatables
        "aServerSide": true, //paginacion y filtrado realizado por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        buttons: [{
        extend:'copyHtml5',
        text:'<i class="fas fa-copy"></i> <strong>Copiar</strong>',
        titleAttr:'Copiar'
      },
      {
        extend:'excelHtml5',
        text:'<i class="fas fa-file-excel"></i> <strong>Excel</strong> ',
        titleAttr:'Exportar a excel'
      },
      {
        extend:'csvHtml5',
        text:'<i class="fas fa-file-csv"></i> <strong>CSV</strong> ',
        titleAttr:'Exportar a CSV'
      },
       {
        extend: 'pdf',
        text: '<i class="fas fa-file-pdf"></i> <strong>PDF</strong> ',
        title: 'Bitácora PQRSD Metro Bogotá',
        orientation: 'landscape',
        filename: 'Bitácora | PQRs'
    }],
        "ajax":
            {
              url: '../ajax/fests.php?op=listar_bi',
              type: "get",
              dataType: "json",
              error: function(e){
                console.log(e.responseText);
                e.responseText=='Expired token'?location.href='logOut.php':null;
              }
            },
            "bDestroy":true,
            "responsive": true,
            "iDisplayLength":20,//paginacion
            "order":[[4,"desc"]]//ordenar los datos
      }).DataTable();
    }

    listar();
  </script>
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