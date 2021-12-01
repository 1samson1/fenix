<?php

    require_once ENGINE_DIR.'/includes/pagination.php';
    
    if(isset($_GET['action']) and $_GET['action'] == 'delete'){

        if($db->table('comments')->where('id', '=', $_GET['delete'])->delete()){
            showSuccess('Комментарий удалён!','Выбраный комментарий успешно удалён!', MODULE_URL);
        }
        else showError('Ошибка удаления!', 'Неизвестная ошибка!', MODULE_URL);

    }
    elseif(isset($_GET['delete'])){

        showConfirm('Удаление комментария', 'Вы действительно хотите удалить выбранный комментарий?', addGetParam('action','delete'), MODULE_URL);
    
    }    
    else{
        $query = $db->table('comments')
            ->select('comments.*', 'news.title as news', 'users.login as autor')
            ->join('users', 'comments.user_id', '=' , 'users.id')
            ->join('news', 'comments.news_id', '=' , 'news.id');
        
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
            'comments' => $query
                ->orderBy('date', 'desc')
                ->offset($pagination->get_begin_item())
                ->limit($_POST['count_on_page'])
                ->get()
        ], MODULE_SKIN_DIR);

    }

?>
