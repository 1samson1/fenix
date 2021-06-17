<?php 
    define("API_ON",true);
    define("API_DIR",dirname(__FILE__));
    define("ENGINE_DIR", dirname(API_DIR));
    define('ROOT_DIR', dirname(ENGINE_DIR));

    header('Content-Type: application/json');  

    session_start();
    
    require_once ENGINE_DIR.'/data/config.php'; // Подключаем глобальный конфиг
    
    ini_set('date.timezone', $config['timezone']); // Инициализания часового пояса

    require_once ENGINE_DIR.'/includes/queryDB.php'; // Подключаем файл класса базы данных
    
    require_once ENGINE_DIR.'/data/dbconfig.php'; // Подключаем конфиг базы данных
    
    require_once ENGINE_DIR.'/includes/response.php'; // Подключаем файл класса ответа в формате json

    $response = new Response(); // Экземпляр ответа в формате json

    require_once ENGINE_DIR.'/modules/auth.php'; // Подключаем модуль авторизации

    if (file_exists(API_DIR.'/'.$_GET['do'].'.php') && $_GET['do'] != 'init') {

        require_once API_DIR .'/'. $_GET['do'].'.php';
        
    } else {
        
        require_once API_DIR . '/404.php';

    }
    

?>
