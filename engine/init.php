<?php

    ini_set('display_errors', 1); // Показывать ошибки

    error_reporting(E_ALL); // Выводить все виды ошибок

    session_start();

    require_once ENGINE_DIR. 'includes/store.php'; // Подключение глобального хранилища

    require_once ENGINE_DIR. 'includes/functions.php'; // Подключаем важные функции 
    
    Store::load('config');
    
    ini_set('date.timezone', Store::get('config.timezone')); // Инициализания часового пояса

    require_once ENGINE_DIR.'includes/queryDB.php'; // Подключаем файл класса базы данных
    
    require_once ENGINE_DIR.'data/dbconfig.php'; // Подключаем конфиг базы данных
    
    require_once ENGINE_DIR.'includes/template.php';// Подключает файл класса шаблонизатора

    require_once ENGINE_DIR.'includes/alerts.php';// Подключает файл класса уведомлений

    $alerts = new Alerts();    
    
?>
