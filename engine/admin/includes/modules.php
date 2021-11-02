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
            global $config, $tpl, $db, $crumbs, $alerts;

            define('MODULE_DIR', $path . $name . DS);
            define('MODULE_SKIN_DIR', MODULE_DIR. 'skin');
            Store::set('MODULE_SKIN', webPath(MODULE_SKIN_DIR));
            define('MODULE_URL', '/admin/?mod='.$name);
            Store::set('MODULE_URL', MODULE_URL);

            require_once ($path . DS . $name. DS . 'init.php');
        }
    }

?>
