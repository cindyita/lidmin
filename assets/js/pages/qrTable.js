function qrUpdateModalData(id) {
    $("#qrUpdateIdText").html(id);
    $("#qrUpdateId").val(id);
    sendAjax(id, 'qrRead')
        .then(function (res) {
            data = JSON.parse(res);
            data = data[0];
            $("#nameUpdate").val(data['name']);
            if (data['id_company']) {
                $("#companyUpdate").val(data['id_company']);    
            }
        })
    .catch(function(error) {
        console.error(error);
    });
}

function qrDeleteModalData(id) {
    $("#qrDeleteIdText").html(id);
    $("#qrDeleteId").val(id);
}

$(document).ready(function () {

    $('#qrUpdateForm').submit(function (event) {

        event.preventDefault();
        
        var nameValue = $('#name').val();
        if (nameValue == 'null') {
            alert('Debes ingresar una descripción');
            return;
        }

        var formData = new FormData(this);

        sendForm(formData,'qr','update');

    });

    $('#qrDeleteForm').submit(function (event) {

        event.preventDefault();
        var formData = new FormData(this);

        sendForm(formData,'qr','delete');
    });

// Select generate QR from (appear fields)
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

  //Limit size file
  fileInput = $('#file');
  fileInput.on('change', function() {
      var file = this.files[0];
      var maxSize = 5 * 1024 * 1024; // 5MB

      if (file.size > maxSize) {
          alert('El archivo excede el tamaño máximo permitido (5MB)');
          $(this).val('');
      }
  });

  //Generate QR
    $('#qrCreateForm').submit(function (event) {
      
        event.preventDefault();

        var companyValue = $('#company').val();
        var generateValue = $('#generate').val();
        var urlValue = $('#url').val();

        if (companyValue == 'null') {
            messageModal('danger', 'Debes seleccionar una o ninguna empresa', 'qrCreateModal');
            return;
        }
        if (generateValue == 'null') {
            messageModal('danger', 'Debes seleccionar un modo de generación', 'qrCreateModal');
            return;
        }

        if (generateValue == 'url' && urlValue == '') {
            messageModal('danger', 'Debes ingresar una url', 'qrCreateModal');
            return;
        }

        if (generateValue == 'url' && urlValue != '' && !validarURL(urlValue)) {
            messageModal('danger', 'La URL es inválida', 'qrCreateModal');
            return;
        }

        if (generateValue == 'file' && !fileInput[0].files[0]) {
            messageModal('danger', 'Debes seleccionar un archivo file', 'qrCreateModal');
            return;
        }

        if (generateValue == 'web' && companyValue == 'null' || generateValue == 'web' && companyValue == '0' ||generateValue == 'web' && companyValue == 0) {
            messageModal('danger', 'No puedes crear un QR a partir de un campo indefinido', 'qrCreateModal');
            return;
        }

        modalLoaderIn('qrCreateModal');

        if (generateValue == 'file') {
        var file = fileInput[0].files[0];
        var formData = new FormData(this);
        formData.append('file', file);
        } else {
        var formData = new FormData(this);
        }
        
        sendForm(formData,'qr','create');
        
    });

});