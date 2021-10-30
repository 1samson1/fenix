<?php 
    $tpl->save('login', 'login', [
        'logout' => '/logout/',
        'registration-link' => '/registration/',
        'profile-link' => '/profile/' . Store::get('USER.login'),
    ]);
?>
