<?php
	define("ENGINE_ON",true);
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT_DIR', dirname(__FILE__) . DS);
    define("ENGINE_DIR", ROOT_DIR . 'engine' . DS);
    define("ADMIN_DIR", ENGINE_DIR.'admin' . DS);
    define("ADMIN_URL", '/admin/');

    require_once ENGINE_DIR.'init.php';

	require_once ADMIN_DIR.'admin.php';

?>
