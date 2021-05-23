<?php
    $tpl->load('base.html');

    if(!isset($tpl->data['{content}']))
        $tpl->set('{content}','');

    $tpl->set('{SKIN}', ADMIN_URL.'/skin');

    if(!$_SESSION['user']['is_admin'] ){
        $tpl->set_block('/\[admin\](.*)\[\/admin\]/sU','');
    }
    else{
        $tpl->set('[admin]', '');
        $tpl->set('[/admin]', '');
    }

    $tpl->print();
?>
