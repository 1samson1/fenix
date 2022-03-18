<?php
	require_once ENGINE_DIR.'/includes/upload.php';
	require_once ENGINE_DIR.'/includes/checkFeild.php';	
	require_once ENGINE_DIR.'/includes/errors.php';	

	if(Store::isset('USER')){
		$user = Store::get('USER');

		/* edit user */

		if(isset($_POST['do_save_profile'])){

			$alerts->set_error_if(!CheckField::login($_POST['login']), 'Ошибка изменения данных пользователя', 'Некорректный логин', 201);

			$alerts->set_error_if(!CheckField::email($_POST['email']), 'Ошибка изменения данных пользователя', 'Некорректный email', 202);

			$foto = new Upload_Image('foto', 'foto_'.$user['id'], 'avatars');

			$alerts->merge($foto->errors);

			if($alerts->is_empty()){
				$fields = [
					'name' => $_POST['name'],
					'surname' => $_POST['surname'],
					'patronymic' => $_POST['patronymic'],
					'login' => $_POST['login'],
					'email' => $_POST['email'],
					'phone' => $_POST['phone'],
					'birthday' => strtotime($_POST['birthday']),
					'gender' => $_POST['gender'],
					'adress' => $_POST['adress'],
				];

				if(isset($_POST['delete_foto']))
					$fields['foto'] = '';
				
				if($foto->filepath)
					$fields['foto'] = $foto->filepath;

				if($db->table('users')->where('id', '=' , $user['id'])->update($fields)){
					
					$alerts->set_success('Данные пользователя обновлены', 'Данные пользователя успешно обновлены!');
					
					$user = array_merge($user, $fields);
					
					if(isset($_POST['delete_foto'])){
						delete_file($user['foto']);
					}
											
					if($foto->filepath){
						delete_file($user['foto']);
						$foto->save();
					}

					if(Store::get('USER.id') == $user['id'])
						Store::set('USER', $user);
					
				}
				else $alerts->set_error('Ошибка изменения данных пользователя', Error_info::reg_user($db->error_num), $db->error_num);
			}	
		}

		/* close edit user */

		/* change password */

		if(isset($_POST['do_change_password'])){
			$alerts->set_error_if(!CheckField::confirm_hash($_POST['lastpassword'],$user['password']), 'Ошибка изменения данных пользователя', 'Пароль не совпадает с предыдущим', 212);
				
			$alerts->set_error_if(!CheckField::confirm_pass($_POST['password'],$_POST['repassword']), 'Ошибка изменения данных пользователя', 'Пароль не совпадает с формой подтверждения', 204);
		
			if($alerts->is_empty()){

				$result = $db->table('users')->where('id', '=' , $user['id'])->update([
					'password' => $db->hash($_POST['password'])
				]);

				if($result) {
					$alerts->set_success('Пароль пользователя обновлен', 'Пароль пользователя успешно обновлен!');
				} 
				else $alerts->set_error('Ошибка изменения пароля пользователя', "Неизвестная ошибка", 520);
			}
		}

		/* close change password */

		/* cancel appointment */

		if(isset($_POST['do_cancal_appointment'])){

			$appointment = $db->table('appointments')->where('number', '=', $_POST['param'])->first();
			
			if($appointment['time'] > time()){

				$result = $db->table('appointments')
					->where('user_id', '=', Store::get('USER.id'))
					->where('number', '=', $_POST['param'])
					->delete();
				
				if($result) {
					$alerts->set_success('Запись на приём', 'Запись на приём успешно отменина!');
				} 
				else $alerts->set_error('Ошибка', "Неизвестная ошибка", 520);
			}
			else $alerts->set_error('Ошибка', "Время отмены записи на приём истекло!", 406);
		}

		/* close cancel appointment  */

		Store::set('title', 'Личный кабинет '.$user['login']);

		$appointments = $db->table('appointments')
				->select('doctors.name as doctor', 'doctors.kabinet as kabinet' , 'specialties.title as specialty', 'appointments.*')
				->join('doctors', 'doctors.id', '=', 'appointments.doctor_id')
				->join('specialties', 'specialties.id', '=', 'doctors.id')
				->where('user_id', '=', $user['id'])
				->orderBy('time', 'desc')
				->get();
		
		foreach ($appointments as &$appointment){
			if($appointment['time'] > time()){
				$appointment['is_can_canceled'] = true;
			} else {
				$appointment['is_can_canceled'] = false;
			}
		}

    	$tpl->save('content', 'profile', [
			'logout_all' => '/logout/?exit=all',
			'user' => $user,
			'appointments' => $appointments
		]);
	}
	else {
		header('Location: /');
	}	
	
?>
