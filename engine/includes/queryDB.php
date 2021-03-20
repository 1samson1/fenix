<?php
    require_once ENGINE_DIR.'/includes/db.php'; // Подключаем файл базового класса базы данных

    class QueryDB extends DataBase{

        /*////////////////// Query for users tools  ////////////////////*/

        public function reg_user($group_id, $name, $surname, $login, $email, $pass){            
            return $this->query('
                INSERT INTO `users` (`group_id`,`name`,`surname`,`login`,`email`,`password`,`date_reg`) 
                    VALUE ("'.$group_id.'","'.htmlspecialchars($name).'","'.htmlspecialchars($surname).'","'.htmlspecialchars($login).'","'.htmlspecialchars($email).'","'.$this->hash(htmlspecialchars($pass)).'","'.time().'")
            ;');
        }
        
        public function check_user($login){
            return $this->query('
                SELECT `users`.*, `groups`.`group_name`, `groups`.`id` AS `group_id` FROM `users`
                    INNER JOIN `groups` ON `users`.`group_id` = `groups`.`id` 
                    WHERE `login` = "'.htmlspecialchars($login).'"
            ;');
        }

        public function update_user($user_id, $name, $surname, $login, $email, $pass, $foto=false, $delete_foto=false){
            $pass = isset($pass[0])?', `users`.`password` = "'.$this->hash(htmlspecialchars($pass)).'"' :'';
            if($delete_foto){
                $foto = ', `users`.`foto` = ""';
            }
            else if($foto){
                $foto = ', `users`.`foto` = "'.$foto.'"';
            }
            else{
                $foto = '';
            }
            return $this->query('
                UPDATE `users`
                    SET  
                        `users`.`name` = "'.htmlspecialchars($name).'",
                        `users`.`surname` = "'.htmlspecialchars($surname).'",
                        `users`.`login` = "'.htmlspecialchars($login).'",
                        `users`.`email` = "'.htmlspecialchars($email).'" 
                        '.$pass.'
                        '.$foto.'
                    WHERE `users`.`id` = "'.$user_id.'"
            ;');
        }

        /*............... Query for work user's token ................*/

        public function add_token($user_id, $token){            
            return $this->query('
                INSERT INTO `user_tokens` (`user_id`, `token`, `date`) 
                    VALUES ('.$user_id.',"'.$token.'","'.time().'")
            ;');
        }  

        public function get_user_by_token($token){
            return $this->query('
                SELECT `users`.*, `groups`.`group_name`, `groups`.`id` AS `group_id` FROM `user_tokens` 
                    INNER JOIN `users` ON `user_tokens`.`user_id` = `users`.`id`
                    INNER JOIN `groups` ON `users`.`group_id` = `groups`.`id`
                    WHERE `token` = "'.htmlspecialchars( $token).'"
            ;');
        }

        public function remove_token($token){
            return $this->query('
                DELETE FROM `user_tokens`
                    WHERE `token` = "'.htmlspecialchars( $token).'"
            ;');
        }

        public function remove_token_all($user_id,$token){
            return $this->query('
                DELETE FROM `user_tokens`
                    WHERE `user_id` = "'.htmlspecialchars( $user_id).'" AND `token` != "'.htmlspecialchars($token).'"
            ;');
        }

    }
?>
