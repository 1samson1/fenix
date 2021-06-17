<?php
    class Response {
        public $response;
        public $errors;          

        public function set($value){  
            $this->response = $value;
        }

        public function set_error($text, $number){            
            $this->errors[]= array(
                'text' => $text,
                'error_num' => $number,
            ); 
        }

        public function set_error_if($condition, $text, $number){  
            if($condition){
                $this->set_error($text, $number);
            }
        } 

        public function get_response(){            
            return array(
                'response' => $this->response,
                'errors' => $this->errors,
            );
        }

        public function send(){
            echo json_encode($this->get_response());
        }
    }
?>
