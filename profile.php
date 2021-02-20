<?php
    require_once "engine/profile.php";
 ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="/engine/js/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="js/recdoc.js" type="text/javascript" defer></script>
    <link rel="stylesheet" href="css/style.css">
    <title>Мой профиль</title>
</head>
<body>
    <div class="inf-gg">
            <div class="content-inf">
				<div class="kabinet">
					<div class="title-kabinet">Профиль</div>
					<?php
                        if(isset($complete)){
                            echo '<div class="kabinet-complete">'.$complete.'</div>';
                        }
                        if(!empty($errors_save)){
                        	 echo '<div class="kabinet-error">'.array_shift($errors_save).'</div>';
                        }
                        if(empty($errors)){
                    ?>
					<div class="content-kabinet">
						<img class="kabinet-foto-user" src="/<?php if($user_info['foto'] == '') echo 'img/noavatar.png'; else echo $user_info['foto']; ?> " alt="">
						<div class="kabinet-user-info">
							<div class="kabinet-info"><b>Логин:</b> <?php echo $user_info['login']; ?></div>
	            			<div class="kabinet-info"><b>Ваше имя:</b> <?php if($user_info['name'] != '') echo $user_info['name']; else echo 'Неизвестно'; ?></div>
	            			<div class="kabinet-info"><b>E-mail:</b> <?php echo $user_info['email']; ?></div>
	            			<div class="kabinet-info"><b>Дата регистрации:</b> <?php echo date('Y-m-d H:i',$user_info['date_reg']); ?></div>
	            			<div class="kabinet-info"><b>Группа:</b> <?php echo $user_info['user_group']; ?></div>
						</div>
					</div>
					<?php  if($_SESSION['logined']['user_group'] == 1 || $_SESSION['logined']['login'] == $user_info['login']){?>
					<div class="kabinet-controls">
						<div class="edit-profile">Редактировать профиль</div>
						<div class="controls">
							<form method="post" enctype="multipart/form-data">
								<div class="title-edit">Информация</div>
								<input type="email" class="con-edit" name="email" placeholder="E-mail" value="<?php echo $user_info['email'] ?>" required>
								<input type="text" class="con-edit" name="name" placeholder="Ваше имя" value="<?php echo $user_info['name'] ?>">
								<div class="title-edit">Аватар</div>
								<input type="file" class="con-edit" name="foto" accept="image/*">
								<label class="checkbox">
					            	<input type="checkbox" class="check-input" name="delete_foto">
					            	<span class="check"></span>
					            	Удалить аватар
					        	</label>
								<div class="title-edit">Пароль</div>
								<input type="password" class="con-edit" name="lastpassword" placeholder="Старый пароль">
								<input type="password" class="con-edit" name="newpassword" placeholder="Новый пароль">
								<input type="password" class="con-edit" name="newpassword_reset" placeholder="Повторите пароль">
								<input type="submit" class="con-edit" name="save_profile" id="save-profile" value="Сохранить">
                    		</form>
						</div>
					</div>
					<?php  }?>
					<?php
						}
                        else{
                            echo '<div class="kabinet-error">'.array_shift($errors).'</div>';
                        }
                    ?>
				</div>
            </div>
    </div>
    <a href="/" title="Вернуться на главную" class="goToTop"></a>
</body>
</html>
