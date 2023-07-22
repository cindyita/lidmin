<?php
    $lockFile = 0;
    if($pdfdata['password'] == null){
        $lockFile = 1;
    }else{
        if(isset($_POST['pass']) && $_POST['pass'] == $pdfdata['password']){
            $lockFile = 1;
        }
    }
    if(isset($_POST['passFile'])){
        
        if($_POST['pass'] == $pdfdata['password']){
            $lockFile = 1;
        }else{
            echo '<script>alert("Contraseña incorrecta");</script>';
        }
    }
    
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($setting['app_name']) && $setting['app_name'] ? $setting['app_name'] : 'LiDMIN'; ?> | Visor de PDF | <?php echo $pdfdata['archive']; ?></title>
    <link rel="shortcut icon" href="<?php echo isset($setting['favicon']) && $setting['favicon'] ? './assets/img/userapp/favicon/'.$setting['favicon'] : './assets/img/system/favicon.png'; ?>" type="image/PNG">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-family:'raleway',sans-serif;
        }
        #pdf-content {
            width: 100%;
            height: 100%;
        }
        .content {
            padding-top:20px;
            text-align: center;
            width: 100%;
        }
        input {
            border-radius: 8px;
            border: 1px solid <?php
            if($setting != 0) {
                echo $setting['color_primary'];
            }else{
                echo '#4F68FF';
            } ?>;
            padding: 9px 12px;
        }
        button {
            border:0;
            color: white;
            background-color: <?php
            if($setting != 0) {
                echo $setting['color_primary'];
            }else{
                echo '#4F68FF';
            } ?>;
            padding: 9px 15px;
            cursor:pointer;
        }
    </style>
</head>
<body>
    <?php if ($lockFile) { ?>
    
            <div id="pdf-content">
                <embed src="<?php echo $pdfFilePath; ?>" width="100%" height="100%" type="application/pdf">
            </div>

    <?php } else { ?>
            <div class="content">
                <h2>El archivo tiene contraseña</h2>
                <p><?php echo $pdfdata['archive']; ?></p>
                <form method="post">
                    <div class="input-group">
                        <input type="password" class="form-control" placeholder="Ingresa la contraseña" name="pass" autocomplete="off" required>
                        <button type="submit" name="passFile">Ingresar</button>
                    </div>
                </form>
            </div>
    <?php } ?>
</body>
</html>