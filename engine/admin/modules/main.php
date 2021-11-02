<?php 

    $crumbs->add(Store::set('title', 'Главная страница'), '');

    $allow_modules = [];
    foreach($modules as $module){
        if( (bool) Store::get('USER.allow_'.$module['name']) )
            $allow_modules[]= $module;
    }

    $tpl->save('content', 'main', [
        'modules' => $allow_modules
    ]);

?>
