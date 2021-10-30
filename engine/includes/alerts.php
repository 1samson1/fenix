<?php 
    class Alerts{

        private $alerts = array();

        public function set_error($title, $text, $number){            
            $this->alerts[]= array(
                'title' => $title,
                'text' => $text,
                'type' => 'error',
                'error_num' => $number,
            ); 
        }

        public function set_error_if($condition, $title, $text, $number){  
            if($condition){
                $this->set_error($title, $text, $number);
            }
        } 

        public function set_success($title, $text){            
            $this->alerts[]= array(
                'title' => $title,
                'text' => $text,
                'type' => 'success',    
            ); 
        }

        public function all(){
            return $this->alerts;
        }

        public function merge($alerts){
            $this->alerts = array_merge($this->alerts, $alerts);
        }

        public function is_empty(){
            return !isset($this->alerts[0]);
        }

        public function set_success_if($condition, $title, $text){  
            if($condition){
                $this->set_success($title, $text);
            }
        } 

    }
?>
