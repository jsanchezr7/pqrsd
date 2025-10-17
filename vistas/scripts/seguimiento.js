var tabla;
var tablaHistorial;

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

//funcion que se ejecuta al inicio
function init(){
  mostrarform(false);
  listar();
  $("#formulario").on("submit",function(e)
  {
    guardaryeditar(e);
  })



  $.post("../ajax/select.php?op=sel_estado", function(r){
              $("#estados_filtro").html(r.result);
              $('#estados_filtro').selectpicker('refresh');
  })


  $.post("../ajax/select.php?op=sel_estado_seg", function(r){
              $("#estado").html(r.result);
              $('#estado').selectpicker('refresh');
  })


  $.post("../ajax/select.php?op=sel_canal_rec", function(r){
              $("#sel_canal").html(r.result);
              $('#sel_canal').selectpicker('refresh');
  })



  $('#form_modal').on('hidden.bs.modal', function () {
    // Your function to be executed on close
    limpiar();
  });

}

$('#tbllistado tbody').on('click','td',function(e){
  var data = tabla.row(this).data();
  
  if (tabla.column(this)[0]>2 && tabla.column(this)[0]<11) {
    mostrarform(true);
    listarHistorial(data[0]);
    id_pqr.value = data[0];
  }
    // console.log(tabla.column(this)[0]);
})

//funcion limpiar
function limpiar(){
  $("#id").val("");
  $("#estado").val("");
  $("#anexos").val("");
  $("#notas").html("");
  $("#fecha").val("");
  anexos = [];
  tbl_an();
  $("#hr_edit").html("");
  $("#entre_edit").html("");
}

//funcion mostrar formulario
function mostrarform(flag)
{
  if (flag)
  {
    $("#listadoregistros").hide();
    $("#formularioregistros").show();
    $("#btnguardar").prop("disabled", false);
    $("#btnvolver").show();
  } 
  else
  {
    $("#listadoregistros").show();
    $("#formularioregistros").hide();
    $("#btnvolver").hide();
  }
}


//funcion cancelar form
function cancelarform()
{
  limpiar();
  mostrarform(false);
}

function listar()
{
var ori_pqr = $("#origen_pqr").val();
var sel_canal = $("#sel_canal").val();
var estados_filtro = $("#estados_filtro").val();
var fecha_crea = $("#fecha_crea").val();
var fecha_ini = $("#fecha_ini").val();
var fecha_fin = $("#fecha_fin").val();
  tabla=$('#tbllistado').dataTable(
  {
    "stateSave": true,//para que quede en la misma pagina al volver
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
                orientation: 'landscape',
                title: 'Informe PQRSD Metro Bogotá',
                filename: 'PQRs',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ,5, 6, 7, 8, 10, 11 ]
                }
            }],
    "ajax":
        {
          url: '../ajax/seguimiento.php?op=listar',
          type: "get",
            data:{estados_filtro: estados_filtro, fecha_crea: fecha_crea, fecha_ini: fecha_ini, fecha_fin: fecha_fin, ori_pqr: ori_pqr, sel_canal:sel_canal},          dataType: "json",
          error: function(e){
            console.log(e.responseText);
          }
        },
        "bDestroy":true,
        "responsive": true,
        "iDisplayLength":20,//paginacion
        "ordering": false
  }).DataTable();
}



function listarHistorial(id)
{
  tablaHistorial=$('#tblHistorial').dataTable({

    "stateSave": true,//para que quede en la misma pagina al volver
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
        orientation: 'landscape',
        title: 'Informe de Seguimiento',
        filename: 'Seguimiento PQRs',
        exportOptions: {
            columns: [ 0, 1, 2, 3, 4 ,5, 6, 7, 8, 10, 11 ]
        }
    }],
    "ajax":
        {
          url: '../ajax/seguimiento.php?op=listarHistorial',
          type: "get",
          data:{id: id},          
          dataType: "json",
          error: function(e){
            console.log(e.responseText);
          }
        },
        "bDestroy":true,
        "responsive": true,
        "iDisplayLength":20,//paginacion
        "ordering": false
  }).DataTable();
}


//funcion para guardar y editar

function guardaryeditar(e)
{
  e.preventDefault(); //No se activará la acción predeterminada del evento
  $("#btnGuardar").prop("disabled",false);
  var formData = new FormData($("#formulario")[0]);
  
  for (i=0; i< anexos.length; i++){
    formData.append('anexos'+i,anexos[i]);
  }

  formData.append('id_pqr', id_pqr.value);

  $.ajax({
    url: "../ajax/seguimiento.php?op=guardaryeditar",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,

      success: function(datos)
      {                    
            bootbox.alert(datos.result);   
            tablaHistorial.ajax.reload();
            limpiar();
            $("#form_modal").modal("hide");
      }

  });
}


function mostrar(id)
{
  $.post("../ajax/seguimiento.php?op=mostrar",{id:id}, function(data, status)
  {
    // console.log(data)
    $("#id").val(data.id);
    $("#estado").val(data.estado).selectpicker('refresh');
    $("#notas").val(data.notas);
    $("#fecha").val(data.fecha);

    const archivos = data['archivos'].split(", ");

    $("#hr_edit").html("");
    $("#entre_edit").html("");

    if (archivos.length > 0 && archivos[0] != '') {
      $("#hr_edit").append('<h5>Anexos Cargados</h5>');
      $("#entre_edit").append('<div class="col-12"><table class="table table-responsive"><thead><tr><th>Nombre Archivo</th><th>URL</th><th>Eliminar</th></tr></thead><tbody id="tbody_edit"></tbody></table></div>');   
    }

    for (var i = 0; i < archivos.length; i++) {
      $("#tbody_edit").append('<tr id="file'+i+''+data.id+'"><td>'+archivos[i]+'</td><td><a href="../ajax/download_file.php?link='+archivos[i]+'&type=3" target="_blank"><button type="button" class="btn btn-info">Abrir</button></a></td><td><button type="button" onclick="eliminarFile(`'+archivos[i]+'`, '+data.id+', '+i+');" type="button" class="btn btn-danger">Eliminar</button></td></tr>'); 
    }
   
  })
}




function eliminarFile(filename, id, pos){
  bootbox.confirm("¿Está seguro que desea eliminar este archivo?",function(result){
    if (result)
    {
      $.post("../ajax/seguimiento.php?op=eliminarFile",{filename:filename, id:id}, function(data, status)
      {
        bootbox.alert(data.result);
        tablaHistorial.ajax.reload();
        $('#file' + pos + id).remove();
      })
    }
  })
  

}





function deleteSeg(id){
  bootbox.confirm("¿Está seguro que desea eliminar este seguimiento y todos sus archivos?",function(result){
    if (result)
    {
      $.post("../ajax/seguimiento.php?op=deleteSeg",{id:id}, function(data, status)
      {
        bootbox.alert(data.result);
        tablaHistorial.ajax.reload();
      })
    }
  })
  

}





function tbl_an(){
  $("#list_anexo").html("");
  for (var a = 0; a < anexos.length; a++) {
    $("#list_anexo").append('<tr id="tr'+a+'"><td>'+anexos[a]['name']+'</td><td><button onclick="del_an('+a+');" type="button" class="btn btn-danger">X</button></td></tr>');
  }
}


function del_an(id){
  $("#list_anexo").html("");
  anexos.splice(id, 1);
  console.log(anexos);
  tbl_an();
}
  

init();