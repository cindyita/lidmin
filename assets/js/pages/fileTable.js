
$(document).ready(function () {
  // Search file
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

  $('#createPasswordForm').submit(function (event) {

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

    sendForm(formData,'password','create');

  });

  //Limit Size file
  fileup = $('#fileup');
  fileup.on('change', function() {
    var file = this.files[0];
    var limit = $("#limit_size_files").val() ? $("#limit_size_files").val() : 5;
    var maxSize = limit * 1024 * 1024;

      if (file.size > maxSize) {
          alert('El archivo excede el tamaño máximo permitido ('+limit+'MB)');
          $(this).val('');
      }
  });

  $('#fileCreateForm').submit(function (event) {
    modalLoaderIn('newfile');
    event.preventDefault();

    if (fileup[0].files[0]) {
      var file = fileup[0].files[0];
      var formData = new FormData(this);
      formData.append('file', file);

      $('#progressBar-content').show();

      const xhr = new XMLHttpRequest();

      xhr.upload.addEventListener('progress', (event) => {
        if (event.lengthComputable) {
          const percentUploaded = (event.loaded / event.total) * 100;
          $('#progressBar').width(percentUploaded + '%');
          $('#progressBar').html(Math.round(percentUploaded) + '%');
        }
      });

      xhr.open('POST', './src/controllers/actionController.php?action=fileCreate');
      xhr.onload = function () {
        if (xhr.status === 200) {
          const res = xhr.responseText;
          switch (res) {
            case '1':
              modalLoaderOut('newfile');
              hideModal('newfile');
              messageLoader('success', 'Se subió el nuevo archivo');
              setTimeout(function() {
                  window.location.reload();
              }, 200);
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
        } else {
          console.error('Error en la solicitud. Código de estado: ' + xhr.status);
        }
      };

      xhr.send(formData);

    } else {
      var formData = new FormData(this);
      $.ajax({
        url: './src/controllers/actionController.php?action=fileCreate',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false, 
      success: function (res) {
          switch (res) {
            case '1':
              modalLoaderOut('newfile');
              hideModal('newfile');
              messageLoader('success', 'Se subió el nuevo archivo');
              setTimeout(function() {
                  window.location.reload();
              }, 200);
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

    }

    
  });

  $('#fileUpdateForm').submit(function (event) {
      modalLoaderIn('fileUpdateModal');
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: './src/controllers/actionController.php?action=fileUpdate',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false, 
          success: function (res) {
              switch (res) {
                case '1':
                  modalLoaderOut('fileUpdateModal');
                  hideModal('fileUpdateModal');
                  messageLoader('success', 'Se editó el archivo');
                  setTimeout(function() {
                      window.location.reload();
                  }, 200);
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

//Modal password
function passwordCreateModalData(id, password) {
  if (password) {
    $("#actualPass").html(password);
  } else {
    $("#actualPass").removeClass("text-primary").addClass("text-muted");
    $("#actualPass").html("[Sin contraseña]");
  }
  
  $("#id_pass").val(id);
}

function passwordDelete() {
    id = $("#id_pass").val();
    $.ajax({
        url: './src/controllers/actionController.php?action=passwordDelete',
        type: 'POST',
        data: {id:id},
      success: function (res) {
          switch (res) {
            case '1':
              messageLoader('success', 'Se removió la contraseña');
              setTimeout(function() {
                  window.location.reload();
              }, 300);
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

function fileDelete(idfile, idqr) {
  if (idqr) {
    message('error', 'No se puede eliminar un archivo enlazado a un QR, elimina el registro del QR para eliminar este archivo');
    return;
  }
  messageLoader('warning', 'Borrando archivo..');
  
    $.ajax({
        url: './src/controllers/actionController.php?action=fileDelete',
        type: 'POST',
        data: {id:idfile},
      success: function (res) {
          switch (res) {
            case '1':
              messageLoader('success', 'Se ha borrado el archivo');
              setTimeout(function() {
                  window.location.reload();
              }, 200);
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

function updateModalData(id, name, id_company) {
  $("#id_edit").val(id);
  $("#title_edit").val(name);
  $("#filetitle").html(name);
  if (!id_company) {
    $("#companyUpdate").val('0');
  } else {
    $("#companyUpdate").val(id_company);
  }
}

