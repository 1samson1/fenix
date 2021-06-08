<?php

    $crumbs->add($head['title'] = 'Новости', MODULE_URL);

    require_once ENGINE_DIR.'/includes/functions.php';
    require_once ENGINE_DIR.'/includes/checkFeild.php';
    
    if($_GET['action'] == 'delete'){

        if($db->remove_news($_GET['delete'])){
            showSuccess('Новость удалена!','Выбраная новость успешно удалена!', MODULE_URL);
        }
        else showError('Ошибка удаления!', 'Неизвестная ошибка!', MODULE_URL);

    }
    elseif(isset($_GET['delete'])){

        showConfirm('Удаление новости', 'Вы действительно хотите удалить выбранную новость?', addGetParam('action','delete'), MODULE_URL);
    
    }
    elseif(isset($_GET['id'])){
        
        $crumbs->add($head['title'] = 'Редактирование новости', '');

        $db->get_news_by_id($_GET['id']);

        if($news = $db->get_row()){

            if(isset($_POST['save'])){

                $alerts->set_error_if(!CheckField::empty($_POST['title']), 'Ошибка добавления!', 'Вы не ввели название страницы!', 564);
            
                $alerts->set_error_if(!CheckField::empty($_POST['short_news']), 'Ошибка добавления!', 'Вы не ввели короткое описание новости!', 575);
    
                $alerts->set_error_if(!CheckField::empty($_POST['full_news']), 'Ошибка добавления!', 'Вы не ввели полное описание новости!', 576);
    
                if(!isset($alerts->alerts_array[0])){
                    if($db->edit_news($_GET['id'], $_POST['title'], $_POST['short_news'], $_POST['full_news'], time())){

                        return showSuccess('Новость изменина!','Успешно изменена новость!', MODULE_URL);

                    }
                    else $alerts->set_error('Ошибка изменения!', 'Неизвестная ошибка!', $db->error_num);
                }
            }

            $tpl->load('edit.html', MODULE_SKIN_DIR);

            $tpl->set('{title}', $news['title']);            
            $tpl->set('{short_news}', $news['short_news']);
            $tpl->set('{full_news}', $news['full_news']);

            $tpl->save('{content}');
        }
        else $alerts->set_error('Oшибка', 'Такой новости не существует!', 404);

    }
    elseif($_GET['action'] == 'addnew'){

        $crumbs->add($head['title'] = 'Добавление новости', '');

        if(isset($_POST['add_news'])){
            $alerts->set_error_if(!CheckField::empty($_POST['title']), 'Ошибка добавления!', 'Вы не ввели название страницы!', 564);
            
            $alerts->set_error_if(!CheckField::empty($_POST['short_news']), 'Ошибка добавления!', 'Вы не ввели короткое описание новости!', 575);

            $alerts->set_error_if(!CheckField::empty($_POST['full_news']), 'Ошибка добавления!', 'Вы не ввели полное описание новости!', 576);

            if(!isset($alerts->alerts_array[0])){
                if($db->add_news($_SESSION['user']['id'], $_POST['title'], $_POST['short_news'], $_POST['full_news'], time(), time())){  

                    return showSuccess('Новость добавлена!','Успешно добавлена новость!', MODULE_URL);

                }
                else $alerts->set_error('Ошибка добавления!', 'Неизвестная ошибка!', $db->error_num);
            }
        }
        
        $tpl->load('addnew.html', MODULE_SKIN_DIR);

        $tpl->save('{content}');

    }
    else{
        
        $tpl->load('main.html', MODULE_SKIN_DIR);
    
        $db->get_news();
        
        $tpl->set_repeat_block('/\[news\](.*)\[\/news\]/sU');
        
        while($news = $db->get_row()){
        
            $tpl->set('{title}', $news['title']);
            $tpl->set('{autor}', $news['autor']);
            $tpl->set('{date_edit}', date('Y.m.d H:i',$news['date_edit']));
            $tpl->set('{date}', date('Y.m.d H:i',$news['date']));
            $tpl->set('{edit-link}',  addGetParam('id', $news['id']));
            $tpl->set('{delete-link}', addGetParam('delete', $news['id']));
            
    
            $tpl->copy_repeat_block();
            
        }
    
        $tpl->save_repeat_block();
    
        $tpl->set('{add_news_link}', addGetParam('action','addnew'));
    
        $tpl->save('{content}');

    }

?>
