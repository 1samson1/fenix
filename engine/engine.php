<?php 

    require_once ENGINE_DIR.'/modules/login.php';

    switch ($_GET['do']) {
        case 'main':
            include_once ENGINE_DIR.'/modules/main.php';
            break;

        case 'registration':
            include_once ENGINE_DIR.'/modules/registration.php';
            break;
        
        case 'user':
            include_once ENGINE_DIR.'/modules/profile.php';
            break;
        
        default:
            $alerts->set_error('Oшибка', 'Такой страницы или файла не существует!', 404);
            break;
    }

    include_once ENGINE_DIR.'/modules/head.php';

    include_once ENGINE_DIR.'/modules/alerts.php';

    $tpl->load_tpl('base.html');

    if(!isset($tpl->data['{content}']))
        $tpl->set('{content}','');

    $tpl->set('{TEMPLATE}', $config['host_url'].'/templates/'.$config['template']);

    $tpl->set('{head}', $head);

    $tpl->set('{registration-link}', '/registration/');

    $tpl->print();
    
?>
