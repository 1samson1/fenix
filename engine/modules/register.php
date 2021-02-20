<?php

	if(isset($_POST['do_reg'])){
		if($_POST['login'] == '')
		{
			$errors[] = 'Вы не ввели логин!';
		}
		if($_POST['email'] == '' || !preg_match('/^\S+@([a-z0-9]+\.[a-z0-9]+|[a-z0-9]+\.[a-z0-9]+\.[a-z0-9]+)$/',$_POST['email']))
		{
			$errors[] = 'Вы ввели некорректный email!';
		}
		if($_POST['password'] == '')
		{
			$errors[] = 'Вы не ввели пароль';
		}
		if($_POST['password'] != $_POST['password_reset'])
		{
			$errors[] = 'Пароль не совпадает с формой подтверждения!';
		}
		if(empty($errors)){
			$add_user = 'INSERT INTO `users` (`login`, `password`, `email`, `name`, `foto`, `user_group`, `date_reg`) VALUES (\''.htmlspecialchars($_POST['login']).'\', \''.password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT).'\' , \''.htmlspecialchars($_POST['email']).'\', \''.htmlspecialchars($_POST['name']).'\' , \''.$fotopath.'\',\''.$defaul_reg_group_user.'\' ,\''.time().'\')';

			$access = mysqli_query($conection, $add_user) or $errors[] = 'Введёный email или логин занят!';

			if($access){
				$complete = 'Вы успешно зарегистрированы. Вы можете <a href="/">войти на сайт </a>';
			}
			else{
				$errors[] = 'Ошибка регистрации попробуйте снова.';
			}
		}
	}	
?>

