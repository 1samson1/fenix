<?php

    require_once ENGINE_DIR.'/includes/functions.php';

    require_once ENGINE_DIR.'/includes/checkFeild.php';

    if($_GET['action'] == 'addnew'){

        if(isset($_POST['add_static'])){
            $alerts->set_error_if(!CheckField::empty($_POST['title']), 'Ошибка добавления!', 'Вы не ввели название страницы!', 564);
            
            $alerts->set_error_if(!CheckField::empty($_POST['url']), 'Ошибка добавления!', 'Вы не ввели адрес  страницы!', 565);
            
            $alerts->set_error_if(!CheckField::empty($_POST['template']), 'Ошибка добавления!', 'Вы не ввели текст страницы!', 566);

            if(!isset($alerts->alerts_array[0])){
                if($db->add_static($_POST['url'], $_POST['title'], $_POST['template'], time(), time())){
                    $alerts->set_success('Страница добавлена!', 'Успешно добавлена страница!');
                }
                else $alerts->set_error('Ошибка добавления!', 'Неизвестная ошибка!', $db->error_num);
            }
        }

        $tpl->load('addnew.html', MODULE_SKIN_DIR);

        $tpl->save('{content}');
    }
    else{
        
        $tpl->load('main.html', MODULE_SKIN_DIR);
    
        $db->get_statics();
        
        $tpl->set_repeat_block('/\[statics\](.*)\[\/statics\]/sU');
        
        while($static = $db->get_row()){
        
            $tpl->set('{title}', $static['title']);
            $tpl->set('{url}', $static['url']);
            $tpl->set('{url-link}', '/static/'.$static['url'].'/');
            $tpl->set('{date_edit}', date('Y.m.d H:i',$static['date_edit']));
            $tpl->set('{date}', date('Y.m.d H:i',$static['date']));
            
    
            $tpl->copy_repeat_block();
            
        }
    
        $tpl->save_repeat_block();
    
        $tpl->set('{add_static_link}', addGetParam('action','addnew'));
    
        $tpl->save('{content}');

    }

?>
