<?php
Layout::header('config',['./assets/css/pages/settings.css']);
?>
<?php

if($data){
    $app_name = $data[0]['app_name'];
    $logo = $data[0]['logo'] ? $data[0]['logo'] : 'Default';
    $favicon = $data[0]['favicon'] ? $data[0]['favicon'] : 'Default';
    $color_primary = $data[0]['color_primary'];
    $color_secondary = $data[0]['color_secondary'];
    $color_tertiary = $data[0]['color_tertiary'];
    $color_font = $data[0]['color_font'];
    $color_font2 = $data[0]['color_font2'];
}else{
    $app_name = "LiDMIN";
    $logo = "Default";
    $favicon = "Default";
    $color_primary = "#4F68FF";
    $color_secondary = "#8597FF";
    $color_tertiary = "#A1AEFF";
    $color_font = "#e4e7ff";
    $color_font2 = "#2E4AF3";
}

?>
    <div id="messages"></div>

    <div class="d-flex justify-content-between">
        <h4>Configuración</h4>
        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#setdefault">Set Default</button>
    </div>
    <hr>

    <div class="mb-5">
        <form method="POST" id="savesettings" enctype="multipart/form-data">
            <div class="settings-form">

                <div class="mb-3 mt-3">
                    <label for="app_name">Nombre de la app</label>
                    <input type="text" name="app_name" id="app_name" class="form-control" placeholder="Nombre que se muestra en la app" value="<?php echo $app_name; ?>" required>
                </div>

                <div class="mb-3 mt-3">
                    <label for="logo">Logo de la app</label>
                    <input type="file" name="logo" id="logo" class="form-control">
                    <?php echo '<span class="text-muted">Actual: '.$logo.'</span>'; ?>
                </div>

                <div class="mb-3 mt-3">
                    <label for="favicon">Favicon de la app</label>
                    <input type="file" name="favicon" id="favicon" class="form-control">
                    <?php echo '<span class="text-muted">Actual: '.$favicon.'</span>'; ?>
                </div>

                <div class="mb-3 mt-3 input-group gap-2">
                    <label for="color_primary">Color primario:</label>
                    <input type="color" name="color_primary" id="color_primary" class="p-1" value="<?php echo $color_primary; ?>">
                </div>

                <div class="mb-3 mt-3 input-group gap-2">
                    <label for="color_secondary">Color secundario:</label>
                    <input type="color" name="color_secondary" id="color_secondary" class="p-1" value="<?php echo $color_secondary; ?>">
                </div>

                <div class="mb-3 mt-3 input-group gap-2">
                    <label for="color_tertiary">Color terciario:</label>
                    <input type="color" name="color_tertiary" id="color_tertiary" class="p-1" value="<?php echo $color_tertiary; ?>">
                </div>

                <div class="mb-3 mt-3 input-group gap-2">
                    <label for="color_font">Color de fondo 1:</label>
                    <input type="color" name="color_font" id="color_font" class="p-1" value="<?php echo $color_font; ?>">
                </div>

                <div class="mb-3 mt-3 input-group gap-2">
                    <label for="color_font2">Color de fondo 2:</label>
                    <input type="color" name="color_font2" id="color_font2" class="p-1" value="<?php echo $color_font2; ?>">
                </div>

                <p class="text-muted my-2">[Actualiza para ver los cambios en la interfaz]</p>

                <button class="btn btn-dark" type="submit">Guardar</button>
            </div>
        </form>
    </div>

    <!----Modals----->
    <!-- Modal set default -->
    <div class="modal fade" id="setdefault">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">¿Quiéres volver a la configuración default?</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <h6>(Se eliminará el logo y favicon subido)</h6>
                <button class="btn btn-danger" onclick="setdefault()" data-bs-dismiss="modal">Aceptar</button>
            </div>

        </div>
    </div>
    </div>

<?php
Layout::footer(['./assets/js/pages/settings.js?upd=2']);