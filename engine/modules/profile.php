<?php
	$find_user = 'SELECT `users`.`id` , `users`.`login` , `users`.`password` , `users`.`email` , `users`.`name` , `users`.`foto` , `users`.`date_reg` , `user_groups`.`group_name` FROM `users` JOIN `user_groups` ON `users`.`user_group` = `user_groups`.`id` WHERE `users`.`id` = '.$_GET['id'];

	$access = mysqli_query($conection,$find_user);
	if($access){
		if($row = mysqli_fetch_assoc($access)){
			$user_info['login'] = $row['login'];
			$user_info['name'] = $row['name'];
			$user_info['email'] = $row['email'];
			$user_info['foto'] = $row['foto'];
			$user_info['user_num_group'] = $row['user_group'];
			$user_info['user_group'] = $row['group_name'];
			$user_info['date_reg'] = $row['date_reg'];
        }
        else $errors[] = 'Пользователя с таким id нет!';
	}
	else $errors[] = 'Ошибка попробуйте снова.';
	
	// Save edit profile in Data Base

	if (isset($_POST['save_profile'])) {
		if($_SESSION['logined']['user_group'] == 1 || $_SESSION['logined']['id'] == $row['id'])
		{
			if($_POST['email'] == '' || !preg_match('/^\S+@([a-z0-9]+\.[a-z0-9]+|[a-z0-9]+\.[a-z0-9]+\.[a-z0-9]+)$/',$_POST['email']))
			{
				$errors_save[] = 'Вы ввели некорректный email!';
			}

			if(empty($errors_save)){				
				///////////////////////////// UPLOAD FILE AVATAR ON SERVER ////////////////////
				if(!empty($_FILES['foto']['name']) && $_FILES['foto']['size'] < $max_size_upload_img && !isset($_POST['delete_foto'])){
					if($_FILES['foto']['error'] == 0){
						$type = getimagesize($_FILES['foto']['tmp_name']);						
						if($type && ($type['mime'] == 'image/png' || $type['mime'] == 'image/gif' || $type['mime'] == 'image/jpeg')){
							preg_match('/\.\w+$/', $_FILES['foto']['name'],$matches);
							$fotopath = 'uploads/avatars/foto_'.$row['id'].$matches[0];
						}						
						else{
							$errors_save[] = 'Файл не является изображением!';
						}						
					}
					else{
						$errors_save[] = 'Ошибка загрузки файла на сервер!';
					}
				}
				//////////////////////////// CREATE QUERY ON DATA BASE //////////////////////
				$edit_profile = 'UPDATE `users` SET ';

				$edit_profile .= '`email` = \''.htmlspecialchars($_POST['email']).'\', `name` = \''.htmlspecialchars($_POST['name']).'\'';

				if(!empty($fotopath)){
					$edit_profile .= ', `foto` = \''.$fotopath.'\'';
				}
				if(isset($_POST['delete_foto'])){
					$edit_profile .= ', `foto` = \'\'';					
				}
				if(!empty($_POST['newpassword'])){
					if($_POST['newpassword'] == $_POST['newpassword_reset'] && password_verify($_POST['lastpassword'],$row['password'])){
						$edit_profile .= ', `password` = \''.password_hash($_POST['newpassword'], PASSWORD_DEFAULT).'\'';
						$pass_save = true;
					}
					else{
						$errors_save[] = 'Вы ввели неверный старый пароль!';
					}
				}

				$edit_profile .= ' WHERE `id` = '.$_GET['id'];				
				///////////////////////// SEND QUERY ON SERVER //////////////////////////
				if(empty($errors_save)){					
					$access_edit = mysqli_query($conection, $edit_profile) or $errors_save[] = 'Запрос к бд не удался!';
				}				
				/////////////////// IF QUERY IS DONE /////////////////////////////////
				if($access_edit){
					$complete = 'Данные профиля обновлены!';
					$user_info['name'] = $_SESSION['logined']['name'] = $_POST['name'];
					$user_info['email'] = $_SESSION['logined']['email'] = $_POST['email'];
					
					if(!empty($fotopath)){
						$user_info['foto'] = $_SESSION['logined']['foto'] = $fotopath;
						if(file_exists($row['foto'])){
							unlink($row['foto']);
						}
						move_uploaded_file($_FILES['foto']['tmp_name'], $fotopath);
					}
					if(isset($_POST['delete_foto'])){
						unlink($row['foto']);
						$user_info['foto'] = $_SESSION['logined']['foto'] ='';
					}

					if(isset($pass_save))
						$_SESSION['logined']['password'] = $_POST['newpassword'];
				}
				else{
					$errors_save[] = 'Ошибка обновления данных профиля попробуйте снова.';
				}
			}
		}
	}
	
?>