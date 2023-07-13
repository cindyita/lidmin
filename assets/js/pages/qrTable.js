function editQr(id, name, company) {
    $("#editqrid").html(id);
    $("#editid").val(id);
    $("#name").val(name);
    if (company) {
        $("#company").val(company);    
    }
}

function deleteQr(id) {
    $("#deleteqrid").html(id);
    $("#deleteid").val(id);
}

$(document).ready(function () {

    $('#editQrForm').submit(function (event) {

        event.preventDefault();
        
        var nameValue = $('#name').val();
        if (nameValue == 'null') {
            alert('Debes ingresar una descripción');
            return;
        }

        var formData = new FormData(this);

        $.ajax({
            url: './src/controllers/actionController.php?action=editqr',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false, 
            success: function (res) {
                if (res == 1) {
                    message('success', 'Se editó el QR correctamente');
                    setTimeout(function() {
                        window.location.reload();
                    }, 800);
                } else {
                    message('error', 'Algo salió mal');
                    console.log(res);
                }
            },
            error: function(xhr) {
                console.error('Error en la solicitud. Código de estado: ' + xhr.status);
            }
        });
    });

    $('#deleteQrForm').submit(function (event) {

        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: './src/controllers/actionController.php?action=deleteqr',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false, 
            success: function (res) {
                if (res == 1) {
                    message('success', 'Se eliminó el QR correctamente');
                    setTimeout(function() {
                        window.location.reload();
                    }, 800);
                } else {
                    message('error', 'Algo salió mal');
                    console.log(res);
                }
            },
            error: function(xhr) {
                console.error('Error en la solicitud. Código de estado: ' + xhr.status);
            }
        });
    });

});