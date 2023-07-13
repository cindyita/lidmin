// Filtro de búsqueda de archivo
$(document).ready(function () {
  
  const searchInput = $('#search-file');
  const archiveCards = $('.archive-card-content');

  searchInput.on('input', function() {
    const searchText = searchInput.val().toLowerCase();

    archiveCards.each(function() {
      const card = $(this);
      const title = card.find('.title').text().toLowerCase();
      const company = card.find('.archive-company').text().toLowerCase();
      const date = card.find('.archive-date').text().toLowerCase();

      if (title.includes(searchText) || company.includes(searchText) || date.includes(searchText)) {
        card.show();
      } else {
        card.hide();
      }
    });
  });

  $('#addpasswordForm').submit(function (event) {

    event.preventDefault();

    var pass = $('#pass').val();
    if (pass == 'null') {
        alert('Debes ingresar una contraseña');
        return;
    }

    if (pass == $("#actualPass").text()) {
      alert('La contraseña no puede ser la misma');
      return;
    }

    var formData = new FormData(this);

    $.ajax({
        url: './src/controllers/actionController.php?action=addpassword',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false, 
      success: function (res) {
          switch (res) {
            case '1':
              message('success', 'Se guardó la contraseña');
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

  });

  //Evitar que suban favicon de gran tamaño
  fileup = $('#fileup');
  fileup.on('change', function() {
      var file = this.files[0];
      var maxSize = 5 * 1024 * 1024; // 5MB

      if (file.size > maxSize) {
          alert('El archivo excede el tamaño máximo permitido (5MB)');
          $(this).val('');
      }
  });

  $('#newfileForm').submit(function (event) {
    event.preventDefault();

    if (fileup[0].files[0]) {
      var file = fileup[0].files[0];
      var formData = new FormData(this);
      formData.append('file', file);
    } else {
        var formData = new FormData(this);
    }

    $.ajax({
        url: './src/controllers/actionController.php?action=newfile',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false, 
      success: function (res) {
          switch (res) {
            case '1':
              message('success', 'Se subió el nuevo archivo');
              setTimeout(function() {
                  window.location.reload();
              }, 700);
              break;
            case 'errortype':
              message('error', 'El tipo de archivo no está permitido');
              break;
            case '0':
              message('error', 'Error al subir el archivo');
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

    $('#editFileForm').submit(function (event) {
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: './src/controllers/actionController.php?action=editfile',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false, 
          success: function (res) {
              switch (res) {
                case '1':
                  message('success', 'Se editó el archivo');
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
    });

  $('#filepass').hide();

  $('#havePass').change(function() {
    if ($(this).is(':checked')) {
      $('#filepass').show();
    } else {
      $('#filepass').hide();
    }
  });

});

//copiar link
function copyToClipboard(text) {
  navigator.clipboard.writeText(text)
    .then(function() {
      message('success', 'Enlace copiado al portapapeles');
    })
    .catch(function() {
      message('error', 'No se pudo copiar el enlace');
    });
}

//Modal password
function modalAddPass(id, password) {
  if (password) {
    $("#actualPass").html(password);
  } else {
    $("#actualPass").removeClass("text-primary").addClass("text-muted");
    $("#actualPass").html("[Sin contraseña]");
  }
  
  $("#id_pass").val(id);
}

function removepassword() {
    id = $("#id_pass").val();
    $.ajax({
        url: './src/controllers/actionController.php?action=removepassword',
        type: 'POST',
        data: {id:id},
      success: function (res) {
          switch (res) {
            case '1':
              message('success', 'Se removió la contraseña');
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

function deletefile(idfile, idqr) {
  if (idqr) {
    message('error', 'No se puede eliminar un archivo enlazado a un QR, elimina el registro del QR para eliminar este archivo');
    return;
    }
  
    $.ajax({
        url: './src/controllers/actionController.php?action=deletefile',
        type: 'POST',
        data: {id:idfile},
      success: function (res) {
          switch (res) {
            case '1':
              message('success', 'Se borró el archivo');
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

function modalEditFile(id, name, id_company) {
  $("#id_edit").val(id);
  $("#title_edit").val(name);
  $("#filetitle").html(name);
  $("#company_edit").val(id_company);
  if (!id_company) {
    $("#company_edit").val('NULL');
  }
}

