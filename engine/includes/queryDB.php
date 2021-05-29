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
                SELECT `doctors`.`id`, `doctors`.`name`, `doctors`.`foto`, `doctors`.`kabinet`, `specialties`.`title` AS `specialty` FROM `doctors` 
                    INNER JOIN `specialties` ON `specialties`.`id` = `doctors`.`specialty_id`
                    WHERE `doctors`.`specialty_id` = '.$this->ecran_html($id).'
            ;');
        }

        public function get_doctor_by_id($id){
            return $this->query('
                SELECT `doctors`.*, `specialties`.`title` AS `specialty`, `specialties`.`id` AS `specialty_id` FROM `doctors` 
                    INNER JOIN `specialties` ON `specialties`.`id` = `doctors`.`specialty_id`
                    WHERE `doctors`.`id` = '.$this->ecran_html($id).'
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
                    `news`.`short_news` AS `body`,
                    (SELECT COUNT(*) FROM `comments` WHERE `comments`.`news_id` = `news`.`id`) AS `count_comments`
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
                    `news`.`full_news` AS `body`,
                    (SELECT COUNT(*) FROM `comments` WHERE `comments`.`news_id` = `news`.`id`) AS `count_comments`
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

        /* Module static */

        public function get_statics(){
            return $this->query('
                SELECT `id`, `title`, `url`, `date_edit`, `date` FROM `static`
            ;');
        }

        public function get_static_by_id($id){
            return $this->query('
                SELECT `id`, `title`, `url`, `template` FROM `static` WHERE `id` = '.$this->ecran_html($id).'
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

        /* Module users*/

        public function get_users(){
            return $this->query('
                SELECT 
                    `users`.`id`,
                    `users`.`login`,
                    `groups`.`group_name`,
                    `groups`.`id` AS `group_id`,
                    `users`.`surname`,
                    `users`.`name`,
                    `users`.`date_reg`
                FROM `users`
                    INNER JOIN `groups` ON `users`.`group_id` = `groups`.`id` 
                    ORDER BY `group_id` ASC
            ;');
        }

        public function get_user_by_id($id){
            return $this->query('
                SELECT 
                    `users`.*,
                    `groups`.`group_name`,
                    `groups`.`id` AS `group_id`
                FROM `users`
                    INNER JOIN `groups` ON `users`.`group_id` = `groups`.`id`
                    WHERE `users`.`id` = "'.$this->ecran_html($id).'"
            ;');
        }       

        public function edit_user($user_id, $group_id, $name, $surname, $login, $email, $pass, $foto=false, $delete_foto=false){
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
                        `users`.`email` = "'.$this->ecran_html($email).'",
                        `users`.`group_id` = "'.$this->ecran_html($group_id).'" 
                        '.$pass.'
                        '.$foto.'
                    WHERE `users`.`id` = "'.$this->ecran_html($user_id).'"
            ;');
        }

        public function remove_user($id){
            return $this->query('
                DELETE FROM `users`
                    WHERE `id` = "'.$this->ecran_html( $id).'"
            ;');
        }

        /* Module news*/

        public function get_news(){
            return $this->query('
                SELECT 
                    `news`.`id`,
                    `news`.`title`,
                    `users`.`login` AS `autor`,
                    `news`.`date_edit`,
                    `news`.`date`
                FROM `news`
                    INNER JOIN `users` ON `news`.`autor` = `users`.`id`
                    ORDER BY `news`.`date` DESC
            ;');
        }

        public function get_news_by_id($id){
            return $this->query('
                SELECT `id`, `title`, `short_news`, `full_news` FROM `news` WHERE `id` = '.$this->ecran_html($id).'
            ;');
        }

        public function add_news($autor, $title, $short_news, $full_news, $date_edit, $date){
            return $this->query('
                INSERT INTO `news` (`autor`, `title`, `short_news`, `full_news`, `date_edit`, `date`) 
                    VALUES ("'.$this->ecran_html($autor).'", "'.$this->ecran_html($title).'", "'.$this->ecran($short_news).'", "'.$this->ecran($full_news).'", '.$this->ecran_html($date_edit).', '.$this->ecran_html($date).')
            ;');
        }

        public function edit_news($id, $title, $short_news, $full_news, $date_edit){
            return $this->query('
                UPDATE `news` 
                    SET                          
                        `title` = "'.$this->ecran_html($title).'",
                        `short_news` = "'.$this->ecran($short_news).'",
                        `full_news` = "'.$this->ecran($full_news).'",
                        `date_edit` = "'.$this->ecran_html($date_edit).'"
                    WHERE `id` = "'.$this->ecran_html($id).'"
            ;');
        }

        public function remove_news($id){
            return $this->query('
                DELETE FROM `news`
                    WHERE `id` = "'.$this->ecran_html( $id).'"
            ;');
        }

        /* Module comments*/

        public function get_comments(){
            return $this->query('
                SELECT 
                    `comments`.`id`,
                    `users`.`login` AS `autor`,
                    `news`.`title` AS `news`,
                    `news`.`id` AS `news_id`,
                    `comments`.`text`,
                    `comments`.`date`
                FROM `comments`
                    INNER JOIN `news` ON `news`.`id` = `comments`.`news_id`
                    INNER JOIN `users` ON `users`.`id` = `comments`.`user_id`
                    ORDER BY `comments`.`date` DESC
            ;');
        }

        public function remove_comment($id){
            return $this->query('
                DELETE FROM `comments`
                    WHERE `id` = "'.$this->ecran_html( $id).'"
            ;');
        }

        /* Module specialties */

        public function get_specialties_mod(){
            return $this->query('
                SELECT `id`, `title` FROM `specialties`        
            ;');
        }

        public function get_specialty_by_id($id){
            return $this->query('
                SELECT `id`, `title`, `description`, `image` FROM `specialties` WHERE `id` = '.$this->ecran_html($id).'
            ;');
        }

        public function add_specialty($title, $description){
            return $this->query('
                INSERT INTO `specialties` (`title`, `description`) 
                    VALUES ("'.$this->ecran_html($title).'", "'.$this->ecran($description).'")
            ;');
        }

        public function edit_specialty($id, $title, $description, $image){
            if($image){
                $image = ', `image` = "'.$image.'"';
            }
            else{
                $image = '';
            }
            return $this->query('
                UPDATE `specialties` 
                    SET                          
                        `title` = "'.$this->ecran_html($title).'",
                        `description` = "'.$this->ecran($description).'"
                        '.$image.'
                    WHERE `id` = "'.$this->ecran_html($id).'"
            ;');
        }

        public function remove_specialty($id){
            return $this->query('
                DELETE FROM `specialties`
                    WHERE `id` = "'.$this->ecran_html( $id).'"
            ;');
        }

        /* Module doctors */

        public function get_doctors(){
            return $this->query('
                SELECT 
                    `doctors`.`id`,
                    `doctors`.`name`,
                    `specialties`.`title` AS `specialty`,
                    `doctors`.`kabinet` 
                FROM `doctors` 
                    INNER JOIN `specialties` ON `specialties`.`id` = `doctors`.`specialty_id`    
            ;');
        }

       public function add_doctor($name, $specialty_id, $kabinet, $mon, $tue, $wed, $thu, $fri, $sat, $sun){
            return $this->query('
                INSERT INTO `doctors` (`name`, `specialty_id`, `kabinet`, `sun`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`) 
                    VALUES (
                        "'.$this->ecran_html($name).'",
                        "'.$this->ecran_html($specialty_id).'",
                        "'.intval($this->ecran_html($kabinet)).'",
                        "'.$this->bool_to_sql($sun).'",
                        "'.$this->bool_to_sql($mon).'",
                        "'.$this->bool_to_sql($tue).'",
                        "'.$this->bool_to_sql($wed).'",
                        "'.$this->bool_to_sql($thu).'",
                        "'.$this->bool_to_sql($fri).'",
                        "'.$this->bool_to_sql($sat).'"
                    )
            ;');
        }       
        
        public function edit_doctor($id, $name, $specialty_id, $foto, $kabinet, $mon, $tue, $wed, $thu, $fri, $sat, $sun){
            if($foto){
                $foto = ', `foto` = "'.$foto.'"';
            }
            else{
                $foto = '';
            }
            return $this->query('
                UPDATE `doctors` 
                    SET
                        `name` = "'.$this->ecran_html($name).'",
                        `specialty_id` = "'.$this->ecran_html($specialty_id).'",
                        `kabinet` = "'.intval($this->ecran_html($kabinet)).'",
                        `sun` = "'.$this->bool_to_sql($sun).'",
                        `mon` = "'.$this->bool_to_sql($mon).'",
                        `tue` = "'.$this->bool_to_sql($tue).'",
                        `wed` = "'.$this->bool_to_sql($wed).'",
                        `thu` = "'.$this->bool_to_sql($thu).'",
                        `fri` = "'.$this->bool_to_sql($fri).'",
                        `sat` = "'.$this->bool_to_sql($sat).'"
                        '.$foto.'
                    WHERE `id` = "'.$this->ecran_html($id).'"
            ;');
        }

        public function remove_doctor($id){
            return $this->query('
                DELETE FROM `doctors`
                    WHERE `id` = "'.$this->ecran_html( $id).'"
            ;');
        }

        /* Module recdoc */

        public function get_recdoc(){
            return $this->query('
                SELECT 
                    `users`.`name`,
                    `users`.`surname`,
                    `doctors`.`name` AS `doctor_name`,
                    `specialties`.`title` AS `doctor_specialty`,
                    `recdoctor`.`time`
                FROM `recdoctor`
                    INNER JOIN `users` ON `recdoctor`.`user_id` = `users`.`id`
                    INNER JOIN `doctors` ON `recdoctor`.`doctor_id` = `doctors`.`id`
                    INNER JOIN `specialties` ON `specialties`.`id` = `doctors`.`specialty_id`
                    ORDER BY `recdoctor`.`time` DESC
            ;');
        }
    }
?>
