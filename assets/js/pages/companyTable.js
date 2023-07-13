function editCompany(id) {
    $.ajax({
        url: './src/controllers/actionController.php?action=selectcompany',
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
            $('#companyid').val(data['id']);
        },
        error: function(xhr) {
            console.error('Error en la solicitud. Código de estado: ' + xhr.status);
        }
    });
}

function deleteCompany(id) {
    $("#deleteCompanyid").html(id);
    $("#deleteid").val(id);
}

$(document).ready(function () {

    //Evitar que suban pdf gran tamaño
    fileInput = $('#logo');
    fileInput.on('change', function() {
        var file = this.files[0];
        var maxSize = 3 * 1024 * 1024; // 5MB

        if (file.size > maxSize) {
            alert('El archivo excede el tamaño máximo permitido (3MB)');
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

        $.ajax({
            url: './src/controllers/actionController.php?action=createcompany',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false, 
            success: function (res) {
                if (res == 1) {
                    message('success', 'Se registró la empresa correctamente');
                    setTimeout(function() {
                        window.location.reload();
                    }, 800);
                    $('#createCompanyForm')[0].reset();
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

    //Evitar que suban pdf gran tamaño
    fileInput = $('#logo_edit');
    fileInput.on('change', function() {
        var file = this.files[0];
        var maxSize = 3 * 1024 * 1024; // 5MB

        if (file.size > maxSize) {
            alert('El archivo excede el tamaño máximo permitido (3MB)');
            $(this).val('');
        }
    });

    $('#editCompanyForm').submit(function (event) {
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

        $.ajax({
            url: './src/controllers/actionController.php?action=editcompany',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false, 
            success: function (res) {
                if (res == 1) {
                    message('success', 'Se editó la empresa correctamente');
                    setTimeout(function() {
                        window.location.reload();
                    }, 800);
                    $('#editCompanyForm')[0].reset();
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

    $('#deleteCompanyForm').submit(function (event) {

        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: './src/controllers/actionController.php?action=deletecompany',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false, 
            success: function (res) {
                if (res == 1) {
                    message('success', 'Se eliminó la empresa correctamente');
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