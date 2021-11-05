<?php 
    define("API_ON",true);
    define('DS', DIRECTORY_SEPARATOR);
    define("API_DIR",dirname(__FILE__) . DS);
    define("ENGINE_DIR", dirname(API_DIR) . DS);
    define('ROOT_DIR', dirname(ENGINE_DIR) . DS);

    header('Content-Type: application/json');      
    
    require_once ENGINE_DIR. 'includes/store.php'; // Подключение глобального хранилища

    require_once ENGINE_DIR. 'includes/functions.php'; // Подключаем важные функции 
    
    Store::load('config');
    
    ini_set('date.timezone', Store::get('config.timezone')); // Инициализания часового пояса

    require_once ENGINE_DIR.'includes/db.php'; // Подключаем файл класса базы данных
    
    require_once ENGINE_DIR.'data/dbconfig.php'; // Подключаем конфиг базы данных
    
    require_once ENGINE_DIR.'includes/response.php'; // Подключаем файл класса ответа в формате json

    $response = new Response(); // Экземпляр ответа в формате json

    require_once ENGINE_DIR.'modules/auth.php'; // Подключаем модуль авторизации

    if (file_exists(API_DIR. DS .$_GET['do'].'.php') && $_GET['do'] != 'init') {

        require_once API_DIR . DS . $_GET['do'].'.php';
        
    } else {
        
        require_once API_DIR . DS . '404.php';

    }
    

?>
