<?php
Layout::header('archivos',['./assets/css/pages/fileTable.css']);
?>
    <div id="messages"></div>

    <div class="mb-4 d-flex justify-content-between">
        <h4>Archivos</h4>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#newfile">Nuevo archivo</button>
    </div>
    
    <div class="mb-5">
        <input class="form-control" type="search" name="search-file" id="search-file" placeholder="Buscar archivo...">
    </div>

    <div class="archive-content" id="archives">

        <?php if ($files) {
            foreach ($files as $key => $value) { ?>
            
                <div class="archive-card">

                    <span class="left-icon">
                        <span class="d-flex flex-column gap-1">
                            <i class="fa-solid fa-lock text-warning <?php echo $value['password'] != null ? "" : "d-none"; ?>"></i>
                            <i class="fa-solid fa-qrcode text-dark <?php echo $value['id_qr'] != null ? "" : "d-none"; ?>"></i>
                        </span>
                    </span>
                    <span class="right-icon dropdown">
                        <span data-bs-toggle="dropdown" type="button" class="btn-dropdown">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </span>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" onclick="copyToClipboard('<?php echo BASEURL.'view.php?page=filepage&file=' . $value['id']; ?>')">
                                    <i class="fa-solid fa-link"></i>
                                    Copiar link
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-dark" href="#" data-bs-toggle="modal" data-bs-target="#editfile" onclick="modalEditFile(<?php echo $value['id'].', \''.$value['name'].'\','.$value['id_company']; ?>)">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Editar
                                </a>

                            </li>
                            <li>
                                <a class="dropdown-item text-warning" href="#" data-bs-toggle="modal" data-bs-target="#addpassword" onclick="modalAddPass(<?php echo $value['id'] . ', \'' . $value['password'] . '\''; ?>)">
                                    <i class="fa-solid fa-key"></i>
                                    Contraseña
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item <?php echo (!$value['id_qr'] ? "text-danger" : "text-muted"); ?>" href="#" onclick="deletefile(<?php echo $value['id'].','.$value['id_qr']; ?>)">
                                    <i class="fa-solid fa-trash"></i>
                                    Eliminar
                                </a>
                            </li>
                        </ul>
                    </span>

                    <a href="<?php echo 'view.php?page=filepage&file=' . $value['id']; ?>" class="archive-card-content" target="_blank">

                        <span class="file-icon">
                            <i class="fa-solid <?php 
                                switch($value['type']){
                                    case "pdf":
                                        echo "fa-file-pdf";
                                    break;
                                    case "docx":
                                        echo "fa-file-word";
                                    break;
                                    case "doc":
                                        echo "fa-file-word";
                                    break;
                                    case "jpg":
                                        echo "fa-file-image";
                                    break;
                                    case "jpeg":
                                        echo "fa-file-image";
                                    break;
                                    case "png":
                                        echo "fa-file-image";
                                    break;
                                    case "gif":
                                        echo "fa-file-image";
                                    break;
                                    case "xlsx":
                                        echo "fa-file-excel";
                                    break;
                                    case "pptx":
                                        echo "fa-file-powerpoint";
                                    break;
                                    case "txt":
                                        echo "fa-file-lines";
                                    break;
                                    case "mp3":
                                        echo "fa-file-audio";
                                    break;
                                    case "wav":
                                        echo "fa-file-audio";
                                    break;
                                    default:
                                        echo "fa-file";
                                    break;
                                }
                            ?>"></i>
                        </span>
                        <span class="title limit-lines"><?php echo $value['name']; ?></span>
                        <span class="subtitle"><?php echo $value['archive']; ?></span>
                        <div class="info">
                            <span class="archive-company"><?php echo ($value['company'] ? $value['company'] : '[Sin empresa]'); ?></span>
                            <span class="archive-date text-end"><?php echo date('d-m-Y', strtotime($value['timestamp_create'])); ?></span>
                        </div>

                    </a>

                </div>
            
        <?php }
        } ?>

    </div>

    <br><br><br><br>

    <!-----Modals------>
    <!-- Contraseña -->
    <div class="modal fade" id="addpassword">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title"><i class="fa-solid fa-key"></i> Nueva contraseña</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="addpasswordForm">
                    <div class="mb-3 mt-3">
                        <p class="d-flex justify-content-between">
                            <span>
                                <strong>Contraseña actual: </strong>
                                <span id="actualPass" class="text-primary"></span>
                            </span>
                            <a class="btn btn-danger btn-sm ms-2" onclick="removepassword()" data-bs-dismiss="modal">Remover</a>
                        </p>
                        <input type="password" class="form-control" id="pass" placeholder="Ingresa la nueva contraseña" name="pass">
                        <input type="hidden" name="id" id="id_pass">
                    </div>
                    <div>
                        <button type="submit" class="btn btn-dark" data-bs-dismiss="modal">Guardar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    </div>
    <!-------------->
    <!-- Nuevo archivo -->
    <div class="modal fade" id="newfile">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Subir archivo</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form id="newfileForm">
                        <div class="mb-3 mt-3">
                            <input type="text" class="form-control" placeholder="Ingresa el titulo del archivo" name="title" autocomplete="off" value="" required>
                        </div>
                        <div class="mb-3 mt-3">
                            <input type="file" class="form-control" id="fileup" name="file" required>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="company">Empresa asociada</label>
                            <select class="form-select" id="company" name="company" required>
                                <option value="0" selected>Ninguna</option>
                                <?php foreach ($company as $key => $value) { ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-check mb-3">
                            <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="havePass" id="havePass">
                                <i class="fa-solid fa-lock ms-2"></i>
                                Agregar contraseña
                            </label>
                        </div>
                        <div class="mb-3 mt-3">
                            <input type="password" placeholder="Ingresa la nueva contraseña" class="form-control" id="filepass" name="filepass" autocomplete="new-password" value="">
                        </div>
                        <button type="submit" class="btn btn-dark" data-bs-dismiss="modal">Subir</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-------------->
    <!----Modal edit---->
    <div class="modal fade" id="editfile">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Editar Archivo: <span id="filetitle"></span></h4>
                    <button type="button" class="close-modal" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form id="editFileForm">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titulo:</label>
                            <input type="text" class="form-control" id="title_edit" placeholder="Ingresa un titulo del archivo" name="title">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="company" class="form-label">Empresa asociada:</label>
                            <select class="form-select" id="company_edit" name="company">
                                <option value="NULL">Ninguna</option>
                                <?php foreach ($company as $key => $value) { ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                                <?php } ?>
                            </select>
                            <input type="hidden" name="id" id="id_edit">
                        </div>
                        <button type="submit" class="btn btn-dark" data-bs-dismiss="modal">Editar</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-------------->
        
<?php
Layout::footer(['./assets/js/pages/fileTable.js?upd=4']);