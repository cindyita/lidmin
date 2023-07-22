<?php
Layout::header('qr');
?>
    <div id="messages"></div>
    <div class="d-flex justify-content-between">
        <h4>QR generados</h4>
        <a data-bs-toggle="modal" data-bs-target="#qrCreateModal"><button class="btn btn-warning">Nuevo QR</button></a>
    </div>
    
    <div class="datatable">
        
        <table class="table table-striped" id="qrTable">
            <thead>
                <tr>
                    <th>Fila</th>
                    <th>id</th>
                    <th>Descripción</th>
                    <th>Destino</th>
                    <th>Tipo</th>
                    <th>Empresa</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Fila</th>
                    <th>id</th>
                    <th>Descripción</th>
                    <th>Destino</th>
                    <th>Tipo</th>
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
                    <td><a href="<?php echo $value['destination']; ?>" target="_blank"><?php echo $value['destination']; ?></a></td>
                    <td><?php echo $value['type']; ?></td>
                    <td><?php echo $value['company']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($value['timestamp_create'])); ?></td>
                    <td>
                        <?php echo btnActions($value['id'],'qr',["copy", "url", "update", "delete"]); ?>
                    </td>
                </tr>
            <?php } }  ?>

            </tbody>
        </table>
    </div>

    <!-----------MODALS------------>
    <!----Modal create---->
    <?php

    $companySelect = companySelect($company,"company");
    $modalContent = '<form method="POST" id="qrCreateForm" enctype="multipart/form-data">
                        <div class="qrGenerator">

                            <div class="mb-3 mt-3">
                                <label for="name">Titulo</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Ingresa un titulo o descripción" required>
                            </div>

                            <div class="mb-3 mt-3">
                                <label for="company">Empresa asociada</label>
                                '.$companySelect.'
                            </div>

                            <div class="mb-3 mt-3">
                                <label for="generate">Generar a partir de</label>
                                <select class="form-select" id="generate" name="generate" required>
                                    <option value="null" hidden>Selecciona</option>
                                    <option value="url">Url</option>
                                    <option value="file">Archivo</option>
                                    <option value="web">Página web de la empresa</option>
                                </select>
                            </div>
                            
                            <div class="mb-3 mt-3" id="url-field">
                                <label for="url">Url</label>
                                <input type="url" name="url" id="url" class="form-control" placeholder="Ingresa una url">
                            </div>

                            <div class="mb-3 mt-3" id="file-field">
                                <label for="file">Archivo</label>
                                <input type="file" name="file" id="file" class="form-control">
                            </div>

                            <button class="btn btn-dark" type="submit">Generar</button>
                        </div>
                    </form>';

    modal('qrCreateModal', $modalContent, 'Generar nuevo QR', '<i class="fa-solid fa-qrcode"></i>');
    ?>
    
    <!----Modal update---->
    <?php 
    $companySelect = companySelect($company,"companyUpdate");
    $modalContent = '<form id="qrUpdateForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Descripción:</label>
                            <input type="text" class="form-control" id="nameUpdate" placeholder="Ingresa una descripción del QR" name="name">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="company" class="form-label">Empresa asociada:</label>
                            '.$companySelect.'
                            <input type="hidden" name="qrUpdateId" id="qrUpdateId">
                        </div>
                        <button type="submit" class="btn btn-dark">Editar</button>
                    </form>';

    modal('qrUpdateModal', $modalContent, 'Editar QR: ', '','qrUpdateIdText');
    ?>

    <!---Modal delete--->
    <?php 

    $modalContent = '<p>[Se eliminará el archivo asociado]</p>
                    <form id="qrDeleteForm">
                        <input type="hidden" name="qrDeleteId" id="qrDeleteId">
                        <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Eliminar</button>
                    </form>';

    modal('qrDeleteModal', $modalContent, 'Se eliminará el QR: ', '','qrDeleteIdText');
    ?>
    <!------------------------>

<?php
Layout::footer(['./assets/js/pages/qrTable.js']);