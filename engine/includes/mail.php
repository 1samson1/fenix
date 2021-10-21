<?php 
    class Mail {

        public static $headers = "";
        public $template = null;

        public function __construct($tpl_name, $data=null) {

            $this->template = $this->load($tpl_name, $data);
            
        }

        public function load($tpl_name, $data = null){

            $file_path = ENGINE_DIR."/mails/".$tpl_name;

            if (file_exists($file_path)){                
                return $this->render(
                    file_get_contents($file_path),
                    $data
                );
            }
            else die('Fatal error! No such file template!');

        }

        public function render($tpl, $data){
            $tpl = preg_replace_callback(
                '/\[\s*([A-z]+)([^]]*)\]((?:(?R)|[^\[\]]++|[\[\]])*?)\[\s*\/\1\s*\]/s',
                function ($matches) use ($data){
                    return $this->replace_block(
                        $matches[1],
                        explode(' ', trim($matches[2])),
                        $matches[3],
                        $data
                    );
                },
                $tpl
            );

            $tpl = preg_replace_callback(
                '/\{([^\{\}]*)\}/m',
                function ($matches) use ($data){
                    return $this->replace(
                        explode(' ',  trim($matches[1]) ),
                        $data
                    );
                },
                $tpl
            );     

            return $tpl;
        }
        
        function replace_block($block, $params, $body, $data){
            switch($block){
                case 'if':
                    if(isset($params[1])){
                        $operator = $params[1];
                    } else {
                        if(strpos($params[0], '!') === false){
                            $operator = 'equal';
                        } else {
                            $params[0] = substr($params[0],1);
                            $operator = 'notequal';
                        }
                    }

                    if($this->if(
                        $this->explode_or($data, $params[0]),
                        $operator,
                        $this->explode_or($data, $params[2])
                    )){
                        return $body;
                    }
                    return false;
                
                case 'for':
                    return $this->for_in(
                        $this->explode_dot($data, $params[2]),
                        $params[0],
                        $body
                    );

                case 'block':
                    $this->set_block($params[0], $body);
                    return false;
                    
            }
        }

        public function replace($params, $data){
            switch($params[0]){
                case 'load':
                case 'extends':
                    return $this->load($params[1], $data);
                
                case 'insert':
                    $tpl = $this->render(
                        (isset($this->data_block[$params[1]]) ? $this->data_block[$params[1]] : ''),
                        $data
                    );
                    unset($this->data_block[$params[1]]);

                    return $tpl;

                default:
                    return $this->explode_dot($data, $params[0]);
            }
        }

        public function if($first, $operator, $second){
            switch($operator){                
                case '=':
                    return $first == $second;

                case '!=':
                    return $first != $second;

                case 'equal':
                    return $first == true;

                case 'notequal':
                    return $first == false;
            }
        }

        public function for_in($array, $value, $body){
            $tpl = '';

            foreach($array as $elm){
                $tpl .= $this->render(
                    $body,
                    array( $value => $elm)
                );
            }

            return $tpl;
        }

        public function set_block($block, $body){
            $this->data_block[$block] = $body;
        }

        public function explode_or($data, $keyset){
            if (strpos($keyset, '"') !== false or strpos($keyset, "'") !== false){
                return str_replace(array('"', "'"), '', $keyset);
            }

            $value = $this->explode_dot($data, $keyset);

            if($value === null){
                return $keyset;
            }

            return $value;
        }

        public function explode_dot($data, $keyset){
            if(strpos($keyset, '.' ) === false)
                return isset($data[$keyset]) ? $data[$keyset]: null;

            $keylist = explode('.', $keyset);
            
            $temp = $data;

            foreach ($keylist as $key){
                if(!isset($temp[$key])){
                    return null;
                }
                $temp = $temp[$key];
                
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