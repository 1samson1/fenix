<?php 
    
    require_once MODULE_DIR.'/save_conf.php';
    require_once MODULE_DIR.'/timezones.php';
    require_once ENGINE_DIR.'/includes/files.php';

    if(isset($_POST['save'])){
       
        $save_conf = new Save_conf(ENGINE_DIR.'/data/config.php');

        $save_conf->set_int_option('reg_user_group', $_POST['reg_user_group']);
        $save_conf->set_bool_option('registration_on', isset($_POST['registration_on']));
        $save_conf->set_str_option('timezone', $_POST['timezone']);
        $save_conf->set_int_option('max_size_upload_img', $_POST['max_size_upload_img']);
        $save_conf->set_str_option('template', $_POST['template']);
        $save_conf->set_int_option('count_news_on_page', $_POST['count_news_on_page']);
        $save_conf->set_int_option('count_comments_on_page', $_POST['count_comments_on_page']);
        $save_conf->set_int_option('who_send_mail', $_POST['who_send_mail']);

        $save_conf->save();    
        
        return showSuccess('Изменения сохранены!','Настройки системы были сохранины!', MODULE_URL);
        
    }

    $tpl->save('content', 'main', [
        'groups' => $db->table('groups')->get(),
        'timezones' => $timezones,
        'templates' => Files::get_dirs(ROOT_DIR.'/templates/'),
    ], MODULE_SKIN_DIR);

?>
