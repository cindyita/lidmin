<?php
Layout::header('dashboard',['./assets/css/pages/dashboard.css']);
?>
    <div id="messages"></div>
    <div class="stats-panel">

        <div class="info-card">
            <div>QR Generados</div>
            <span><i class="fa-solid fa-qrcode"></i> <span id="qrcount"><?php echo $stats['qr_count']; ?></span></span>
        </div>

        <div class="info-card">
            <div>Empresas</div>
            <span><i class="fa-solid fa-building"></i> <span id="companycount"><?php echo $stats['company_count']; ?></span></span>
        </div>

        <div class="info-card">
            <div>Archivos</div>
            <span><i class="fa-solid fa-file"></i> <span id="qrcount"><?php echo $stats['file_count']; ?></span></span>
        </div>

        <div class="info-card">
            <div>Carpetas</div>
            <span><i class="fa-solid fa-folder"></i> <span id="qrcount"><?php echo $stats['folder_count']; ?></span></span>
        </div>

    </div>
    
    <br><br>
    <h4>Registros más recientes</h4>
    <br>

    <div class="datatable">
        <table class="table table-striped" id="dashboardTable">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Tipo</th>
                    <th>Nombre</th>
                    <th>Empresa</th>
                    <th>Creado</th>
                </tr>
            </thead>
            <tbody>

            <?php if ($data) {
                foreach ($data as $key => $value) { ?>
            
                <tr>
                    <td><?php echo $value['id']; ?></td>
                    <td><?php echo $value['icon'].' '.$value['type']; ?></td>
                    <td><?php echo $value['name']; ?></td>
                    <td><?php echo $value['company'] ? $value['company'] : ''; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($value['timestamp_create'])); ?></td>
                </tr>
            <?php } }  ?>

            </tbody>
        </table>
    </div>

    <div class="mb-5">
        
    </div>

<?php
Layout::footer(['./assets/js/pages/dashboard.js']);