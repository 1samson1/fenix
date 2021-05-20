<?php 
    require_once ENGINE_DIR.'/includes/functions.php';

    $tpl->load('userpanel.html');

    if(isset($_SESSION['user'])){
        $tpl->set('{login}', $_SESSION['user']['login']);

        if($_SESSION['user']['foto'])$foto = '/'.$_SESSION['user']['foto'];
        else $foto = '{SKIN}/img/noavatar.png';
        $tpl->set('{foto}', $foto);
        $tpl->set('{profile-link}', '/profile/'.$_SESSION['user']['login'].'/');

        $tpl->set('{logout}', addGetParam('action', 'logout'));
    }

    $tpl->save('{user-panel}');
?>
