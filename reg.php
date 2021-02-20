<?php
    require_once "engine/registration.php";
 ?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Регистрация</title>
</head>
<body>
    <div class="inf-gg">
            <div class="iformation">Регистрация</div>
            <div class="content-inf">
                <div class="registration">
                    <?php
                        if(isset($complete)){
                            echo '<div class="complete">'.$complete.'</div>';
                        }
                    ?>
                    <form action="reg.php" method="post" enctype="multipart/form-data">
                        <input type="text" name="login" placeholder="Логин" value="<?php if(!isset($complete)) echo @$_POST['login'] ?>" required>
                        <input type="email" name="email" placeholder="E-mail" value="<?php if(!isset($complete)) echo @$_POST['email'] ?>" required>
                        <input type="text" name="name" value="<?php if(!isset($complete)) echo @$_POST['name'] ?>" placeholder="Ваше имя">
                        <input type="password" name="password" placeholder="Пароль" required>
                        <input type="password" name="password_reset" placeholder="Повторите пароль" required>
                        <input type="submit" name="do_reg" id="do-reg" value="Отправить">
                    </form>
                    <?php
                        if(!empty($errors)){
                            echo '<div class="error">'.array_shift($errors).'</div>';
                        }
                    ?>
                </div>
            </div>
    </div>
    <a href="/" title="Вернуться на главную" class="goToTop"></a>
</body>
</html>
