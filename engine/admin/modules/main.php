<?php 

    $crumbs->add('Главная страница', '');

    $tpl->load('main.html'); 
    
    $tpl->set_repeat_block('/\[modules\](.*)\[\/modules\]/sU');
    
    foreach($modules as $module){

        $tpl->set('{module-title}', $module['verbose_name']);
        $tpl->set('{module-image}', ADMIN_URL_STATIC.'/modules/'.$module['name'].'/'.$module['image']);
        $tpl->set('{module-description}', $module['description']);
        $tpl->set('{module-link}', '/admin/'.'?mod='.$module['name']);

        $tpl->copy_repeat_block();
    }

    $tpl->save_repeat_block();

    $tpl->save('{content}');
?>
