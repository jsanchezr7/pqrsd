
$.ajaxSetup({
  headers: {
    'Authorization': `Bearer ${store.JWT}`
  },
  dataType    :"json", // all requests should respond with json string by default
  type        : "POST", // all request should POST by default
  timeout     :   0,
  error       : function(xhr, textStatus, errorThrown){
    console.log(xhr)
    if (xhr.responseText=='Expired token') {
      location.href='logOut.php';
    }
  }
});

//funcion que se ejecuta al inicio
function init(){
 
}


function filtrobte_reque(id_bte){
  $.post("../ajax/bte.php?op=filtrobte_reque",{id_bte:id_bte}, function(data, status)
  {
    // console.log(data)
    bootbox.alert(data.result);    
  });
}


function filtrobte_dates(fecha_ini, fecha_fin){
    
    $('#loading_modal').modal({backdrop: 'static', keyboard: false});
    
  $.post("../ajax/bte.php?op=filtrobte_dates",{fecha_ini:fecha_ini, fecha_fin:fecha_fin}, function(data, status)
  {
    // console.log(data)
    
    $('#loading_modal').modal('hide');
    
    bootbox.alert(data.result);    
  });
  // $.ajax({
  //   cache:false,
  //   async:false,
  //   url: '../ajax/bte.php?op=filtrobte_dates',
  //   data: {fecha_ini:fecha_ini, fecha_fin:fecha_fin},
  //   timeout:0,
  //   // beforeSend:function(msgg){   
  //   //   $('#loading').show();
  //   // },
  //   success: function(data){
  //     // console.log(data)
  //     bootbox.alert(data.result);
  //   }
  // });
}

function getParams(){
    $('#loading_modal').modal({backdrop: 'static', keyboard: false});
  $.post("../ajax/bte.php?op=getParams", function(data, status)
  {
      $('#loading_modal').modal('hide');
    bootbox.alert(data.result);   
  });
}


init();