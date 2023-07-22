<?php

// Evitar el almacenamiento en caché de la página
/*
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
*/

require_once("config.php");
require_once("info.php");
require_once("./src/controllers/baseController.php");

BaseController::login();

