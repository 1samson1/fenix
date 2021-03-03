<?php   
    require_once ENGINE_DIR.'/modules/auth.php';

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
        
        case 'user':
            include_once ENGINE_DIR.'/modules/profile.php';
            break;
        
        default:
            $alerts->set_error('Oшибка', 'Такой страницы или файла не существует!', 404);
            break;
    }    

    include_once ENGINE_DIR.'/modules/head.php';

    /* LOAD ALERTS TEMPLATE ======================================== */

    include_once ENGINE_DIR.'/modules/alerts.php';

    /* LOAD LOGIN TEMPLATE ========================================= */

    include_once ENGINE_DIR.'/modules/login.php';

    /* LOAD BASE TEMPLATE ========================================= */

    require_once ENGINE_DIR.'/modules/base.php';

?>
