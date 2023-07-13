$(document).ready(function () {
    
    /*--Recordar login--*/
    if (localStorage.getItem('rememberedUsername')) {
        $('#username').val(localStorage.getItem('rememberedUsername'));
        $('#remember').prop('checked', true);
    }
    /*------------------*/

    $('#login').submit(function (event) {

        $('#error-login').hide();

        /*----Función de recuerdame---*/
        if ($('#remember').is(':checked')) {

        localStorage.setItem('rememberedUsername', $('#username').val());
        } else {
        localStorage.removeItem('rememberedUsername');
        }
        /*----------------------------*/

        event.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: './src/controllers/actionController.php?action=login',
            type: 'POST',
            data: formData,
            success: function (res) {
                if (res == 1) {
                    window.location.href = 'dashboard.php';
                } else if (res == 2) {
                    $('#error-login').html("Error: El captcha es inválido");
                    $('#error-login').show();
                } else if (res == 0) {
                    $('#error-login').html("Error: El usuario y/o contraseña son incorrectos");
                    $('#error-login').show();
                } else {
                    $('#error-login').html("Error: Hubo un error al iniciar sesión");
                    $('#error-login').show();
                    console.log(res);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud. Código de estado: ' + xhr.status);
            }
        });
    });
    
});