<?php
    class Template{

        public $dir = '';
        public $template = null;
        public $copy_template = null;
        public $data = array();
        public $data_block = array();
        
        public function set($tag,$value){        
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

        public function set_block($block,$value){    
            if( is_array($value ) ) {
                if( count($value ) ) {
                    foreach ($value as $key => $key_var ) {
                        $this->set_block( $key, $key_var );
                    }
                }
                return;
            }
            
            $this->data_block[$block] = $value;
        }

        public function load_tpl($tpl_name){
            $file_path = $this->dir."/".$tpl_name;

            if (file_exists($file_path)){
                $this->template = file_get_contents($file_path);
            }
            else die('fatal error!');
        }    

        public function replace_tags($template){
            foreach($this->data as $tag => $value){
                $template = str_replace($tag, $value, $template);
                unset($this->data_block[$tag]);
            }

            return $template;
        }

        public function replace_block($template){
            foreach($this->data_block as $block => $value){
                $template = preg_replace($block, $value, $template);
                unset($this->data_block[$block]);
            }

            return $template;
        }

        public function replace_all($template){
            $template = $this->replace_block($template);    
            $template = $this->replace_tags($template);    

            return $template;
        }
        
        public function copy_tpl(){
            $this->copy_template .= $this->replace_all($this->template);            
        }


        public function save($tag){
            $this->data[$tag] = $this->template;
            $this->clear();
        }

        public function save_copy($tag){
            $this->template = $this->copy_template;
            $this->save($tag);
        }

        public function clear(){
            $this->template = null;
            $this->copy_template = null;
        }
        
        public function print(){
            $this->template = $this->replace_all($this->template);
            echo $this->template;
        }
    }
?>
