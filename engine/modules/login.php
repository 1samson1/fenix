<?php 
    $tpl->load('login.html');

    if(isset($_SESSION['user'])){
        $tpl->set('{login}', $_SESSION['user']['login']);
        $tpl->set('{name}', $_SESSION['user']['name']);
        $tpl->set('{surname}', $_SESSION['user']['surname']);

        if($_SESSION['user']['foto'])$foto = '/'.$_SESSION['user']['foto'];
        else $foto = '{TEMPLATE}/img/noavatar.png';
        $tpl->set('{foto}', $foto);
        $tpl->set('{profile-link}', '/profile/'.$_SESSION['user']['login'].'/');

        $tpl->set('{logout}','/logout/');
    }

    $tpl->save('{login}');
?>
