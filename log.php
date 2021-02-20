<?php
    require_once "engine/login.php";
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
