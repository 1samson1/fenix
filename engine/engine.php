<?php   

    // Создание экземпляра шаблонизатора
    $tpl = new Template(
        ROOT_DIR . 'templates' . DS . Store::get('config.template')
    ); 

    Store::set('TEMPLATE', webPath($tpl->dir));

    define('TEMPLATE_DIR', $tpl->dir); // Задание директории шаблонов

    /* LOAD AUTHORIZATION FILE ======================================== */

    require_once ENGINE_DIR.'modules/auth.php';

    /* BAD ROUTER  ======================================== */

    require_once ENGINE_DIR.'router.php';

    /* LOAD ALERTS TEMPLATE ======================================== */

    $tpl->save('alerts', 'alerts', ['alerts' => $alerts->all()]);

    /* LOAD LOGIN TEMPLATE ========================================= */

    require_once ENGINE_DIR.'modules/login.php';

    /* LOAD BASE TEMPLATE ========================================= */

    require_once ENGINE_DIR.'modules/base.php';

?>
