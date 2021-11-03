<?php 

    $crumbs->add(Store::set('title', 'Главная страница'), '');

    $allow_modules = [];
    foreach($modules as $name => $module){
        if( (bool) Store::get('USER.allow_'.$name) )
            $allow_modules[]= array_merge($module, ['name' => $name]);
    }

    $tpl->save('content', 'main', [
        'modules' => $allow_modules
    ]);

?>
