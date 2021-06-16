<?php

    define("ADMIN_URL", '/admin/');

    define("ADMIN_URL_STATIC", '/engine/admin');

    session_start();

    require_once ENGINE_DIR.'/data/config.php'; // Подключаем глобальный конфиг
    
    ini_set('date.timezone', $config['timezone']); // Инициализания часового пояса

    require_once ENGINE_DIR.'/includes/queryDB.php'; // Подключаем файл класса базы данных
    
    require_once ENGINE_DIR.'/data/dbconfig.php'; // Подключаем конфиг базы данных

    require_once ENGINE_DIR.'/includes/template.php';// Подключает файл класса шаблонизатора

    $tpl = new Template(ADMIN_DIR.'/skin'); // Создание экземпляра шаблонизатора

    define('SKIN_DIR', $tpl->dir); // Задание директории шаблонов
    
    require_once ENGINE_DIR.'/includes/alerts.php'; // Подключает файл класса уведомлений

    $alerts = new Alerts(); // Создание экземпляра менеджера уведомлений
    
    require_once ADMIN_DIR.'/modules/auth.php'; // Подключает файл авторизации

    require_once ADMIN_DIR.'/data/modules.php'; // Подключает файл класса модулей
    
    require_once ADMIN_DIR.'/includes/modules.php'; // Подключает файл класса модулей

    require_once ADMIN_DIR.'/includes/breadcrumbs.php'; // Подключает файл класса хлебныхкрошек

    $crumbs = new BreadCrumbs('Главная', ADMIN_URL);

    require_once ADMIN_DIR.'/modules/msg.php'; // Подключает файл системных сообщений

    if(!$_SESSION['user']['is_admin']){
        require_once (ADMIN_DIR . '/modules/login.php');
    }
    else{

        if (Modules::check($_GET['mod'], $modules)) {

            Modules::load(ADMIN_DIR . '/modules', $_GET['mod']);
            
        } else{
            
            require_once ADMIN_DIR . '/modules/main.php';
    
        }
    }

    /* LOAD HEAD FILE ======================================== */

    require_once ADMIN_DIR.'/modules/head.php';

    /* LOAD BREADCRUMBS TEMPLATE ======================================== */

    require_once ADMIN_DIR.'/modules/breadcrumbs.php';

    /* LOAD ALERTS TEMPLATE ======================================== */

    require_once ADMIN_DIR.'/modules/alerts.php';

    /* LOAD USERPANEL TEMPLATE ========================================= */

    require_once ADMIN_DIR.'/modules/userpanel.php';

    /* LOAD BASE TEMPLATE ========================================= */    

    require_once (ADMIN_DIR . '/modules/base.php');

?>
