<?php   

    /* LOAD AUTHORIZATION FILE ======================================== */

    require_once ENGINE_DIR.'/modules/auth.php';

    /* BAD ROURER  ======================================== */

    switch ($_GET['do']) {
        case 'main':
            include_once ENGINE_DIR.'/modules/main.php';
            break;

        case 'registration':
            include_once ENGINE_DIR.'/modules/registration.php';
            break;

        case 'logout':
            include_once ENGINE_DIR.'/modules/logout.php';           
            break;
        
        case 'profile':
            include_once ENGINE_DIR.'/modules/profile.php';
            break;
        
        default:
            $alerts->set_error('Oшибка', 'Такой страницы или файла не существует!', 404);
            $head['title'] = 'Страница не найдена!';
            break;
    }  
    
    /* LOAD HEAD FILE ======================================== */

    include_once ENGINE_DIR.'/modules/head.php';

    /* LOAD ALERTS TEMPLATE ======================================== */

    include_once ENGINE_DIR.'/modules/alerts.php';

    /* LOAD LOGIN TEMPLATE ========================================= */

    include_once ENGINE_DIR.'/modules/login.php';

    /* LOAD BASE TEMPLATE ========================================= */

    require_once ENGINE_DIR.'/modules/base.php';

?>
