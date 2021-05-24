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
                SELECT `users`.*, `groups`.`group_name`, `groups`.`id` AS `group_id` FROM `users`
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
                SELECT `users`.*, `groups`.`group_name`, `groups`.`id` AS `group_id` FROM `user_tokens` 
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

        /*////////////////// Query for recdoc page  ////////////////////*/

        public function get_specialties(){
            return $this->query('
                SELECT * FROM `specialties`
            ;');
        }
        
        public function get_doctors_by_specialty($id){
            return $this->query('
                SELECT `doctors`.*, `specialties`.`title` AS `specialty` FROM `doctors` 
                    INNER JOIN `specialties` ON `specialties`.`id` = `doctors`.`specialty_id`
                    WHERE `doctors`.`specialty_id` = '.$this->ecran_html($id).'
            ;');
        }

        public function get_doctor_by_id($id){
            return $this->query('
                SELECT `doctors`.*, `specialties`.`title` AS `specialty` FROM `doctors` 
                    INNER JOIN `specialties` ON `specialties`.`id` = `doctors`.`specialty_id`
                    WHERE `doctors`.`id` = '.$this->ecran_html($id).'
            ;');
        }

        public function get_doctor_schedule_by_id($id){
            return $this->query('
                SELECT  `Sun`, `Mon`, `Tue`, `Wed`, `Thu`, `Fri`, `Sat` FROM `schedule` 
                   WHERE `schedule`.`id_doctor` = '.$this->ecran_html($id).'
            ;');
        }

        public function recording($doctor_id, $user_id, $date, $time){
            return $this->query('
                INSERT INTO `recdoctor` (`time`, `doctor_id`, `user_id`) 
                    VALUES ('.strtotime($time,strtotime($date)).', '.$this->ecran_html($doctor_id).', '.$this->ecran_html($user_id).')
            ;');
        }

        /*////////////////// Query for news ////////////////////*/

        public function get_short_news($count, $begin=0){
            return $this->query('
                SELECT 
                    `news`.`id`,
                    `users`.`login` AS `autor`, 
                    `news`.`title`,
                    `news`.`date`,
                    `news`.`short_news` AS `body` 
                FROM `news` 
                    INNER JOIN `users` ON `news`.`autor` = `users`.`id`
                    ORDER BY `news`.`date` DESC
                    LIMIT '.$begin.', '.$count.'
            ;');
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

        public function get_comments_by_news_id($id){
            return $this->query('
                SELECT 
                    `comments`.`id`,
                    `users`.`name`, 
                    `users`.`surname`, 
                    `users`.`foto`, 
                    `comments`.`text`,
                    `comments`.`date`
                FROM `comments` 
                    INNER JOIN `users` ON `comments`.`user_id` = `users`.`id`
                    WHERE `comments`.`news_id` = '.$this->ecran_html($id).'
                    ORDER BY `comments`.`date` DESC
            ;');
        }

        /*////////////////// Query for pagination  ////////////////////*/

        public function count_pages($table){
            return $this->query('
                SELECT count(*) as `count` FROM `'.$table.'`
            ;');
        }

        /*////////////////// Query for static page  ////////////////////*/

        public function get_static($url){
            return $this->query('
                SELECT * FROM `static` WHERE `static`.`url` = "'.$this->ecran_html($url).'"
            ;');
        }

        /*////////////////// Query for adminpanel ////////////////////*/

        public function get_groups(){
            return $this->query('
                SELECT * FROM `groups`
            ;');
        }

        public function get_statics(){
            return $this->query('
                SELECT `id`, `title`, `url`, `date_edit`, `date` FROM `static`
            ;');
        }

        public function get_static_by_id($id){
            return $this->query('
                SELECT `id`, `title`, `url`, `template` FROM `static` WHERE `id` = '.$this->ecran($id).'
            ;');
        }

        public function add_static($url, $title, $template, $date_edit, $date){
            return $this->query('
                INSERT INTO `static` (`url`, `title`, `template`, `date_edit`, `date`) 
                    VALUES ("'.$this->ecran_html($url).'", "'.$this->ecran_html($title).'", "'.$this->ecran($template).'", '.$this->ecran_html($date_edit).', '.$this->ecran_html($date).')
            ;');
        }

        public function edit_static($id, $url, $title, $template, $date_edit){
            return $this->query('
                UPDATE `static` 
                    SET  
                        `url` = "'.$this->ecran_html($url).'",
                        `title` = "'.$this->ecran_html($title).'",
                        `template` = "'.$this->ecran($template).'",
                        `date_edit` = "'.$this->ecran_html($date_edit).'"
                    WHERE `id` = "'.$id.'"
            ;');
        }

        public function remove_static($id){
            return $this->query('
                DELETE FROM `static`
                    WHERE `id` = "'.$this->ecran_html( $id).'"
            ;');
        }

    }
?>
