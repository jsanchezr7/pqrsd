const store = {};

// Inserts the jwt to the store object
store.setJWT = function (data) {
  this.JWT = data;
};

$("#frmAcceso").on('submit', async (e) => {
  e.preventDefault();

  const res = await fetch('../ajax/authenticate.php', {
    headers: {
      'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
    }
  });


  if (res.status >= 200 && res.status <= 299) {
    const jwt = await res.text();
    store.setJWT(jwt);

    logina=$("#logina").val();
    clavea=$("#clavea").val();


    $.ajax({
        url: '../ajax/usuarios.php?op=verificar',
        type: 'POST',
        data: {
            "logina":logina,
            "clavea":clavea,
            "token":JSON.stringify(store)
        },
        headers: {
            'Authorization': `Bearer ${store.JWT}`
        },
        success: function (data) {
   
   
            data = JSON.parse(data);
            if (data != undefined){
                $(location).attr("href","../vistas/pqr.php");
            }else{
               window.alert("Usuario y/o Password incorrectos");
            }
        }
    });

  } else {
    // Handle errors
    console.log(res.status, res.statusText);
  }
});


$("#form_correo").on('submit',function(e)
{
    e.preventDefault();
    correo=$("#correo").val();

    $.post("../ajax/usuarios.php?op=send_email",
        {"correo":correo},
        function(data)
    {
        alert(data.result);
        window.location.href = "https://aplicaciones.mab.com.co/pqr/vistas/login.php";
    });
});
