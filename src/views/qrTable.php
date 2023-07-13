<?php
Layout::header('qr');
?>
    <div id="messages"></div>
    <div class="d-flex justify-content-between">
        <h4>QR generados</h4>
        <a href="dashboard.php"><button class="btn btn-warning">Nuevo QR</button></a>
    </div>
    
    <div class="datatable">
        
        <table class="table table-striped" id="qrTable">
            <thead>
                <tr>
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
                foreach ($data as $key => $value) { ?>
            
                <tr>
                    <td><?php echo $value['id']; ?></td>
                    <td><?php echo $value['name']; ?></td>
                    <td><a href="<?php echo $value['destination']; ?>" target="_blank"><?php echo $value['destination']; ?></a></td>
                    <td><?php echo $value['type']; ?></td>
                    <td><?php echo $value['company']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($value['timestamp_create'])); ?></td>
                    <td>
                        <span class="d-flex gap-1 align-items-center">
                            <a href="<?php echo BASEURL . "view.php?page=verqr&qr=" . $value['id']; ?>" target="_blank">
                                <button class="btn btn-dark"><i class="fa-solid fa-arrow-up-right-from-square"></i></button>
                            </a>
                            <a data-bs-toggle="modal" data-bs-target="#editQr" onclick="editQr(<?php echo $value['id']; ?>,'<?php echo $value['name']; ?>',<?php echo $value['company_id']; ?>);">
                                <button class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></i></button>
                            </a>
                            <a data-bs-toggle="modal" data-bs-target="#deleteQr" onclick="deleteQr(<?php echo $value['id']; ?>);">
                                <button class="btn btn-danger"><i class="fa-solid fa-trash"></i></i></button>
                            </a>
                        </span>
                    </td>
                </tr>
            <?php } }  ?>

            </tbody>
        </table>
    </div>

    <!-----------MODALS------------>
    <!----Modal edit---->
    <div class="modal fade" id="editQr">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Editar QR <span id="editqrid">1</span></h4>
                    <button type="button" class="close-modal" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form id="editQrForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Descripción:</label>
                            <input type="text" class="form-control" id="name" placeholder="Ingresa una descripción del QR" name="name">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="company" class="form-label">Empresa asociada:</label>
                            <select class="form-select" id="company" name="company">
                                <option value="NULL">Ninguna</option>
                                <?php foreach ($company as $key => $value) { ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" name="editid" id="editid">
                        </div>
                        <button type="submit" class="btn btn-dark" data-bs-dismiss="modal">Editar</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!---Modal delete--->
    <div class="modal fade" id="deleteQr">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">¿Quieres eliminar el QR <span id="deleteqrid">1</span>?</h4>
                    <button type="button" class="close-modal" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <p>[Se eliminará el archivo asociado]</p>
                    <form id="deleteQrForm">
                        <input type="hidden" name="deleteid" id="deleteid">
                        <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Eliminar</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


<?php
Layout::footer(['./assets/js/pages/qrTable.js']);