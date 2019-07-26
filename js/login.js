'use strict'
$('#login').submit(function (e) {
    e.preventDefault();
    var email = $('#inputEmail').val();
    var pass = $('#inputPassword').val();
    logIn(email, pass);
});

function logIn(email, pass) {
    var data = "email=" + email + "&pass=" + pass + "&script=InAndOut&function=logIn";
    $.ajax({
        type: "POST",
        url: "php/main.php",
        data: data,
        success: function (r) {
            if (r != '') {
                r = JSON.parse(r);
                sessionStorage.setItem("nombreUsuario", r.usuario);
                if (r.permiso == 1 && r.estado == 'Activado') {
                    //  pagina para administradores
                    window.location.href = 'ventas';
                } else if (r.permiso == 2 && r.estado == "Activado") {
                    //  pagia para usuarios
                    window.location.href = 'venta';
                } else if (r.estado == "Desactivado") {
                    //  cuando la cuenta del usuario se encuentre desactivada
                    $('#inputs').animateCss('animated shake');
                    $('#mensaje').animateCss('animated flash');
                    $('#mensaje').text('Usuario Desactivado');
                    $("#mensaje").css("color", "yellow");
                }
            } else {
                //  cuando los datos ingresados no coincidan con la base de datos
                $('#inputs').animateCss('animated shake');
                $('#mensaje').animateCss('animated flash');
                $('#mensaje').text('Datos Incorrectos');
                $("#mensaje").css("color", "red");
            }
        }
    });
}