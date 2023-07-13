<?php
Layout::header('empresas');
?>
    <div id="messages"></div>
    <div class="d-flex justify-content-between">
        <h4>Empresas</h4>
        <a data-bs-toggle="modal" data-bs-target="#createCompany"><button class="btn btn-warning">Nueva empresa</button></a>
    </div>

    <div class="datatable">
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Logo</th>
                    <th>Nombre</th>
                    <th>Sitio web</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Color</th>
                    <th>creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>id</th>
                    <th>Logo</th>
                    <th>Nombre</th>
                    <th>Sitio web</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Color</th>
                    <th>creado</th>
                    <th>Acciones</th>
                </tr>
            </tfoot>
            <tbody>

            <?php if ($data) {
                foreach ($data as $key => $value) { ?>
            
                <tr>
                    <td><?php echo $value['id']; ?></td>
                    <td><img src="./assets/img/company/<?php echo $value['logo']; ?>" width="auto" height="40px"></td>
                    <td><?php echo $value['name']; ?></td>
                    <td><a href="<?php echo $value['website']; ?>" target="_blank"><?php echo $value['website']; ?></a></td>
                    <td><?php echo $value['email']; ?></td>
                    <td><?php echo $value['phone']; ?></td>
                    <td><span style="background-color:<?php echo $value['primary_color']; ?>"><?php echo $value['primary_color']; ?></span></td>
                    <td><?php echo date('Y-m-d', strtotime($value['timestamp_create'])); ?></td>
                    <td>
                        <a data-bs-toggle="modal" data-bs-target="#editCompany" onclick="editCompany(<?php echo $value['id']; ?>);">
                            <button class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></i></button>
                        </a>
                        <a data-bs-toggle="modal" data-bs-target="#deleteCompany" onclick="deleteCompany(<?php echo $value['id']; ?>);">
                            <button class="btn btn-danger"><i class="fa-solid fa-trash"></i></i></button>
                        </a>
                        
                    </td>
                </tr>
            <?php }
            } ?>

            </tbody>
        </table>
    </div>

    <!-----------MODALS------------>
    <!----Modal create---->
    <div class="modal fade" id="createCompany">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Registrar empresa</h4>
                    <button type="button" class="close-modal" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form id="createCompanyForm">
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
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!----Modal edit---->
    <div class="modal fade" id="editCompany">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Editar empresa</h4>
                    <button type="button" class="close-modal" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form id="editCompanyForm">
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
                        <input type="hidden" name="companyid" id="companyid">
                        <button type="submit" data-bs-dismiss="modal" class="btn btn-dark">Editar</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!---Modal delete--->
    <div class="modal fade" id="deleteCompany">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">¿Quieres eliminar el registro de empresa  <span id="deleteCompanyid">1</span>?</h4>
                    <button type="button" class="close-modal" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form id="deleteCompanyForm">
                        <input type="hidden" name="deleteid" id="deleteid">
                        <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Eliminar</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


<?php
Layout::footer(['./assets/js/pages/companyTable.js?upd=1']);