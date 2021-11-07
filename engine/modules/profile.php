<?php
	require_once ENGINE_DIR.'/includes/upload.php';
	require_once ENGINE_DIR.'/includes/checkFeild.php';	
	require_once ENGINE_DIR.'/includes/errors.php';	

	$user = $db->table('users')
		->select('groups.*', 'users.*')
		->join('groups', 'users.group_id', '=', 'groups.id')
		->where('users.login', '=', (isset($_GET['param1']) ? $_GET['param1'] : null))
		->first();

	if($user){

		/* edit user */

		if(isset($_POST['do_save_profile'])){
			if(Store::get('USER.id') == $user['id']){

				$alerts->set_error_if(!CheckField::login($_POST['login']), 'Ошибка изменения данных пользователя', 'Некорректный логин', 201);

				$alerts->set_error_if(!CheckField::email($_POST['email']), 'Ошибка изменения данных пользователя', 'Некорректный email', 202);

				if(isset($_POST['password'][0]) || isset($_POST['repassword'][0]) || isset($_POST['lastpassword'][0])){
					$alerts->set_error_if(!CheckField::confirm_hash($_POST['lastpassword'],$user['password']), 'Ошибка изменения данных пользователя', 'Пароль не совпадает с предыдущим', 212);
					
					$alerts->set_error_if(!CheckField::confirm_pass($_POST['password'],$_POST['repassword']), 'Ошибка изменения данных пользователя', 'Пароль не совпадает с формой подтверждения', 204);
				}
				
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

                    if(isset($_POST['password'][0]))
                        $fields['password'] = $db->hash($_POST['password']); 

                    if(isset($_POST['delete_foto']))
                        $fields['foto'] = '';
                    
                    if($foto->filepath)
                        $fields['foto'] = $foto->filepath;

                    if($db->table('users')->where('id', '=' , $user['id'])->update($fields)){
                        
                        $alerts->set_success('Данные профиля обновлены', 'Данные профиля успешно обновлены!');
						
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
			else $alerts->set_error('Oшибка редактирования профиля', 'Невозможно изменить данные пользователя, нет доступа', 218);
		}

		/* close edit user */

		Store::set('title', 'Личный кабинет '.$user['login']);
		
    	$tpl->save('content', 'profile', [
			'logout_all' => '/logout/?exit=all',
			'user' => $user
		]);
	}
	else {
		$alerts->set_error('Oшибка', 'Такого пользователя не существует!', 404);
		Store::set('title', 'Профиль не найден');
	}	
	
?>
