
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Главная</title>
</head>
<body>
    <div class="main">
        <div class="gregient">
            <div class="title-page">Электронная регистратура</div>
            <div class="obrazec">
                <div class="title-obrazec">Образец полиса</div>
                <div class="img-obtazec"><img src="img/3.jpg" alt=""></div>
            </div>
            <?php

            //require_once"log.php";


            ?>
            <!-- BEGIN  -->
            <?php
    
    if(!isset($_SESSION['logined'])){
?>
<div class="vhod">
    <div class="title-vhod">Вход</div>
    <form method="POST">
        <input class="login" name="login" type="text" placeholder="Логин" required>
        <input class="pass" name="password" type="password" placeholder="Пароль" required>
        <input class ="submit" name="do_logined" type="submit" value="Войти">
    </form>
    <a href="/reg.php" class="reg-link">Регистрация</a>
    <?php
        if(!empty($errors)){
            echo '<div class="login-error">'.array_shift($errors).'</div>';
        }
     ?>
</div>
<?php
    }
    else {
?>

<div class="logined">
    <a href="/profile.php?id=<?php echo $_SESSION['logined']['id'] ?>" class="title-profile">Профиль</a>
    <div class="profile">
        <img class="foto-user" src="/<?php if($_SESSION['logined']['foto'] == '') echo 'img/noavatar.png'; else echo $_SESSION['logined']['foto']; ?> " alt="">
        <div class="user-info">
            <div class="login-profile"><b>Логин:</b> <?php echo $_SESSION['logined']['login']; ?></div>
            <div class="user-name"><b>Ваше имя:</b> <?php if($_SESSION['logined']['name'] != '') echo $_SESSION['logined']['name']; else echo 'Неизвестно'; ?></div>
            <!-- <div class="user-email"><b>Ваш E-mail:</b> <?php echo $_SESSION['logined']['email']; ?></div> -->
        </div>
    </div>
    <?php  if($_SESSION['logined']['user_group'] == 1){?>
    <a href="/admin.php" class="admin">Админпанель</a>
    <?php } ?>
    <a href="/engine/logout.php" class="logout">Выйти из профиля</a>
</div>

<?php
    }
?>
            <!-- END -->
            <div class="contacts">Телефон для вопросов и для записи на приём<label class="telefon">8-800-576-08-19</label></div>
            <div class="iformation">Меню сайта</div>
            <div class="information-boln">
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <div class="title-inf">Страхование</div>
                                <div class="text-inf">Узнать статус страхования и дополнительную информацию</div>
                                <div class ="cnopka"><a href="strahovanie.html">Узнать</a></div>
                            </td>
                            <td>
                                <div class="title-inf">Донорство</div>
                                <div class="text-inf">Вся информация о донорах и возможность стать донором</div>
                                <div class ="cnopka"><a href="donorstvo.html">Узнать</a></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="title-inf">Прайс-лист</div>
                                <div class="text-inf">Цены на приём врачей. Другая медицинская помощь оказывается за дополнительные услуги</div>
                                <div class ="cnopka"><a href="prace.html">Посмотреть</a></div>
                            </td>
                            <td>
                                <div class="title-inf">Рейтинг врачей</div>
                                <div class="text-inf">Рейтинг врачей нашей больницы.</div>
                                <div class ="cnopka"><a href="rating.html">Узнать</a></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="title-inf">Информация о больнице</div>
                                <div class="text-inf">Узнать о нашей больнице подробнее. План здания.</div>
                                <div class ="cnopka"><a href="information.html">Узнать</a></div>
                            </td>
                            <td>
                                <div class="title-inf">Записаться к врачу</div>
                                <div class="text-inf">Запись к врачу на приём.</div>
                                <div class ="cnopka"><a class="rec" href="recdoc.php">Записаться</a></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>