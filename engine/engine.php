<?php   

    /* LOAD AUTHORIZATION FILE ======================================== */

    require_once ENGINE_DIR.'/modules/auth.php';

    /* BAD ROUTER  ======================================== */

    switch ($_GET['do']) {
        case 'main':
            require_once ENGINE_DIR.'/modules/main.php';
            break;

        case 'registration':
            require_once ENGINE_DIR.'/modules/registration.php';
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
            $head['title'] = 'Страница не найдена!';
            break;
    }  
    
    /* LOAD HEAD FILE ======================================== */

    require_once ENGINE_DIR.'/modules/head.php';

    /* LOAD ALERTS TEMPLATE ======================================== */

    require_once ENGINE_DIR.'/modules/alerts.php';

    /* LOAD LOGIN TEMPLATE ========================================= */

    require_once ENGINE_DIR.'/modules/login.php';

    /* LOAD BASE TEMPLATE ========================================= */

    require_once ENGINE_DIR.'/modules/base.php';

?>
