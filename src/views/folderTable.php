<?php
Layout::header('carpetas',["./assets/css/pages/folderTable.css"]);
?>
    <div id="messages"></div>
    <div class="d-flex justify-content-between">
        <h4>Carpetas</h4>
        <a data-bs-toggle="modal" data-bs-target="#folderCreateModal"><button class="btn btn-warning">Nueva carpeta</button></a>
    </div>
    
    <div class="datatable">
        <table class="table table-striped" id="folderTable">
            <thead>
                <tr>
                    <th>Fila</th>
                    <th>id</th>
                    <th>Nombre</th>
                    <th>Empresa</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Fila</th>
                    <th>id</th>
                    <th>Nombre</th>
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
                    <td><i class="fa-solid fa-folder pe-2"></i><?php echo $count; ?></td>
                    <td><?php echo $value['id']; ?></td>
                    <td><?php if ($value['password']){ echo '<i class="fa-solid fa-lock text-warning" title="Este elemento tiene contraseña"></i> '; }  echo $value['name']; ?></td>
                    <td><?php echo $value['company']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($value['timestamp_create'])); ?></td>
                    <td>
                        <?php echo btnActions($value['id'],'folder',["add","view", "copy","url","password", "update", "delete"]); ?>
                    </td>
                </tr>
            <?php } }  ?>

            </tbody>
        </table>
    </div>

    <!-----------MODALS------------>
    <?php
    /*----Modal Add archives---*/
    $modalContent = '<form method="POST" id="folderAddForm" enctype="multipart/form-data">
                        <div class="folderAdd">

                            <div class="input-group mb-3">
                                <select class="form-select" id="selectType">
                                    <option hidden>Selecciona un tipo</option>
                                    <option value="qr">Qr</option>
                                    <option value="link">Enlace</option>
                                    <option value="archive">Archivo</option>
                                </select>
                            </div>
                            
                            <div class="input-group mb-4">
                                <select class="form-select" id="selectItem">
                                </select>
                                <a class="btn btn-primary" onclick="folderAddElement()">
                                    <i class="fa-solid fa-plus mt-2"></i>
                                </a>
                            </div>

                            <h6>Se agregarán los elementos:</h6>

                            <ul class="list-group pb-4" id="folderAddList">
                            </ul>

                            <input type="hidden" name="folderAddId" id="folderAddId">
                            <button class="btn btn-dark" type="submit">Guardar</button>
                        </div>
                    </form>';

    modal('folderAddModal', $modalContent, 'Agregar archivos a la carpeta ', '<i class="fa-solid fa-file-circle-plus"></i>');

    /*----Modal view content---*/
    $modalContent = '<span id="folderViewModalFiles">Cargando contenido..</span>';
    modal('folderViewModal', $modalContent, '', '<i class="fa-solid fa-folder-open"></i>','folderViewIdText','lg');

    /*----Modal create---*/
    $companySelect = companySelect($company,"company");
    $modalContent = '<form method="POST" id="folderCreateForm" enctype="multipart/form-data">
                        <div class="folderGenerator">

                            <div class="mb-3 mt-3">
                                <label for="name">Nombre</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Nombre de la carpeta" required>
                            </div>

                            <div class="mb-3 mt-3">
                                <label for="company">Empresa asociada</label>
                                '.$companySelect.'
                            </div>

                            <div class="mb-3 mt-3">
                                <label for="description">Descripción</label>
                                <textarea class="form-control" name="description" id="description" cols="30" rows="5" placeholder="Descripción de la carpeta"></textarea>
                            </div>

                            <button class="btn btn-dark" type="submit">Crear</button>
                        </div>
                    </form>';

    modal('folderCreateModal', $modalContent, 'Crear nuevo folder', '<i class="fa-solid fa-foldercode"></i>');
    ?>
    
    
    <!----Modal update---->
    <?php 
    $companySelect = companySelect($company,"companyUpdate");
    $modalContent = '<form id="folderUpdateForm">
                        <div class="mb-3 mt-3">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" id="nameUpdate" class="form-control" placeholder="Nombre de la carpeta" required>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="company">Empresa asociada</label>
                            '.$companySelect.'
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="description">Descripción</label>
                            <textarea class="form-control" name="description" id="descriptionUpdate" cols="30" rows="5" placeholder="Descripción de la carpeta"></textarea>
                        </div>
                        <input type="hidden" name="folderUpdateId" id="folderUpdateId">
                        <button type="submit" class="btn btn-dark">Editar</button>
                    </form>';

    modal('folderUpdateModal', $modalContent, 'Editar carpeta: ', '','folderUpdateIdText');
    ?>

    <!---Modal delete--->
    <?php 

    $modalContent = '<p>[No se eliminarán los archivos pero si las asociaciones]</p>
                    <form id="folderDeleteForm">
                        <input type="hidden" name="folderDeleteId" id="folderDeleteId">
                        <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Eliminar</button>
                    </form>';

    modal('folderDeleteModal', $modalContent, 'Se eliminará la carpeta: ', '','folderDeleteIdText');

    /*----Modal view content---*/
    $modalContent = '<form id="createPasswordForm">
                        <div class="mb-3 mt-3">
                            <p class="d-flex justify-content-between">
                                <span>
                                    <strong>Contraseña actual: </strong>
                                    <span id="actualPass" class="text-primary"></span>
                                </span>
                                <a class="btn btn-danger btn-sm ms-2" onclick="passwordDelete()" data-bs-dismiss="modal">Remover</a>
                            </p>
                            <input type="password" class="form-control" id="pass" placeholder="Ingresa la nueva contraseña" name="pass">
                            <input type="hidden" name="id" id="id_pass">
                        </div>
                        <div>
                            <button type="submit" class="btn btn-dark" data-bs-dismiss="modal">Guardar</button>
                        </div>
                    </form>';
    modal('folderPasswordModal', $modalContent, 'Contraseña de la carpeta: ', '<i class="fa-solid fa-key"></i>','folderPasswordIdText');

    /*-------------------------*/


Layout::footer(['./assets/js/pages/folderTable.js']);

?>

<?php 
