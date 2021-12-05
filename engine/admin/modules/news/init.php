<?php

    require_once ENGINE_DIR.'/includes/checkFeild.php';
    require_once ENGINE_DIR.'/includes/pagination.php';
    
    if(isset($_GET['action']) and $_GET['action'] == 'delete'){

        if($db->table('news')->where('id', '=', $_GET['delete'])->delete()){
            showSuccess('Новость удалена!','Выбраная новость успешно удалена!', MODULE_URL);
        }
        else showError('Ошибка удаления!', 'Неизвестная ошибка!', MODULE_URL);

    }
    elseif(isset($_GET['delete'])){

        showConfirm('Удаление новости', 'Вы действительно хотите удалить выбранную новость?', addGetParam('action','delete'), MODULE_URL);
    
    }
    elseif(isset($_GET['id'])){
        
        $crumbs->add(Store::set('title', 'Редактирование новости'), '');

        if($news = $db->table('news')->where('id', '=', $_GET['id'])->first()){

            if(isset($_POST['save'])){

                $alerts->set_error_if(!CheckField::empty($_POST['title']), 'Ошибка добавления!', 'Вы не ввели название страницы!', 564);
            
                $alerts->set_error_if(!CheckField::empty($_POST['short_news']), 'Ошибка добавления!', 'Вы не ввели короткое описание новости!', 575);
    
                $alerts->set_error_if(!CheckField::empty($_POST['full_news']), 'Ошибка добавления!', 'Вы не ввели полное описание новости!', 576);
    
                if($alerts->is_empty()){

                    $db->table('news')->where('id', '=' , $news['id'])->update([
                        'title' => $_POST['title'],
                        'short_news' => [
                            'html' => true,
                            'value' => $_POST['short_news'],
                        ],
                        'full_news' => [
                            'html' => true,
                            'value' => $_POST['full_news'],
                        ],
                        'date_edit' => time()
                    ]);

                    if($db->result){

                        return showSuccess('Новость изменина!','Успешно изменена новость!', MODULE_URL);

                    }
                    else $alerts->set_error('Ошибка изменения!', 'Неизвестная ошибка!', $db->error_num);
                }
            }

            $tpl->save('content', 'edit', [
                'news' => $news
            ], MODULE_SKIN_DIR);
        }
        else $alerts->set_error('Oшибка', 'Такой новости не существует!', 404);

    }
    elseif(isset($_GET['action']) and $_GET['action'] == 'addnew'){

        $crumbs->add(Store::set('title', 'Добавление новости'), '');

        if(isset($_POST['add'])){
            $alerts->set_error_if(!CheckField::empty($_POST['title']), 'Ошибка добавления!', 'Вы не ввели название страницы!', 564);
            
            $alerts->set_error_if(!CheckField::empty($_POST['short_news']), 'Ошибка добавления!', 'Вы не ввели короткое описание новости!', 575);

            $alerts->set_error_if(!CheckField::empty($_POST['full_news']), 'Ошибка добавления!', 'Вы не ввели полное описание новости!', 576);

            if($alerts->is_empty()){

                $db->table('news')->insert([
                    'autor' => Store::get('USER.id'),
                    'title' => $_POST['title'],
                    'short_news' => [
                        'html' => true,
                        'value' => $_POST['short_news'],
                    ],
                    'full_news' => [
                        'html' => true,
                        'value' => $_POST['full_news'],
                    ],
                    'date_edit' => time(),
                    'date' => time()
                ]);

                if($db->result)
                    return showSuccess('Новость добавлена!','Успешно добавлена новость!', MODULE_URL);

                else $alerts->set_error('Ошибка добавления!', 'Неизвестная ошибка!', $db->error_num);
            }
        }

        $tpl->save('content', 'addnew', [], MODULE_SKIN_DIR);

    }
    else{
        $query = $db->table('news')
            ->select('news.*', 'users.login as autor')
            ->join('users', 'news.autor', '=', 'users.id');
        
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
            'news' => $query
                ->offset($pagination->get_begin_item())
                ->limit($_POST['count_on_page'])
                ->get()
        ], MODULE_SKIN_DIR);
    }

?>
