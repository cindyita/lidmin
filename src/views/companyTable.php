<?php
Layout::header('empresas');
?>
    <div id="messages"></div>
    <div class="d-flex justify-content-between">
        <h4>Empresas</h4>
        <a data-bs-toggle="modal" data-bs-target="#companyCreateModal"><button class="btn btn-warning">Nueva empresa</button></a>
    </div>

    <div class="datatable">
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fila</th>
                    <th>id</th>
                    <th>Logo</th>
                    <th>Nombre</th>
                    <th>Sitio web</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Color</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Fila</th>
                    <th>id</th>
                    <th>Logo</th>
                    <th>Nombre</th>
                    <th>Sitio web</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Color</th>
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
                    <td><img src="./assets/img/company/<?php echo $value['logo']; ?>" width="auto" height="40px"></td>
                    <td><?php echo $value['name']; ?></td>
                    <td><a href="<?php echo $value['website']; ?>" target="_blank"><?php echo $value['website']; ?></a></td>
                    <td><?php echo $value['email']; ?></td>
                    <td><?php echo $value['phone']; ?></td>
                    <td><span style="background-color:<?php echo $value['primary_color']; ?>"><?php echo $value['primary_color']; ?></span></td>
                    <td><?php echo date('Y-m-d', strtotime($value['timestamp_create'])); ?></td>
                    <td>
                        <?php echo btnActions($value['id'],'company',["update", "delete"]); ?>
                    </td>
                </tr>

            <?php }
                } ?>

            </tbody>
        </table>
    </div>

    <!-----------MODALS------------>
    <!----Modal create---->
    <?php
    $modalContent = '<form id="createCompanyForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="name" placeholder="Ingresa el nombre de la empresa" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="color" class="form-label">Color primario:</label>
                            <input type="color" class="form-control form-control-color" value="#171717" id="color" name="color" required>
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo:</label>
                            <input type="file" class="form-control" id="logo" name="logo">
                        </div>
                        <div class="mb-3">
                            <label for="web" class="form-label">Sitio web: (Opcional)</label>
                            <input type="url" class="form-control" id="web" placeholder="Ingresa el sitio web de la empresa" name="web">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email: (Opcional)</label>
                            <input type="email" class="form-control" id="email" placeholder="Ingresa el email de la empresa" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="tel" class="form-label">Teléfono: (Opcional)</label>
                            <input type="tel" class="form-control" id="tel" placeholder="Ingresa el teléfono de la empresa" name="tel">
                        </div>
                        <button type="submit" data-bs-dismiss="modal" class="btn btn-dark">Registrar</button>
                    </form>';
    modal('companyCreateModal', $modalContent, 'Registrar empresa', '<i class="fa-solid fa-building"></i>');
    ?>

    <!----Modal edit---->
    <?php
    $modalContent = '<form id="companyUpdateForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="name_edit" placeholder="Ingresa el nombre de la empresa" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="color" class="form-label">Color primario:</label>
                            <input type="color" class="form-control form-control-color" value="#171717" id="color_edit" name="color" required>
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo:</label>
                            <input type="file" class="form-control" id="logo_edit" name="logo">
                            Actual: <span id="archive_name" class="text-primary"></span>
                            <input type="hidden" name="logo_actual" id="logo_actual">
                        </div>
                        <div class="mb-3">
                            <label for="web" class="form-label">Sitio web: (Opcional)</label>
                            <input type="url" class="form-control" id="web_edit" placeholder="Ingresa el sitio web de la empresa" name="web">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email: (Opcional)</label>
                            <input type="email" class="form-control" id="email_edit" placeholder="Ingresa el email de la empresa" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="tel" class="form-label">Teléfono: (Opcional)</label>
                            <input type="tel" class="form-control" id="tel_edit" placeholder="Ingresa el teléfono de la empresa" name="tel">
                        </div>
                        <input type="hidden" name="companyUpdateId" id="companyUpdateId">
                        <button type="submit" data-bs-dismiss="modal" class="btn btn-dark">Editar</button>
                    </form>';
    modal('companyUpdateModal', $modalContent, 'Editar empresa', '');
    ?>
    <!---Modal delete--->
    <?php
    $modalContent = '<form id="companyDeleteForm">
                        <input type="hidden" name="companyDeleteId" id="companyDeleteId">
                        <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Eliminar</button>
                    </form>';
    modal('companyDeleteModal', $modalContent, 'Se eliminará el registro de empresa: ', '','companyDeleteIdText');
    ?>


<?php
Layout::footer(['./assets/js/pages/companyTable.js']);