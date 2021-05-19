<?php
    require_once ENGINE_DIR.'/includes/checkFeild.php';    

    if(!$_COOKIE['user_token']){
        if(isset($_POST['do_login'])){

            $alerts->set_error_if(!CheckField::login($_POST['login']), 'Ошибка авторизации', 'Некорректный логин', 201);

            $alerts->set_error_if(!CheckField::empty($_POST['password']), 'Ошибка авторизации', 'Вы не ввели пароль', 203);  

            if(!isset($alerts->alerts_array[0])){

                $db->check_user($_POST['login']);
                if($user = $db->get_row()){
                    if (CheckField::confirm_hash($_POST['password'], $user['password'])) {
                        unset($user['password']);
                        $token = $db->hash(time());
                        $db->add_token($user['id'], $token);
                        
                        if(!$db->error){
                            setcookie('user_token', $token, time() + $config['life_time_token'], '/');
                            $_SESSION['user']= $user;
                        }
                        else $alerts->set_error('Ошибка авторизации', 'Не удалось выдать токен', 207);
                    }
                    else $alerts->set_error('Ошибка авторизации', 'Неправильный пароль от учётной записи', 205);
                    
                } else $alerts->set_error('Ошибка авторизации', 'Пользователя с таким именем нет!', 206);
            }
        }
    }
    else{
        $db->get_user_by_token($_COOKIE['user_token']);
        if($user = $db->get_row()){
            $_SESSION['user']= $user;
        } 
        else {            
            setcookie('user_token', '', 0, '/');
            unset($_SESSION['user']);
        }
    }    
?>
