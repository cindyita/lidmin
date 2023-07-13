
/*----LOGOUT----*/
function logout(){
    console.log('logout');
    $.ajax({
        url: './src/controllers/actionController.php?action=logout',
        type: 'POST',
        success: function (res) {
            if (res == 1) {
                window.location.href = 'index.php';
            } else {
                console.log('error en logout: '+res);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud. Código de estado: ' + xhr.status);
        }
    });
}

function message(type, text) {
    var alertClass = (type === 'success') ? 'alert-success' : 'alert-danger';
    var typeText = (type === 'success') ? 'Éxito' : 'Error';

    var html = '<div class="alert ' + alertClass + ' alert-dismissible" id="message">';
    html += '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    html += '<strong>' + typeText + '</strong> ' + text;
    html += '</div>';

    var $message = $(html);
    $message.hide().appendTo('#messages').fadeIn();

    setTimeout(function() {
        $message.fadeOut(function() {
            $(this).remove();
        });
    }, 4000);
}
/*------------------*/