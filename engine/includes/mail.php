<?php 
    class Mail {

        public static $headers = "";
        public $template = null;

        public function __construct($tpl_name, $data=null) {

            $this->template = $this->load($tpl_name, $data);
            
        }
        
        public function load($tpl_name, &$data=null){
            $file_path = ENGINE_DIR."/mails/".$tpl_name;

            if (file_exists($file_path)){
                
                return preg_replace_callback(
                    '/\{(.*)\}/sU',
                    function ($matches) use ($data){
                        return $this->replace( explode(' ',  trim($matches[1]) ), $data);
                    },
                    file_get_contents($file_path)
                );     
                
            }
            else die('Fatal error! No such file template!');
        }

        public function replace($params, &$data){
            
            switch($params[0]){
                case 'load':
                    return $this->load($params[1], $data);
                    break;

                default:
                    return $this->implode_dot($data, $params[0]);
            }

            
        }

        public function implode_dot(&$data, $keyset){
            if(strpos($keyset, '.' ) === false)
                return $data[$keyset];

            $keylist = explode('.', $keyset);
            
            $temp = &$data;

            foreach ($keylist as $key){
                $temp = &$temp[$key];
            }

            return $temp;
        }

        public static function set_headers($headers){
            self::$headers = self::_prepare_headers($headers);
        }

        public function send($to, $subject){
            if(is_array($to)){
                $to = implode(',', $to).',';
            }

            mail($to, $subject, $this->template, self::$headers);
        }

        private static function _prepare_headers($headers){
            $_headers = "";

            foreach ($headers as $key => $value){
                $_headers .= $key.': '.$value."\r\n";
            }
            
            return $_headers;
        }
    }
?>
