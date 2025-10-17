var tabla;
var num_pon = 1;
var allowedExtensions;


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
  var form = false;
  mostrarform(true);
  limpiar();
  listar();
  $("#print2").hide();
  $("#print3").hide();
  $("#edit_bte").hide();
  $('.data_ciu').prop("required", true);
  $("#formulario_ciu").on("submit",function(e)
  {
    guardaryeditar_ciu(e);
  })

  $("#formulario_pqr").on("submit",function(e)
  {
    e.preventDefault();
    // $("#btnGuardarciu").click();
    //   setTimeout(function() {
        guardaryeditar_pqr(e);
      // }, 100); 
  })

  $("#entrega_view").on("submit",function(e)
  {
    entrega_view(e);
  });

  $("#asignacion").on("submit",function(e)
  {
    asignacion(e);
  });

  $.post("../ajax/select.php?op=sel_canal_rec", function(r){
    $("#codigoCanal").html(r.result);
    $('#codigoCanal').selectpicker('refresh');
    $("#sel_canal").html(r.result);
    $('#sel_canal').selectpicker('refresh');
  })

  $.post("../ajax/select.php?op=sel_estado", function(r){
              $("#estado").html(r.result);
              $('#estado').selectpicker('refresh');
              $("#estados_filtro").html(r.result);
              $('#estados_filtro').selectpicker('refresh');
  })


  $.post("../ajax/select.php?op=sel_estado_tra", function(r){
              $("#estado_tra").html(r.result);
              $('#estado_tra').selectpicker('refresh');
  })

  $.post("../ajax/select.php?op=sel_estado_int", function(r){
  })

  $.post("../ajax/select.php?op=sel_usu", function(r){
              $("#sel_usu").html(r.result);
              $('#sel_usu').selectpicker('refresh');
  })


  $.post("../ajax/select.php?op=sel_tipo_soli", function(r){
              $("#codigoTipoRequerimiento").html(r.result);
              $('#codigoTipoRequerimiento').selectpicker('refresh');
  })

  $.post("../ajax/select.php?op=sel_localidad", function(r){
              $("#localidad").html(r.result);
              $('#localidad').selectpicker('refresh');
              $("#codigoLocalidadPeticion").html(r.result);
              $('#codigoLocalidadPeticion').selectpicker('refresh');
  })

  $.post("../ajax/select.php?op=sel_area", function(r){
              $("#area").html(r.result);
              $('#area').selectpicker('refresh');
  })

  $.post("../ajax/select.php?op=sel_tipoid", function(r){
              $("#codigoTipoIdentificacion").html(r.result);
              $('#codigoTipoIdentificacion').selectpicker('refresh');
  })

  $.post("../ajax/select.php?op=sel_genero", function(r){
              $("#idGenero").html(r.result);
              $('#idGenero').selectpicker('refresh');
  })

  $.post("../ajax/select.php?op=sel_aten_pref", function(r){
              $("#idAtencionPreferencial").html(r.result);
              $('#idAtencionPreferencial').selectpicker('refresh');
              $("#idAtencionPreferencial_anonim").html(r.result);
              $('#idAtencionPreferencial_anonim').selectpicker('refresh');
  })

  $.post("../ajax/select.php?op=sel_juridica", function(r){
              $("#tipoPersonaJuridica").html(r.result);
              $('#tipoPersonaJuridica').selectpicker('refresh');
  })

  $.post("../ajax/select.php?op=sel_opcion_pet", function(r){
    $("#codigoOpcion").html(r.result);
    $('#codigoOpcion').selectpicker('refresh');
  })

  $.post("../ajax/select.php?op=sel_dependencia", function(r){
              $("#codigoDependencia").html(r.result);
              $('#codigoDependencia').selectpicker('refresh');
  })

  $.post("../ajax/select.php?op=sel_entidad_dist", function(r){
              $("#entidadDistrital").html(r.result);
              $('#entidadDistrital').selectpicker('refresh');
  })

  $.post("../ajax/select.php?op=sel_etnia", function(r){
              $("#idEtnia").html(r.result);
              $('#idEtnia').selectpicker('refresh');
  })

  $.post("../ajax/select.php?op=sel_idDiscapacidad", function(r){
              $("#idDiscapacidad").html(r.result);
              $('#idDiscapacidad').selectpicker('refresh');
  })

  $.post("../ajax/select.php?op=sel_categoria", function(r){
              $("#categoria").html(r.result);
              $('#categoria').selectpicker('refresh');
  })

    $.post("../ajax/pqr.php?op=get_ext", function(data){
    allowedExtensions = data.map(function(obj) {
      return obj.nombre;
    });
  })


  $('.anonim').hide();
  $('.juridica').hide();
  $('.cont_mod').hide();
  $('.verifica').hide();
  $('.ent_prv').hide();
  $('.red').hide();
  $('.ponderantes').hide();
  $('.representados').hide();
  $('.anonimo').hide();
  $('#btnenviobte').hide();
  $(".id_bte").hide();
  $('.email').hide();
  $('.ubicacion').hide();
  $('.email_anonim').hide();
  $('.ubicacion_anonim').hide();
}

$(document).ready(function(){
  $("#area").change(function () {

    $("#area option:selected").each(function () {
      area = $(this).val();
      
      $.post("../ajax/select.php?op=sel_user", { area: area }, function(r){
        $("#user").html(r.result);
        $('#user').selectpicker('refresh');
      });            
    });
  })
});

$(document).ready(function(){
  $("#localidad").change(function () {

    $("#localidad option:selected").each(function () {
      localidad = $(this).val();
      
      $.post("../ajax/select.php?op=sel_upz", { localidad: localidad }, function(r){
        $("#codigoUpz").html(r.result);
        $('#codigoUpz').selectpicker('refresh');
        $("#codigoUpz").val(0);
        $('#codigoUpz').selectpicker('refresh');
      });            
    });
  })
});

$(document).ready(function(){
  $("#codigoUpz").change(function () {

    $("#codigoUpz option:selected").each(function () {
      codigoUpz = $(this).val();
      
      $.post("../ajax/select.php?op=sel_barrio", { codigoUpz: codigoUpz }, function(r){
        $("#idBarrio").html(r.result);
        $('#idBarrio').selectpicker('refresh');
        $("#idBarrio").val(0);
        $('#idBarrio').selectpicker('refresh');
      });            
    });
  })
});

$(document).ready(function(){
  $("#codigoLocalidadPeticion").change(function () {

    $("#codigoLocalidadPeticion option:selected").each(function () {
      codigoLocalidadPeticion = $(this).val();
      
      $.post("../ajax/select.php?op=sel_upz", { localidad: codigoLocalidadPeticion }, function(r){
        $("#codigoUpzPeticion").html(r.result);
        $('#codigoUpzPeticion').selectpicker('refresh');
        $("#codigoUpzPeticion").val(0);
        $('#codigoUpzPeticion').selectpicker('refresh');
      });            
    });
  })
});

$(document).ready(function(){
  $("#codigoUpzPeticion").change(function () {

    $("#codigoUpzPeticion option:selected").each(function () {
      codigoUpzPeticion = $(this).val();
      
      $.post("../ajax/select.php?op=sel_barrio", { codigoUpz: codigoUpzPeticion }, function(r){
        $("#codigoBarrioPeticion").html(r.result);
        $('#codigoBarrioPeticion').selectpicker('refresh');
        $("#codigoBarrioPeticion").val(0);
        $('#codigoBarrioPeticion').selectpicker('refresh');
      });            
    });
  })
});

$(document).ready(function(){
  $("#tipoPersonaJuridica").change(function () {

    $("#tipoPersonaJuridica option:selected").each(function () {
      tipoPersonaJuridica = $(this).val();
      
      $.post("../ajax/select.php?op=sel_tipoPersonaJuridica", { tipoPersonaJuridica: tipoPersonaJuridica }, function(r){
        $("#codigoTipoEntidad").html(r.result);
        $('#codigoTipoEntidad').selectpicker('refresh');
        $("#codigoTipoEntidad").val(0);
        $('#codigoTipoEntidad').selectpicker('refresh');
      });            
    });
  })
});

$(document).ready(function(){
  $("#categoria").change(function () {

    $("#categoria option:selected").each(function () {
      categoria = $(this).val();
      
      $.post("../ajax/select.php?op=sel_subtema", { categoria: categoria }, function(r){
        $("#subtema").html(r.result);
        $('#subtema').selectpicker('refresh');
        $("#subtema").val(0);
        $('#subtema').selectpicker('refresh');
      });            
    });
  })
});

$('#estado_view').change(function(){
  if (id_bte.value!=0) {
    $('#btnenviobte').show();
  }else{
    $('#btnenviobte').hide();
  }
})



$('#idTipoPersona').change(function(){
  switch (this.value){
    case '1':
      $('.juridica').hide();
      $('.cont_mod').hide();
      $('.mod_ciu').show();
      $('.entidad').hide();
      $('#nombre').html('Primer nombre: *');
      $('#primerApellido').prop('required', true);
      $('#correoElectronico').prop('required', false);
      $('#primerNombre').attr('placeholder','Primer nombre');
      $('#correoElectronicoContacto').prop('required', false);
      $('#codigoTipoIdentificacion').val(0).prop('disabled', false).selectpicker('refresh');
    break;

    case '2':
      $('.juridica').show();
      $('.cont_mod').show();
      $('.mod_ciu').hide();
      $('.entidad').show();
      $('.verifica').show();
      $('#nombre').html('Nombre empresa: *');
      $('#primerNombre').attr('placeholder','Nombre empresa');
      $('#primerApellido').prop('required', false);
      $('#correoElectronico').prop('required', false);
      $('#correoElectronicoContacto').prop('required', true);
      $('#codigoTipoIdentificacion').val(2).prop('disabled', true).selectpicker('refresh');
    break;

    case '3':
      $('.juridica').show();
      $('.cont_mod').show();
      $('.mod_ciu').hide();
      $('.entidad').hide();
      $('#nombre').html('Nombre establecimiento: *');
      $('#primerNombre').attr('placeholder','Nombre establecimiento');
      $('#primerApellido').prop('required', false);
      $('#correoElectronico').prop('required', false);
      $('#correoElectronicoContacto').prop('required', true);
      $('#codigoTipoIdentificacion').val(0).prop('disabled', false).selectpicker('refresh');
    break;
  }
})

$('#codigoTipoIdentificacion').change(function(){
  if (idTipoPersona.value!=2) {
    this.value==2?$('.verifica').show():$('.verifica').hide();
  }else{
    $('.verifica').show();
  }
  
})

$('#tipoPersonaJuridica').change(function(){
  this.value==1?$('.ent_prv').show():$('.ent_prv').hide();
})

$('#codigoCanal').change(function(){
  this.value==5?$('.red').show():$('.red').hide();
})

$('#codigoOpcion').change(function(){
  $('#list_pond').html('');
  $('#list_repr').html('');
  num_pon = 1;
  switch (this.value){
    case '1':
      $('.contacto').hide();
      $('.ponderantes').hide();
      $('.representados').hide();
    break;

    case '2':
      $('.contacto').hide();
      $('.ponderantes').hide();
      $('.representados').show();
    break;

    case '3':
      $('.contacto').show();
      $('.ponderantes').hide();
      $('.representados').hide();
    break;

    case '5':
      $('.contacto').show();
      $('.ponderantes').show();
      $('.representados').hide();
    break;
  }
})


function anonimo_form(value){
  switch (value){
    case '1':
      $('.ponderantes').hide();
      $('.representados').hide();
      $('#list_pond').html('');
      $('#list_repr').html('');
      num_pon = 1;
      $('#nav-ciu-tab, #nav-ciu, #nav-resp-tab, #nav-resp').removeClass('active');
      $('.ident, #nav-ciu-tab').hide();
      $('#nav-pqr-tab, #nav-pqr').addClass('active');
      $('#nav-pqr').css({ 'opacity' : 1 });
      $('#id_ciu').val(0);
      $('.anonimo').show();
      $('#codigoOpcion').prop('required', false);
      $('.data_ciu').prop('required', false);

    break;

    case '0':
      $('#codigoOpcion').prop('required', true);
      $('.anonimo').hide();
      $('.ident, #nav-ciu-tab').show();
    break;
  }
}

//funcion limpiar
function limpiar(){
  $("#edit_bte").hide();
  $(".id_bte").hide();
  $('#btnenviobte').hide();
  $('#formularioregistros').find('input').val('');
  $('#formularioregistros').find('textarea').val('');
  $('#adultoMayor').prop('checked',false);
  $('#notificacionFisica').prop('checked',false);
  $('#notificacionElectronica').prop('checked',false);
  $('#notificacionFisica_anonim').prop('checked',false);
  $('#notificacionElectronica_anonim').prop('checked',false);
  $('#formularioregistros').find('select').val('0').selectpicker('refresh');
  $("#list_ad").html("");
  $("#id").val("");
  $("#list_pond").html("");
  $("#list_repr").html("");
  $('.ponderantes').hide();
  $('.representados').hide();
    adjuntos=[];
  $('#nombre').html('Primer nombre:');
  $('#primerNombre').attr('placeholder','Primer nombre');
  $("#list_anexo").html("");
    anexos=[];
    num_pon = 1;
  $("#hr").html("");
  $("#entre").html("");
  $("#entre_view_r").html("");
  $("#hr_view_r").html("");
  $("#hr_obs").html("");
  $("#entre_obs").html("");
  $("#hr_edit").html("");
  $("#entre_edit").html("");
  $('.email').hide();
  $('.ubicacion').hide();
  $('.email_anonim').hide();
  $('.ubicacion_anonim').hide();
  $('#correoElectronico').prop('required', false);
  $('#direccionResidencia').prop('required', false);
  $('#direccionResidenciaContacto_anonim').prop('required', false);
  $('#correoElectronicoContacto_anonim').prop('required', false);
  $('#fecha_ley').prop('readonly', true);
  $('.form_ciu').show();
  $('#asign').html("");

  $('.anonimo').hide();
  $('.ident, #nav-ciu-tab').show();

  $('#list_asign').html("");
  $('#list_asign2').html("");
  $('#formularioregistros').find('input:checkbox').val(1);
   
}





function mostrarform(option){
  switch (option){
    case'form1':
      $("#listadoregistros").hide();
      $("#formularioregistros").show();
      $("#nav-tabContent").show();
      $(".anexo").show();
      $(".form").show();
      $("#view").hide();
      $("#entregas").hide();
      $("#obs_asig").show();
      $("#botonB").hide();
      $("#btnvolver").show();
      $('#estado').prop('disabled', true);
      $('#estado').val(1);
      $('#estado').selectpicker('refresh');
      $("#botonA").hide();
    break;

    default:
      $("#listadoregistros").show();
      $("#formularioregistros").hide();
      $(".anexo").hide();
      $("#view").hide();
      $("#entregas").hide();
      $("#obs_asig").hide();
      $("#botonB").show();
      $("#botonA").show();
      $("#btnvolver").hide();
    break;
  }
} 


function cancelarmodal()
{
  $('#asignacion').find('select').val('0').selectpicker('refresh');
  people = [];
  $('#t_people').html('');
}

function objOrLis(obj) {
  return Array.isArray(obj)
    ? 'list'
    : typeof obj
}

//funcion cancelar form
function cancelarform()
{
  limpiar();
  mostrarform(true);
}

$('#tbllistado tbody').on('click','td',function(e){
  var data = tabla.row(this).data();
  
  if (tabla.column(this)[0]>2 && tabla.column(this)[0]<11) {
    mostrarform('form1');
    mostrar(data[0]);
    $('#nav-resp-tab').show();
  }
    // console.log(tabla.column(this)[0]);
})

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
          url: '../ajax/pqr.php?op=listar',
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

//funcion para guardar y editar

function guardaryeditar_ciu(e)
{
  e.preventDefault(); //No se activará la acción predeterminada del evento
  $('#loading_modal').modal({backdrop: 'static', keyboard: false});
  $("#btnGuardar").prop("disabled",false).selectpicker('refresh');
  $('#codigoTipoIdentificacion').prop('disabled', false);
  var formData = new FormData($("#formulario_ciu")[0]);
  if (anonim.value==0) {
    $.ajax({
      url: "../ajax/pqr.php?op=guardaryeditar_ciu",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(datos)
        {            
          console.log(datos)
          // let dataArray = datos.split(/(?<=})/);
          bootbox.alert(datos.message);
          setTimeout(function() {
            $('#loading_modal').modal('hide');
          }, 1000);     
          if (datos.type==1) {
            $("#id_ciu").val(datos.ciudadano);
            $('#nav-ciu-tab, #nav-ciu').removeClass('active'); 
            $('#nav-pqr-tab, #nav-pqr').addClass('active');    
          } 
        }
    });
  }
}


function guardaryeditar_pqr(e)
{
  e.preventDefault(); //No se activará la acción predeterminada del evento
  $('#loading_modal').modal({backdrop: 'static', keyboard: false});
  $("#btnGuardar").prop("disabled",false);
  var formData = new FormData($("#formulario_pqr")[0]);
  let id_ciu = $('#id_ciu').val();
  formData.append('id_ciu',id_ciu);
  let anonim = $('#anonim').val();
  formData.append('anonim',anonim);
  
  for (i=0; i< anexos.length; i++){
    formData.append('anexos'+i,anexos[i]);
  }
  console.log(codigoOpcion.value)

    if (codigoOpcion.value==2){
      let rep = $("[name='numeroIdentificacionRep[]']").map(function(){return $(this).val();}).get();
      if (rep.length==0) {setTimeout(function() {$('#loading_modal').modal('hide');}, 1000); return bootbox.alert('Debe añadir un representado.');}
    }  
    if (codigoOpcion.value==5) {
      let pod = $("[name='numeroIdentificacionPod[]']").map(function(){return $(this).val();}).get();
      if (pod.length==0) {setTimeout(function() {$('#loading_modal').modal('hide');}, 1000); return bootbox.alert('Debe añadir un poderdante.');}
    }
       
  $.ajax({
    url: "../ajax/pqr.php?op=guardaryeditar_pqr",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,

      success: function(datos)
      {      
      console.log(datos)     
        // let dataArray = datos.split(/(?<=})/);
        var message = "";
        var pass;
        if (datos.length>1) {
          for (var i = 0; i < datos.length; i++) {
            let data = datos[i];
            message = message+data.message+" | "; 
            message = data.hasOwnProperty('id_bte') ? message+" BTE: "+data.id_bte+" | ":message;
            message = data.hasOwnProperty('error') ? message+" Error: "+data.error+" | ":message;
            data.hasOwnProperty('id_pqr') ? $("#id").val(data.id_pqr):$("#id").val(''); 
            pass=data.type==1?true:false;
          }
        }else{
          if (objOrLis(datos)=='list') {
            message = message + datos[0]['message'];
            pass = datos[0]['type'];
          }else{
            message = message + datos.message;
            $("#id").val(datos.id_pqr); 
            pass = datos.type;
          }
          
        }
        
        bootbox.alert(message);
        setTimeout(function() {
          $('#loading_modal').modal('hide');
        }, 1000);     
        if (pass) {
          $('#estado').prop('disabled', false); 
          $("#estado").val(1); 
          $('#estado').selectpicker('refresh'); 
          $("#edit_bte").show();
          // show_an(); 
          anexos=[]; 
        } 

        tabla.ajax.reload();
        // console.log(type_edit.value)
        type_edit.value==1?limpiar()+mostrarform(true):false;
      }

  });
  // limpiar();
  tabla.ajax.reload();
}


function mostrar(id)
{
  $.post("../ajax/pqr.php?op=mostrar",{id:id}, function(data, status)
  {
    data.id_bte==0?$('#edit_bte').show():$('#edit_bte').hide();
    $('#estado').prop('disabled', false);
    $('#estado').val(data.estado);
    $('#estado').selectpicker('refresh');
    $("#id").val(data.id_pqr);
    $("#codigoOpcion").val(data.codigoOpcion);
    $('#codigoOpcion').selectpicker('refresh');
    $("#id_pqr2").val(data.id_pqr);
    $(".id_bte").show();
    $("#id_bte").val(data.id_bte);
    $("#id_emb").val(data.id_emb);
    $("#id_ciu").val(data.id_ciu);
    $("#codigoTipoRequerimiento").val(data.codigoTipoRequerimiento);
    $('#codigoTipoRequerimiento').selectpicker('refresh');
    $("#asunto_obs").val(data.asunto_obs);
    $("#codigoDependencia").val(data.codigoDependencia);
    $('#codigoDependencia').selectpicker('refresh');
    $("#codigoProcesoCalidad").val(data.codigoProcesoCalidad);
    $('#codigoProcesoCalidad').selectpicker('refresh');
    $("#codigoCanal").val(data.codigoCanal);
    $('#codigoCanal').selectpicker('refresh');
    $("#codigoRedSocial").val(data.codigoRedSocial);
    $('#codigoRedSocial').selectpicker('refresh');
    $("#numeroRadicado").val(data.numeroRadicado);
    $("#fechaRadicado").val(data.fechaRadicado);
    $("#numeroFolios").val(data.numeroFolios);
    $("#observaciones").val(data.observaciones);
    $("#codigoLocalidadPeticion").val(data.codigoLocalidadPeticion);
    $('#codigoLocalidadPeticion').selectpicker('refresh');
    $.post("../ajax/select.php?op=sel_upz", { localidad: data.codigoLocalidadPeticion }, function(r){
                $("#codigoUpzPeticion").html(r.result);
                $('#codigoUpzPeticion').selectpicker('refresh');
                $("#codigoUpzPeticion").val(data.codigoUpzPeticion);
                $('#codigoUpzPeticion').selectpicker('refresh');
    });  
    $.post("../ajax/select.php?op=sel_barrio", { codigoUpz: data.codigoUpzPeticion }, function(r){
                $("#codigoBarrioPeticion").html(r.result);
                $('#codigoBarrioPeticion').selectpicker('refresh');
                $("#codigoBarrioPeticion").val(data.codigoBarrioPeticion);
                $('#codigoBarrioPeticion').selectpicker('refresh');
    });
    $("#codigoEstratoPeticion").val(data.codigoEstratoPeticion);
    $('#codigoEstratoPeticion').selectpicker('refresh');
    $("#direccionHechos").val(data.direccionHechos);
    $("#ubicacionAproximada").val(data.ubicacionAproximada);
    $("#fecha_crea").val(data.fecha_crea);
    $("#group_int").val(data.group_int);
    $('#group_int').selectpicker('refresh');
    $("#ori_pqr").val(data.ori_pqr);
    $('#ori_pqr').selectpicker('refresh');
    $("#estado").val(data.estado);
    $('#estado').selectpicker('refresh');
    $("#estado_tra").val(data.estado_tra);
    $('#estado_tra').selectpicker('refresh');
    $("#fecha_asigna").val(data.fecha_asigna);
    $("#fecha_ley").val(data.fecha_ley);
    data.estado_tra==25?$('#fecha_ley').prop('readonly', false):$('#fecha_ley').prop('readonly', true);

    data.notificacionFisica_anonim==1?$('#notificacionFisica_anonim').prop('checked',true):$('#notificacionFisica_pqr').prop('checked',false);
    data.notificacionElectronica_anonim==1?$('#notificacionElectronica_anonim').prop('checked',true):$('#notificacionElectronica_pqr').prop('checked',false);
    
    $("#tieneProcedencia").val(data.tieneProcedencia);
    $('#tieneProcedencia').selectpicker('refresh');
    $("#esCopia").val(data.esCopia);
    $('#esCopia').selectpicker('refresh');

    $("#idAtencionPreferencial_anonim").val(data.idAtencionPreferencial_anonim);
    $('#idAtencionPreferencial_anonim').selectpicker('refresh');
    $("#correoElectronico_anonim").val(data.correoElectronico_anonim);
    
    console.log(data)
    if (parseInt(data.ciudadano)!=0) {
      anonimo_form('0');
      $("#codigoTipoIdentificacion").val(data.codigoTipoIdentificacion);
      $('#codigoTipoIdentificacion').selectpicker('refresh');
      $("#numeroDocumento").val(data.numeroDocumento);
      $("#id_ciu").val(data.id_ciu);
      $("#primerNombre").val(data.primerNombre);
      $("#segundoNombre").val(data.segundoNombre);  
      $("#primerApellido").val(data.primerApellido);
      $("#segundoApellido").val(data.segundoApellido);
      $("#fechaNacimiento").val(data.fechaNacimiento);
      $("#idGenero").val(data.idGenero);
      $('#idGenero').selectpicker('refresh');
      $("#correoElectronico").val(data.correoElectronico);
      $("#localidad").val(data.localidad);
      $('#localidad').selectpicker('refresh');
      $("#direccionResidencia").val(data.direccionResidencia);
      $("#codigoPostal").val(data.codigoPostal);
      $("#telefonoFijo").val(data.telefonoFijo);
      $("#celular").val(data.celular);
      $("#pbx").val(data.pbx);
      $("#idTipoPersona").val(data.idTipoPersona);
      $('#idTipoPersona').selectpicker('refresh');
      $("#tipoPersonaJuridica").val(data.tipoPersonaJuridica);
      $('#tipoPersonaJuridica').selectpicker('refresh');
      $("#idAtencionPreferencial").val(data.idAtencionPreferencial);
      $('#idAtencionPreferencial').selectpicker('refresh');
      $("#idEtnia").val(data.idEtnia);
      $('#idEtnia').selectpicker('refresh');
      $("#idDiscapacidad").val(data.idDiscapacidad);
      $('#idDiscapacidad').selectpicker('refresh');
      $("#idOrientacionSexual").val(data.idOrientacionSexual);
      $('#idOrientacionSexual').selectpicker('refresh');
      $("#idEmbarazo").val(data.idEmbarazo);
      $('#idEmbarazo').selectpicker('refresh');
      data.adultoMayor==1?$('#adultoMayor').prop('checked',true):$('#adultoMayor').prop('checked',false);
      data.notificacionFisica==1?$('#notificacionFisica').prop('checked',true)+$('.ubicacion').show():$('#notificacionFisica').prop('checked',false)+$('.ubicacion').hide();
      data.notificacionElectronica==1?$('#notificacionElectronica').prop('checked',true)+$('.email').show():$('#notificacionElectronica').prop('checked',false)+$('.email').hide();
      data.adultoMayor==1?$("#adultoMayor").attr('checked', true):null;
      // console.log(data)
      $.post("../ajax/select.php?op=sel_upz", { localidad: data.localidad }, function(r){
                  $("#codigoUpz").html(r.result);
                  $('#codigoUpz').selectpicker('refresh');
                  $("#codigoUpz").val(data.codigoUpz);
                  $('#codigoUpz').selectpicker('refresh');
      });  
      $.post("../ajax/select.php?op=sel_barrio", { codigoUpz: data.codigoUpz }, function(r){
                  $("#idBarrio").html(r.result);
                  $('#idBarrio').selectpicker('refresh');
                  $("#idBarrio").val(data.idBarrio);
                  $('#idBarrio').selectpicker('refresh');
      });  
      $("#idEstrato").val(data.idEstrato);
      $('#idEstrato').selectpicker('refresh');
      $("#direccionResidencia").val(data.direccionResidencia);
      $("#codigoPostal").val(data.codigoPostal);
      $("#nombreCompletoContacto").val(data.nombreCompletoContacto);
      $("#direccionResidenciaContacto").val(data.direccionResidenciaContacto);
      $("#correoElectronicoContacto").val(data.correoElectronicoContacto);
      $("#telefonoFijoContacto").val(data.telefonoFijoContacto);
      $("#celularContacto").val(data.celularContacto);
      $("#cargoContacto").val(data.cargoContacto);
    }else{
      $("#idAtencionPreferencial_anonim").val(data.idAtencionPreferencial_anonim);
      $("#nombreCompletoContacto_anonim").val(data.nombreCompletoContacto_anonim);
      $("#telefonoFijoContacto_anonim").val(data.telefonoFijoContacto_anonim);
      $("#celularContacto_anonim").val(data.celularContacto_anonim);
      $("#correoElectronicoContacto_anonim").val(data.correoElectronicoContacto_anonim);
      $("#direccionResidenciaContacto_anonim").val(data.direccionResidenciaContacto_anonim);
      data.notificacionFisica_anonim==1?$('#notificacionFisica_anonim').prop('checked',true)+$('.ubicacion_anonim').show():$('#notificacionFisica_anonim').prop('checked',false)+$('.ubicacion_anonim').hide();
      data.notificacionElectronica_anonim==1?$('#notificacionElectronica_anonim').prop('checked',true)+$('.email_anonim').show():$('#notificacionElectronica_anonim').prop('checked',false)+$('.email_anonim').hide();
      anonimo_form('1');
      $('#anonim').val(data.anonimo);
      $('#anonim').selectpicker('refresh');
    }

    if (data.estado==3) {
      $("#nav-tabContent :input").prop('readonly', true);
      $('#nav-tabContent').find('select').prop('disabled', true);
      $('#nav-tabContent').find('button').prop('disabled', true);        
    }else{
      $("#nav-tabContent :input").prop('readonly', false);
      $('#nav-tabContent').find('select').prop('disabled', false);
      $('#nav-tabContent').find('button').prop('disabled', false);
    }

    show_an();
    edit_pond(data.id_pqr);
    edit_repr(data.id_pqr);
    view(data.id_pqr);
    show_cierre(data.id_pqr);
    view_r();
  })
}

function show_an(){
  $("#hr_edit").html("");
  $("#entre_edit").html("");
  var id = $('#id').val();
  var con = 2;
  $.post("../ajax/pqr.php?op=contra",{id:id,con:con}, function(data, status)
  {
    // console.log(data)
    if (data['archivo'].length > 0 ) {
      $("#hr_edit").append('<h5>Anexos Cargados</h5>');
      $("#entre_edit").append('<div class="col-12"><table class="table table-responsive"><thead><tr><th>Nombre Archivo</th><th>URL</th><th>Eliminar</th></tr></thead><tbody id="tbody_edit"></tbody></table></div>');   
    }

    for (var i = 0; i < data['archivo'].length; i++) {
      if (estado.value==3 || estado.value==5) {
        $("#tbody_edit").append('<tr><td>'+data['archivo'][i]+'</td><td><button onclick="download_file(`'+data['archivo'][i]+'`, '+con+')" type="button" class="btn btn-info">Abrir</button></td></tr>');
      }else{
        $("#tbody_edit").append('<tr><td>'+data['archivo'][i]+'</td><td><button onclick="download_file(`'+data['archivo'][i]+'`, '+con+')" type="button" class="btn btn-info">Abrir</button></td><td><button type="button" onclick="eliminar_edit('+data['id_entrega'][i]+');" type="button" class="btn btn-danger">Eliminar</button></td></tr>'); 
      }
    }
   
  })
}

function download_file(link, type_file){
  window.open("../ajax/download_file.php?link="+link+"&type="+type_file, '_blank');
}


function limpiar_ciu(){
  $('#nav-ciu').find('input:text').val('');
  $('#nav-ciu').find('textarea').val('');
  $('#adultoMayor').prop('checked',false);
  $('#notificacionFisica').prop('checked',false);
  $('#notificacionElectronica').prop('checked',false);
  $('#nav-ciu').find('select').val('0').selectpicker('refresh');
  $('#numeroDocumento').val(num_doc);
  $('#idTipoPersona').val(tipo_per);
  $('#idTipoPersona').selectpicker('refresh');
  $('#codigoTipoIdentificacion').val(tipo_doc); 
  $('#codigoTipoIdentificacion').selectpicker('refresh');
  $("#id_ciu").val('');
  $('.email').hide();
  $('.ubicacion').hide();
  $('#correoElectronico').prop('required', false);
  $('#direccionResidencia').prop('required', false);
}



function filtro(doc)
{
  $.post("../ajax/pqr.php?op=filtro",{doc:doc}, function(data, status)
  {
    let num_doc = $('#numeroDocumento').val();
    let tipo_per = $('#idTipoPersona').val();
    let tipo_doc = $('#codigoTipoIdentificacion').val();

    if (data==null) {
      $.post("../ajax/pqr.php?op=filtrobte",{doc:doc,codigoTipoIdentificacion:tipo_doc}, function(data2, status)
      {
        if (data2!=null) {
          bootbox.confirm("¿Este usuario ya existe en BTE, ¿desea complementar la información desde BTE?",function(result){
            if (result)
            {
              let fecha_nac = data2.fechaNacimiento==null?'':data2.fechaNacimiento.split('T')[0];
              $("#celular").val(data2.celular);
              $("#codigoTipoIdentificacion").val(data2.tipoIdentificacion.id);
              $('#codigoTipoIdentificacion').selectpicker('refresh');
              $("#numeroDocumento").val(data2.numeroIdentificacion);
              $("#fechaNacimiento").val(fecha_nac);
              $("#idGenero").val(data2.genero.id);
              $('#idGenero').selectpicker('refresh');
              $("#primerNombre").val(data2.primerNombre);
              $("#segundoNombre").val(data2.segundoNombre);  
              $("#primerApellido").val(data2.primerApellido);
              $("#segundoApellido").val(data2.segundoApellido);
              $("#telefonoFijo").val(data2.telefonoFijo);
              $("#idTipoPersona").val(data2.tipoPersona.id);

              $("#correoElectronico").val(data2.correoElectronico);
              $("#direccionResidencia").val(data2.direccionResidencia);
              $("#idEstrato").val(data2.estrato.id);
              $('#idEstrato').selectpicker('refresh');

              data2.notificacionFisica==true?$('#notificacionFisica').prop('checked',true)+$('.ubicacion').show():$('#notificacionFisica').prop('checked',false)+$('.ubicacion').hide();
              data2.notificacionElectronica==true?$('#notificacionElectronica').prop('checked',true)+$('.email').show():$('#notificacionElectronica').prop('checked',false)+$('.email').hide();
            }
          });
        }
      })
    } else {

      bootbox.confirm("¿Este usuario ya existe en la base de datos local, ¿desea complementar la información?",function(result){
        if (result)
        {
          $("#codigoTipoIdentificacion").val(data.codigoTipoIdentificacion);
          $('#codigoTipoIdentificacion').selectpicker('refresh');
          $("#numeroDocumento").val(data.numeroDocumento);
          $("#id_ciu").val(data.id_ciu);
          $("#primerNombre").val(data.primerNombre);
          $("#segundoNombre").val(data.segundoNombre);  
          $("#primerApellido").val(data.primerApellido);
          $("#segundoApellido").val(data.segundoApellido);
          $("#fechaNacimiento").val(data.fechaNacimiento);
          $("#idGenero").val(data.idGenero);
          $('#idGenero').selectpicker('refresh');
          $("#correoElectronico").val(data.correoElectronico);
          $("#localidad").val(data.localidad);
          $('#localidad').selectpicker('refresh');
          $("#direccionResidencia").val(data.direccionResidencia);
          $("#codigoPostal").val(data.codigoPostal);
          $("#telefonoFijo").val(data.telefonoFijo);
          $("#celular").val(data.celular);
          $("#pbx").val(data.pbx);
          $("#idTipoPersona").val(data.idTipoPersona);
          $('#idTipoPersona').selectpicker('refresh');
          $("#tipoPersonaJuridica").val(data.tipoPersonaJuridica);
          $('#tipoPersonaJuridica').selectpicker('refresh');
          $("#idAtencionPreferencial").val(data.idAtencionPreferencial);
          $('#idAtencionPreferencial').selectpicker('refresh');
          $("#idEtnia").val(data.idEtnia);
          $('#idEtnia').selectpicker('refresh');
          $("#idDiscapacidad").val(data.idDiscapacidad);
          $('#idDiscapacidad').selectpicker('refresh');
          $("#idOrientacionSexual").val(data.idOrientacionSexual);
          $('#idOrientacionSexual').selectpicker('refresh');
          $("#idEmbarazo").val(data.idEmbarazo);
          $('#idEmbarazo').selectpicker('refresh');
          data.adultoMayor==1?$('#adultoMayor').prop('checked',true):$('#adultoMayor').prop('checked',false);
          data.notificacionFisica==1?$('#notificacionFisica').prop('checked',true)+$('.ubicacion').show():$('#notificacionFisica').prop('checked',false)+$('.ubicacion').hide();
          data.notificacionElectronica==1?$('#notificacionElectronica').prop('checked',true)+$('.email').show():$('#notificacionElectronica').prop('checked',false)+$('.email').hide();
          data.adultoMayor==1?$("#adultoMayor").attr('checked', true):null;
          // console.log(data)
          $.post("../ajax/select.php?op=sel_upz", { localidad: data.localidad }, function(r){
            $("#codigoUpz").html(r.result);
            $('#codigoUpz').selectpicker('refresh');
            $("#codigoUpz").val(data.codigoUpz);
            $('#codigoUpz').selectpicker('refresh');
          });  
          $.post("../ajax/select.php?op=sel_barrio", { codigoUpz: data.codigoUpz }, function(r){
            $("#idBarrio").html(r.result);
            $('#idBarrio').selectpicker('refresh');
            $("#idBarrio").val(data.idBarrio);
            $('#idBarrio').selectpicker('refresh');
          });  
          $("#idEstrato").val(data.idEstrato);
          $('#idEstrato').selectpicker('refresh');
          $("#direccionResidencia").val(data.direccionResidencia);
          $("#codigoPostal").val(data.codigoPostal);
          $("#nombreCompletoContacto").val(data.nombreCompletoContacto);
          $("#direccionResidenciaContacto").val(data.direccionResidenciaContacto);
          $("#correoElectronicoContacto").val(data.correoElectronicoContacto);
          $("#telefonoFijoContacto").val(data.telefonoFijoContacto);
          $("#celularContacto").val(data.celularContacto);
          $("#cargoContacto").val(data.cargoContacto);
        }
      });
    }
  })
}


function tbl_ad(){
  $("#list_ad").html("");
  for (var a = 0; a < adjuntos.length; a++) {
    $("#list_ad").append('<tr id="tr'+a+'"><td>'+adjuntos[a]['name']+'</td><td><button onclick="del_file('+a+');" type="button" class="btn btn-danger">X</button></td></tr>');
  }
}



function del_file(id){
  $("#list_ad").html("");
  adjuntos.splice(id, 1);
  console.log(adjuntos);
  tbl_ad();
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


function view(id){

  $("#entre_obs").html("");
  $("#list_asign").html("");
   $.post("../ajax/pqr.php?op=mostrarobs",{id:id}, function(data, status)
  {
    for (var i = 0; i < data['id_obs'].length; i++) {
      $("#entre_obs").append('<tr><td>'+data['observa'][i]+'</td><td>'+data['date_obs'][i]+'</td></tr>');
    }

    for (var i = 0; i < data['nom_as'].length; i++) {
      // console.log(data['nom_as'][i])
      $("#list_asign").append('<tr><td>'+data['nom_as'][i]+'</td><td>'+data['nombre'][i]+'</td><td>'+data['observ'][i]+'</td><td>'+data['fecha_asign'][i]+'</td></tr>');
    }

  })


 }


 function view_r(){
  
  con=1;
  let id = $('#id_pqr2').val();
  $("#entre_view_r").html('');
  $("#hr_view_r").html('');
  $.post("../ajax/pqr.php?op=contra",{id:id,con:con}, function(data, status)
  {
    if (data['archivo'].length > 0) {
      $("#hr_view_r").append(
      '<h5>Entrega Anterior</h5>'+
      '<hr>');
      $("#entre_view_r").append('<div class="col-12"><table class="table table-responsive"><thead><tr><th>Nombre Archivo</th><th>URL</th><th>Eliminar</th></tr></thead><tbody id="tbody2"></tbody></table></div>');
    
    }else{
      $("#hr_view_r").append(
      '<h5>No se han realizado entregas</h5>');
    }

    for (var i = 0; i < data['archivo'].length; i++) {
      //var url_file = encodeURIComponent(data['link'][i]);
      if (estado_view.value==3) {
        $("#tbody2").append('<tr><td>'+data['archivo'][i]+'</td><td><button onclick="download_file(`'+data['archivo'][i]+'`, '+con+')" type="button" class="btn btn-info">Abrir</button></td></tr>');
      }else{
        $("#tbody2").append('<tr><td>'+data['archivo'][i]+'</td><td><button onclick="download_file(`'+data['archivo'][i]+'`, '+con+')" type="button" class="btn btn-info">Abrir</button></td><td><button onclick="eliminar('+data['id_entrega'][i]+');" type="button" class="btn btn-danger">Eliminar</button></td></tr>');
      }
      
    }
   
  })

 }



 function show_cierre(id_pqr){
  $(`#id_pqr2`).val(id_pqr);
  $.post("../ajax/pqr.php?op=show_cierre",{id_pqr:id_pqr}, function(data, status)
  {

      $(`#estado_view`).val(data.estado);
      $('#estado_view').selectpicker('refresh');
      $(`#date_respc`).val(data.fecha_cierre);
      $(`#rad_respc`).val(data.rad_cierre);
      $(`#categoria`).val(data.categoria);
      $('#categoria').selectpicker('refresh');
      $.post("../ajax/select.php?op=sel_subtema", { categoria: data.categoria }, function(r){
        $("#subtema").html(r.result);
        $('#subtema').selectpicker('refresh');
        $(`#subtema`).val(data.subtema);
        $('#subtema').selectpicker('refresh');
      });  

      status = [3,5];

      if ((status.includes(data.estado)) && data.id_bte!=0) {
        $('#btnenviobte').show();
      }
  })
 }



 function eliminar(id_ent){
  $('#loading_modal').modal({backdrop: 'static', keyboard: false});
  $.post("../ajax/pqr.php?op=eliminar",{id_ent:id_ent}, function(e)
  {
    bootbox.alert(e.result);
    setTimeout(function() {
      $('#loading_modal').modal('hide');
    view_r();
    }, 1000);  
    var id = $('#id_pqr2').val();
    view(id);
  })
 }

 function eliminar_edit(id_ent){

 $('#loading_modal').modal({backdrop: 'static', keyboard: false});
  $.post("../ajax/pqr.php?op=eliminar",{id_ent:id_ent}, function(e)
  {
    setTimeout(function() {
      $('#loading_modal').modal('hide');
    }, 1000);
    bootbox.alert(e.result);
    show_an();
  })
 }




function entrega_view(e){
 e.preventDefault(); //No se activará la acción predeterminada del evento
  $("#btnEntrega_view").prop("disabled",false);
  $('#loading_modal').modal({backdrop: 'static', keyboard: false});
  var formData = new FormData($("#entrega_view")[0]);
  for (i=0; i< adjuntos.length; i++){
  formData.append('adjuntos'+i,adjuntos[i]);
  }
//   for (var pair of formData.entries()) {
//     console.log(pair[0]+ ', ' + pair[1]); 
// }
  $.ajax({
    url: "../ajax/pqr.php?op=entrega_view",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,

      success: function(datos)
      {   
        console.log(datos)
        bootbox.alert(datos.result); 
        setTimeout(function() {
          $('#loading_modal').modal('hide');
        }, 1000);          
        // mostrarform(true);
        tabla.ajax.reload();
        if (type_sub.value==1) {
          limpiar();
          mostrarform(true);
        }else{
          view_r();
          view(id_pqr2.value);
          adjuntos = [];
          $('#list_ad').html('');
        }
      }

  });
  // limpiar();
  tabla.ajax.reload();
}



function fechas(){
  var fecha = $('#fecha_crea').val();
  var codigoTipoRequerimiento = $('#codigoTipoRequerimiento').val();
 $.post("../ajax/pqr.php?op=fechas",{codigoTipoRequerimiento:codigoTipoRequerimiento}, function(data, status)
  {

  var startDate = fecha;
startDate = new Date(startDate.replace(/-/g, "/"));
var endDate = "", noOfDaysToAdd = data.n_days, count = 0;
// var fest = ["2021/11/11"];
while(count < noOfDaysToAdd){
    endDate = new Date(startDate.setDate(startDate.getDate() + 1));
    if(endDate.getDay() != 0 && endDate.getDay() != 6){
       count++;
       // console.log(startDate.toISOString().split('T')[0],' ',count);
    }

    for (var i = 0; i < data['fests'].length; i++){
    if (startDate.toISOString().split('T')[0]==data['fests'][i]) {
        count--;
    // console.log(data['fests'][i])
    }
}
}

  $('#fecha_ley').val(endDate.toISOString().split('T')[0]);
  })
}

function confirm(id_pqr){
  $("#confirm").modal();
  var id_pqr = id_pqr;
  $('#id_pqr_con').val(id_pqr);
  $.post("../ajax/pqr.php?op=show_asign",{id_pqr:id_pqr}, function(data, status)
      {
      if (data!=null){
        $('#asign').append('<div class="col-6"><label>Observación asignación:</label><p>'+data.observ+'</p></div>');
      }

    })
}

function confirmar(){
  var id_pqr=$('#id_pqr_con').val();

  $.post("../ajax/pqr.php?op=confirmar",{id_pqr:id_pqr},function(e){
    bootbox.alert(e.result);
    tabla.ajax.reload();
  });
  $("#confirm").modal('toggle');
}




function asign(id_pqr,tipo){
  $('#asignar').modal({
    backdrop: 'static',
    keyboard: false
  })
  var id_pqr = id_pqr;
  $('#id_pqrm').val(id_pqr);

  if (tipo==1) {
    $('.obs_form').hide();
  }else{
    $('.obs_form').show();
  }

  $.post("../ajax/pqr.php?op=show_asign",{id_pqr:id_pqr}, function(data, status)
      {
      if (data!=null){
        $("#user").val(data.id_user);
        $('#user').selectpicker('refresh');
      }
    })
}

function cancel_asign(){
  $("#confirm").modal('toggle');
  var id_pqr=$('#id_pqr_con').val();
  asign(id_pqr,2);
}

function asignacion(e)
{
  e.preventDefault(); //No se activará la acción predeterminada del evento
  $("#btnAsigna").prop("disabled",false);
  var formData = new FormData($("#asignacion")[0]);
  formData.append('people', JSON.stringify(people));

  $.ajax({
    url: "../ajax/pqr.php?op=asignacion",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,

      success: function(datos)
      {                   
            $('#asignar').modal('hide');
            bootbox.alert(datos.result);  
            console.log(datos); 
            mostrarform(true);
            tabla.ajax.reload();
      }

  });
  limpiar();
  tabla.ajax.reload();
}


function add_asign(id){
  if(people.find(element => element === id)) return bootbox.alert('Ya se encuentra seleccionado');
  people.push(id);
  let name = $( '#user option:selected' ).text();
    $("#t_people").append('<blockquote id="block_'+id+'" class="card-blockquote">'+
        '<div class="row">'+
          '<div class="col-10">'+
            '<p>'+name+'</p>'+
          '</div>'+
          '<div class="col-2">'+
            '<button class="btn btn-danger" type="button" onclick="$(`#block_'+id+'`).remove();people.splice(people.indexOf(`'+id+'`),1);"><i class="fas fa-times"></i></button>'+
          '</div>'+
        '</div>'+
      '</blockquote>');
    console.log(people)
}


function add_pond()
{
      var inputs = '<div class="card col-6" id="card_no_'+num_pon+'">'+
                      '<div class="card-header">'+
                        '<h5 class="card-title">Poderdante No. '+num_pon+'</h5>'+
                          '<div class="card-tools">'+
                            '<button type="button" class="btn btn-tool" data-card-widget="remove" onclick="num_pon--;$(`#card_no_'+num_pon+'`).remove();" title="Remove">'+
                              '<i class="fas fa-times"></i>'+
                            '</button>'+
                          '</div>'+
                      '</div>'+
                      '<div class="card-body">'+
                        '<div class="row ">'+
                          '<div class="col-4">'+
                            '<label>Tipo identificación:</label>'+
                            '<input type="hidden" name="id_pod[]" value="0">'+
                            '<select class="form-control" data-live-search="true" name="codigoTipoIdentificacionPod[]" id="codigoTipoIdentificacionPod'+num_pon+'" required></select>'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>No. identificación:</label>'+
                            '<input type="text" class="form-control" name="numeroIdentificacionPod[]" placeholder="Número identificación" required>'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Teléfono:</label>'+
                            '<input type="text" class="form-control" name="telefonoPod[]" placeholder="Teléfono">'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Primer apellido:</label>'+
                            '<input type="text" class="form-control" name="primerApellidoPod[]" placeholder="Primer apellido" required>'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Segundo apellido:</label>'+
                            '<input type="text" class="form-control" name="segundoApellidoPod[]" placeholder="Segundo apellido">'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Primer nombre:</label>'+
                            '<input type="text" class="form-control" name="primerNombrePod[]" placeholder="Primer nombre" required>'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Segundo nombre:</label>'+
                            '<input type="text" class="form-control" name="segundoNombrePod[]" placeholder="Segundo nombre">'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                    '</div>';
      $('#list_pond').append(inputs);
      select_id('codigoTipoIdentificacionPod',0);
}



function edit_pond(id_pqr)
{

  $.post("../ajax/pqr.php?op=poderdantes",{id_pqr:id_pqr}, function(data, status){
    data['id_pod'].length>0?$('.ponderantes').show():null;
    for (var i = 0; i < data['id_pod'].length; i++) {
      var inputs = '<div class="card col-6" id="card_no_'+num_pon+'">'+
                      '<div class="card-header">'+
                        '<h5 class="card-title">Poderdante No. '+num_pon+'</h5>'+
                          '<div class="card-tools">'+
                            '<button type="button" class="btn btn-tool" data-card-widget="remove" onclick="num_pon--;remove_pond('+num_pon+','+data.id_pod[i]+');" title="Remove">'+
                              '<i class="fas fa-times"></i>'+
                            '</button>'+
                          '</div>'+
                      '</div>'+
                      '<div class="card-body">'+
                        '<div class="row ">'+
                          '<div class="col-4">'+
                            '<label>Tipo identificación:</label>'+
                            '<input type="hidden" name="id_pod[]" value="'+data.id_pod[i]+'">'+
                            '<select class="form-control" data-live-search="true" name="codigoTipoIdentificacionPod[]" id="codigoTipoIdentificacionPod'+num_pon+'" required></select>'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>No. identificación:</label>'+
                            '<input type="text" class="form-control" name="numeroIdentificacionPod[]" value="'+data.numeroIdentificacionPod[i]+'" placeholder="Número identificación" required>'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Teléfono:</label>'+
                            '<input type="text" class="form-control" name="telefonoPod[]" value="'+data.telefonoPod[i]+'" placeholder="Teléfono">'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Primer apellido:</label>'+
                            '<input type="text" class="form-control" name="primerApellidoPod[]" value="'+data.primerApellidoPod[i]+'" placeholder="Primer apellido" required>'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Segundo apellido:</label>'+
                            '<input type="text" class="form-control" name="segundoApellidoPod[]" value="'+data.segundoApellidoPod[i]+'" placeholder="Segundo apellido">'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Primer nombre:</label>'+
                            '<input type="text" class="form-control" name="primerNombrePod[]" value="'+data.primerNombrePod[i]+'" placeholder="Primer nombre" required>'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Segundo nombre:</label>'+
                            '<input type="text" class="form-control" name="segundoNombrePod[]" value="'+data.segundoNombrePod[i]+'" placeholder="Segundo nombre">'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                    '</div>';
      $('#list_pond').append(inputs);
      select_id('codigoTipoIdentificacionPod',data.codigoTipoIdentificacionPod);
    }
  })
}

function add_repr()
{
      var inputs = '<div class="card col-6" id="card_no_'+num_pon+'">'+
                      '<div class="card-header">'+
                        '<h5 class="card-title">Representado No. '+num_pon+'</h5>'+
                          '<div class="card-tools">'+
                            '<button type="button" class="btn btn-tool" data-card-widget="remove" onclick="num_pon--;$(`#card_no_'+num_pon+'`).remove();" title="Remove">'+
                              '<i class="fas fa-times"></i>'+
                            '</button>'+
                          '</div>'+
                      '</div>'+
                      '<div class="card-body">'+
                        '<div class="row ">'+
                          '<div class="col-4">'+
                            '<label>Tipo identificación:</label>'+
                            '<input type="hidden" name="id_rep[]" value="0">'+
                            '<select class="form-control" data-live-search="true" name="codigoTipoIdentificacionRep[]" id="codigoTipoIdentificacionRep'+num_pon+'" required></select>'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>No. identificación:</label>'+
                            '<input type="text" class="form-control" name="numeroIdentificacionRep[]" placeholder="Número identificación" required>'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Teléfono:</label>'+
                            '<input type="text" class="form-control" name="telefonoRep[]" placeholder="Teléfono">'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Primer apellido:</label>'+
                            '<input type="text" class="form-control" name="primerApellidoRep[]" placeholder="Primer apellido" required>'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Segundo apellido:</label>'+
                            '<input type="text" class="form-control" name="segundoApellidoRep[]" placeholder="Segundo apellido">'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Primer nombre:</label>'+
                            '<input type="text" class="form-control" name="primerNombreRep[]" placeholder="Primer nombre" required>'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Segundo nombre:</label>'+
                            '<input type="text" class="form-control" name="segundoNombreRep[]" placeholder="Segundo nombre">'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                    '</div>';
      $('#list_repr').append(inputs);
      select_id('codigoTipoIdentificacionRep',0);
}

function edit_repr(id_pqr)
{

  $.post("../ajax/pqr.php?op=representados",{id_pqr:id_pqr}, function(data, status){
    data['id_rep'].length>0?$('.representados').show():null;
    for (var i = 0; i < data['id_rep'].length; i++) {
      var inputs = '<div class="card col-6" id="card_no_'+num_pon+'">'+
                      '<div class="card-header">'+
                        '<h5 class="card-title">Representado No. '+num_pon+'</h5>'+
                          '<div class="card-tools">'+
                            '<button type="button" class="btn btn-tool" onclick="num_pon--;remove_rep('+num_pon+','+data.id_rep[i]+');" title="Remove">'+
                              '<i class="fas fa-times"></i>'+
                            '</button>'+
                          '</div>'+
                      '</div>'+
                      '<div class="card-body">'+
                        '<div class="row ">'+
                          '<div class="col-4">'+
                            '<label>Tipo identificación:</label>'+
                            '<input type="hidden" name="id_rep[]" value="'+data.id_rep[i]+'">'+
                            '<select class="form-control" data-live-search="true" name="codigoTipoIdentificacionRep[]" id="codigoTipoIdentificacionRep'+num_pon+'" required></select>'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>No. identificación:</label>'+
                            '<input type="text" class="form-control" name="numeroIdentificacionRep[]" value="'+data.numeroIdentificacionRep[i]+'" placeholder="Número identificación" required>'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Teléfono:</label>'+
                            '<input type="text" class="form-control" name="telefonoRep[]" value="'+data.telefonoRep[i]+'" placeholder="Teléfono">'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Primer apellido:</label>'+
                            '<input type="text" class="form-control" name="primerApellidoRep[]" value="'+data.primerApellidoRep[i]+'" placeholder="Primer apellido" required>'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Segundo apellido:</label>'+
                            '<input type="text" class="form-control" name="segundoApellidoRep[]" value="'+data.segundoApellidoRep[i]+'" placeholder="Segundo apellido">'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Primer nombre:</label>'+
                            '<input type="text" class="form-control" name="primerNombreRep[]" value="'+data.primerNombreRep[i]+'" placeholder="Primer nombre" required>'+
                          '</div>'+
                          '<div class="col-4">'+
                            '<label>Segundo nombre:</label>'+
                            '<input type="text" class="form-control" name="segundoNombreRep[]" value="'+data.segundoNombreRep[i]+'" placeholder="Segundo nombre">'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                    '</div>';
      $('#list_repr').append(inputs);
      select_id('codigoTipoIdentificacionRep',data.codigoTipoIdentificacionRep);
    }
  })
}

function remove_pond(num_pon,id_pod){
  bootbox.confirm("¿Está seguro que desea eliminar este poderdante?",function(result){
    if (result)
    {
      $.post("../ajax/pqr.php?op=remove_pond",{id_pod:id_pod}, function(e){
        bootbox.alert(e.result);
        $("#card_no_"+num_pon).slideUp().remove();
      });
    }
  })
}

function remove_rep(num_pon,id_rep){
  bootbox.confirm("¿Está seguro que desea eliminar este representado?",function(result){
    if (result)
    {
      $.post("../ajax/pqr.php?op=remove_rep",{id_rep:id_rep}, function(e){
        bootbox.alert(e.result);
        $("#card_no_"+num_pon).slideUp().remove();
      });
    }
  })
}
  
function select_id(input,val){
  let r = $('#codigoTipoIdentificacion').html();
  $('#'+input+num_pon).html(r.result);
  $('#'+input+num_pon).selectpicker('refresh');
  $('#'+input+num_pon).val(val);
  $('#'+input+num_pon).selectpicker('refresh');

  num_pon++;
}




function activar_pqr(id_pqr){
  bootbox.confirm("¿Esta seguro que desea habilitar esta PQRSD?",function(result){
    if (result)
    {
      $.post("../ajax/pqr.php?op=activar_pqr",{id_pqr:id_pqr},function(e){
        bootbox.alert(e.result);
        tabla.ajax.reload();
      });
    }
  });
}

function desactivar_pqr(id_pqr){
  bootbox.confirm("¿Esta seguro que desea inhabilitar esta PQRSD?",function(result){
    if (result)
    {
      $.post("../ajax/pqr.php?op=desactivar_pqr",{id_pqr:id_pqr},function(e){
        bootbox.alert(e.result);
        tabla.ajax.reload();
      });
    }
  });
}



function seguimiento(id_pqr){
  bootbox.confirm("¿Habilitar seguimiento?",function(result){
    if (result)
    {
      $.post("../ajax/pqr.php?op=seguimiento",{id_pqr:id_pqr},function(e){
        bootbox.alert(e.result);
        tabla.ajax.reload();
      });
    }
  });
}



init();