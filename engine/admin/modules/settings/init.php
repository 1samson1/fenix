<?php 
    
    $crumbs->add($head['title'] = 'Настройки системы', MODULE_URL);

    require_once MODULE_DIR.'/save_conf.php';

    if(isset($_POST['save'])){
        if($_SESSION['user']['is_admin']){
            $save_conf = new Save_conf(ENGINE_DIR.'/data/config.php');
    
            $save_conf->set_int_option('reg_user_group', $_POST['save_conf']['reg_user_group']);
            $save_conf->set_bool_option('registration_on', isset($_POST['save_conf']['registration_on']));
            $save_conf->set_str_option('timezone', $_POST['save_conf']['timezone']);
            $save_conf->set_int_option('max_size_upload_img', $_POST['save_conf']['max_size_upload_img']);
            $save_conf->set_str_option('template', $_POST['save_conf']['template']);
            $save_conf->set_int_option('count_news_on_page', $_POST['save_conf']['count_news_on_page']);
    
            $save_conf->save();    
            
            return showSuccess('Изменения сохранены!','Настройки системы были сохранины!', MODULE_URL);
        }
        else return showError('Ошибка доступа!', 'У вас не достаточно прав!', MODULE_URL);
    }


    $tpl->load('main.html', MODULE_SKIN_DIR);

    /* Select group registration users */

    $db->get_groups();
    while ($group = $db->get_row()) {
        $groups .= '<option value="'.$group['id'].'" '.($group['id'] == $config['reg_user_group']?'selected':'').'>'.$group['group_name'].'</option>';
    }
    $tpl->set('{groups}', $groups);

    /* Select registration on */
    
    if($config['registration_on']) $tpl->set('{registration_on}', 'checked');
    else $tpl->set('{registration_on}', '');
    
    /* Timezones show list */

    require_once MODULE_DIR.'/timezones.php';

    foreach($timezones as $key => $value){
        $timezone .= '<option value="'.$key.'" '.($key == $config['timezone']?'selected':'').'>'.$value.'</option>';
    }
    $tpl->set('{timezones}', $timezone);

    /* Maximum allow weight file image */
    
    $tpl->set('{max_size_upload_img}', $config['max_size_upload_img']);

    /* Template */

    require_once ENGINE_DIR.'/includes/files.php';

    foreach (Files::get_dirs(ROOT_DIR.'/templates/') as $template){
        $templates .= '<option value="'.$template.'" '.($template == $config['template']?'selected':'').'>'.$template.'</option>';
    }
    $tpl->set('{templates}', $templates);


    /* Сount news on page */
    
    $tpl->set('{count_news_on_page}', $config['count_news_on_page']);

    $tpl->save('{content}');

?>
