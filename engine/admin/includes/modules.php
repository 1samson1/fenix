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
            global $config, $tpl, $db, $head, $crumbs, $alerts;

            define('MODULE_DIR', $path . DS . $name);
            define('MODULE_SKIN_DIR', MODULE_DIR. 'skin' . DS);
            Store::set('MODULE_SKIN', webPath(MODULE_SKIN_DIR));
            define('MODULE_URL', '/admin/?mod='.$name);

            require_once ($path . DS . $name. DS . 'init.php');
        }
    }

?>
