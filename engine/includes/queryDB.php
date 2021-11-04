<?php
    require_once ENGINE_DIR.'/includes/db.php'; // Подключаем файл базового класса базы данных

    class QueryDB extends DataBase{

        /*////////////////// Query for users tools  ////////////////////*/

        public function reg_user($group_id, $name, $surname, $login, $email, $pass){            
            return $this->query('
                INSERT INTO `users` (`group_id`,`name`,`surname`,`login`,`email`,`password`,`date_reg`) 
                    VALUE ("'.$group_id.'","'.$this->ecran_html($name).'","'.$this->ecran_html($surname).'","'.$this->ecran_html($login).'","'.$this->ecran_html($email).'","'.$this->hash($this->ecran_html($pass)).'","'.time().'")
            ;');
        }
        
        public function check_user($login){
            return $this->query('
                SELECT `groups`.*, `users`.* FROM `users`
                    INNER JOIN `groups` ON `users`.`group_id` = `groups`.`id` 
                    WHERE `login` = "'.$this->ecran_html($login).'"
            ;');
        }

        public function update_user($user_id, $name, $surname, $login, $email, $pass, $foto=false, $delete_foto=false){
            $pass = isset($pass[0])?', `users`.`password` = "'.$this->hash($this->ecran_html($pass)).'"' :'';
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
                        `users`.`name` = "'.$this->ecran_html($name).'",
                        `users`.`surname` = "'.$this->ecran_html($surname).'",
                        `users`.`login` = "'.$this->ecran_html($login).'",
                        `users`.`email` = "'.$this->ecran_html($email).'" 
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
                SELECT `groups`.*, `users`.* FROM `user_tokens` 
                    INNER JOIN `users` ON `user_tokens`.`user_id` = `users`.`id`
                    INNER JOIN `groups` ON `users`.`group_id` = `groups`.`id`
                    WHERE `token` = "'.$this->ecran_html( $token).'"
            ;');
        }

        public function remove_token($token){
            return $this->query('
                DELETE FROM `user_tokens`
                    WHERE `token` = "'.$this->ecran_html( $token).'"
            ;');
        }

        public function remove_token_all($user_id,$token){
            return $this->query('
                DELETE FROM `user_tokens`
                    WHERE `user_id` = "'.$this->ecran_html( $user_id).'" AND `token` != "'.$this->ecran_html($token).'"
            ;');
        }

        /*............... Query for lostpassword ................*/

        public function get_user_by_email_or_login($lostpassword){
            return $this->query('
                SELECT * FROM `users` 
                    WHERE `login` = "'.$this->ecran_html($lostpassword).'" or `email` = "'.$this->ecran_html($lostpassword).'"
            ;');
        }

        public function get_lostpassword($token){
            return $this->query('
                SELECT * FROM `lostpassword`
                    INNER JOIN `users` ON `lostpassword`.`user_id` = `users`.`id`
                    WHERE `token` = "'.$this->ecran_html($token).'"
            ;');
        }

        public function change_password($user_id, $password){
            return $this->query('
                UPDATE `users`
                    SET
                        `users`.`password` = "'.$this->hash($this->ecran_html($password)).'"
                    WHERE `users`.`id` = '.$user_id.'
            ;');
        }

        public function add_lostpassword($user_id, $token, $date){
            return $this->query('
                INSERT INTO `lostpassword` (`user_id`, `token`, `date`) 
                    VALUES ('.$user_id.',"'.$token.'", "'.$date.'")
            ;');
        }

        public function remove_lostpassword($user_id){
            return $this->query('
                DELETE FROM `lostpassword`
                    WHERE `user_id` = "'.$this->ecran_html($user_id).'"
            ;');
        }

        /*////////////////// Query for recdoc page  ////////////////////*/

        public function get_specialties(){
            return $this->get_array( $this->query('
                SELECT * FROM `specialties`
            ;'));
        }
        
        public function get_doctors_by_specialty($id){
            return $this->get_array( $this->query('
                SELECT `doctors`.`id`, `doctors`.`name`, `doctors`.`foto`, `doctors`.`kabinet`, `specialties`.`title` AS `specialty` FROM `doctors` 
                    INNER JOIN `specialties` ON `specialties`.`id` = `doctors`.`specialty_id`
                    WHERE `doctors`.`specialty_id` = '.$this->ecran_html($id).'
            ;'));
        }

        public function get_doctor_by_id($id){
            return $this->query('
                SELECT `doctors`.*, `specialties`.`title` AS `specialty`, `specialties`.`id` AS `specialty_id` FROM `doctors` 
                    INNER JOIN `specialties` ON `specialties`.`id` = `doctors`.`specialty_id`
                    WHERE `doctors`.`id` = '.$this->ecran_html($id).'
            ;');
        }

        public function recording($doctor_id, $user_id, $appointment, $date, $time){
            return $this->query('
                INSERT INTO `recdoctor` (`time`, `doctor_id`, `user_id`, `appointment`) 
                    VALUES ('.strtotime($time,strtotime($date)).', '.$this->ecran_html($doctor_id).', '.$this->ecran_html($user_id).', "'.$appointment.'")
            ;');
        }

        /*////////////////// Query for news ////////////////////*/

        public function get_short_news($count, $begin=0){
            return $this->get_array( $this->query('
                SELECT 
                    `news`.`id`,
                    `users`.`login` AS `autor`, 
                    `news`.`title`,
                    `news`.`date`,
                    `news`.`short_news` AS `body`,
                    (SELECT COUNT(*) FROM `comments` WHERE `comments`.`news_id` = `news`.`id`) AS `count_comments`
                FROM `news`
                    INNER JOIN `users` ON `news`.`autor` = `users`.`id`
                    ORDER BY `news`.`date` DESC
                    LIMIT '.$begin.', '.$count.'
            ;'));
        }

        public function get_full_news($id){
            return $this->query('
                SELECT 
                    `news`.`id`,
                    `users`.`login` AS `autor`, 
                    `news`.`title`,
                    `news`.`date`,
                    `news`.`full_news` AS `body`
                FROM `news`
                    INNER JOIN `users` ON `news`.`autor` = `users`.`id`
                    WHERE `news`.`id` = '.$this->ecran_html($id).'
            ;');
        }

        /*////////////////// Query for comments  ////////////////////*/

        public function add_comment($news_id, $user_id, $text, $date){
            return $this->query('
                INSERT INTO `comments` (`news_id`, `user_id`, `text`, `date`) 
                    VALUES ('.$this->ecran_html($news_id).', '.$this->ecran_html($user_id).', "'.$this->ecran($text).'", '.$date.')
            ;');
        }

        public function get_comments_by_news_id($news_id, $count, $begin=0){
            return $this->get_array( $this->query('
                SELECT 
                    `comments`.`id`,
                    `users`.`name`, 
                    `users`.`surname`, 
                    `users`.`foto`, 
                    `comments`.`text`,
                    `comments`.`date`
                FROM `comments` 
                    INNER JOIN `users` ON `comments`.`user_id` = `users`.`id`
                    WHERE `comments`.`news_id` = '.$this->ecran_html($news_id).'
                    ORDER BY `comments`.`date` DESC
                    LIMIT '.$begin.', '.$count.'
            ;'));
        }

        /*////////////////// Query for pagination  ////////////////////*/         

         public function count_news(){
            return $this->query('
                SELECT count(*) as `count` FROM `news`
            ;');
        }

         public function count_comments_for_news($news_id){
            return $this->query('
                SELECT count(*) as `count` FROM `comments` WHERE `news_id` = "'.$this->ecran_html($news_id).'"
            ;');
        }

        /*////////////////// Query for static page  ////////////////////*/

        public function get_static($url){
            return $this->query('
                SELECT * FROM `static` WHERE `static`.`url` = "'.$this->ecran_html($url).'"
            ;');
        }
               
    }
?>
