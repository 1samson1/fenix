<?php
    require_once ENGINE_DIR.'/includes/checkFeild.php';	
	require_once ENGINE_DIR.'/includes/errors.php';	

	if(isset($_POST['do_login'])){

        $alerts->set_error_if(!CheckField::login($_POST['login']), 'Ошибка регистрации', 'Некорректный логин', 201);

		$alerts->set_error_if(!CheckField::pass($_POST['password']), 'Ошибка регистрации', 'Вы не ввели пароль', 203);  

        if(!isset($alerts->alerts_array[0])){

            $db->check_user($_POST['login']);
            if($user = $db->get_row()){
                if (CheckField::confirm_hash($_POST['password'], $user['password'])) {
                    #Code...
                }
                else $alerts->set_error('Ошибка регистрации', 'Неправильный пароль от учётной записи', 205);
                
            } else $alerts->set_error('Ошибка регистрации', 'Пользователя с таким именем нет!', 206);
        }
    }    
?>
