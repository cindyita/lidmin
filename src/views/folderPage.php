<?php
    $lockFolder = 0;
    if($folderdata['password'] == null){
        $lockFolder = 1;
    }
    if(isset($_POST['passFolder'])){
        
        if($_POST['pass'] == $folderdata['password']){
            $lockFolder = 1;
        }else{
            echo '<script>alert("Contraseña incorrecta");</script>';
        }
    }

    if ($company != 0) {
        $colorHexadecimal = $company['primary_color'];
        $colorf = sscanf($colorHexadecimal, "#%02x%02x%02x");
        $colorfont = 'rgba(' . $colorf[0] . ',' . $colorf[1] . ',' . $colorf[2] . ',0.2)';
        $colorfontMed = 'rgba(' . $colorf[0] . ',' . $colorf[1] . ',' . $colorf[2] . ',0.7)';
    }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($setting['app_name']) && $setting['app_name'] ? $setting['app_name'] : 'LiDMIN'; ?> | CARPETA</title>
    <link rel="shortcut icon" href="<?php echo isset($setting['favicon']) && $setting['favicon'] ? './assets/img/userapp/favicon/'.$setting['favicon'] : './assets/img/system/favicon.png'; ?>" type="image/PNG">
    <link rel="stylesheet" href="./assets/css/app.css?upd=<?php echo VERSION; ?>">
    <link rel="stylesheet" href="./assets/css/pages/pages.css?upd=<?php echo VERSION; ?>">


    <script src="https://kit.fontawesome.com/e0df5df9e9.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    
    <link href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/r-2.4.1/datatables.min.css" rel="stylesheet"/>
    <script src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/r-2.4.1/datatables.min.js"></script>
    <style>
            
        <?php if ($company != 0 || $setting != 0) { ?>
            :root {
                 <?php if ($company != 0) {
                        echo '--primary:'.$company['primary_color'].';';
                        echo '--lightfont:'.$colorfont.';';
                        echo '--font:'.$company['primary_color'].';';
                        echo '--tertiary:'.$company['primary_color'].';';
                        echo '--secondary:'.$colorfontMed.';';
                    } elseif($setting != 0 && $company == 0) {
                        echo '--primary:'.$setting['color_primary'].';';
                        echo '--lightfont:'.$setting['color_font'].';';
                        echo '--font:'.$setting['color_font2'].';';
                        echo '--tertiary:'.$setting['color_tertiary'].';';
                        echo '--secondary:'.$setting['color_secondary'].';';
                    } ?>
            }
        <?php } ?>
        .content header {
            background-color: <?php
            if ($company != 0) {
                echo $colorfont;
            } elseif($setting != 0 && $company == 0) {
                echo $setting['color_font'];
            }else{
                echo 'var(--lightfont)';
            } ?>;
            height:150px;
        }
        .content header img {
            max-height: 90%;
            width: auto;
        }
        .content .content #share-buttons a {
            color: var(--primary);
        }
        .btn-primary:hover{
            opacity: 0.8;
            background-color: var(--primary);
        }
        .preview {
            width: 300px;
            text-align: center;
        }
        .preview:hover {
            opacity: 0.8;
        }
        .preview img {
            max-width: 100% !important;
        }
        .datatable {
            width:90%;
        }
        @media (max-width: 1024px){
            .datatable {
                width:100%;
            }
        }

    </style>
</head>
<body>

    <div class="content">
        <header>
            <?php if ($company != 0) { ?>
                <img src="./assets/img/company/<?php echo $company['logo']; ?>" />
            <?php }elseif($setting != 0 && $company == 0 && $setting['logo']){ ?>
                <img src="./assets/img/userapp/logo/<?php echo $setting['logo']; ?>" height="85%" />
            <?php }else{ ?>
                <h3><i class="fa-solid fa-folder-open"></i> <?php echo $folderdata['name']; ?></h3>
            <?php } ?>
        </header>
        <div class="content">
            <?php if ($lockFolder) { ?>

                <?php if ($company != 0 || $setting['logo']) { ?>
                    <h3><i class="fa-solid fa-folder-open"></i> <?php echo $folderdata['name']; ?></h3>
                <?php } ?>
                
                <p><?php echo $folderdata['description']; ?></p>

                <div id="messages"></div>

                <div class="datatable">
                    <table class="table table-striped" id="folderTable">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Nombre</th>
                                <th>Tipo doc</th>
                                <th>Destino</th>
                                <th>Acciones</th>
                            </tr>
                        <tbody>
                    <?php foreach ($folderitems as $key => $value) { ?>
                            
                            <?php
                                switch ($value['typeItem']) {
                                    case "archive":
                                        $icon = '<i class="fa-solid fa-file"></i>';
                                        $nameType = 'Archivo';
                                        $ver = 'filepage';
                                        $verid = 'file';
                                        break;
                                    case "qr":
                                        $icon = '<i class="fa-solid fa-qrcode"></i>';
                                        $nameType = 'QR';
                                        $ver = 'verqr';
                                        $verid = 'qr';
                                        break;
                                    case "link":
                                        $icon = '<i class="fa-solid fa-link"></i>';
                                        $nameType = 'Enlace';
                                        $ver = 'verenlace';
                                        $verid = 'enlace';
                                        break;
                                    default:
                                        break;
                                }
                            ?>

                            <tr>
                                <td><?php echo $icon.' '.$nameType; ?></td>
                                <td><a href="<?php 
                                            if($value['typeItem'] == 'link'){
                                                echo $value['destination'];
                                            }else{
                                                echo BASEURL . 'view.php?page=' . $ver . '&' . $verid . '=' . $value['id']; 
                                            }
                                        ?>" target="_blank"><?php if ($value['password']){ echo '<i class="fa-solid fa-lock text-warning" title="Este elemento tiene contraseña"></i> '; } echo $value['name']; ?></td>
                                <td><?php echo $value['type']; ?></a></td>
                                <td><a href="<?php echo $value['destination']; ?>" target="_blank"><?php echo $value['destination']; ?></a></td>
                                <td>
                                    <span class="d-flex gap-1 align-items-center">
                                        <a title="Copiar url" onclick="copyToClipboard('<?php if($value['typeItem'] == 'link'){echo $value['destination'];}else{echo BASEURL . 'view.php?page=' . $ver . '&' . $verid . '=' . $value['id']; }  ?>')">
                                            <button class="btn btn-primary">
                                                <i class="fa-solid fa-copy"></i>
                                            </button>
                                        </a>
                                        <a href="<?php 
                                            if($value['typeItem'] == 'link'){
                                                echo $value['destination'];
                                            }else{
                                                echo BASEURL . 'view.php?page=' . $ver . '&' . $verid . '=' . $value['id']; 
                                            }
                                            
                                        ?>" title="Abrir" target="_blank">
                                            <button class="btn btn-primary">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </button>
                                        </a>
                                        <?php if ($value['type'] == 'pdf') { ?>
                                                <form method="post" action="<?php echo BASEURL . "view.php?page=verpdf&id=" . $value['id']; ?>" target="_blank">
                                                    <input type="hidden" name="pass" value="<?php echo $filedata['password']; ?>">
                                                    <button class="btn btn-primary" type="submit" title="Ver PDF"><i class="fa-solid fa-eye"></i></button>
                                                </form>
                                        <?php } ?>
                                        <?php if ($value['typeItem'] == 'archive') { ?>
                                        <a title="Descargar" href="<?php echo BASEURL . 'assets/doc/'.$folderdata['id_user'].'/'. $value['archive']; ?>" download onerror="handleDownloadError()">
                                            <button class="btn btn-primary">
                                                <i class="fa-solid fa-download"></i>
                                            </button>
                                        </a>
                                        <?php } ?>
                                    </span>
                                </td>
                            </tr>
                    <?php } ?>
                        </tbody>
                    </table>
                </div>
                

            <?php } else { ?>

                <h4>La carpeta tiene contraseña</h4>
                <p>Carpeta: <?php echo $folderdata['name']; ?></p>
                <form method="post">
                    <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Ingresa la contraseña" name="pass" autocomplete="off" required>
                    <button class="btn btn-primary" type="submit" name="passFolder">Ingresar</button>
                    </div>
                </form>

            <?php } ?>

        </div>
    </div>

    <script src="./assets/js/datatable.js?upd='.VERSION.'"></script>
    <script>
        function copyToClipboard(text) {
        navigator.clipboard.writeText(text)
            .then(function() {
                message('success', 'Enlace copiado al portapapeles');
            })
            .catch(function() {
                message('error', 'No se pudo copiar el enlace');
            });
        }
        function message(type, text) {
            var alertClass = (type === 'success') ? 'alert-success' : 'alert-danger';
            var typeText = (type === 'success') ? 'Éxito' : 'Error';

            var html = '<div class="alert ' + alertClass + ' alert-dismissible" id="message">';
            html += '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            html += '<strong>' + typeText + '</strong> ' + text;
            html += '</div>';

            var $message = $(html);
            $message.hide().appendTo('#messages').fadeIn();

            setTimeout(function() {
                $message.fadeOut(function() {
                    $(this).remove();
                });
            }, 4000);
        }
        function handleDownloadError() {
            message('error', 'El archivo no existe');
        }
    </script>
</body>
</html>