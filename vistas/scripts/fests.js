var tabla;

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
  mostrarform2(false);
  listar();
  $("#formulario").on("submit",function(e)
  {
    guardaryeditar(e);
  })
  $("#formularioedit").on("submit",function(e)
  {
    editar(e);
  });

}

//funcion limpiar
function limpiar(){
  $("#id").val("");
  $("#fecha_fest").val("");
  $("#fecha_fest2").val("");

  $("#list_ad").html("");
  $("#adjuntos").val("");
    fechas=[];
   
}

//funcion mostrar formulario
function mostrarform(flag)
{
  limpiar();
  if (flag)
  {
    $("#listadoregistros").hide();
    $("#formularioregistros").show();
    $("#btnguardar").prop("disabled", false);
    $("#btnagregar").hide();
    $("#btnvolver").show();
  } 
  else
  {
    $("#listadoregistros").show();
    $("#formularioregistros").hide();
    $("#btnagregar").show();
    $("#btnvolver").hide();
  }
}

function mostrarform2(flag)
{
  limpiar();
  if (flag)
  {
    $("#listadoregistros").hide();
    $("#editar").show();
    $("#btnagregar").hide();
  $("#btnvolver").show();
  } 
  else
  {
    $("#listadoregistros").show();
    $("#editar").hide();
    $("#btnagregar").show();
  $("#btnvolver").hide();
  }
}

//funcion cancelar form
function cancelarform()
{
  limpiar();
  mostrarform(false);
  mostrarform2(false);
}

function listar()
{
  let anio = $('#anio').val();
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
        title: 'Festivos PQRSD Metro Bogotá',
        filename: 'Festivos | PQRs',
        exportOptions: {
            columns: [ 0]
        }
    }],
    "ajax":
        {
          url: '../ajax/fests.php?op=listar',
          type: "get",
          data: {anio:anio},
          dataType: "json",
          error: function(e){
            console.log(e.responseText);
            e.responseText=='Expired token'?location.href='logOut.php':null;
          }
        },
        "bDestroy":true,
        "iDisplayLength":5,//paginacion
        "order":[[0,"asc"]]//ordenar los datos
  }).DataTable();
}

//funcion para guardar y editar

function guardaryeditar(e)
{
  e.preventDefault(); //No se activará la acción predeterminada del evento
  $("#btnGuardar").prop("disabled",false);
  var formData = new FormData($("#formulario")[0]);
  formData.append('fechas',fechas);
  $.ajax({
    url: "../ajax/fests.php?op=guardaryeditar",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,

      success: function(datos)
      {                    
            bootbox.alert(datos.result);           
            mostrarform(false);
            tabla.ajax.reload();
      }

  });
  limpiar();
  tabla.ajax.reload();
}

function editar(e)
{
  e.preventDefault(); //No se activará la acción predeterminada del evento
  $("#btnEditar").prop("disabled",false);
  var formData = new FormData($("#formularioedit")[0]);
  $.ajax({
    url: "../ajax/fests.php?op=editar",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,

      success: function(datos)
      {                    
            bootbox.alert(datos.result);           
            mostrarform2(false);
            tabla.ajax.reload();
      }

  });
  limpiar();
  tabla.ajax.reload();
}


function mostrar(id)
{
  mostrarform2(true);
  $.post("../ajax/fests.php?op=mostrar",{id:id}, function(data, status)
  {
    // console.log(data)
  $("#id").val(data.id_fest);
  $("#fecha_fest2").val(data.date_fest);
 
})
}

function tbl_ad(){
    for (var a = 0; a < fechas.length; a++) {
      var event = new Date(fechas[a]);
      var dia = new Date();
      dia.setDate(event.getDate()+1);
var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    
console.log(event.toLocaleDateString('es-ES', options));
    $("#list_ad").append('<tr id="tr'+a+'"><td>'+dia.toLocaleDateString('es-ES', options)+'</td><td><button onclick="del_file('+a+');" type="button" class="btn btn-danger">X</button></td></tr>');
  }
  $("#nombre_estado").val("");
}
function del_file(id){
  $("#list_ad").html("");
  fechas.splice(id, 1);
  tbl_ad();
}

  

init();