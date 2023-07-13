<?php 
    $colorHexadecimal = $company['primary_color'];
    $colorfont = sscanf($colorHexadecimal, "#%02x%02x%02x");
    $colorfont = 'rgba(' . $colorfont[0] . ',' . $colorfont[1] . ',' . $colorfont[2] . ',0.2)';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($setting['app_name']) ? $setting['app_name'] : 'LiDMIN'; ?> | QR</title>
    <link rel="shortcut icon" href="<?php echo isset($setting['favicon']) ? './assets/img/userapp/favicon/'.$setting['favicon'] : './assets/img/system/favicon.png'; ?>" type="image/PNG">
    <link rel="stylesheet" href="./assets/css/app.css">
    <link rel="stylesheet" href="./assets/css/pages/pages.css">
    <script src="https://kit.fontawesome.com/e0df5df9e9.js" crossorigin="anonymous"></script>
    <style>
        header {
            background-color: <?php
            if ($company != 0) {
                echo $colorfont;
            } elseif($setting != 0 && $company == 0) {
                echo $setting['color_font'];
            }else{
                echo '#4F68FF';
            } ?>;
        }
        .container .content #share-buttons a {
            color: <?php
            if ($company != 0) {
                echo $company['primary_color'];
            } elseif($setting != 0 && $company == 0) {
                echo $setting['color_primary'];
            }else{
                echo '#4F68FF';
            } ?>;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <?php if ($company != 0) { ?>
                <img src="./assets/img/company/<?php echo $company['logo']; ?>" />
            <?php }elseif($setting != 0 && $company == 0){ ?>
                <img src="./assets/img/userapp/logo/<?php echo $setting['logo']; ?>" height="85%" />
            <?php }else{ ?>
                <h2>Ver QR</h2>
            <?php } ?>
        </header>
        <div class="content">
            <?php if ($company != 0) { ?>
                <p><?php echo $company['name']; ?></p>
            <?php } ?>
            <h2><?php echo $qrdata['name']; ?></h2>
            <img src="./assets/img/qr/<?php echo $qrdata['archive']; ?>" />
            <div id="share-buttons">
                Compartir: 
                <a class="share-button" id="facebook-share"><i class="fa-brands fa-facebook"></i></a>
                <a class="share-button" id="twitter-share"><i class="fa-brands fa-twitter"></i></a>
                <a class="share-button" id="linkedin-share"><i class="fa-brands fa-linkedin"></i></a>
            </div>
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