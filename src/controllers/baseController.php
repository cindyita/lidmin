<?php
require_once "./src/models/BaseModel.php";
require_once "./src/views/layout/layout.php";
//use Endroid\QrCode\QrCode;

class BaseController
{
    private $baseModel;


    /**
     * Constructor de la clase BaseController.
     * Crea una instancia del modelo BaseModel.
     */
    public function __construct()
    {
        $this->baseModel = new BaseModel();
    }

    /**
     * Revisar si hay una sesión
     */
    public static function checkSession()
    {
        $allowedPages = array('login');
        $currentPage = basename($_SERVER['PHP_SELF'], '.php');
        if (!in_array($currentPage, $allowedPages)) {

            if (!isset($_SESSION['user_id'])) {

                header('Location: index.php');
                exit();
            }
        }
    }

    /**
     * Muestra la página de login.
     */
    public static function login()
    {   
        session_start();
        if (isset($_SESSION['user_id'])) {

            header('Location: dashboard.php');
            exit();
        }else{
            require_once "./src/views/login.php";
        }
        
    }

    /**
     * Muestra la página de inicio.
     */
    public static function dashboard()
    {
        BaseController::checkSession();
        $data = new BaseModel();
        $company = $data->select("reg_company", "id_user = ".$_SESSION['user_id']);
        $stats = $data->select("vw_statistics", "id_user = ".$_SESSION['user_id']);
        $stats = $stats[0];
        require_once "src/views/dashboard.php";
    }

    /**
     * Muestra la página de error 404 (página no encontrada).
     */
    public static function error404()
    {
        BaseController::checkSession();
        require_once "./src/views/error404.php";
    }

    /**
     * Muestra la tabla de códigos QR generados
     */
    public static function qrTable()
    {
        BaseController::checkSession();
        $bd = new BaseModel();
        $data = $bd->query("SELECT q.id, q.name, q.type, q.archive, c.name AS company, c.id AS company_id, q.destination, q.timestamp_create FROM reg_qr q LEFT JOIN reg_company c ON q.id_company = c.id WHERE q.id_user = :id_user", array(":id_user" => $_SESSION['user_id']));
        $company = $bd->select("reg_company", "id_user = ".$_SESSION['user_id']);
        require_once "./src/views/qrTable.php";
    }
    /**
     * Muestra la tabla de empresas
     */
    public static function companyTable()
    {
        BaseController::checkSession();
        $data = new BaseModel();
        $data = $data->select("reg_company", "id_user = ".$_SESSION['user_id']);
        require_once "./src/views/companyTable.php";
    }

    /**
     * Muestra la página de archivos
     */
    public static function fileTable()
    {
        BaseController::checkSession();
        $bd = new BaseModel();
        $files = $bd->query("SELECT q.id AS id, q.name AS name, q.archive AS archive,q.id_company, c.name AS company, q.id_qr, q.type,q.password, q.timestamp_create AS timestamp_create FROM reg_archive q LEFT JOIN reg_company c ON q.id_company = c.id WHERE q.id_user = :id_user", array(":id_user" => $_SESSION['user_id']));

        $company = $bd->select("reg_company", "id_user = ".$_SESSION['user_id']);

        require_once "./src/views/fileTable.php";
    }

    /**
     * Muestra la página de configuración
     */
    public static function settings()
    {
        BaseController::checkSession();
        $data = new BaseModel();
        $data = $data->select("sys_setting", "id_user = ".$_SESSION['user_id']);
        require_once "./src/views/settings.php";
    }

    /**
     * Muestra la página de usuarios
     */
    public static function usersTable()
    {
        BaseController::checkSession();
        $data = new BaseModel();
        $data = $data->select("sys_user", "1");
        require_once "./src/views/usersTable.php";
    }

    /**
     * Muestra la página visor de PDF
     */
    public static function viewPdf($id)
    {   
        $data = new BaseModel();
        $pdfdata = $data->select("reg_archive", "id = ".$id);
        if(!$pdfdata){
            echo "No se encontró el archivo";
            return;
        }
        $pdfdata = $pdfdata[0];
        if($pdfdata['id_user']){
            $setting = $data->select("sys_setting", "id_user = ".$pdfdata['id_user']);
            if($setting){
                $setting = $setting[0];
            }else{
                $setting = 0;
            }
        }
        $pdfFilePath = $pdfdata['archive'];
        $pdfFilePath = BASEURL.'assets/doc/'.$pdfdata['id_user'].'/' . $pdfFilePath;
        require_once "./src/views/viewPdf.php";
    }

    /**
     * Muestra la página visor de QR
     */
    public static function viewQr($qr)
    {   
        $data = new BaseModel();
        $qrdata = $data->select("reg_qr", "id = ".$qr);
        if(!$qrdata){
            echo "Esta página ya no está disponible";
            exit;
        }
        $qrdata = $qrdata[0];
        if($qrdata['id_company']){
            $company = $data->select("reg_company", "id = ".$qrdata['id_company']);
            $company = $company[0];
        }elseif($qrdata['id_user']){
            $setting = $data->select("sys_setting", "id_user = ".$qrdata['id_user']);
            if($setting){
                $setting = $setting[0];
            }else{
                $setting = 0;
            }
            $company = 0;
        }
        require_once "./src/views/viewQr.php";
    }

    /**
     * Muestra la página de archivo
     */
    public static function filePage($file)
    {   
        $data = new BaseModel();
        $filedata = $data->select("reg_archive", "id = ".$file);
        if(!$filedata){
            echo "Esta página ya no está disponible";
            exit;
        }
        $filedata = $filedata[0];

        if($filedata['id_company']){
            $company = $data->select("reg_company", "id = ".$filedata['id_company']);
            $company = $company[0];
        }else{
            $company = 0;
        }

        if($filedata['id_user']){
            $setting = $data->select("sys_setting", "id_user = ".$filedata['id_user']);
            if($setting){
                $setting = $setting[0];
            }else{
                $setting = 0;
            }
        }
        require_once "./src/views/filePage.php";
    }

    
}
