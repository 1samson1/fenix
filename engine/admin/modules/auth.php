<?php
    require_once ENGINE_DIR.'/includes/checkFeild.php';    

    function logout(){
        global $db;

        setcookie('user_token', '', 0, '/');
		if(isset($_COOKIE['user_token'])){
			$db->table('user_tokens')
				->where('token', '=', $_COOKIE['user_token'])
				->delete();
		}
		
        header('Location: /admin/');
        die();
    }

    if(isset($_GET['action']) and $_GET['action'] == 'logout'){
        logout();
    }

    if(!isset($_COOKIE['user_token'])){
        if(isset($_POST['do_login'])){

            $alerts->set_error_if(!CheckField::login($_POST['login']), 'Ошибка авторизации', 'Некорректный логин', 201);

            $alerts->set_error_if(!CheckField::empty($_POST['password']), 'Ошибка авторизации', 'Вы не ввели пароль', 203);  

            if($alerts->is_empty()){

                $user = $db->table('users')
                    ->select('groups.*', 'users.*')
                    ->join('groups', 'users.group_id', '=', 'groups.id')
                    ->where('users.login', '=', $_POST['login'])
                    ->first();

                if($user){

                    if( (bool) $user['allow_adminpanel'] ){
                        if (CheckField::confirm_hash($_POST['password'], $user['password'])) {
                            unset($user['password']);
                            $token = $db->hash(time());
                            $db->table('user_tokens')->insert(['user_id' => $user['id'], 'token' => $token, 'date' => time()]);
                            
                            if(!$db->error){

                                setcookie('user_token', $token, time() + Store::get('config.life_time_token'), '/');
                                Store::set('USER', $user);

                            }else $alerts->set_error('Ошибка авторизации', 'Не удалось выдать токен!', 207);

                        }else $alerts->set_error('Ошибка авторизации', 'Неправильный пароль от учётной записи!', 205);
                    
                    } else $alerts->set_error('Ошибка авторизации', 'У вас нет доступа к админпанели!', 215);
                    
                } else $alerts->set_error('Ошибка авторизации', 'Пользователя с таким именем нет!', 206);
            }
        }
    }
    else{
        $user = $db->table('user_tokens')
            ->select('groups.*', 'users.*')
            ->join('users', 'user_tokens.user_id', '=', 'users.id')
            ->join('groups', 'users.group_id', '=', 'groups.id')
            ->first();
        
        if($user){
            if( (bool) $user['allow_adminpanel'] ){
                Store::set('USER', $user);
            }
            else logout();
        } 
        else logout();
    }    
?>
