<?php
Layout::header('enlaces');
?>
    <div id="messages"></div>
    <div class="d-flex justify-content-between">
        <h4>Enlaces</h4>
        <a data-bs-toggle="modal" data-bs-target="#linkCreateModal"><button class="btn btn-warning">Nuevo Enlace</button></a>
    </div>
    
    <div class="datatable">
        
        <table class="table table-striped" id="linkTable">
            <thead>
                <tr>
                    <th>Fila</th>
                    <th>id</th>
                    <th>Titulo</th>
                    <th>URL</th>
                    <th>Empresa</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Fila</th>
                    <th>id</th>
                    <th>Titulo</th>
                    <th>URL</th>
                    <th>Empresa</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </tfoot>
            <tbody>

            <?php if ($data) {
                $count = 0;
                foreach ($data as $key => $value) {
                    $count++; ?>
            
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $value['id']; ?></td>
                    <td><?php echo $value['name']; ?></td>
                    <td><a href="<?php echo $value['url']; ?>" target="_blank"><?php echo $value['url']; ?></a></td>
                    <td><?php echo $value['company']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($value['timestamp_create'])); ?></td>
                    <td>
                        <?php echo btnActions($value['id'],'link',["update", "delete"]); ?>
                    </td>
                </tr>
            <?php } }  ?>

            </tbody>
        </table>
    </div>

    <!-----------MODALS------------>
    <?php
    /*-----Modal create-----*/
    $companySelect = companySelect($company,"company");
    $modalContent = '<form method="POST" id="linkCreateForm" enctype="multipart/form-data">
                        <div class="linkCreateClass">

                            <div class="mb-3 mt-3">
                                <label for="name">Titulo</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Ingresa un titulo o descripción" required>
                            </div>

                            <div class="mb-3 mt-3">
                                <label for="url">URL</label>
                                <input type="url" name="url" id="url" class="form-control" placeholder="Ingresa la URL del enlace" required>
                            </div>

                            <div class="mb-3 mt-3">
                                <label for="company">Empresa asociada</label>
                                '.$companySelect.'
                            </div>

                            <button class="btn btn-dark" type="submit" data-bs-dismiss="modal">Generar</button>
                        </div>
                    </form>';
    modal('linkCreateModal', $modalContent, 'Generar nuevo enlace', '<i class="fa-solid fa-link"></i>');
    /*-----Modal update-----*/
    $companySelect = companySelect($company,"companyUpdate");
    $modalContent = '<form id="linkUpdateForm">
                        <div class="mb-3 mt-3">
                            <label for="name">Titulo</label>
                            <input type="text" name="name" id="nameUpdate" class="form-control" placeholder="Ingresa un titulo o descripción" required>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="url">URL</label>
                            <input type="url" name="url" id="urlUpdate" class="form-control" placeholder="Ingresa la URL del enlace" required>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="company" class="form-label">Empresa asociada</label>
                            '.$companySelect.'
                            <input type="hidden" name="linkUpdateId" id="linkUpdateId">
                        </div>
                        <button type="submit" class="btn btn-dark" data-bs-dismiss="modal">Editar</button>
                    </form>';
    modal('linkUpdateModal', $modalContent, 'Editar enlace', '','linkUpdateIdText');

    /*-----Modal delete-----*/
    $modalContent = '<form id="linkDeleteForm">
                        <input type="hidden" name="linkDeleteId" id="linkDeleteId">
                        <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Eliminar</button>
                    </form>';
    modal('linkDeleteModal', $modalContent, 'Se eliminará el enlace: ', '','linkDeleteIdText');
    ?>


<?php
Layout::footer(['./assets/js/pages/linkTable.js']);