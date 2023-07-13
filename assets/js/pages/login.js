$(document).ready(function () {
    
    /*--Recordar login--*/
    if (localStorage.getItem('rememberedUsername')) {
        $('#username').val(localStorage.getItem('rememberedUsername'));
        $('#remember').prop('checked', true);
    }
    /*------------------*/

    $('#login').submit(function (event) {

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
            success: function(res) {
                if (res == 1) {
                    window.location.href = 'dashboard.php';
                }else if(res == 2){
                    $('#error-captcha').show();
                } else if(res == 0){
                    console.log('Error login');
                    $('#error-login').show();
                } else {
                    console.log(res);
                    $('#error-fatal').show();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud. Código de estado: ' + xhr.status);
            }
        });
    });
    
});