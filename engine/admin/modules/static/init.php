<?php

    require_once ENGINE_DIR.'/includes/checkFeild.php';
    require_once ENGINE_DIR.'/includes/pagination.php';
    
    if(isset($_GET['action']) and $_GET['action'] == 'delete'){

        if($db->table('static')->where('id', '=', $_GET['delete'])->delete()){
            showSuccess('Страница удалена!','Выбраная страница успешно удалена!', MODULE_URL);
        }
        else showError('Ошибка удаления!', 'Неизвестная ошибка!', MODULE_URL);

    }
    elseif(isset($_GET['delete'])){

        showConfirm('Удаление страницы', 'Вы действительно хотите удалить выбранную страницу?', addGetParam('action','delete'), MODULE_URL);
    
    }
    elseif(isset($_GET['id'])){

        $crumbs->add(Store::set('title', 'Редактирование страницы'), '');

        if($static = $db->table('static')->where('id', '=', $_GET['id'])->first()){

            if(isset($_POST['save'])){

                $alerts->set_error_if(!CheckField::empty($_POST['title']), 'Ошибка изменения!', 'Вы не ввели название страницы!', 564);
            
                $alerts->set_error_if(!CheckField::empty($_POST['url']), 'Ошибка изменения!', 'Вы не ввели адрес  страницы!', 565);
                
                $alerts->set_error_if(!CheckField::empty($_POST['template']), 'Ошибка изменения!', 'Вы не ввели текст страницы!', 566);
    
                if($alerts->is_empty()){
                    $db->table('static')->where('id', '=' , $static['id'])->update([
                        'url' => $_POST['url'],
                        'title' => $_POST['title'],
                        'template' => [
                            'html' => true,
                            'value' => $_POST['template'],
                        ],
                        'date_edit' => time(),
                    ]);

                    if($db->result){

                        return showSuccess('Страница изменина!','Успешно изменена страница!', MODULE_URL);

                    }
                    else $alerts->set_error('Ошибка изменения!', 'Неизвестная ошибка!', $db->error_num);
                }
            }

            $tpl->save('content', 'edit', [
                'static' => $static
            ], MODULE_SKIN_DIR);
        }
        else $alerts->set_error('Oшибка', 'Такой страницы не существует!', 404);

    }
    elseif(isset($_GET['action']) and $_GET['action'] == 'addnew'){

        $crumbs->add(Store::set('title', 'Добавление страницы'), '');

        if(isset($_POST['add'])){
            $alerts->set_error_if(!CheckField::empty($_POST['title']), 'Ошибка добавления!', 'Вы не ввели название страницы!', 564);
            
            $alerts->set_error_if(!CheckField::empty($_POST['url']), 'Ошибка добавления!', 'Вы не ввели адрес  страницы!', 565);
            
            $alerts->set_error_if(!CheckField::empty($_POST['template']), 'Ошибка добавления!', 'Вы не ввели текст страницы!', 566);

            if($alerts->is_empty()){

                $db->table('static')->insert([
                    'url' => $_POST['url'],
                    'title' => $_POST['title'],
                    'template' => [
                        'html' => true,
                        'value' => $_POST['template'],
                    ],
                    'date_edit' => time(),
                    'date' => time()
                ]);

                if($db->result)
                    return showSuccess('Страница добавлена!','Успешно добавлена страница!', MODULE_URL);

                else $alerts->set_error('Ошибка добавления!', 'Неизвестная ошибка!', $db->error_num);
            }
        }

        $tpl->save('content', 'addnew', [], MODULE_SKIN_DIR);

    }
    else{
        $query = $db->table('static');
        
        (isset($_POST['count_on_page']) and $_POST['count_on_page'] > 0) ?: $_POST['count_on_page'] = 50;
        
        $count = $query->count();

        $pagination = new Pagination(
            function () use ($count) { return $count; },
            false,
            $_POST['count_on_page'],
            isset($_POST['page']) ? $_POST['page'] : 1
        );
    
        $pagination->gen_post_tpl();

        $tpl->save('content', 'main', [
            'count' => $count,
            'statics' => $query
                ->offset($pagination->get_begin_item())
                ->limit($_POST['count_on_page'])    
                ->get()
        ], MODULE_SKIN_DIR);
    }

?>
