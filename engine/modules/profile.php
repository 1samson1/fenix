<?php
	require_once ENGINE_DIR.'/includes/upload.php';
	require_once ENGINE_DIR.'/includes/checkFeild.php';	
	require_once ENGINE_DIR.'/includes/errors.php';	

	$db->check_user($_GET['param1']);
	if($user = $db->get_row()){
		/* edit user */

		if(isset($_POST['do_save_profile'])){
			if($_SESSION['user']['group_id'] == $config['admin_group'] || $_SESSION['user']['id'] == $user['id']){

				$alerts->set_error_if(!CheckField::login($_POST['login']), 'Ошибка изменения данных пользователя', 'Некорректный логин', 201);

				$alerts->set_error_if(!CheckField::email($_POST['email']), 'Ошибка изменения данных пользователя', 'Некорректный email', 202);

				if(isset($_POST['password'][0]) || isset($_POST['repassword'][0]) || isset($_POST['lastpassword'][0])){
					$alerts->set_error_if(!CheckField::confirm_hash($_POST['lastpassword'],$user['password']), 'Ошибка изменения данных пользователя', 'Пароль не совпадает с предыдущим', 212);
					
					$alerts->set_error_if(!CheckField::confirm_pass($_POST['password'],$_POST['repassword']), 'Ошибка изменения данных пользователя', 'Пароль не совпадает с формой подтверждения', 204);
				}
				
				$foto = new Upload_Image('foto', 'foto_'.$user['id'], 'avatars');

				$alerts->alerts_array = array_merge($alerts->alerts_array, $foto->errors);

				if(!isset($alerts->alerts_array[0])){

					if($db->update_user($user['id'], $_POST['name'], $_POST['surname'], $_POST['login'], $_POST['email'], $_POST['password'], $foto->filepath, isset($_POST['delete_foto']))){
						$alerts->set_success('Данные профиля обновлены', 'Данные профиля успешно обновлены!');
						$user['name'] = $_POST['name'];
						$user['surname'] = $_POST['surname'];
						$user['login'] = $_POST['login'];
						$user['email'] = $_POST['email'];

						if(isset($_POST['delete_foto'])){
							delete_file($user['foto']);
							$user['foto'] = '';
						}
											 
						if($foto->filepath){
							delete_file($user['foto']);
							$foto->save();
							$user['foto'] = $foto->filepath;
						}
					}
					else $alerts->set_error('Ошибка изменения данных пользователя', Error_info::reg_user($db->error_num), $db->error_num);
				}	
			}
			else $alerts->set_error('Oшибка редактирования профиля', 'Невозможно изменить данные пользователя, нет доступа', 218);
		}

		/* close edit user */

		$head['title'] = 'Профиль '.$user['login'];
		$tpl->set('{login}',$user['login']);
		$tpl->set('{email}', $user['email']);
		$tpl->set('{name}', $user['name']);
		$tpl->set('{surname}', $user['surname']);
		$tpl->set('{date-reg}', date('d.m.Y', $user['date_reg']));
		$tpl->set('{group}',$user['group_name']);

        if($user['foto']) $foto = '/'.$user['foto'];
        else $foto = '{TEMPLATE}/img/noavatar.png';
        $tpl->set('{foto}', $foto);

		$tpl->set('{logout_all}','/logout/?exit=all');

		$tpl->load('profile.html');    
    	$tpl->save('{content}');
	}
	else {
		$alerts->set_error('Oшибка', 'Такого пользователя не существует!', 404);
		$head['title'] = 'Профиль не найден';
	}	
	
?>
