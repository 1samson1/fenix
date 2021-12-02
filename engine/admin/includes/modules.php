<?php

    class Modules{

        public static function check($name, $modules){
            return array_key_exists($name, $modules);
        }

        public static function load($path, $name, $modules){
            global $config, $tpl, $db, $crumbs, $alerts;

            define('MODULE_DIR', $path . $name . DS);
            define('MODULE_SKIN_DIR', MODULE_DIR. 'skin');
            Store::set('MODULE_SKIN', webPath(MODULE_SKIN_DIR));
            Store::set('ADMIN_URL', ADMIN_URL);
            define('MODULE_URL', '/admin/?mod='.$name);
            Store::set('MODULE_URL', MODULE_URL);
            $crumbs->add(Store::set('title', $modules[$name]['verbose_name']) , MODULE_URL);

            require_once ($path . DS . $name. DS . 'init.php');
        }
    }

?>
