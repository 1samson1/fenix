<?php
    // Конфиг базы данных

    define("DB_HOST","localhost");

    define("DB_USER","root");

    define("DB_PASS","");

    define("DB_NAME","fenix");

    $db = new DataBase(DB_HOST, DB_USER, DB_PASS, DB_NAME);
?>