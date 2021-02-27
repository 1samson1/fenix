<?php
    class Error_info{
        static public function reg_user($err_num){
            switch ($err_num) {

                case 1062: return 'Пользователь с такими данными уже существует.';
                
                default: return 'Ошибка регистрации попробуйте снова.';
                   
            }
        }        
    }
?>
