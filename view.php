<?php

require_once("config.php");
require_once("./src/controllers/baseController.php");

$page = isset($_GET['page']) ? $_GET['page'] : "";

if (isset($_GET['page'])) {

    if ($page === 'verpdf' && method_exists('baseController', 'viewPdf')) {
        BaseController::viewPdf($_GET['id']);
    }elseif ($page === 'verqr' && method_exists('baseController', 'viewQr')) {
        BaseController::viewQr($_GET['qr']);
    }elseif ($page === 'filepage' && method_exists('baseController', 'filePage')) {
        BaseController::filePage($_GET['file']);
    } else {
        echo "Error 404: No encontramos lo que buscas.";
    }

}