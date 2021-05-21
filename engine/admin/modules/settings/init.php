<?php 
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
