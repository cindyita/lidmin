<?php
    $lockFile = 0;
    if($filedata['password'] == null){
        $lockFile = 1;
    }
    if(isset($_POST['passFile'])){
        
        if($_POST['pass'] == $filedata['password']){
            $lockFile = 1;
        }else{
            echo '<script>alert("Contraseña incorrecta");</script>';
        }
    }

    if ($company != 0) {
        $colorHexadecimal = $company['primary_color'];
        $colorfont = sscanf($colorHexadecimal, "#%02x%02x%02x");
        $colorfont = 'rgba(' . $colorfont[0] . ',' . $colorfont[1] . ',' . $colorfont[2] . ',0.2)';
    }


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($setting['app_name']) && $setting['app_name'] ? $setting['app_name'] : 'LiDMIN'; ?> | ARCHIVO: <?php echo $filedata['name']; ?></title>
    <link rel="shortcut icon" href="<?php echo isset($setting['favicon']) && $setting['favicon'] ? './assets/img/userapp/favicon/'.$setting['favicon'] : './assets/img/system/favicon.png'; ?>" type="image/PNG">
    <link rel="stylesheet" href="./assets/css/app.css?upd=<?php echo VERSION; ?>">
    <link rel="stylesheet" href="./assets/css/pages/pages.css?upd=<?php echo VERSION; ?>">
    <script src="https://kit.fontawesome.com/e0df5df9e9.js" crossorigin="anonymous"></script>
    <style>
        header {
            background-color: <?php
            if ($company != 0) {
                echo $colorfont;
            } elseif($setting != 0 && $company == 0) {
                echo $setting['color_font'];
            }else{
                echo '#E4E7FF';
            } ?>;
        }
        header img {
            max-height: 90%;
            width: auto;
        }
        .content .content #share-buttons a {
            color: <?php
            if ($company != 0) {
                echo $company['primary_color'];
            } elseif($setting != 0 && $company == 0) {
                echo $setting['color_primary'];
            }else{
                echo '#4F68FF';
            } ?>;
        }
        .btn-pdf {
            background-color: <?php
            if ($company != 0) {
                echo $company['primary_color'];
            } elseif($setting != 0 && $company == 0) {
                echo $setting['color_primary'];
            }else{
                echo '#4F68FF';
            } ?> !important;
        }
        .btn-primary {
            border:0;
            color: white;
            background-color: <?php
            if ($company != 0) {
                echo $company['primary_color'];
            } elseif($setting != 0 && $company == 0) {
                echo $setting['color_primary'];
            }else{
                echo '#4F68FF';
            } ?>;
            padding: 9px 15px;
            cursor:pointer;
        }
        .btn-primary:hover{
            opacity: 0.8;
        }
        .preview {
            width: 90%;
            text-align: center;
        }
        .preview:hover {
            opacity: 0.8;
        }
        .preview img {
            max-width: 80%;
        }
        @media (max-width: 1024px){
            .preview img {
                max-width: 100%;
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
                <h3>Ver archivo <?php echo $filedata['type']; ?></h3>
            <?php } ?>
        </header>
        <div class="content">
            <?php if ($lockFile) { ?>

                <h3><?php echo $filedata['name']; ?></h3>
                <p><?php echo $filedata['archive']; ?></p>
                <a href="<?php echo BASEURL . 'assets/doc/'.$filedata['id_user'].'/'. $filedata['archive']; ?>" download>
                    <button class="btn-pdf">Descargar <i class="fa-solid fa-download"></i></button>
                </a>
                <?php if ($filedata['type'] == 'jpg' || $filedata['type'] == 'png' || $filedata['type'] == 'jpeg') { ?>
                    <a href="<?php echo BASEURL.'assets/doc/'.$filedata['id_user'].'/'.$filedata['archive']; ?>" class="preview" target="_blank">
                        <img src="./assets/doc/<?php echo $filedata['id_user'].'/'.$filedata['archive']; ?>" alt="preview img">
                    </a>
                <?php } ?>
                <?php if ($filedata['type'] == 'pdf') { ?>
                    <form method="post" action="<?php echo BASEURL . "view.php?page=verpdf&id=" . $filedata['id']; ?>">
                        <input type="hidden" name="pass" value="<?php echo $filedata['password']; ?>">
                        <button class="btn-pdf" type="submit">Ver PDF <i class="fa-solid fa-eye"></i></button>
                    </form>
                <?php } ?>
                
                <div id="share-buttons">
                    Compartir: 
                    <a class="share-button" id="facebook-share"><i class="fa-brands fa-facebook"></i></a>
                    <a class="share-button" id="twitter-share"><i class="fa-brands fa-twitter"></i></a>
                    <a class="share-button" id="linkedin-share"><i class="fa-brands fa-linkedin"></i></a>
                </div>

            <?php } else { ?>

                <h3>El archivo tiene contraseña</h3>
                <p><?php echo $filedata['archive']; ?></p>
                <form method="post">
                    <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Ingresa la contraseña" name="pass" autocomplete="off" required>
                    <button class="btn-primary" type="submit" name="passFile">Ingresar</button>
                    </div>
                </form>

            <?php } ?>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var facebookShareButton = document.getElementById('facebook-share');
            var twitterShareButton = document.getElementById('twitter-share');
            var linkedinShareButton = document.getElementById('linkedin-share');

            // Compartir en Facebook
            facebookShareButton.addEventListener('click', function(e) {
                e.preventDefault();
                var url = encodeURIComponent(window.location.href);
                window.open('https://www.facebook.com/sharer/sharer.php?u=' + url, 'Compartir en Facebook', 'width=550,height=550');
            });

            // Compartir en Twitter
            twitterShareButton.addEventListener('click', function(e) {
                e.preventDefault();
                var url = encodeURIComponent(window.location.href);
                window.open('https://twitter.com/intent/tweet?url=' + url, 'Compartir en Twitter', 'width=550,height=550');
            });

            // Compartir en LinkedIn
            linkedinShareButton.addEventListener('click', function(e) {
                e.preventDefault();
                var url = encodeURIComponent(window.location.href);
                window.open('https://www.linkedin.com/sharing/share-offsite/?url=' + url, 'Compartir en LinkedIn', 'width=550,height=550');
            });
        });
    </script>
</body>
</html>