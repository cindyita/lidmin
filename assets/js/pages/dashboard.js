$(document).ready(function () {

   // Seleccionar generar QR a partir de (aparecer campos)
  const generateSelect = $('#generate');
  const urlField = $('#url-field');
  const fileField = $('#file-field');

  urlField.hide();
  fileField.hide();

  generateSelect.on('change', function() {
    const selectedOption = generateSelect.val();
    if (selectedOption === 'url') {
      urlField.show();
      fileField.hide();
    } else if (selectedOption === 'file') {
      urlField.hide();
      fileField.show();
    } else {
      urlField.hide();
      fileField.hide();
    }
  });

  //Evitar que suban archivo gran tamaño
  fileInput = $('#file');
  fileInput.on('change', function() {
      var file = this.files[0];
      var maxSize = 5 * 1024 * 1024; // 5MB

      if (file.size > maxSize) {
          alert('El archivo excede el tamaño máximo permitido (5MB)');
          $(this).val('');
      }
  });

  //Generar QR
  $('#generateqr').submit(function (event) {

    event.preventDefault();

    var companyValue = $('#company').val();
    var generateValue = $('#generate').val();
    var urlValue = $('#url').val();

    if (companyValue == 'null') {
        alert('Debes seleccionar una o ninguna empresa');
        return;
    }
    if (generateValue == 'null') {
        alert('Debes seleccionar un modo de generación');
        return;
    }

    if (generateValue == 'url' && urlValue == '') {
        alert('Debes ingresar una url');
        return;
    }

    if (generateValue == 'file' && !fileInput[0].files[0]) {
        alert('Debes seleccionar un archivo file');
        return;
    }

    if (generateValue == 'web' && companyValue == 'null') {
        alert('No puedes crear un QR a partir de un campo indefinido');
        return;
    }

    if (generateValue == 'file') {
      var file = fileInput[0].files[0];
      var formData = new FormData(this);
      formData.append('file', file);
    } else {
      var formData = new FormData(this);
    }
    

    $.ajax({
        url: './src/controllers/actionController.php?action=generateqr',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false, 
      success: function (res) {
            switch (res) {
              case '1':
                message('success', 'Se generó el QR correctamente');
                $('#generateqr')[0].reset();
                break;
              case '0':
                message('error', 'Error al generar el código QR');
                break;
              case 'errorpattern':
                message('error', 'Se ingresaron caracteres inválidos');
                break;
              case 'errortype':
                message('error', 'No se permite esa extensión de archivo');
                break;
              default:
                message('error', 'Algo salió mal');
                console.log(res);
                break;
            }
        },
        error: function(xhr) {
            console.error('Error en la solicitud. Código de estado: ' + xhr.status);
        }
    });
  });

});