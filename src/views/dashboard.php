<?php
Layout::header('dashboard',['./assets/css/pages/dashboard.css']);
?>
    <div id="messages"></div>
    <div class="stats-panel">

        <div class="info-card">
            <div>QR Generados</div>
            <span><i class="fa-solid fa-qrcode"></i> <?php echo $stats['qr_count']; ?></span>
        </div>

        <div class="info-card">
            <div>Empresas</div>
            <span><i class="fa-solid fa-building"></i> <?php echo $stats['company_count']; ?></span>
        </div>

        <div class="info-card">
            <div>Archivos</div>
            <span><i class="fa-solid fa-file"></i> <?php echo $stats['file_count']; ?></span>
        </div>

    </div>
    
    <br><br>
    <h4>Generar</h4>
    <br>

    <div class="mb-5">
        <form method="POST" id="generateqr" enctype="multipart/form-data">
            <div class="qrGenerator">
                <h5>Generar nuevo QR</h5>

                <div class="mb-3 mt-3">
                    <label for="title">Titulo</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Ingresa un titulo o descripción" required>
                </div>

                <div class="mb-3 mt-3">
                    <label for="company">Empresa asociada</label>
                    <select class="form-select" id="company" name="company" required>
                        <option value="null" hidden>Selecciona una o ninguna empresa</option>
                        <option value="0" selected>Ninguna</option>
                        <?php foreach ($company as $key => $value) { ?>
                            <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                        <?php } ?>
                    </select>
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
        </form>
    </div>

<?php
Layout::footer(['./assets/js/pages/dashboard.js?upd=1']);