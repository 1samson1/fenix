<?php

    $crumbs->add($head['title'] = 'Статические страницы', MODULE_URL);

    require_once ENGINE_DIR.'/includes/functions.php';
    require_once ENGINE_DIR.'/includes/checkFeild.php';
    
    if($_GET['action'] == 'delete'){

        if($db->remove_static($_GET['delete'])){
            showSuccess('Страница удалена!','Выбраная страница успешно удалена!', MODULE_URL);
        }
        else showError('Ошибка удаления!', 'Неизвестная ошибка!', MODULE_URL);

    }
    elseif(isset($_GET['delete'])){

        showConfirm('Удаление страницы', 'Вы действительно хотите удалить выбранную страницу?', addGetParam('action','delete'), MODULE_URL);
    
    }
    elseif(isset($_GET['id'])){

        $crumbs->add($head['title'] = 'Редактирование страницы', '');

        $db->get_static_by_id($_GET['id']);

        if($static = $db->get_row()){

            if(isset($_POST['save'])){

                $alerts->set_error_if(!CheckField::empty($_POST['title']), 'Ошибка изменения!', 'Вы не ввели название страницы!', 564);
            
                $alerts->set_error_if(!CheckField::empty($_POST['url']), 'Ошибка изменения!', 'Вы не ввели адрес  страницы!', 565);
                
                $alerts->set_error_if(!CheckField::empty($_POST['template']), 'Ошибка изменения!', 'Вы не ввели текст страницы!', 566);
    
                if(!isset($alerts->alerts_array[0])){
                    if($db->edit_static($_GET['id'], $_POST['url'], $_POST['title'], $_POST['template'], time())){

                        return showSuccess('Страница изменина!','Успешно изменена страница!', MODULE_URL);

                    }
                    else $alerts->set_error('Ошибка изменения!', 'Неизвестная ошибка!', $db->error_num);
                }
            }

            $tpl->load('edit.html', MODULE_SKIN_DIR);

            $tpl->set('{title}', $static['title']);
            $tpl->set('{url}', $static['url']);
            $tpl->set('{template}', $static['template']);

            $tpl->save('{content}');
        }
        else $alerts->set_error('Oшибка', 'Такой страницы не существует!', 404);

    }
    elseif($_GET['action'] == 'addnew'){

        $crumbs->add($head['title'] = 'Добавление страницы', '');

        if(isset($_POST['add_static'])){
            $alerts->set_error_if(!CheckField::empty($_POST['title']), 'Ошибка добавления!', 'Вы не ввели название страницы!', 564);
            
            $alerts->set_error_if(!CheckField::empty($_POST['url']), 'Ошибка добавления!', 'Вы не ввели адрес  страницы!', 565);
            
            $alerts->set_error_if(!CheckField::empty($_POST['template']), 'Ошибка добавления!', 'Вы не ввели текст страницы!', 566);

            if(!isset($alerts->alerts_array[0])){
                if($db->add_static($_POST['url'], $_POST['title'], $_POST['template'], time(), time())){  

                    return showSuccess('Страница добавлена!','Успешно добавлена страница!', MODULE_URL);

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
            $tpl->set('{edit-link}',  addGetParam('id', $static['id']));
            $tpl->set('{delete-link}', addGetParam('delete', $static['id']));
            
    
            $tpl->copy_repeat_block();
            
        }
    
        $tpl->save_repeat_block();
    
        $tpl->set('{add_static_link}', addGetParam('action','addnew'));
    
        $tpl->save('{content}');

    }

?>
