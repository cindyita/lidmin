<?php

require_once "../models/BaseModel.php";
require_once("../../config.php");
require '../../vendor/autoload.php';
use Endroid\QrCode\QrCode;
session_start();

$action = $_GET['action'];
$data = isset($_POST) ? $_POST : '';
switch ($action) {
    case 'login':
        login($data);
    break;
    case 'logout':
        logout();
    break;
    //QR -------------
    case 'qrReadTable':
        qrReadTable();
    break;
    case 'qrRead':
        qrRead($data);
    break;
    case 'qrCreate':
        qrCreate($data);
    break;
    case 'qrUpdate':
        qrUpdate($data);
    break;
    case 'qrDelete':
        qrDelete($data);
    break;
    //LINK -------------
    case 'linkReadTable':
        linkReadTable();
    break;
    case 'linkRead':
        linkRead($data);
    break;
    case 'linkCreate':
        linkCreate($data);
    break;
    case 'linkUpdate':
        linkUpdate($data);
    break;
    case 'linkDelete':
        linkDelete($data);
    break;
    //FILE ---------------
    case 'fileCreate':
        fileCreate($data);
    break;
    case 'fileUpdate':
        fileUpdate($data);
    break;
    case 'fileDelete':
        fileDelete($data);
    break;
    case 'passwordCreate':
        passwordCreate($data);
    break;
    case 'passwordDelete':
        passwordDelete($data);
    break;
    // FOLDER ----------------
    case 'folderReadFiles':
        folderReadFiles($data);
    break;
    case 'folderReadTable':
        folderReadTable();
    break;
    case 'folderRead':
        folderRead($data);
    break;
    case 'folderCreate':
        folderCreate($data);
    break;
    case 'folderUpdate':
        folderUpdate($data);
    break;
    case 'folderDelete':
        folderDelete($data);
    break;
    case 'folderReadType':
        folderReadType($data);
    break;
    case 'folderAdd':
        folderAdd($data);
    break;
    case 'elementFolderDelete':
        elementFolderDelete($data);
    break;
    case 'folderReadPassword':
        folderReadPassword($data);
    break;
    case 'folderPasswordCreate':
        folderPasswordCreate($data);
    break;
    case 'folderPasswordDelete':
        folderPasswordDelete($data);
    break;
    // COMPANY ---------------
    case 'companyReadTable':
        companyReadTable();
    break;
    case 'companyRead':
        companyRead($data);
    break;
    case 'companyCreate':
        companyCreate($data);
    break;
    case 'companyUpdate':
        companyUpdate($data);
    break;
    case 'companyDelete':
        companyDelete($data);
    break;
    // SETTINGS -------------
    case 'savesettings':
        saveSettings($data);
    break;
    case 'setdefault':
        setdefault();
    break;
    // ----------------------
    default:
        echo json_encode("No se definió una acción");
    break;
}

function login($data){

    try{
        // ReCaptcha
        /*-----------*/
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify'; 
        $recaptcha_secret = '6LcVxR0nAAAAAEsXfq83Av-3i-KALzwKclGK7vUQ'; 
        $recaptcha_response = $data['g-recaptcha-response']; 
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response); 
        $recaptcha = json_decode($recaptcha); 

        if($recaptcha->success == true){

            $bm = new BaseModel();
            $user = $bm->select("sys_user", "username = '".$data['username']."'");
            $user = $user == null ? null : $user[0];

            if(isset($user) && isset($user['password']) && $user['password'] == md5($data['pswd']))
            {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['limit_size_files'] = $user['limit_size_files'];
                echo 1;
            }else{
                echo 0;
            }
        /*-------*/
        } else {
            echo 2;
        }
        
    }catch(exception $e){
        echo json_encode('error: '.$e);
    }
    
}

function logout()
{   
    try{
        session_destroy();
        echo 1;
    }catch(exception $e){
        echo $e;
    }
    
}

function qrReadTable() {
    try {
        $bd = new BaseModel();
        $res = $bd->query("SELECT ROW_NUMBER() OVER (ORDER BY q.id) AS Fila, q.id AS id, q.name AS `Descripción`, q.destination AS Destino, q.type AS Tipo, c.name AS Empresa, DATE_FORMAT(q.timestamp_create, '%Y-%m-%d') AS Creado, CONCAT('') AS Acciones FROM reg_qr q LEFT JOIN reg_company c ON q.id_company = c.id WHERE q.id_user = :id_user", array(":id_user" => $_SESSION['user_id']));
        echo json_encode($res);
    } catch(PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
}
function qrRead($data) {

    try {
        $bd = new BaseModel();
        $res = $bd->query("SELECT * FROM reg_qr WHERE id_user = :id_user AND id = :id", array(":id_user" => $_SESSION['user_id'],":id"=>$data['data']));
        echo json_encode($res);
    } catch(PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function qrCreate($data)
{
    $bm = new BaseModel();

    $datetime = date('Y-m-d H:i:s');
    $timestamp = strtotime($datetime);
    $dateNumber = preg_replace('/[^0-9]/', '', strval($timestamp));

    $patronText = '/^[a-zA-Z0-9\s\-]+$/';
    /*$patronUrl = '/^(http|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-_.,@?^=%&amp;:/~\+#]*[\w\-_@\?^=%&amp;/~\+#])?$/';*/

    if (!preg_match($patronText, $data['name'])) {
        echo "errorpattern";
        exit;
    }
    /*
    if ($data['generate'] == 'url' && !preg_match($patronUrl, $data['url'])) {
        echo "errorpattern";
        exit;
    }
    */
    $companyid = $data['company'] ? $data['company'] : '0';
    $company = $data['company'] == 0 ? null : $data['company'];

    switch ($data['generate']) {
        case 'url':
            try{
                $name = 'qr_'.$_SESSION['user_id'].'_'.$data['company'].'_'.$dateNumber;
                $qrCode = new QrCode($data['url']);

                $routeBaseUser = '../../assets/img/qr/' . $_SESSION['user_id'] . '/';
                if (!file_exists($routeBaseUser)) {
                    mkdir($routeBaseUser, 0777, true);
                }

                $qrCode->writeFile("../../assets/img/qr/{$_SESSION['user_id']}/{$name}.png");

                $insert = array(
                    'name' => $data['name'],
                    'type' => $data['generate'],
                    'archive' => $name.'.png',
                    'id_company' => $company,
                    'destination' => $data['url'],
                    'id_user' => $_SESSION['user_id']
                );
                $res = $bm->insert("reg_qr", $insert);
                echo $res;

            }catch(PDOException $e){
                echo "Error en la consulta: " . $e->getMessage();
            }
            
        break;
        case 'file':
        
            try{
                if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK){

                    $originalFileName = $_FILES['file']['name'];
                    $extension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
                    //$nameFile = 'file_'.$_SESSION['user_id'].'_'.$companyid.'_'.$dateNumber.'.'.$extension;
                    $nameFile = $originalFileName;
                    $type = $_FILES['file']['type'];
                    //$size = $_FILES['file']['size'];
                    $temp = $_FILES['file']['tmp_name'];

                    $allowedExtensions = ['pdf', 'doc', 'docx', 'txt', 'xlsx', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'mp3', 'wav']; // Extensiones permitidas
                    $allowedMimeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'image/jpeg', 'image/png', 'image/gif', 'audio/mpeg', 'audio/wav']; // Tipos MIME permitidos

                    if (!in_array($extension, $allowedExtensions)) {
                        echo "errortype";
                        return;
                    }

                    if (!in_array($type, $allowedMimeTypes)) {
                        echo "errortype";
                        return;
                    }

                    $routeBaseUser = '../../assets/doc/' . $_SESSION['user_id'] . '/';
                    if (!file_exists($routeBaseUser)) {
                        mkdir($routeBaseUser, 0777, true);
                    }

                    $routeSave = '../../assets/doc/'.$_SESSION['user_id'].'/'. $nameFile;
                    if (file_exists($routeSave)) {
                        $nameFile = $dateNumber.'_'.$nameFile;
                        $routeSave = '../../assets/doc/'.$_SESSION['user_id'].'/'. $nameFile;
                    }
                    move_uploaded_file($temp, $routeSave);

                    $insert = array(
                        'name' => $data['name'],
                        'archive' => $nameFile,
                        'type' => $extension,
                        'id_qr' => null,
                        'id_company' => $company,
                        'id_user' => $_SESSION['user_id']
                    );
                    $bm->insert("reg_archive", $insert);
                    $lastid = $bm->lastid("reg_archive");
                    $lastid = $lastid[0]['id'];

                    $urlfile = BASEURL.'view.php?page=filepage&file=' . $lastid;

                    $name = 'qr_'.$_SESSION['user_id'].'_'.$companyid.'_'.$dateNumber;
                    $qrCode = new QrCode($urlfile);

                    $routeBaseUser = '../../assets/img/qr/' . $_SESSION['user_id'] . '/';
                    if (!file_exists($routeBaseUser)) {
                        mkdir($routeBaseUser, 0777, true);
                    }

                    $qrCode->writeFile("../../assets/img/qr/{$_SESSION['user_id']}/{$name}.png");

                    $insert2 = array(
                        'name' => $data['name'],
                        'type' => $data['generate'],
                        'archive' => $name.'.png',
                        'id_company' => $company,
                        'destination' => $urlfile,
                        'id_user' => $_SESSION['user_id']
                    );
                    $bm->insert("reg_qr", $insert2);

                    $lastid2 = $bm->lastid("reg_qr");
                    $lastid2 = $lastid2[0]['id'];

                    $upd = array(
                        'id_qr' => $lastid2
                    );
                    $res = $bm->update("reg_archive", $upd,"id = ".$lastid);

                    echo $res;
                }else{
                    echo 0;
                }
            }catch(PDOException $e){
                echo "Error en la consulta: " . $e->getMessage();
            }
            
        break;
        case 'web':
            
            try{
                $name = 'qr_'.$_SESSION['user_id'].'_'.$companyid.'_'.$dateNumber;

                $companyreg = $bm->select("reg_company", "id = ".$companyid);
            
                $qrCode = new QrCode($companyreg[0]['website']);

                $routeBaseUser = '../../assets/img/qr/' . $_SESSION['user_id'] . '/';
                if (!file_exists($routeBaseUser)) {
                    mkdir($routeBaseUser, 0777, true);
                }

                $qrCode->writeFile("../../assets/img/qr/{$_SESSION['user_id']}/{$name}.png");

                $insert = array(
                    'name' => $data['name'],
                    'type' => $data['generate'],
                    'archive' => $name.'.png',
                    'id_company' => $company,
                    'destination' => $companyreg[0]['website'],
                    'id_user' => $_SESSION['user_id']
                );

                $res = $bm->insert("reg_qr", $insert);
                echo $res;

            }catch(PDOException $e){
                echo "Error en la consulta: " . $e->getMessage();
            }
        break;
    }
}

function qrUpdate($data) {
    $bm = new BaseModel();
    try {

        $company = $data['company'] != 'NULL' && $data['company'] != '0' ? $data['company'] : null;
        $upd = array(
            'name' => $data['name'],
            'id_company' => $company,
        );
        $res = $bm->update("reg_qr", $upd,"id = ".$data['qrUpdateId']);
        echo $res;
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function qrDelete($data){
    try{
        $bm = new BaseModel();

        $qr = $bm->select("reg_qr", "id = ".$data['qrDeleteId']);
        $qrarchive = $qr[0]['archive'];

        $archive = "../../assets/img/qr/{$qr[0]['id_user']}/{$qrarchive}";
        if (file_exists($archive)) {
            unlink($archive);
        }

        if($qr[0]['type'] == 'file'){
            $file = $bm->select("reg_archive", "id_qr = ".$data['qrDeleteId']);
            $filearchive = $file[0]['archive'];
            $archive = "../../assets/doc/{$_SESSION['user_id']}/{$filearchive}";
            if (file_exists($archive)) {
                unlink($archive);
            }
            $bm->delete("reg_archive", "id_qr = ".$data['qrDeleteId']);
        }

        $res = $bm->delete("reg_qr", "id = ".$data['qrDeleteId']);
        $bm->delete("rel_folder_files", "id_item = ".$data['qrDeleteId']." AND type = 'qr'");
        echo $res;
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function linkReadTable() {
    try {
        $bd = new BaseModel();
        $res = $bd->query("SELECT ROW_NUMBER() OVER (ORDER BY q.id) AS Fila, q.id AS id, q.name AS `Titulo`, q.url AS `URL`, c.name AS Empresa, DATE_FORMAT(q.timestamp_create, '%Y-%m-%d') AS Creado, CONCAT('') AS Acciones FROM reg_link q LEFT JOIN reg_company c ON q.id_company = c.id WHERE q.id_user = :id_user", array(":id_user" => $_SESSION['user_id']));
        echo json_encode($res);
    } catch(PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
}
function linkRead($data) {

    try {
        $bd = new BaseModel();
        $res = $bd->query("SELECT * FROM reg_link WHERE id_user = :id_user AND id = :id", array(":id_user" => $_SESSION['user_id'],":id"=>$data['data']));
        echo json_encode($res);
    } catch(PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function linkCreate($data){
    try{
        $bm = new BaseModel();

        $name = isset($data['name']) ? $data['name'] : null;
        $url = isset($data['url']) ? $data['url'] : null;
        $company = isset($data['company']) ? $data['company'] : null;

        $insert = array(
            'name' => $name,
            'url' => $url,
            'id_company'=>$company,
            'id_user' => $_SESSION['user_id']
        );
        $bm->insert("reg_link", $insert);
        echo 1;

    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function linkUpdate($data) {

    try{
        $bm = new BaseModel();

        $name = isset($data['name']) ? $data['name'] : null;
        $url = isset($data['url']) ? $data['url'] : null;
        $company = isset($data['company']) ? $data['company'] : null;


        $upd = array(
            'name' => $name,
            'url' => $url,
            'id_company'=>$company
        );
    
        $bm->update("reg_link", $upd,"id =".$data['linkUpdateId']);
        echo 1;

    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function linkDelete($data) {
    try{
        $bm = new BaseModel();
        $bm->delete("reg_link", "id = ".$data['linkDeleteId']);
        $bm->delete("rel_folder_files", "id_item = ".$data['linkDeleteId']." AND type = 'link'");
        echo 1;
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}


function saveSettings($data) {
    try{
        $bm = new BaseModel();

        $app_name = isset($data['app_name']) ? $data['app_name'] : null;
        $color_primary = isset($data['color_primary']) ? $data['color_primary'] : null;
        $color_secondary = isset($data['color_secondary']) ? $data['color_secondary'] : null;
        $color_tertiary = isset($data['color_tertiary']) ? $data['color_tertiary'] : null;
        $color_font = isset($data['color_font']) ? $data['color_font'] : null;
        $color_font2 = isset($data['color_font2']) ? $data['color_font2'] : null;

        $nameLogo = NULL;
        $nameFavicon = NULL;

        $existRegister = $bm->select("sys_setting", "id_user = ".$_SESSION['user_id']);

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $temp = $_FILES['logo']['tmp_name'];

            $imageInfo = getimagesize($temp);
            if ($imageInfo === false) {
                echo "errortype";
                return;
            }else{
                $timestamp = time();
                $dateNumber = filter_var($timestamp, FILTER_SANITIZE_NUMBER_INT);
                $originalFileName = $_FILES['logo']['name'];
                $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
                $nameLogo = "applogo_{$_SESSION['user_id']}_{$dateNumber}.{$extension}";

                $routeSave = '../../assets/img/userapp/logo/' . $nameLogo;
                move_uploaded_file($temp, $routeSave);
            }

            if(!empty($existRegister) && isset($existRegister[0]['logo'])) {
                $archive = "../../assets/img/userapp/logo/{$existRegister[0]['logo']}";
                if (file_exists($archive)) {
                    unlink($archive);
                }
            }
        }

        if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] === UPLOAD_ERR_OK) {
            $temp = $_FILES['favicon']['tmp_name'];

            $imageInfo = getimagesize($temp);
            if ($imageInfo === false) {
                echo "errortype";
                return;
            }else{
                $timestamp = time();
                $dateNumber = filter_var($timestamp, FILTER_SANITIZE_NUMBER_INT);
                $originalFileName = $_FILES['favicon']['name'];
                $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
                $nameFavicon = "appfavicon_{$_SESSION['user_id']}_{$dateNumber}.{$extension}";

                $routeSave = '../../assets/img/userapp/favicon/' . $nameFavicon;
                move_uploaded_file($temp, $routeSave);
            }

            if(!empty($existRegister) && isset($existRegister[0]['favicon'])) {
                $archive = "../../assets/img/userapp/favicon/{$existRegister[0]['favicon']}";
                if (file_exists($archive)) {
                    unlink($archive);
                }
            }
        }

        if (!empty($existRegister)) {

            $insert = array(
                'app_name' => $app_name,
                'color_primary' => $color_primary,
                'color_secondary' => $color_secondary,
                'color_tertiary' => $color_tertiary,
                'color_font' => $color_font,
                'color_font2' => $color_font2
            );
            
            if ($nameLogo !== NULL) {
                $insert['logo'] = $nameLogo;
            }
            if ($nameFavicon !== NULL) {
                $insert['favicon'] = $nameFavicon;
            }
            
            $bm->update("sys_setting", $insert,"id_user = ".$_SESSION['user_id']);
            $newData = $bm->select("sys_setting", "id_user = ".$_SESSION['user_id']);
            echo json_encode($newData);
        }else{
            $insert = array(
                'app_name' => $app_name,
                'logo' => $nameLogo,
                'favicon' => $nameFavicon,
                'color_primary' => $color_primary,
                'color_secondary' => $color_secondary,
                'color_tertiary' => $color_tertiary,
                'color_font' => $color_font,
                'color_font2' => $color_font2,
                'id_user' => $_SESSION['user_id']
            );
            $bm->insert("sys_setting", $insert);
            $newData = $bm->select("sys_setting", "id_user = ".$_SESSION['user_id']);
            echo json_encode($newData);
        }

    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function folderCreate($data) {
    try{
        $bm = new BaseModel();

        $name = isset($data['name']) ? $data['name'] : null;
        $description = isset($data['description']) ? $data['description'] : null;
        $company = isset($data['company']) ? $data['company'] : null;

        $insert = array(
            'name' => $name,
            'description' => $description,
            'id_company'=>$company,
            'id_user' => $_SESSION['user_id']
        );
        $bm->insert("reg_folder", $insert);
        echo 1;

    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function folderReadFiles($data) {
    try {
        $bd = new BaseModel();

        // Folder files
        $resultFiles = $bd->query("SELECT f.type, 
            CASE f.type
                WHEN 'qr' THEN qr.id
                WHEN 'link' THEN link.id
                WHEN 'archive' THEN archive.id
            END AS id,
            CASE f.type
                WHEN 'qr' THEN qr.name
                WHEN 'link' THEN link.name
                WHEN 'archive' THEN archive.name
            END AS name
        FROM rel_folder_files f
        LEFT JOIN reg_qr qr ON f.type = 'qr' AND f.id_item = qr.id
        LEFT JOIN reg_link link ON f.type = 'link' AND f.id_item = link.id
        LEFT JOIN reg_archive archive ON f.type = 'archive' AND f.id_item = archive.id
        WHERE f.id_folder = :id_folder", array(":id_folder" => $data['data']));

        // Folder Info
        $resultFolder = $bd->query("SELECT name AS folder_name, description AS folder_description
        FROM reg_folder WHERE id = :id_folder", array(":id_folder" => $data['data']));

        $response = array(
            "files" => $resultFiles,
            "folder" => $resultFolder
        );

        echo json_encode($response);
    } catch(PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function folderReadTable() {
    try {
        $bd = new BaseModel();
        $res = $bd->query("SELECT CONCAT('<i class=\"fa-solid fa-folder pe-2\"></i>', ROW_NUMBER() OVER (ORDER BY q.id)) AS Fila, 
                          q.id AS id, 
                          IF(q.password IS NOT NULL, CONCAT('<i class=\"fa-solid fa-lock text-warning\"></i> ', q.name), q.name) AS `Nombre`,
                          c.name AS Empresa, 
                          DATE_FORMAT(q.timestamp_create, '%Y-%m-%d') AS Creado, 
                          CONCAT('') AS Acciones 
                          FROM reg_folder q 
                          LEFT JOIN reg_company c ON q.id_company = c.id 
                          WHERE q.id_user = :id_user", 
                          array(":id_user" => $_SESSION['user_id']));

        echo json_encode($res);
        
    } catch(PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
}


function folderRead($data) {
    try {
        $bd = new BaseModel();
        $res = $bd->query("SELECT * FROM reg_folder WHERE id_user = :id_user AND id = :id", array(":id_user" => $_SESSION['user_id'],":id"=>$data['data']));
        echo json_encode($res);
    } catch(PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function folderUpdate($data) {
    try{
        $bm = new BaseModel();

        $name = isset($data['name']) ? $data['name'] : null;
        $description = isset($data['description']) ? $data['description'] : null;
        $company = isset($data['company']) ? $data['company'] : null;

        $upd = array(
            'name' => $name,
            'description' => $description,
            'id_company'=>$company
        );
    
        $bm->update("reg_folder", $upd,"id =".$data['folderUpdateId']);
        echo 1;

    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function folderDelete($data) {
    try{
        $bm = new BaseModel();
        $bm->delete("rel_folder_files", "id_folder = ".$data['folderDeleteId']);
        $bm->delete("reg_folder", "id = ".$data['folderDeleteId']);
        echo 1;
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function folderReadType($data) {
    try {
        $type = $data['data'];
        switch ($type) {
            case 'archive':
                $table = "reg_archive";
                break;
            case 'qr':
                $table = "reg_qr";
                break;
            case 'link':
                $table = "reg_link";
                break;
            default:
                echo json_encode("Ningún tipo seleccionado");
                return;
            break;
        }
        $bd = new BaseModel();
        $res = $bd->query("SELECT id,name FROM $table WHERE id_user = :id_user", array(":id_user" => $_SESSION['user_id']));
        echo json_encode($res);
    } catch(PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function folderAdd($data) {
    try{
        $bm = new BaseModel();

        if (is_string($data['addList']) && json_decode($data['addList'], true)) {
            $addListArray = json_decode($data['addList'], true);

            foreach ($addListArray as $key => $value) {
                $select = $bm->select("rel_folder_files", "id_folder = ".$data['folderAddId']." AND id_item = ".$key." AND type = '".$value['type']."'");
                if(!$select){
                    $insert = array(
                        'id_folder' => $data['folderAddId'],
                        'id_item' => $key,
                        'type'=>$value['type']
                    );
                    $bm->insert("rel_folder_files", $insert);
                }
            }
        } else {
            echo json_encode('Invalid JSON data for list');
        }
        echo 1;

    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function elementFolderDelete($data) {

    try{
        $idFolder = $data['data']['folder'];
        $idItem = $data['data']['element'];
        $type = $data['data']['type'];

        $bm = new BaseModel();
        $delete = $bm->delete("rel_folder_files", "id_folder = ".$idFolder." AND id_item = ".$idItem." AND type = '".$type."'");
        echo $delete;
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function folderReadPassword($data) {
    try{
        $bm = new BaseModel();
        $data = $bm->query("SELECT name,password FROM reg_folder WHERE id = :id", array(":id" => $data['data']));
        echo json_encode($data);
        
    } catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function folderPasswordCreate($data) {
    $bm = new BaseModel();
    try {
        $id = $data['id'];
        $pass = $data['pass'];
        $upd = array(
            'password' => $pass
        );
        $bm->update("reg_folder", $upd,"id = ".$id);
        echo 1;
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function folderPasswordDelete($data) {
    $bm = new BaseModel();
    try {
        $id = $data['id'];

        $upd = array(
            'password' => null
        );
        $bm->update("reg_folder", $upd,"id = ".$id);
        echo 1;
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function companyReadTable() {
    try {
        $bd = new BaseModel();

        $res = $bd->query("SELECT ROW_NUMBER() OVER (ORDER BY q.id) AS Fila, q.id AS id, CONCAT('<img src=\"./assets/img/company/',q.logo,'\" height=\"40px\">') AS `Logo`, q.name AS `Nombre`, q.website AS `Sitio web`, q.email AS `Email`, q.phone AS `Teléfono` , q.primary_color AS `Color` , DATE_FORMAT(q.timestamp_create, '%Y-%m-%d') AS Creado, CONCAT('') AS Acciones FROM reg_company q WHERE q.id_user = :id_user", array(":id_user" => $_SESSION['user_id']));
        echo json_encode($res);
    } catch(PDOException $e) {
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function companyRead($data) {
    try{
        $bm = new BaseModel();
        $company = $bm->select("reg_company", "id = '".$data['id']."'");
        echo json_encode($company);
        
    } catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function companyCreate($data){
    try{
        $bm = new BaseModel();

        $web = isset($data['web']) ? $data['web'] : null;
        $email = isset($data['email']) ? $data['email'] : null;
        $tel = isset($data['tel']) ? $data['tel'] : null;
        $color = isset($data['color']) ? $data['color'] : null;
        $nameFile = NULL;

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $originalNameFile = $_FILES['logo']['name'];
            $timestamp = time();
            $dateNumber = filter_var($timestamp, FILTER_SANITIZE_NUMBER_INT);
            $extension = pathinfo($originalNameFile, PATHINFO_EXTENSION);
            $nameFile = "companylogo_{$_SESSION['user_id']}_{$dateNumber}.{$extension}";
            $temp = $_FILES['logo']['tmp_name'];

            $routeSave = '../../assets/img/company/' . $nameFile;
            move_uploaded_file($temp, $routeSave);
        }

        $insert = array(
            'name' => $data['name'],
            'website' => $web,
            'email' => $email,
            'phone' => $tel,
            'logo' => $nameFile,
            'primary_color' => $color,
            'id_user' => $_SESSION['user_id']
        );
        $bm->insert("reg_company", $insert);
        echo 1;

    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function companyUpdate($data) {

    try{
        $bm = new BaseModel();

        $web = isset($data['web']) ? $data['web'] : null;
        $email = isset($data['email']) ? $data['email'] : null;
        $tel = isset($data['tel']) ? $data['tel'] : null;
        $color = isset($data['color']) ? $data['color'] : null;

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {

            $logo_actual = $data['logo_actual'];
            $archive = "../../assets/img/company/{$logo_actual}";
            if (file_exists($archive)) {
                unlink($archive);
            }

            $nameFile = $_FILES['logo']['name'];
            $temp = $_FILES['logo']['tmp_name'];

            $routeSave = '../../assets/img/company/' . $nameFile;
            move_uploaded_file($temp, $routeSave);

            $upd = array(
                'name' => $data['name'],
                'website' => $web,
                'email' => $email,
                'phone' => $tel,
                'logo' => $nameFile,
                'primary_color' => $color
            );
        }else{
            $upd = array(
                'name' => $data['name'],
                'website' => $web,
                'email' => $email,
                'phone' => $tel,
                'primary_color' => $color
            );
        }
        
        $bm->update("reg_company", $upd,"id =".$data['companyUpdateId']);
        echo 1;

    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function companyDelete($data) {
    try{
        $bm = new BaseModel();

        $company = $bm->select("reg_company", "id = ".$data['companyDeleteId']);
        $companyarchive = $company[0]['logo'];

        $archive = "../../assets/img/company/{$companyarchive}";
        if (file_exists($archive)) {
            unlink($archive);
        }

        $bm->delete("reg_company", "id = ".$data['companyDeleteId']);
        echo 1;
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function passwordCreate($data) {
    $bm = new BaseModel();
    try {
        $id = $data['id'];
        $pass = $data['pass'];
        $upd = array(
            'password' => $pass
        );
        $bm->update("reg_archive", $upd,"id = ".$id);
        echo 1;
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function passwordDelete($data) {
    $bm = new BaseModel();
    try {
        $id = $data['id'];

        $upd = array(
            'password' => null
        );
        $bm->update("reg_archive", $upd,"id = ".$id);
        echo 1;
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function setdefault() {
    try{
        $bm = new BaseModel();

        $id_user = $_SESSION['user_id'];

        $setting = $bm->select("sys_setting", "id_user = ".$id_user);
        $logo = $setting[0]['logo'] ? $setting[0]['logo'] : 0;
        $favicon = $setting[0]['favicon'] ? $setting[0]['favicon'] : 0;

        $archive = "../../assets/img/userapp/logo/{$logo}";
        if (file_exists($archive)) {
            unlink($archive);
        }
        $archive = "../../assets/img/userapp/favicon/{$favicon}";
        if (file_exists($archive)) {
            unlink($archive);
        }

        $bm->delete("sys_setting", "id_user = ".$id_user);
        echo 1;
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function fileCreate($data) {
    try{
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK){

            $bm = new BaseModel();
            $datetime = date('Y-m-d H:i:s');
            $timestamp = strtotime($datetime);
            $dateNumber = preg_replace('/[^0-9]/', '', strval($timestamp));
            $companyid = $data['company'] ? $data['company'] : '0';
            $company = $data['company'] == 0 ? null : $data['company'];

            $originalFileName = $_FILES['file']['name'];
            $extension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
            //$nameFile = 'file_'.$_SESSION['user_id'].'_'.$companyid.'_'.$dateNumber.'.'.$extension;
            $nameFile = $originalFileName;
            $type = $_FILES['file']['type'];
            //$size = $_FILES['file']['size'];
            $temp = $_FILES['file']['tmp_name'];

            $allowedExtensions = ['pdf', 'doc', 'docx', 'txt', 'xlsx', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'mp3', 'wav']; // Extensiones permitidas
            $allowedMimeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'image/jpeg', 'image/png', 'image/gif', 'audio/mpeg', 'audio/wav']; // Tipos MIME permitidos

            if (!in_array($extension, $allowedExtensions)) {
                echo "errortype";
                return;
            }

            if (!in_array($type, $allowedMimeTypes)) {
                echo "errortype";
                return;
            }

            $routeBaseUser = '../../assets/doc/' . $_SESSION['user_id'] . '/';
            if (!file_exists($routeBaseUser)) {
                mkdir($routeBaseUser, 0777, true);
            }

            $routeSave = '../../assets/doc/'.$_SESSION['user_id'].'/'. $nameFile;
            if (file_exists($routeSave)) {
                $nameFile = $dateNumber.'_'.$nameFile;
                $routeSave = '../../assets/doc/'.$_SESSION['user_id'].'/'. $nameFile;
            }
            move_uploaded_file($temp, $routeSave);

            $insert = array(
                'name' => $data['title'],
                'archive' => $nameFile,
                'type' => $extension,
                'id_qr' => null,
                'id_company' => $company,
                'password' => ($data['filepass'] ? $data['filepass'] : null),
                'id_user' => $_SESSION['user_id']
            );

            $bm->insert("reg_archive", $insert);

            echo 1;
        }else{
            echo 0;
        }
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function fileDelete($data) {
    try{
        $bm = new BaseModel();

        $id = $data['id'];

        $file = $bm->select("reg_archive", "id = ".$id);
        $fileurl = $file[0]['archive'];

        $archive = "../../assets/doc/{$_SESSION['user_id']}/{$fileurl}";
        if (file_exists($archive)) {
            unlink($archive);
        }
        $bm->delete("reg_archive", "id = ".$id);
        $bm->delete("rel_folder_files", "id_item = ".$id." AND type = 'archive'");
        echo 1;
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function fileUpdate($data) {
    
    try{
        $bm = new BaseModel();

        $title = $data['title'];
        $company = $data['company'];

        $upd = array(
            'name' => $title,
            'id_company' => ($company && $company != 0 && $company != 'NULL' ? $company : NULL)
        );
        
        $bm->update("reg_archive", $upd,"id =".$data['id']);
        echo 1;

    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}