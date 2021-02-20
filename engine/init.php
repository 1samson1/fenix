<?php
    if(!defined("ENGINE_DIR")){
        define("ENGINE_DIR",dirname(__FILE__));
    }       
    
    require_once ENGINE_DIR.'/data/config.php'; // Подключаем глобальный конфиг
    
    require_once ENGINE_DIR.'/includes/queryDB.php'; // Подключаем файл класса базы данных
    
    require_once ENGINE_DIR.'/data/dbconfig.php'; // Подключаем конфиг базы данных
    
    ini_set('date.timezone', $config['timezone']);

    require_once ENGINE_DIR.'/engine.php'
?>