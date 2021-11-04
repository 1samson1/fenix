<?php
    switch (isset($_GET['do']) ? $_GET['do'] : '') {
        case 'main':
            require_once ENGINE_DIR.'/modules/main.php';
            break;

        case 'registration':
            require_once ENGINE_DIR.'/modules/registration.php';
            break;

        case 'lostpassword':
            require_once ENGINE_DIR.'/modules/lostpassword.php';           
            break;

        case 'logout':
            require_once ENGINE_DIR.'/modules/logout.php';           
            break;
        
        case 'profile':
            require_once ENGINE_DIR.'/modules/profile.php';
            break;
        
        case 'static':
            require_once ENGINE_DIR.'/modules/static.php';
            break;

        case 'news':
            require_once ENGINE_DIR.'/modules/news.php';
            break;

        case 'recdoc':
            require_once ENGINE_DIR.'/modules/recdoc.php';
            break;
        
        default:
            $alerts->set_error('Oшибка', 'Такой страницы или файла не существует!', 404);
            Store::set('title', 'Страница не найдена!');
            break; 
    }
?>
