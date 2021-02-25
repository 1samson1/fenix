<?php 
    switch ($_GET['do']) {
        case 'main':
            include_once ENGINE_DIR.'/modules/main.php';
            break;

        case 'register':
            include_once ENGINE_DIR.'/modules/register.php';
            break;

        case 'login':
            include_once ENGINE_DIR.'/modules/main.php';
            break;
        
        case 'user':
            include_once ENGINE_DIR.'/modules/profile.php';
            break;
        
        default:
            echo "404 file not found";
            break;
    }

    $tpl->load_tpl('base.html');
    $tpl->set('{TEMPLATE}', $config['host_url'].'/templates/'.$config['template']);
    $tpl->print();
    
?>
