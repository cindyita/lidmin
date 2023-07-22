<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lidmin | Administador de enlaces, QR, archivos y más</title>
    <link rel="shortcut icon" href="./assets/img/system/favicon.png" type="image/PNG">
    <link rel="stylesheet" href="./assets/css/app.css?upd=<?php echo VERSION; ?>" />
    <link rel="stylesheet" href="./assets/css/login.css?upd=<?php echo VERSION; ?>" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <!-----------ReCaptcha------------>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-----------/ReCaptcha------------>
</head>
<body>

    <div class="content-login">

        <div class="login">

            <div class="logo">
                <img src="./assets/img/system/logo.png" alt="logo">
            </div>

            <h2>Login</h2>

            <br><hr><br>

            <div>
                <form method="post" id="login">
                    <div class="alert-error" id="error-login"></div>
                    <div class="username">
                        <label for="username" class="form-label">Usuario</label><br>
                        <input type="text" class="form-control" id="username" placeholder="Ingresa tu nombre de usuario" name="username" maxlength="50" required>
                    </div>
                    <div class="password">
                        <label for="pwd" class="form-label">Contraseña</label><br>
                        <input type="password" class="form-control" id="pwd" placeholder="Ingresa tu contraseña" maxlength="50" name="pswd" required>
                    </div>
                    <div>
                        <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"> Recuerdame
                        </label>
                    </div>
                    <!------>
                    <div class="g-recaptcha" data-sitekey="6LcVxR0nAAAAAFrJIgfTBJVTh0cI7FucX7wJcoIZ"></div>
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </form>
            </div>

        </div>
        
    </div>

    <script src="./assets/js/pages/login.js?upd=<?php echo VERSION; ?>"></script>
    
</body>
</html>