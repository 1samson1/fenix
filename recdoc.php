<?php 
    require_once "engine/config.php";  
    require_once "engine/login.php";  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="/engine/css/preloader.css">
    <link rel="stylesheet" href="/engine/css/jquery.datetimepicker.min.css">
    <script src="/engine/js/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="/engine/js/jquery.datetimepicker.full.min.js"></script>
    <script src="js/recdoc.js" type="text/javascript"></script>
    <script src="/engine/js/default.js" type="text/javascript"></script>
    <title>Записаться к врачу</title>
</head>
<body>
    <div id="preloader">
        <div class="preloader">
            <div class="bigBoll"></div>
            <div class="smallBoll">
                <div class="smallBollCenter"></div>
            </div>
        </div>
    </div>
    <?php if(isset($_SESSION['logined'])){  ?>    
    <div class="records">
        <div class="logined-rec">
            <a class="link" href="/profile.php?id=<?php echo $_SESSION['logined']['id'] ?>"><img class="foto-user" src="/<?php if($_SESSION['logined']['foto'] == '') echo 'img/noavatar.png'; else echo $_SESSION['logined']['foto']; ?> " alt=""></a>
            <div class="logined-login"><b>Ваш логин: </b><?php echo $_SESSION['logined']['login']; ?></div>
            <div class="logined-name"><b>Ваше имя: </b><?php if($_SESSION['logined']['name'] != '') echo $_SESSION['logined']['name']; else echo 'Неизвестно'; ?></div>
            <a href="/engine/logout.php" class="logined-logout">Выйти из профиля</a>
        </div>
        <div class="body-records">
            <?php             
                $get_list_doctors = 'SELECT `doctors`.`id` , `doctors`.`name` , `doctors`.`specialty_id`, `doctors`.`foto` , `doctors`.`kabinet` , `specialties`.`title` FROM `doctors` JOIN `specialties` ON `doctors`.`specialty_id` = `specialties`.`id`';
                
                $access = mysqli_query($conection,$get_list_doctors);
                while($row = mysqli_fetch_assoc($access)){                    
            ?>                    
                <?php 
                    if($specion != $row['title']){
                ?>
                <div class="title-specion"><?php echo $row['title']; ?></div>
                <?php
                        $specion = $row['title']; 
                    }                            
                ?>
                <div class="body-specion">
                    <div class="inf-body-specion">
                        <div class="incont fio-img">                    
                            <div class="fio"><?php echo $row['name']; ?></div>
                            <div class="foto">
                                <img src="<?php echo $row['foto']; ?>" alt="" srcset="">
                            </div>
                        </div>
                        <div class="incont grafic">
                            <ul>
                                <li>График работы </li>
                                <li><b>Пн</b> 8:30-11:00 </li>
                                <li><b>Вт</b> 8:30-14:00 </li>
                                <li><b>Ср</b> 8:30-14:00 </li>
                                <li><b>Чт</b> 8:30-13:00 </li>
                                <li><b>Пт</b> 10:00-16:00 </li>
                            </ul>
                        </div>
                        <div class="incont date-time-rec">
                            <label>
                                Выберите дату и время для записи на приём:
                                <input class="date" type="text" name="daterec" id="date-recdoc">
                            </label>                
                        </div>
                        <div class="incont recdoc" onclick="recDoc('<?php echo $row['id']; ?>');">                            
                            <div class="cen-lab">Записаться</div>
                        </div>
                    </div>                    
                </div> 
            <?php
                }
            ?>    
        </div>    
    </div>
    <?php } else {?>
    <div class="vhod" style="margin:0 auto; ">
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
    <?php } ?>
    <a href="/" title="Вернуться на главную" class="goToTop"></a> 
    <div class="modalWinRec">        
        <div class="fonModalWinRec"></div>   
        <div class="modalWinRecText">
            <div class="close"></div>
            <div class="titleModalWinRec">Талон успешно оформлен</div>
            <div class="textModalWinRec">
                <div class="thank">Пожалуйста не опаздывайте на приём.</div>
                <div class="timeRec"></div>
                <div class="cabinet"></div>
                <div class="otdelenie"></div>
            </div>
        </div>    
    </div>     
</body>
</html>
