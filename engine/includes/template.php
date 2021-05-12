<?php
    class Template{

        public $dir = '';
        public $template = null;
        public $copy_template = null;
        public $data = array();
        public $data_block = array();
        public $data_block_param = array();


        public function __construct(){
            global $config;

            $this->dir = ROOT_DIR.'/templates/'.$config['template'];
        }
        
        public function set($tag,$value){        
            $this->data[$tag] = $value;
        }

        public function set_block($block,$value){    
            $this->data_block[$block] = $value;
        }

        public function set_block_param($block,$param){    
            $this->data_block_param[$block] = $param;
        }

        public function load_tpl($tpl_name){
            $file_path = $this->dir."/".$tpl_name;

            if (file_exists($file_path)){
                $this->template = file_get_contents($file_path);
            }
            else die('fatal error!');

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
