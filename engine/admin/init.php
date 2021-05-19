<?php

    define("ADMIN_URL", '/engine/admin');

    session_start();

    require_once ENGINE_DIR.'/data/config.php'; // Подключаем глобальный конфиг
    
    ini_set('date.timezone', $config['timezone']);

    require_once ENGINE_DIR.'/includes/queryDB.php'; // Подключаем файл класса базы данных
    
    require_once ENGINE_DIR.'/data/dbconfig.php'; // Подключаем конфиг базы данных

    require_once ENGINE_DIR.'/includes/template.php';// Подключает файл класса шаблонизатора

    $tpl = new Template(ADMIN_DIR.'/skin'); // Создание экземпляра шаблонизатора

    define('SKIN_DIR', $tpl->dir); // Задание директории шаблонов
    
    require_once ENGINE_DIR.'/includes/alerts.php';// Подключает файл класса уведомлений

    $alerts = new Alerts();    

    require_once ADMIN_DIR.'/modules/auth.php';    

    if(!$is_logined){
        require_once (ADMIN_DIR . '/modules/login.php');
    }
    else{

        if (file_exists( ADMIN_DIR . '/modules/' . $_GET['mod'] . '.php')) {
    
            require_once (ADMIN_DIR . '/modules/' . $_GET['mod'] . '.php');
            
        } else{
            
            require_once (ADMIN_DIR . '/modules/main.php');
    
        }
    }

    /* LOAD ALERTS TEMPLATE ======================================== */

    require_once ADMIN_DIR.'/modules/alerts.php';

    /* LOAD USERPANEL TEMPLATE ========================================= */

    require_once ADMIN_DIR.'/modules/userpanel.php';

    /* LOAD BASE TEMPLATE ========================================= */    

    require_once (ADMIN_DIR . '/modules/base.php');

?>
