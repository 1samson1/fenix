<?php
    class Template{

        public $dir = '';
        public $template = null;
        public $data = array ();	   
        
        function set($tag,$value) {        
            if( is_array($value ) ) {
                if( count($value ) ) {
                    foreach ($value as $key => $key_var ) {
                        $this->set( $key, $key_var );
                    }
                }
                return;
            }
            
            $this->data[$tag] = $value;
        }

        public function load_tpl($tpl_name){
            $file_path = $this->dir."/".$tpl_name;

            if (file_exists($file_path)){
                $this->template = file_get_contents($file_path);
            }
            else die('fatal error!');
        }    

        public function save($tag){
            $this->data[$tag] = $this->template;
            $this->clear();
        }

        public function clear(){
            $this->template = null;
        }
        
        public function print(){
            foreach($this->data as $tag => $value){
                $this->template = str_replace($tag, $value, $this->template);
            }
                        
            echo $this->template;
        }
    }
?>
