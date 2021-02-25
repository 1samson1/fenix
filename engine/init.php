<?php
    if(!defined("ENGINE_DIR")){
        define("ENGINE_DIR",dirname(__FILE__));
    }       
    
    require_once ENGINE_DIR.'/data/config.php'; // Подключаем глобальный конфиг
    
    ini_set('date.timezone', $config['timezone']);

    require_once ENGINE_DIR.'/includes/queryDB.php'; // Подключаем файл класса базы данных
    
    require_once ENGINE_DIR.'/data/dbconfig.php'; // Подключаем конфиг базы данных
    
    require_once ENGINE_DIR.'/includes/template.php';// Подключает файл класса шаблонизатора

    $tpl = new Template(); // Создание экземпляра шаблонизатора

    $tpl->dir = ROOT_DIR.'/templates/'.$config['template'];

    define('TEMPLATE_DIR', $tpl->dir); // Задание директории шаблонов

    require_once ENGINE_DIR.'/engine.php'; // Подключает файл движка
?>