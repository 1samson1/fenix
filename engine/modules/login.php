<?php 
    $tpl->save('login', 'login', [
        'logout' => '/logout/',
        'admin-link' => '/admin/',
        'registration-link' => '/registration/',
        'profile-link' => '/profile/' . Store::get('USER.login') . '/',
    ]);
?>
