<?php 

class Layout{

    static function header($page = '',$stylesIn = ''){
        
        $stylesOut = '';
        if ($stylesIn != '' && is_array($stylesIn)){
            foreach ($stylesIn as $value) {
                $stylesOut .= '<link rel="stylesheet" href="'.$value.'?upd='.rand().'">';
            }
        }

        $data = new BaseModel();
        $dataSetting = $data->select("sys_setting", "id_user = ".$_SESSION['user_id']);

        if($dataSetting){
            $dataSetting = $dataSetting[0];
            $colors = '<style>
                    :root {
                        --primary:'.$dataSetting['color_primary'].';
                        --secondary: '.$dataSetting['color_secondary'].';
                        --tertiary:'.$dataSetting['color_tertiary'].';
                        --white: #fff;
                        --black: #000;
                        --lightfont: '.$dataSetting['color_font'].';
                        --font:'.$dataSetting['color_font2'].';
                    }
                </style>';
            $logourl = $dataSetting['logo'] ? './assets/img/userapp/logo/'.$dataSetting['logo'] : './assets/img/system/logo.png';
            $favicon = $dataSetting['favicon'] ? './assets/img/userapp/favicon/'.$dataSetting['favicon'] : './assets/img/system/favicon.png';
            $nameApp = $dataSetting['app_name'] ? $dataSetting['app_name'] : 'LiDMIN';
        }else{
            $colors = '<style>
                    :root {
                        --primary:#4F68FF;
                        --secondary: #8597FF;
                        --tertiary:#A1AEFF;
                        --white: #fff;
                        --black: #000;
                        --lightfont: #e4e7ff;
                        --font:#2E4AF3;
                    }
                </style>';
            $logourl = './assets/img/system/logo.png';
            $favicon = './assets/img/system/favicon.png';
            $nameApp = 'LiDMIN';
        }
        

        echo '
                <title>'.$nameApp.' | Dashboard</title>
                <link rel="stylesheet" href="./assets/css/app.css?upd='.rand().'">
                '.$colors.'
                <link rel="shortcut icon" href="'.$favicon.'" type="image/PNG">
                '.$stylesOut.'
                
            </head>
            <body>
            
                <div class="main">

                    <div class="sidebar">
                        <div class="logomenu">

                            <div class="logo">
                                <img src="'.$logourl.'" alt="logo">
                            </div>

                            <div class="menu">

                                <div class="menu-mobile">
                                    <button class="btn btn-dark" data-bs-toggle="collapse" data-bs-target="#menu-mobile"><i class="fa-solid fa-bars"></i></button>
                                    
                                </div>
                                
                                <nav>
                                    <a href="dashboard.php" class="'.(($page == '' || $page == 'dashboard') ? "active" : "").'">
                                        <li>
                                            <i class="fa-solid fa-gauge-high"></i>
                                            <span>Generar QR</span>
                                        </li>
                                    </a>
                                    <a href="dashboard.php?page=qr" class="'.($page == 'qr' ? "active" : "").'">
                                        <li>
                                            <i class="fa-solid fa-qrcode"></i>
                                            <span>QR generados</span>
                                        </li>
                                    </a>
                                    <a href="dashboard.php?page=empresas" class="'.($page == 'empresas' ? "active" : "").'">
                                        <li>
                                            <i class="fa-solid fa-building"></i>
                                            <span>Empresas</span>
                                        </li>
                                    </a>
                                    <a href="dashboard.php?page=archivos" class="'.($page == 'archivos' ? "active" : "").'">
                                        <li>
                                            <i class="fa-solid fa-file"></i>
                                            <span>Archivos</span>
                                        </li>
                                    </a>
                                    <a href="dashboard.php?page=config" class="'.($page == 'config' ? "active" : "").'">
                                        <li>
                                            <i class="fa-solid fa-gear"></i>
                                            <span>Configuración</span>
                                        </li>
                                    </a>
                                    <!---<a href="dashboard.php?page=usuarios" class="">
                                        <li>
                                            <i class="fa-solid fa-user"></i>
                                            <span>Usuarios</span>
                                        </li>
                                    </a>--->
                                </nav>
                            </div>

                        </div>

                        <div class="user">
                            '.$_SESSION['username'].' <a class="btn-logout" onclick="logout()">(Salir)</a>
                        </div>
                        
                    </div>

                    <div id="menu-mobile" class="collapse collapse-menu-mobile">
                        <nav>
                            <a href="dashboard.php" class="'.($page == '' ? "active" : "").'">
                                <li>
                                    <i class="fa-solid fa-gauge-high"></i>
                                    <span>Generar</span>
                                </li>
                            </a>
                            <a href="dashboard.php?page=qr" class="'.($page == 'qr' ? "active" : "").'">
                                <li>
                                    <i class="fa-solid fa-qrcode"></i>
                                    <span>QR generados</span>
                                </li>
                            </a>
                            <a href="dashboard.php?page=empresas" class="'.($page == 'empresas' ? "active" : "").'">
                                <li>
                                    <i class="fa-solid fa-building"></i>
                                    <span>Empresas</span>
                                </li>
                            </a>
                            <a href="dashboard.php?page=archivos" class="'.($page == 'archivos' ? "active" : "").'">
                                <li>
                                    <i class="fa-solid fa-file"></i>
                                    <span>Archivos</span>
                                </li>
                            </a>
                            <a href="dashboard.php?page=config" class="'.($page == 'config' ? "active" : "").'">
                                <li>
                                    <i class="fa-solid fa-gear"></i>
                                    <span>Configuración</span>
                                </li>
                            </a>
                            <!---<a href="dashboard.php?page=usuarios" class="">
                                <li>
                                    <i class="fa-solid fa-user"></i>
                                    <span>Usuarios</span>
                                </li>
                            </a>---->
                        </nav>
                    </div>

                    <div class="content">
        ';
    }

    static function footer($scriptsIn = ''){
        $scriptsOut = '';
        if ($scriptsIn != '' && is_array($scriptsIn)){
            foreach ($scriptsIn as $value) {
                $scriptsOut .= '<script src="'.$value.'"></script>';
            }
        }
        echo '
                    </div>

                    <div class="user-mobile">
                        '.$_SESSION['username'].' (<a class="btn-logout" onclick="logout()">Salir</a>)
                    </div>

                </div>
                <!--SCRIPTS-->
                <script src="./assets/js/datatable.js"></script>
                <script src="./assets/js/app.js?upd='.rand().'"></script>
                '.$scriptsOut.'
        ';
    }
}