<?php

    class Store {
        
        private static $vars = [];        

        public static function set($key, $value){
            
            if(isset(self::$vars[$key])){
                return new Exception("This key is exist ($key)");
            }

            self::$vars[$key] = $value;
        }

        public static function get($keyset){
            $data = self::$vars;

            if(strpos($keyset, '.' ) === false)
                return isset($data[$keyset]) ? $data[$keyset]: null;

            $keylist = explode('.', $keyset);

            foreach ($keylist as $key){
                if($key === 'length')
                    return count($data);

                if(!isset($data[$key]))
                    return null;
                
                $data = $data[$key];
                
            }

            return $data;
        }

        public static function all(){
            return self::$vars;
        }

        public static function remove($key){
            unset(self::$vars[$key]);
        }

        public static function load($data_name){
            $data_name = strtolower($data_name);
            $file = ENGINE_DIR . DS . 'data' . DS . $data_name . '.php';

            if(file_exists($file)){
                return self::set($data_name , include ($file));
            }

            return self::set($data_name , null);;
        }

    }
?>
