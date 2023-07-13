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
    case 'generateqr':
        generateQR($data);
    break;
    case 'savesettings':
        saveSettings($data);
    break;
    case 'editqr':
        editQr($data);
    break;
    case 'deleteqr':
        deleteQr($data);
    break;
    case 'selectcompany':
        selectCompany($data);
    break;
    case 'createcompany':
        createCompany($data);
    break;
    case 'editcompany':
        editCompany($data);
    break;
    case 'deletecompany':
        deleteCompany($data);
    break;
    case 'deletefile':
        deletefile($data);
    break;
    case 'editfile':
        editfile($data);
    break;
    case 'addpassword':
        addpassword($data);
    break;
    case 'removepassword':
        removepassword($data);
    break;
    case 'setdefault':
        setdefault();
    break;
    case 'newfile':
        newfile($data);
    break;
    default:
        echo json_encode("No se definió una acción");
    break;
}

function login($data){
    echo json_encode($data);
    /*
    try{
        // ReCaptcha
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify'; 
        $recaptcha_secret = '6LcVxR0nAAAAAEsXfq83Av-3i-KALzwKclGK7vUQ'; 
        $recaptcha_response = $data['g-recaptcha-response']; 
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response); 
        $recaptcha = json_decode($recaptcha); 

        if($recaptcha->score >= 0.7){

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
                echo 1;
            }else{
                echo 0;
            }

        } else {

            echo 2;

        }
        
    }catch(exception $e){
        echo json_encode('error: '.$e);
    }*/
    
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

function generateQR($data)
{
    $bm = new BaseModel();

    $datetime = date('Y-m-d H:i:s');
    $timestamp = strtotime($datetime);
    $dateNumber = preg_replace('/[^0-9]/', '', strval($timestamp));

    $patronText = '/^[a-zA-Z0-9\s\-]+$/';
    /*$patronUrl = '/^(http|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-_.,@?^=%&amp;:/~\+#]*[\w\-_@\?^=%&amp;/~\+#])?$/';*/

    if (!preg_match($patronText, $data['title'])) {
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
                $qrCode->writeFile("../../assets/img/qr/{$name}.png");

                $insert = array(
                    'name' => $data['title'],
                    'type' => $data['generate'],
                    'archive' => $name.'.png',
                    'id_company' => $company,
                    'destination' => $data['url'],
                    'id_user' => $_SESSION['user_id']
                );
                $bm->insert("reg_qr", $insert);
                echo 1;

            }catch(PDOException $e){
                echo "Error en la consulta: " . $e->getMessage();
            }
            
        break;
        case 'file':
        

            try{
                if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK){

                    $originalFileName = $_FILES['file']['name'];
                    $extension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
                    $nameFile = 'file_'.$_SESSION['user_id'].'_'.$companyid.'_'.$dateNumber.'.'.$extension;
                    $type = $_FILES['file']['type'];
                    //$size = $_FILES['file']['size'];
                    $temp = $_FILES['file']['tmp_name'];

                    $allowedExtensions = ['pdf', 'doc', 'docx', 'txt', 'xlsx', 'rar', 'pptx', 'jpg', 'jpeg', 'png', 'gif']; // Extensiones permitidas
                    $allowedMimeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/x-rar-compressed', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'image/jpeg', 'image/png', 'image/gif']; // Tipos MIME permitidos

                    // Verificar extensión permitida
                    if (!in_array($extension, $allowedExtensions)) {
                        echo "errortype";
                        return;
                    }
                    // Verificar tipo MIME permitido
                    if (!in_array($type, $allowedMimeTypes)) {
                        echo "errortype";
                        return;
                    }

                    $routeSave = '../../assets/doc/' . $nameFile;
                    move_uploaded_file($temp, $routeSave);

                    $insert = array(
                        'name' => $data['title'],
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
                    $qrCode->writeFile("../../assets/img/qr/{$name}.png");

                    $insert2 = array(
                        'name' => $data['title'],
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
                    $bm->update("reg_archive", $upd,"id = ".$lastid);

                    echo 1;
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
                $qrCode->writeFile("../../assets/img/qr/{$name}.png");

                $insert = array(
                    'name' => $data['title'],
                    'type' => $data['generate'],
                    'archive' => $name.'.png',
                    'id_company' => $company,
                    'destination' => $companyreg[0]['website'],
                    'id_user' => $_SESSION['user_id']
                );

                $bm->insert("reg_qr", $insert);
                echo 1;

            }catch(PDOException $e){
                echo "Error en la consulta: " . $e->getMessage();
            }
        break;
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

function editQr($data) {
    $bm = new BaseModel();
    try {
        $company = $data['company'] != 'NULL' ? $data['company'] : null;
        $upd = array(
            'name' => $data['name'],
            'id_company' => $company,
        );
        $bm->update("reg_qr", $upd,"id = ".$data['editid']);
        echo 1;
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function deleteQr($data){
    try{
        $bm = new BaseModel();

        $qr = $bm->select("reg_qr", "id = ".$data['deleteid']);
        $qrarchive = $qr[0]['archive'];

        $archive = "../../assets/img/qr/{$qrarchive}";
        if (file_exists($archive)) {
            unlink($archive);
        }

        if($qr[0]['type'] == 'file'){
            $file = $bm->select("reg_archive", "id_qr = ".$data['deleteid']);
            $filearchive = $file[0]['archive'];
            $archive = "../../assets/doc/{$filearchive}";
            if (file_exists($archive)) {
                unlink($archive);
            }
            $bm->delete("reg_archive", "id_qr = ".$data['deleteid']);
        }

        $bm->delete("reg_qr", "id = ".$data['deleteid']);
        echo 1;
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function selectCompany($data) {
    try{
        $bm = new BaseModel();
        $company = $bm->select("reg_company", "id = '".$data['id']."'");
        echo json_encode($company);
        
    } catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function createCompany($data){
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

function editCompany($data) {

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
        
        $bm->update("reg_company", $upd,"id =".$data['companyid']);
        echo 1;

    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function deleteCompany($data) {
    try{
        $bm = new BaseModel();

        $company = $bm->select("reg_company", "id = ".$data['deleteid']);
        $companyarchive = $company[0]['logo'];

        $archive = "../../assets/img/company/{$companyarchive}";
        if (file_exists($archive)) {
            unlink($archive);
        }

        $bm->delete("reg_company", "id = ".$data['deleteid']);
        echo 1;
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function addpassword($data) {
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

function removepassword($data) {
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

function newfile($data) {
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
            $nameFile = 'file_'.$_SESSION['user_id'].'_'.$companyid.'_'.$dateNumber.'.'.$extension;
            $type = $_FILES['file']['type'];
            //$size = $_FILES['file']['size'];
            $temp = $_FILES['file']['tmp_name'];

            $allowedExtensions = ['pdf', 'doc', 'docx', 'txt', 'xlsx', 'rar', 'pptx', 'jpg', 'jpeg', 'png', 'gif']; // Extensiones permitidas
            $allowedMimeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/x-rar-compressed', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'image/jpeg', 'image/png', 'image/gif']; // Tipos MIME permitidos

            if (!in_array($extension, $allowedExtensions)) {
                echo "errortype";
                return;
            }

            if (!in_array($type, $allowedMimeTypes)) {
                echo "errortype";
                return;
            }

            $routeSave = '../../assets/doc/' . $nameFile;
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

function deletefile($data) {
    try{
        $bm = new BaseModel();

        $id = $data['id'];

        $file = $bm->select("reg_archive", "id = ".$id);
        $fileurl = $file[0]['archive'];

        $archive = "../../assets/doc/{$fileurl}";
        if (file_exists($archive)) {
            unlink($archive);
        }

        $bm->delete("reg_archive", "id = ".$id);
        echo 1;
    }catch(PDOException $e){
        echo "Error en la consulta: " . $e->getMessage();
    }
}

function editfile($data) {
    
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