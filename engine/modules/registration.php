<?php

	require_once ENGINE_DIR.'/includes/checkFeild.php';	
	require_once ENGINE_DIR.'/includes/errors.php';	

	if(!Store::isset('USER') && Store::get('config.registration_on')){

		if(isset($_POST['do_reg'])){

			$alerts->set_error_if(!CheckField::login($_POST['login']), 'Ошибка регистрации', 'Некорректный логин', 201);

			$alerts->set_error_if(!CheckField::email($_POST['email']), 'Ошибка регистрации', 'Некорректный email', 202);

			$alerts->set_error_if(!CheckField::empty($_POST['password']), 'Ошибка регистрации', 'Вы не ввели пароль', 203);

			$alerts->set_error_if(!CheckField::confirm_pass($_POST['password'],$_POST['repassword']), 'Ошибка регистрации', 'Пароль не совпадает с формой подтверждения', 204);

			if($alerts->is_empty()){

				$db->table('users')->insert([
					'group_id' => Store::get('config.reg_user_group'),
					'name' => $_POST['name'],
					'surname' => $_POST['surname'],
					'patronymic' => $_POST['patronymic'],
					'login' => $_POST['login'],
					'email' => $_POST['email'],
					'phone' => $_POST['phone'],
					'birthday' => strtotime($_POST['birthday']),
					'gender' => $_POST['gender'],
					'adress' => $_POST['adress'],
					'password' => $db->hash($_POST['password']),
					'date_reg' => time()
				]);

				if($db->result){
					$alerts->set_success('Регистрация прошла успешно', 'Вы успешно зарегистрированы.');
				}
				else $alerts->set_error('Ошибка регистрации', Error_info::reg_user($db->error_num), $db->error_num);
			}
		}
		
		Store::set('title', 'Регистрация');

		$tpl->save('content', 'registration');
	}
	else {
		$alerts->set_error('Ошибка', 'Регистрация не доступна!', 233);
		Store::set('title', 'Регистрация не доступна!');
		header('Location: /');
	}

?>
