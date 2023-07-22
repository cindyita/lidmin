var folderAddListArray = {};
$(document).ready(function () {

    $('#folderCreateForm').submit(function (event) {
        event.preventDefault();
        var nameValue = $('#name').val();
        if (nameValue == 'null') {
            alert('Debes ingresar un nombre');
            return;
        }
        var formData = new FormData(this);
        sendForm(formData, 'folder', 'create');
    });

    $('#folderUpdateForm').submit(function (event) {
        event.preventDefault();
        var nameValue = $('#name').val();
        if (nameValue == 'null') {
            alert('Debes ingresar un nombre');
            return;
        }
        var formData = new FormData(this);
        sendForm(formData, 'folder', 'update');
    });

    $('#folderDeleteForm').submit(function (event) {
        event.preventDefault();
        var formData = new FormData(this);
        sendForm(formData, 'folder', 'delete');
    });

    $("#folderAddForm").submit(function (event) {
        event.preventDefault();
        var formData = new FormData(this);
        var jsonFolderAddListArray = JSON.stringify(folderAddListArray);
        formData.append('addList', jsonFolderAddListArray);

        sendForm(formData, 'folder', 'add');
    });


    $("#selectType").on("change", function () {
        var type = $(this).val();

        sendAjax(type, 'folderReadType')
            .then(function (res) {
                var data = JSON.parse(res);
                var element = '<option hidden>Selecciona un elemento</option>';
                data.forEach(function (item) {
                    element += '<option value="' + item['id'] + '">[' + item['id'] + '] ' + item['name'] + '</option > ';
                });
                $("#selectItem").html(element);
            })
            .catch(function (error) {
                console.error(error);
            });
    });

    $('#createPasswordForm').submit(function (event) {

        modalLoaderIn('folderPasswordModal');

        event.preventDefault();

        var pass = $('#pass').val();
        if (pass == 'null') {
            alert('Debes ingresar una contraseña');
            modalLoaderOut('folderPasswordModal');
            return;
        }

        if (pass == $("#actualPass").text()) {
            alert('La contraseña no puede ser la misma');
            modalLoaderOut('folderPasswordModal');
            return;
        }

        var formData = new FormData(this);

        sendForm(formData, 'folderPassword', 'create');
        
        $.ajax({
        url: './src/controllers/actionController.php?action=folderPasswordCreate',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false, 
        success: function (res) {
                modalLoaderOut('folderPasswordModal');
                switch (res) {
                    case '1':
                        message('success', title + ' éxito en: '+metodo);
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

    });


});

function folderPasswordModalData(id) {
    sendAjax(id, 'folderReadPassword')
        .then(function (res) {
            data = JSON.parse(res);
            password = data[0]['password'];
            
            $("#folderPasswordIdText").html(data[0]['name']);
            if (password) {
                $("#actualPass").html(password);
            } else {
                $("#actualPass").removeClass("text-primary").addClass("text-muted");
                $("#actualPass").html("[Sin contraseña]");
            }
        })
    .catch(function(error) {
        console.error(error);
    });
  
  $("#id_pass").val(id);
}

function passwordDelete() {
    id = $("#id_pass").val();
    $.ajax({
        url: './src/controllers/actionController.php?action=folderPasswordDelete',
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

function folderViewModalData(id) {
    $("#folderViewModalFiles").html('<div class="spinner-border spinner-border-sm"></div>');
    
    sendAjax(id, 'folderReadFiles')
        .then(function (res) {
            data = JSON.parse(res);

            $("#folderViewIdText").html(data["folder"][0]["folder_name"]);
            list = '<strong>Descripción:</strong><p>' + data["folder"][0]['folder_description'] + '</p><br>';
            
            if (data["files"][0]) {

                list += '<table class="table table-striped" id="folderFilesTable"><thead><tr><th>Nombre</th><th>Tipo</th><th>id</th><th>Acciones</th></tr></thead><tbody></tbody>';

                data["files"].forEach(element => {
                    list += '<tr>';
                    list += '<td>';

                    switch (element['type']) {
                        case 'qr':
                            list += '<i class="fa-solid fa-qrcode pe-2"></i>';
                            break;
                        case 'archive':
                            list += '<i class="fa-solid fa-file pe-2"></i>';
                            break;
                        case 'link':
                            list += '<i class="fa-solid fa-link pe-2"></i>';
                            break;
                        default:
                            list += '<i class="fa-solid fa-file pe-2"></i>';
                            break;
                    }

                    list += element['name'] + '</td>';
                    list += '<td>' + element['type'] + '</td>';
                    list += '<td>' + element['id'] + '</td>'; 
                    list += '<td><a class="text-danger cursor-pointer" onclick="folderDeleteElementList('+id+','+element['id']+',\''+element['type']+'\')"><i class="fa-solid fa-circle-xmark"></i></a></td>';
                    list += '</tr>';
                });
                list += '</tbody></table>';
                $("#folderViewModalFiles").html(list);
            } else {
                list += '<p class="text-muted">[Aún no hay archivos asociados]</p>';
                $("#folderViewModalFiles").html(list);
            }
            
        })
    .catch(function(error) {
        console.error(error);
    });
}

function folderUpdateModalData(id) {
    $("#folderUpdateIdText").html(id);
    $("#folderUpdateId").val(id);
    sendAjax(id, 'folderRead')
        .then(function (res) {
            data = JSON.parse(res);
            data = data[0];
            $("#nameUpdate").val(data['name']);
            $("#descriptionUpdate").val(data['description']);
            if (data['id_company']) {
                $("#companyUpdate").val(data['id_company']);    
            } else {
                $("#companyUpdate").val("0");  
            }
        })
    .catch(function(error) {
        console.error(error);
    });
}

function folderDeleteModalData(id) {
    $("#folderDeleteId").val(id);
    $("#folderDeleteIdText").html(id);
}

function folderAddModalData(id) {
    $("#folderAddId").val(id);
}

function folderAddElement() {
    var type = $("#selectType").val();
    var elementId = $("#selectItem").val();
    var elementName = $("#selectItem option:selected").text();

    var list = '';
    var icon = '';
    switch (type) {
        case "archive":
            icon = '<i class="fa-solid fa-file"></i>';
            break;
        case "qr":
            icon = '<i class="fa-solid fa-qrcode"></i>';
            break;
        case "link":
            icon = '<i class="fa-solid fa-link"></i>';
            break;
        default:
            break;
    }

    folderAddListArray[elementId] = { name: icon + ' ' + elementName, type: type };

    Object.keys(folderAddListArray).forEach(function (key) {
        var element = folderAddListArray[key].name;
        list += '<li class="list-group-item d-flex justify-content-between align-items-center"><span>' + element + '</span><a class="text-danger cursor-pointer" onclick="folderDeleteElement('+key+')"><i class="fa-solid fa-circle-xmark"></i></a></li>';
    });

    $("#folderAddList").html(list);
}

function folderDeleteElement(id) {
    delete (folderAddListArray[id]);
    list = '';
    Object.keys(folderAddListArray).forEach(function (key) {
        var element = folderAddListArray[key].name;
        list += '<li class="list-group-item d-flex justify-content-between align-items-center"><span>' + element + '</span><a class="text-danger" onclick="folderDeleteElement('+key+')"><i class="fa-solid fa-circle-xmark"></i></a></li>';
    });
    $("#folderAddList").html(list);
}

function folderDeleteElementList(idFolder,idElement,type) {

    sendAjax({ folder: idFolder, element: idElement, type: type }, 'elementFolderDelete')
        .then(function (res) {
            
            if (res == 1) {
                messageModal('success', 'Se eliminó el elemento', 'folderViewModal',true);
                folderViewModalData(idFolder);
            } else {
                messageModal('danger', res, 'folderViewModal');
                console.log(res);
            }
            
        })
    .catch(function(error) {
        console.error(error);
    });
}
