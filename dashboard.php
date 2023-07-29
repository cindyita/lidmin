<?php

session_start();
require_once("config.php");
require_once("info.php");
require_once("./src/controllers/baseController.php");
require_once("./src/resources/html.php");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!----required---->
    <link href="./assets/required/bootstrap5/bootstrap.min.css" rel="stylesheet">
    <script src="./assets/required/bootstrap5/bootstrap.bundle.min.js"></script>

    <link href="./assets/required/fontawesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="./assets/required/fontawesome/css/brands.min.css" rel="stylesheet">
    <link href="./assets/required/fontawesome/css/solid.min.css" rel="stylesheet">

    <script src="./assets/required/jquery/jquery-3.7.0.min.js"></script>
    
    <link href="./assets/required/datatables/datatables.min.css" rel="stylesheet"/>
    <script src="./assets/required/datatables/datatables.min.js"></script>
    <!---------------->
<?php
$page = isset($_GET['page']) ? $_GET['page'] : "";

if (isset($_GET['page'])) {

    if ($page === 'qr' && method_exists('baseController', 'qrTable')) {
        BaseController::qrTable();
    }elseif ($page === 'empresas' && method_exists('baseController', 'companyTable')) {
        BaseController::companyTable();
    }elseif ($page === 'archivos' && method_exists('baseController', 'fileTable')) {
        BaseController::fileTable();
    }elseif ($page === 'enlaces' && method_exists('baseController', 'linkTable')) {
        BaseController::linkTable();
    } elseif ($page === 'carpetas' && method_exists('baseController', 'folderTable')) {
        BaseController::folderTable();
    }elseif ($page === 'usuarios' && method_exists('baseController', 'usersTable')) {
        //BaseController::usersTable();
    }elseif ($page === 'config' && method_exists('baseController', 'settings')) {
        BaseController::settings();
    } else {
        BaseController::error404();
    }

}else{

    if (empty($page)) {
        BaseController::dashboard();
    }

}
?>
</body>
</html>
<?php 
