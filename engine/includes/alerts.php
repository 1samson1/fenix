<?php 
    class Alerts{

        public $alerts_array = array();

        public function set_error($title, $text, $number){            
            $this->alerts_array[]= array(
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
            $this->alerts_array[]= array(
                'title' => $title,
                'text' => $text,
                'type' => 'success',    
            ); 
        }

        public function set_success_if($condition, $title, $text){  
            if($condition){
                $this->set_success($title, $text);
            }
        } 

    }
?>
