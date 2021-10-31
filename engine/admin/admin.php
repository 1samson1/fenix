<?php

    $tpl = new Template(ADMIN_DIR . 'skin' . DS); // Создание экземпляра шаблонизатора

    define('SKIN_DIR', $tpl->dir); // Задание директории шаблонов

    Store::set('SKIN', webPath($tpl->dir));
    
    require_once ADMIN_DIR.'modules/auth.php'; // Подключает файл авторизации

    require_once ADMIN_DIR.'data/modules.php'; // Подключает файл доступных модулей
    
    require_once ADMIN_DIR.'includes/modules.php'; // Подключает файл класса модулей

    require_once ADMIN_DIR.'modules/msg.php'; // Подключает файл системных сообщений

    require_once ADMIN_DIR.'includes/breadcrumbs.php'; // Подключает файл класса хлебныхкрошек

    $crumbs = new BreadCrumbs('Главная', ADMIN_URL);


    if(!Store::isset('USER') ){
        $tpl->save('content', 'login');
    }
    else{

        if (isset($_GET['mod']) and Modules::check($_GET['mod'], $modules)) {

            Modules::load(ADMIN_DIR . 'modules' . DS , $_GET['mod']);
            
        } else{
            
            require_once ADMIN_DIR . 'modules/main.php';
    
        }
    }

    /* LOAD BREADCRUMBS TEMPLATE ======================================== */

    require_once ADMIN_DIR.'modules/breadcrumbs.php';

    /* LOAD ALERTS TEMPLATE ======================================== */

    $tpl->save('alerts', 'alerts', ['alerts' => $alerts->all()]);    

    /* LOAD BASE TEMPLATE ========================================= */    

    require_once ADMIN_DIR . 'modules/base.php';

?>
