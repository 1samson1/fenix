<?php
    $tpl->load('base.html');

    if(!isset($tpl->data['{content}']))
        $tpl->set('{content}','');

    $tpl->set('{TEMPLATE}', '/templates/'.$config['template']);

    $tpl->set('{head}', $head);

    if($_SESSION['user']['id'] == $user['id'] || $_SESSION['user']['group_id'] == $config['admin_group']){
        $tpl->set('[user]', '');
        $tpl->set('[/user]', '');
    }
    else $tpl->set_block('user','', 's');

    if(isset($_SESSION['user'])){
        if($_SESSION['user']['group_id']  != 1){
            $tpl->set_block('admin','');
        }
        else{
            $tpl->set('{admin-link}', '/admin/');
            $tpl->set('[admin]', '');
            $tpl->set('[/admin]', '');
        }
    }
    else{
        $tpl->set('{registration-link}', '/registration/');
    }


    $tpl->print();
?>
