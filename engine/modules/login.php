<?php
    /*require_once "config.php";*/

	if(isset($_POST['do_logined'])){
        if($_POST['login' == '']){
            $errors[] = 'Вы не ввели логин';
        }
        if($_POST['password'] == ''){
			$errors[] = 'Вы не ввели пароль';
        }
        if(empty($errors)){
            $login_user = 'SELECT * FROM `users` WHERE `login` = \''.$_POST['login'].'\'';

            $access = mysqli_query($conection,$login_user) or $errors[] = 'Запрос к бд не удался!';
            
            if($row = mysqli_fetch_assoc($access)){
                if (password_verify($_POST['password'], $row['password'])) {
                    $_SESSION['logined'] = $row;
                }
                else {
                    $errors[] = 'Неправильный пароль от учётной записи';
                }
            }
            else $errors[] = 'Пользователя с таким именем нет!';
        }
    }    
?>

