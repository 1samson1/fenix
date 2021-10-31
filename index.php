<?php 
    define("ENGINE_ON",true);
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT_DIR', dirname(__FILE__) . DS);
    define("ENGINE_DIR", ROOT_DIR . 'engine' . DS);

    require_once ENGINE_DIR.'init.php';

    require_once ENGINE_DIR.'engine.php';


?>
