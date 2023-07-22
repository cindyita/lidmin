
function linkUpdateModalData(id) {
    $("#linkUpdateIdText").html(id);
    $("#linkUpdateId").val(id);
    sendAjax(id, 'linkRead')
        .then(function (res) {
            data = JSON.parse(res);
            data = data[0];
            $("#urlUpdate").val(data['url']);
            $("#nameUpdate").val(data['name']);
            if (data['id_company']) {
                $("#companyUpdate").val(data['id_company']);    
            }
        })
    .catch(function(error) {
        console.error(error);
    });
}

function linkDeleteModalData(id) {
    $("#linkDeleteIdText").html(id);
    $("#linkDeleteId").val(id);
}

$(document).ready(function () {

    $('#linkUpdateForm').submit(function (event) {
        
        event.preventDefault();
        
        var nameValue = $('#name').val();
        if (nameValue == 'null') {
            alert('Debes ingresar una descripción');
            return;
        }

        var formData = new FormData(this);

        sendForm(formData, 'link', 'update');
        
    });

    $('#linkDeleteForm').submit(function (event) {

        event.preventDefault();
        var formData = new FormData(this);

        sendForm(formData,'link','delete');
    });

  //Limit size file
  fileInput = $('#file');
  fileInput.on('change', function() {
      var file = this.files[0];
      var limit = $("#limit_size_files").val() ? $("#limit_size_files").val() : 5;
      var maxSize = limit * 1024 * 1024;

      if (file.size > maxSize) {
          alert('El archivo excede el tamaño máximo permitido ('+limit+'MB)');
          $(this).val('');
      }
  });

  //Create link
    $('#linkCreateForm').submit(function (event) {
      
        event.preventDefault();

        var companyValue = $('#company').val();
        var urlValue = $('#url').val();

        if (companyValue == 'null') {
            messageModal('danger', 'Debes seleccionar una o ninguna empresa', 'linkCreateModal');
            return;
        }

        if (urlValue == '') {
            messageModal('danger', 'Debes ingresar una url', 'linkCreateModal');
            return;
        }

        modalLoaderIn('linkCreateModal');

        var formData = new FormData(this);
        
        sendForm(formData,'link','create');
        
    });

});