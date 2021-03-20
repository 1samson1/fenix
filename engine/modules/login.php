<?php 
    $tpl->load_tpl('login.html');

    if(isset($_SESSION['user'])){
        $tpl->set('{login}', $_SESSION['user']['login']);

        if($_SESSION['user']['foto'])$foto = $config['host_url'].'/'.$_SESSION['user']['foto'];
        else $foto = '{TEMPLATE}/img/noavatar.png';
        $tpl->set('{foto}', $foto);
        $tpl->set('{profile-link}', '/profile/'.$_SESSION['user']['login'].'/');

        $tpl->set('{logout}','/logout/');
    }

    $tpl->save('{login}');
?>
