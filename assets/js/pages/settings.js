$(document).ready(function () {

  //Limit size logo
  logoInput = $('#logo');
  logoInput.on('change', function() {
      var file = this.files[0];
      var limit = $("#limit_size_files").val() ? $("#limit_size_files").val() : 5;
      var maxSize = limit * 1024 * 1024;

      if (file.size > maxSize) {
          alert('El archivo excede el tamaño máximo permitido ('+limit+'MB)');
          $(this).val('');
      }
  });

  //Limit size favicon
  faviconInput = $('#favicon');
  faviconInput.on('change', function() {
      var file = this.files[0];
      var limit = $("#limit_size_files").val() ? $("#limit_size_files").val() : 5;
      var maxSize = limit * 1024 * 1024;

      if (file.size > maxSize) {
          alert('El archivo excede el tamaño máximo permitido ('+limit+'MB)');
          $(this).val('');
      }
  });

  //save settings
  $('#savesettings').submit(function (event) {

    event.preventDefault();

    var app_name = $('#app_name').val();

    if (app_name == 'null') {
        alert('Debes ingresar el nombre de la app');
        return;
    }

    if (logoInput[0].files[0]) {
      var file = logoInput[0].files[0];
      var formData = new FormData(this);
      formData.append('file', file);
    } else {
        var formData = new FormData(this);
    }

    if (faviconInput[0].files[0]) {
      var file = faviconInput[0].files[0];
      formData.append('file', file);
    }

    $.ajax({
        url: './src/controllers/actionController.php?action=savesettings',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false, 
      success: function (res) {
          switch (res) {
            case res.startsWith("Error"):
              message('error', 'Algo salió mal');
              console.log(res);
            case 'errortype':
              message('error', 'No se permite esa extensión de archivo');
              break;
            default:
              messageLoader('success', 'Se guardó la configuración correctamente');
              var data = JSON.parse(res);
              data = data[0];

              $('#app_name').val(data.app_name);
              $('#color_primary').val(data.color_primary);
              $('#color_secondary').val(data.color_secondary);
              $('#color_tertiary').val(data.color_tertiary);
              $('#color_font').val(data.color_font);
              $('#color_font2').val(data.color_font2);
              if (data.logo) {
                $('#logo').next('.text-muted').text('Actual: ' + data.logo);
              }
              if (data.favicon) {
                $('#favicon').next('.text-muted').text('Actual: ' + data.logo);
              }

              setTimeout(function() {
                    window.location.reload();
                }, 700);
              
            break;
          }
        },
        error: function(xhr) {
            console.error('Error en la solicitud. Código de estado: ' + xhr.status);
        }
    });
  });

});

function setdefault() {
    $.ajax({
        url: './src/controllers/actionController.php?action=setdefault',
        type: 'POST',
        processData: false,
        contentType: false, 
      success: function (res) {
        switch (res) {
            case '1':
              message('success', 'Se guardó la configuración correctamente. Actualizando...');
              setTimeout(function() {
                    window.location.reload();
                }, 700);
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
}