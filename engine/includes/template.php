<?php

    class Template{

        public $dir = '';
        public $save_data = array();

        public function __construct($dir = false){
            $this->dir = $dir;
        }

        public function load($tpl_name, $data = [], $dir = null){

            if(strpos($tpl_name, '.' ) === false)
                $tpl_name = $tpl_name . '.html';

            if($dir) $file_path = $dir . DS . $tpl_name;
            else $file_path = $this->dir . DS . $tpl_name;

            if (file_exists($file_path)){                
                return $this->render(
                    file_get_contents($file_path),
                    $data
                );
            }
            else die("Fatal error! No such file ( $file_path ) template!");

        }

        public function render($tpl, $data){
            $tpl = preg_replace_callback(
                '/\[\s*([A-z]+)([^]]*)\]((?:(?R)|[^\[\]]++|[\[\]])*?)\[\s*\/\1\s*\]/s',
                function ($matches) use ($data){
                    return $this->replace_block(
                        $matches[1],
                        $this->split(' ', trim($matches[2])),
                        $matches[3],
                        $data
                    );
                },
                $tpl
            );

            $tpl = $this->replace_tags($tpl);

            $tpl = preg_replace_callback(
                '/\{([^\{\}]*)\}/m',
                function ($matches) use ($data){
                    return $this->replace(
                        $this->split(' ',  trim($matches[1]) ),
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
                        (isset($params[0]) ? $this->split_or($data, $params[0]) : null),
                        $operator,
                        (isset($params[2]) ? $this->split_or($data, $params[2]) : null)
                    )){
                        return $body;
                    }
                    return false;
                
                case 'for':
                    return $this->for_in(
                        $this->split_dot($data, $params[2]),
                        $params[0],
                        $body
                    );

                case 'block':
                    $this->set_block($params[0], $body);
                    return false;
                    
            }
        }

        public function replace_tags($template){
            foreach($this->save_data as $tag => $value){
                if( strpos($template, $tag)  !== false ){
                    $template = str_replace($tag, $value, $template);
                    unset($this->save_data[$tag]);
                }                
            }

            return $template;
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
                
                case 'filter':
                    return $this->filter(
                        $params[3],
                        $this->split_dot($data, $params[1]),
                        array_slice($params, 4)
                    );

                default:
                    return $this->split_dot($data, $params[0]);
            }
        }

        public function if($first, $operator, $second){
            switch($operator){                
                case '=':
                    return $first == $second;

                case '>':
                    return $first > $second;

                case '>=':
                    return $first >= $second;

                case '<':
                    return $first < $second;

                case '<=':
                    return $first <= $second;

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

        public function filter($filter, $value, $params){
            switch ($filter) {
                case 'date':
                    return date(
                        substr($params[0], 1, -1),
                        $value
                    );
            }
        }

        public function split_or($data, $keyset){
            if (strpos($keyset, '"') !== false){
                return str_replace('"', '', $keyset);
            }

            $value = $this->split_dot($data, $keyset);

            if($value === null){
                return $keyset;
            }

            return $value;
        }

        public function split_dot($data, $keyset){
            if(strpos($keyset, '.' ) === false)
                return isset($data[$keyset]) ? $data[$keyset]: null;

            $keylist = explode('.', $keyset);
            
            $temp = $data;

            foreach ($keylist as $key){
                if($key === 'length')
                    return count($temp);

                if(!isset($temp[$key]))
                    return null;
                
                $temp = $temp[$key];
                
            }

            return $temp;
        }

        public function split($separator, $string){
            $tok = strtok($string, $separator);   
            $long_tok = null;
            $parts = array();
        
            while ($tok !== false) {
                if(strpos( $tok, '"') === false or substr_count($tok, '"') > 1){
                    if( $long_tok === null )
                        $parts[]= $tok;
                    else
                        $long_tok .= ' '.$tok;
                }
                else{
                    if( $long_tok === null ){
                        $long_tok = $tok;
                    }
                    else{
                        $parts[]= $long_tok.' '.$tok;
                        $long_tok = null;
                    }
                }
                $tok = strtok($separator);
            }
    
            return $parts;
        }

        public function set($tag, $value = ''){
            $tag = '{'.$tag.'}';
            $this->save_data[$tag] = $value;
        }

        public function save($tag, $tpl_name, $data = [], $dir_name= null){
            $data = array_merge($data, Store::all());

            $this->set($tag, $this->load($tpl_name, $data, $dir_name));
        }
        
        public function print($tpl_name, $data = [], $dir_name= null){
            $data = array_merge($data, Store::all());
            
            echo $this->load($tpl_name, $data, $dir_name);
        }         
    }
?>
