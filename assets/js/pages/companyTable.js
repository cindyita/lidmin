function companyUpdateModalData(id) {
    $.ajax({
        url: './src/controllers/actionController.php?action=companyRead',
        type: 'POST',
        data: { id: id },
        success: function (res) {
            data = JSON.parse(res);
            data = data[0];
            $('#name_edit').val(data['name']);
            $('#color_edit').val(data['primary_color']);
            $('#archive_name').html(data['logo']);
            $('#logo_actual').val(data['logo']);
            $('#web_edit').val(data['website']);
            $('#email_edit').val(data['email']);
            $('#tel_edit').val(data['phone']);
            $('#companyUpdateId').val(data['id']);
        },
        error: function(xhr) {
            console.error('Error en la solicitud. Código de estado: ' + xhr.status);
        }
    });
}

function companyDeleteModalData(id) {
    $("#companyDeleteIdText").html(id);
    $("#companyDeleteId").val(id);
}

$(document).ready(function () {

    //Evitar que suban pdf gran tamaño
    fileInput = $('#logo');
    fileInput.on('change', function() {
        var file = this.files[0];
        var limit = $("#limit_size_files").val() ? $("#limit_size_files").val() : 5;
        var maxSize = limit * 1024 * 1024;

        if (file.size > maxSize) {
            alert('El archivo excede el tamaño máximo permitido ('+limit+'MB)');
            $(this).val('');
        }
    });

    $('#createCompanyForm').submit(function (event) {
        event.preventDefault();
        
        var nameValue = $('#name').val();
        if (nameValue == 'null') {
            alert('Debes ingresar el nombre de la empresa');
            return;
        }

        var colorValue = $('#color').val();
        if (colorValue == 'null') {
            alert('Debes ingresar un color');
            return;
        }

        var file = $('#logo')[0].files[0];
        var formData = new FormData(this);
        formData.append('file', file);

        if (!file) {
            alert('Debes seleccionar un logo');
            return;
        }

        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
        if (!allowedExtensions.test(file.name)) {
            alert('Solo se permiten archivos de imagen (jpg, jpeg, png, gif)');
            return;
        }
        sendForm(formData,'company','create');
    });

    //Limit size pdf
    fileInput = $('#logo_edit');
    fileInput.on('change', function() {
        var file = this.files[0];
        var limit = $("#limit_size_files").val() ? $("#limit_size_files").val() : 5;
        var maxSize = limit * 1024 * 1024;

        if (file.size > maxSize) {
            alert('El archivo excede el tamaño máximo permitido ('+limit+'MB)');
            $(this).val('');
        }
    });

    $('#companyUpdateForm').submit(function (event) {
        event.preventDefault();
        
        var nameValue = $('#name_edit').val();
        if (nameValue == 'null') {
            alert('Debes ingresar el nombre de la empresa');
            return;
        }

        var colorValue = $('#color_edit').val();
        if (colorValue == 'null') {
            alert('Debes ingresar un color');
            return;
        }

        var file = $('#logo_edit')[0].files[0];

        if (file) {

            var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
            if (!allowedExtensions.test(file.name)) {
                alert('Solo se permiten archivos de imagen (jpg, jpeg, png, gif)');
                return;
            }

            var formData = new FormData(this);
            formData.append('file', file);
        } else {
            var formData = new FormData(this);
        }
        sendForm(formData,'company','update');
    });

    $('#companyDeleteForm').submit(function (event) {
        event.preventDefault();
        var formData = new FormData(this);
        sendForm(formData,'company','delete');
    });

});