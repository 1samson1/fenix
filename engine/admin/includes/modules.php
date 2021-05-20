<?php

    class Modules{

        public static function check($name, $modules){
            foreach($modules as $module){
                if($module['name'] == $name){
                    return true;
                }
            }
            return false;
        }

        public static function load($path,$name){
            global $config, $tpl, $db, $alerts;

            define('MODULE_DIR', $path.'/'.$name);
            define('MODULE_SKIN_DIR', MODULE_DIR.'/skin');
            define('MODULE_URL', ADMIN_URL.'/modules/'.$name);

            require_once ($path.'/'.$name. '/init.php');
        }
    }

?>
