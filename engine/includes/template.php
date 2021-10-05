<?php

    require_once ENGINE_DIR.'/includes/functions.php'; 

    class Template{

        public $dir = '';
        public $repeat = null;
        public $repeat_block = null;
        public $template = null;
        public $copy_template = null;
        public $data = array();
        public $data_block = array();
        public $data_block_param = array();
        public $endlines = null;


        public function __construct($dir = false){
            global $config;

            if($dir){
                $this->dir = $dir;
            }
            else $this->dir = ROOT_DIR.'/templates/'.$config['template'];
        }
        
        public function set($tag,$value=''){        
            $this->data[$tag] = $value;
        }

        public function set_block($block, $value, $flags="sU"){    
            $block = '/\['.$block.'\](.*)\[\/'.$block.'\]/'.$flags;

            $this->data_block[$block] = $value;
        }

        public function set_repeat_block($block, $flags="sU"){

            $block = '/\['.$block.'\](.*)\[\/'.$block.'\]/'.$flags;

            if(preg_match($block, $this->template, $matches)){
                $this->repeat = $block;
                $this->repeat_block = $matches[1];
            }
        }

        public function set_block_param($block,$param){    
            $this->data_block_param[$block] = $param;
        }

        public function load($tpl_name, $dir = false){

            if($dir) $file_path = $dir."/".$tpl_name;
            else $file_path = $this->dir."/".$tpl_name;
            
            if (file_exists($file_path)) $this->template = file_get_contents($file_path);
            else die('Fatal error! No such file template!');

            $this->check_group();
        }

        public function replace_tags($template){
            foreach($this->data as $tag => $value){
                if( strpos($template, $tag)  !== false ){
                    $template = str_replace($tag, $value, $template);
                    unset($this->data[$tag]);
                }                
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

        public function replace_block_param($template){
            foreach($this->data_block_param as $block => $param){
                $this->block_param = $param;
                $template = preg_replace_callback(
                    $block, 
                    function ($matches) use ($param){
                        if ($matches[1] == $param){
                            return $matches[2];
                        }
                        return '';
                    },
                    $template
                );            
                unset($this->data_block_param[$block]);
            }

            return $template;
        }

        public function replace_all($template){
            $template = $this->replace_tags($template);    
            $template = $this->replace_block($template);    
            $template = $this->replace_block_param($template);    

            return $template;
        }        

        public function append($value){
            $this->endlines = $value;
        }
        
        public function copy_tpl(){
            $this->copy_template .= $this->replace_all($this->template);            
        }

        public function copy_repeat_block(){
            $this->copy_template .= $this->replace_all($this->repeat_block);            
        }

        public function save($tag){
            if( strpos( $this->template, "{custom" ) !== false ) {	
                $this->custom();
            }
            $this->template = $this->replace_all($this->template);       
            $this->template .= $this->endlines;
            $this->data[$tag] = $this->template;
            $this->clear();
        }

        public function save_copy($tag){
            $this->template = $this->copy_template;
            $this->template .= $this->endlines;
            $this->data[$tag] = $this->template;
            $this->clear();
        }

        public function save_repeat_block(){
            $this->template .= $this->endlines;
            if($this->repeat){
                $this->template = preg_replace($this->repeat, $this->copy_template, $this->template);
            }
            $this->repeat = null;
            $this->repeat_block = null;
            $this->copy_template = null;
            $this->endlines = null;
            $this->temp = null;
        }

        public function clear(){
            $this->template = null;
            $this->copy_template = null;
            $this->endlines = null;
            $this->temp = null;
        }
        
        public function print(){
            $this->template = $this->replace_all($this->template);
            echo $this->template;
        }

        private function check_group(){
            $this->template = preg_replace_callback(
                '/\[((not-)group|group)=?([0-9]*)?\](.*)\[\/\1\]/s',
                function ($matches){
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
                },
                $this->template
            );     
        }

        private function custom(){
            $this->template = preg_replace_callback(
                '/\{custom(.+?)\}/is',
                'custom',
                $this->template
            );
        }
    }
?>
