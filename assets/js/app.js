/**
 * Javascript Funcions
 */

/**
 * Performs a logout action by sending an AJAX request to the server.
 * Redirects to 'index.php' if the logout is successful.
 * Logs an error message if the logout fails.
 */
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

/**
 * Displays a message with the specified type and text.
 * Automatically fades out the message after 4 seconds.
 *
 * @param {string} type - The type of the message ('success' or 'error').
 * @param {string} text - The text of the message to be displayed.
 */
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

/**
 * Displays a message with loader with the specified type and text.
 * Automatically fades out the message after 4 seconds.
 *
 * @param {string} type - The type of the message ('success' or 'error').
 * @param {string} text - The text of the message to be displayed.
 */
function messageLoader(type, text) {
    var alertClass = (type === 'success') ? 'alert-success' : (type === 'danger' ? 'alert-danger' : 'alert-'+type);
    var typeText = (type === 'success') ? 'Éxito' : (type === 'danger' ? 'Error' : '');
    var loading = (type === 'success' || type === 'danger') ? '. Actualizando..' : '';

    var html = '<div class="alert ' + alertClass + ' alert-dismissible" id="message">';
    html += '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    html += '<strong>' + typeText + '</strong> ' + text + loading +' <div class="spinner-border spinner-border-sm text-'+type+'"></div>';
    html += '</div>';

    var $message = $(html);
    $message.hide().appendTo('#messages').fadeIn();

    setTimeout(function() {
        $message.fadeOut(function() {
            $(this).remove();
        });
    }, 4000);
}

/**
 * Displays a message with the specified type and text inside a modal.
 * The message is automatically removed when the modal is closed.
 * The message can also be manually dismissed by clicking a button.
 *
 * @param {string} type - The type of the message ('danger' or any other type).
 * @param {string} text - The text of the message to be displayed.
 * @param {string} modal - The ID of the modal where the message should be displayed.
 */
function messageModal(type, text, modal, timeOutRemove = false) {
    var typeText = (type === 'danger') ? 'ERROR' : (type == 'success' ? 'Éxito' : 'Alerta');

    var html = '<span class="text-' + type + '">';
    html += '<strong>' + typeText + ': </strong> ' + text;
    html += '</span><br>';

    var $msg = $(html);
    $msg.hide().appendTo('#' + modal + ' .msgModal').fadeIn();

    $('#' + modal).on('hidden.bs.modal', function() {
        $msg.fadeOut(function() {
            $msg.remove();
        });
    });
    $('#' + modal + ' button').on('click', function() {
        $msg.remove();
    });
    if (timeOutRemove) {
        setTimeout(function() {
            $msg.fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    }
}

/**
 * Appear the loading spinner from the specified modal.
 *
 * @param {string} modal - The ID of the modal from which to remove the loading spinner.
 */
function modalLoaderIn(modal) {
    var loader = $('#' + modal + ' .loading');
    loader.fadeIn();
}

/**
 * Removes the loading spinner from the specified modal.
 *
 * @param {string} modal - The ID of the modal from which to remove the loading spinner.
 */
function modalLoaderOut(modal) {
    var loader = $('#' + modal + ' .loading');
    loader.remove();
}

/**
 * Validates whether the given URL is valid.
 *
 * @param {string} url - The URL to be validated.
 * @returns {boolean} - True if the URL is valid, false otherwise.
 */
function validarURL(url) {

  var regexURL = /^(ftp|http|https):\/\/[^ "]+$/;

  if (regexURL.test(url)) {
    return true;
  } else {
    return false;
  }
}

/**
 * Hides the specified modal.
 *
 * @param {string} modal - The ID of the modal to hide.
 */
function hideModal(modal) {
    var modalElement = document.getElementById(modal);
    var bootstrapModal = bootstrap.Modal.getInstance(modalElement);
    bootstrapModal.hide();
}

/**
 * Sends a form data via AJAX to perform a CRUD operation.
 * @param {FormData} formData - The form data to be sent.
 * @param {string} id - (Page) The ID of the item associated with the CRUD operation.
 * @param {string} crudMethod - The CRUD method (e.g., create, read, update, delete).
 */
function sendForm(formData,id,crudMethod) {
    title = id.toUpperCase();
    
    switch (crudMethod) {
        case 'update':
            metodo = "Actualizar"
            break;
        case 'create':
            metodo = "Crear"
            break;
        case 'delete':
            metodo = "Borrar"
            break;
        case 'read':
            metodo = "Consultar"
            break;
        case 'add':
            metodo = "Agregar"
            break;
        default:
            break;
    }
    crudMethod = crudMethod.charAt(0).toUpperCase() + crudMethod.slice(1);

    $.ajax({
        url: './src/controllers/actionController.php?action='+id+crudMethod,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false, 
        success: function (res) {
            hideModal(id+crudMethod+'Modal');
            modalLoaderOut(id + crudMethod + 'Modal');
            switch (res) {
                case '1':
                    message('success', title + ' éxito en: ' + metodo);
                    if (crudMethod == 'Create') {
                        messageLoader('success', title + ' éxito en: ' + metodo);
                        setTimeout(function() {
                            window.location.reload();
                        }, 300);
                    } else {
                        reloadTable(id+'ReadTable');
                    }
                break;
                case '0':
                    message('error', 'Error al '+metodo+': '+title);
                break;
                case 'errorpattern':
                    message('error', 'Se ingresaron caracteres inválidos');
                break;
                case 'errortype':
                    message('error', 'No se permite esa extensión de archivo');
                    break;
                case 'get':
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

/**
 * Performs an AJAX request using jQuery.ajax and returns a promise with the result.
 * @param {Object} data - The data to be sent in the AJAX request.
 * @param {string} action - The action to be performed in the server controller.
 * @returns {Promise} - A promise that resolves with the response data from the AJAX request or rejects with an error.
 */
function sendAjax(data, action) {
  return new Promise(function(resolve, reject) {
    $.ajax({
      url: './src/controllers/actionController.php?action=' + action,
      type: 'POST',
      data: { data: data },
      success: function(res) {
        resolve(res);
      },
      error: function(xhr) {
        console.error('Error en la solicitud. Código de estado: ' + xhr.status);
        reject('error');
      }
    });
  });
}

/**
 * Reloads the table data by making an AJAX request to the specified action URL.
 * @param {string} action - The action parameter for the AJAX request URL.
 */
actionsTable = 0;
function reloadTable(action) {
    $.ajax({
        url: './src/controllers/actionController.php?action=' + action,
        type: 'POST',
        success: function (data) {
            data = JSON.parse(data);

            var columnsCount = table.columns().count();
            var lastColumnIndex = columnsCount - 1;
        
            var actionsColumnData = table.column(lastColumnIndex, { search: 'applied' }).data().toArray();
            if (actionsColumnData[0] != '') {
                actionsTable = actionsColumnData;
            } else {
                actionsColumnData = actionsTable;
            }

            table.clear().rows.add(data).draw();

            table.column(lastColumnIndex).nodes().each(function (cell, index) {
                $(cell).html(actionsColumnData[index]);
            });
            
        },
        error: function (xhr) {
            console.error('Error en la solicitud. Código de estado: ' + xhr.status);
        }
    });
}

/**
 * Copies the specified text to the clipboard.
 * @param {string} text - The text to be copied.
 */
function copyToClipboard(text) {
  navigator.clipboard.writeText(text)
    .then(function() {
      message('success', 'Enlace copiado al portapapeles');
    })
    .catch(function() {
      message('error', 'No se pudo copiar el enlace');
    });
}
