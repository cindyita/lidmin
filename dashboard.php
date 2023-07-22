<?php

session_start();
require_once("config.php");
require_once("./src/controllers/baseController.php");
require_once("./src/resources/html.php");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!----required---->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    
    <script src="https://kit.fontawesome.com/e0df5df9e9.js" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    
    <link href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/r-2.4.1/datatables.min.css" rel="stylesheet"/>
    <script src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/r-2.4.1/datatables.min.js"></script>
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
