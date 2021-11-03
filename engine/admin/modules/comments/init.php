<?php

    require_once ENGINE_DIR.'/includes/checkFeild.php';
    
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
        
        $tpl->save('content', 'main', [
            'comments' => $db->table('comments')
                ->select('comments.*', 'news.title as news', 'users.login as autor')
                ->join('users', 'comments.user_id', '=' , 'users.id')
                ->join('news', 'comments.news_id', '=' , 'news.id')
                ->orderBy('date', 'desc')
                ->get()
        ], MODULE_SKIN_DIR);

    }

?>
