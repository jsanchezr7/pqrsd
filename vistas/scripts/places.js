var tabla;

//funcion que se ejecuta al inicio
function init(){
  mostrarform(false);
  mostrarform2(false);
  mostrarform3(false);
  listar();
  listar2();
  $("#formulario").on("submit",function(e)
  {
    guardaryeditar(e);
  })
  $("#formulario2").on("submit",function(e)
  {
    guardar(e);
  })
  $("#formularioedit").on("submit",function(e)
  {
    editar(e);
  });
  $("#formularioedit2").on("submit",function(e)
  {
    editar2(e);
  });

  $.post("../ajax/places.php?op=sel_local", function(r){
              $("#sel_local").html(r);
              $('#sel_local').selectpicker('refresh');
              $("#sel_local_edit").html(r);
              $('#sel_local_edit').selectpicker('refresh');
  })
}

//funcion limpiar
function limpiar(){
  $("#id").val("");
  $("#tipo").val("");
  $("#localidad").val("");
  $("#barrio").val("");
  $("#sel_local").val(0);
  $('#sel_local').selectpicker('refresh');

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

function mostrarform3(flag)
{
  limpiar();
  if (flag)
  {
    $("#listadoregistros").hide();
    $("#editar2").show();
    $("#btnagregar").hide();
  $("#btnvolver").show();
  } 
  else
  {
    $("#listadoregistros").show();
    $("#editar2").hide();
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
  mostrarform3(false);
}

function listar()
{
  tabla=$('#tbllistado').dataTable(
  {
    "aProcessing": true,//activamos el procesamiento del datatables
    "aServerSide": true, //paginacion y filtrado realizado por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdf'],
    "ajax":
        {
          url: '../ajax/places.php?op=listar',
          type: "get",
          dataType: "json",
          error: function(e){
            // console.log(e.responseText);
          }
        },
        "bDestroy":true,
        "iDisplayLength":5,//paginacion
        "order":[[0,"asc"]]//ordenar los datos
  }).DataTable();
}


function listar2()
{
  tabla2=$('#tbllistado2').dataTable(
  {
    "aProcessing": true,//activamos el procesamiento del datatables
    "aServerSide": true, //paginacion y filtrado realizado por el servidor
    dom: 'Bfrtip',//Definimos los elementos del control de tabla
    buttons: ['copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdf'],
    "ajax":
        {
          url: '../ajax/places.php?op=listar2',
          type: "get",
          dataType: "json",
          error: function(e){
            // console.log(e.responseText);
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
  $.ajax({
    url: "../ajax/places.php?op=guardaryeditar",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,

      success: function(datos)
      {                    
            bootbox.alert(datos);           
            mostrarform(false);
            tabla.ajax.reload();
      }

  });
  limpiar();
  tabla.ajax.reload();
}

function guardar(e)
{
  e.preventDefault(); //No se activará la acción predeterminada del evento
  $("#btnGuardar2").prop("disabled",false);
  var formData = new FormData($("#formulario2")[0]);
  $.ajax({
    url: "../ajax/places.php?op=guardar",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,

      success: function(datos)
      {                    
            bootbox.alert(datos);           
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
    url: "../ajax/places.php?op=editar",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,

      success: function(datos)
      {                    
            bootbox.alert(datos);           
            mostrarform2(false);
            tabla.ajax.reload();
      }

  });
  limpiar();
  tabla.ajax.reload();
}

function editar2(e)
{
  e.preventDefault(); //No se activará la acción predeterminada del evento
  $("#btnEditar2").prop("disabled",false);
  var formData = new FormData($("#formularioedit2")[0]);
  $.ajax({
    url: "../ajax/places.php?op=editar2",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,

      success: function(datos)
      {                    
            bootbox.alert(datos);           
            cancelarform(false);
            tabla.ajax.reload();
      }

  });
  limpiar();
  tabla2.ajax.reload();
}


function mostrar(id,tipo)
{
    $.post("../ajax/places.php?op=mostrar",{id:id,tipo:tipo}, function(data, status)
  {
    var data = JSON.parse(data);
    console.log(data)
    if (data.localidad!=null) {
      mostrarform2(true);
      $("#id_local").val(data.id_local);
      $("#local_edit").val(data.localidad);
    } else {
      mostrarform3(true);
      $("#id_barrio").val(data.id_bar);
      $("#barrio_edit").val(data.barrio);
      $("#sel_local_edit").val(data.id_local);
      $('#sel_local_edit').selectpicker('refresh');
    }
  
 
})
}
  

init();