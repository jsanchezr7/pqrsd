var tabla;

$.ajaxSetup({
  headers: {
    'Authorization': `Bearer ${store.JWT}`
  },
  dataType    :"json", // all requests should respond with json string by default
  type        : "POST", // all request should POST by default
  error       : function(xhr, textStatus, errorThrown){
    console.log(xhr)
    console.log(errorThrown)
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
  });
  $.post("../ajax/usuarios.php?op=permisos&id=",function(r){
    $("#permisos").html(r.result);
  })

  $.post("../ajax/usuarios.php?op=area", function(r){
    $("#area").html(r.result);
    $('#area').selectpicker('refresh');
  })

  
}

//funcion limpiar
function limpiar(){
  $("#id").val("");
  $("#documento").val("");
  $("#nombre").val("");
  $("#cargo").val("");
  $("#email").val("");
  $("#password").val("");
  $("#area").val(0);
  $('#area').selectpicker('refresh');   
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

//funcion cancelar form
function cancelarform()
{
  limpiar();
  mostrarform(false);
}

function listar()
{
  tabla=$('#tbllistado').dataTable(
  {
    "aProcessing": true,//Activamos el procesamiento del datatables
      "aServerSide": true,//Paginación y filtrado realizados por el servidor
      "stateSave": true,//para que quede en la misma pagina al volver
      dom: 'Bfrtip',//Definimos los elementos del control de tabla
      "autoWidth": false,
      responsive: true,
      buttons: [              
      {
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
        extend:'pdf',
        text:'<i class="fas fa-file-pdf"></i> <strong>PDF</strong>',
        titleAttr:'Exportar a pdf',
        exportOptions: {
          columns: [ 1, 3, 4, 5 ]
        }
      }
      ],
    "ajax":
        {
          url: '../ajax/usuarios.php?op=listar',
          type: "get",
          error: function(e){
            console.log(e.responseText);
          }
        },
        "bDestroy":true,
        "iDisplayLength":5,//paginacion
        "order":[[0,"desc"]]//ordenar los datos
  }).DataTable();
}

//funcion para guardar y editar

function guardaryeditar(e)
{
  e.preventDefault(); //No se activará la acción predeterminada del evento
  $("#btnGuardar").prop("disabled",false);
  var formData = new FormData($("#formulario")[0]);

  $.ajax({
    url: "../ajax/usuarios.php?op=guardaryeditar",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function(datos)
      {                   
        console.log(datos)
        bootbox.alert(datos.result);           
        mostrarform(false);
        tabla.ajax.reload();
      }

  });
  limpiar();
tabla.ajax.reload();
}


function mostrar(id)
{
  $.post("../ajax/usuarios.php?op=mostrar",{id:id}, function(data, status){
    mostrarform(true);

    $("#id").val(data.id);
    $("#documento").val(data.documento);
    $("#nombre").val(data.nombre);
    $("#cargo").val(data.cargo);
    $("#email").val(data.email);
    $("#password").val(data.password);
    $("#area").val(data.area);
    $('#area').selectpicker('refresh');
   
    

  });
}

function desactivarUsu(id)
{

 
  bootbox.confirm("¿Esta seguro que desea desactivar este usuario?",function(result){
    if (result)
    {
      $.post("../ajax/usuarios.php?op=desactivarUsu",{id:id },function(e){
        bootbox.alert(e.result);
        tabla.ajax.reload();
      });
        

      }
    })
  }

  function activarUsu(id)
{
  bootbox.confirm("¿Esta seguro que desea activar este usuario?",function(result){
    if (result){
      $.post("../ajax/usuarios.php?op=activarUsu",{id:id},function(e){
        bootbox.alert(e.result);
        tabla.ajax.reload();
      });
    }
  })
}


  

init();