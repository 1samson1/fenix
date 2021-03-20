<?php
    $tpl->load_tpl('base.html');

    if(!isset($tpl->data['{content}']))
        $tpl->set('{content}','');

    $tpl->set('{TEMPLATE}', $config['host_url'].'/templates/'.$config['template']);

    $tpl->set('{head}', $head);

    if(isset($_SESSION['user'])){
        if($_SESSION['user']['group_id']  != 1){
            $tpl->set_block('/\[admin\](.*)\[\/admin\]/sU','');
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