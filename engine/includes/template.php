<?php
    class Template{

        public $dir = '';
        public $template = null;
        public $copy_template = null;
        public $data = array();
        public $data_block = array();

        public function __construct(){
            global $config;

            $this->dir = ROOT_DIR.'/templates/'.$config['template'];
        }
        
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

            $this->template = preg_replace_callback('/\[((not-)group|group)=?([0-9]*)?\](.*)\[\/\1\]/s', array(&$this, 'check_group'), $this->template);            
        }    

        public function check_group($matches){
            if($matches[2]){
                if($_SESSION['user']['group_id']) return '';
                else return $matches[4];
            }
            else {
                if($matches[3]){
                    if($_SESSION['user']['group_id'] == $matches[3]) return $matches[4];
                    else return '';
                }
                else {
                    if(!$_SESSION['user']['group_id']) return '';
                    else return $matches[4];
                }
            }
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
            $template = $this->replace_tags($template);    
            $template = $this->replace_block($template);    

            return $template;
        }
        
        public function copy_tpl(){
            $this->copy_template .= $this->replace_all($this->template);            
        }

        public function save($tag){
            $this->template = $this->replace_all($this->template);       
            $this->data[$tag] = $this->template;
            $this->clear();
        }

        public function save_copy($tag){
            $this->template = $this->copy_template;
            $this->data[$tag] = $this->template;
            $this->clear();
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
