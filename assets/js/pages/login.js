$(document).ready(function () {
    
    /*--Recordar login--*/
    if (localStorage.getItem('rememberedUsername')) {
        $('#username').val(localStorage.getItem('rememberedUsername'));
        $('#remember').prop('checked', true);
    }
    /*------------------*/

    $('#login').submit(function (event) {

        var recaptchaResponse = $('#g-recaptcha-response').val();

        $.ajax({
        url: 'https://www.google.com/recaptcha/api/siteverify',
        type: 'POST',
        data: {
            secret: '6LcVxR0nAAAAAEsXfq83Av-3i-KALzwKclGK7vUQ',
            response: recaptchaResponse
        },
        dataType: 'json',
        success: function(response) {
            console.log(response);
            if (response['success'] == false) {
                $('#error-captcha').show();
                return;
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
        });


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