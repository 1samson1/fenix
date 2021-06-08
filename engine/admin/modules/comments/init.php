<?php

    $crumbs->add($head['title'] = 'Комментарии', MODULE_URL);

    require_once ENGINE_DIR.'/includes/functions.php';
    require_once ENGINE_DIR.'/includes/checkFeild.php';
    
    if($_GET['action'] == 'delete'){

        if($db->remove_comment($_GET['delete'])){
            showSuccess('Комментарий удалён!','Выбраный комментарий успешно удалён!', MODULE_URL);
        }
        else showError('Ошибка удаления!', 'Неизвестная ошибка!', MODULE_URL);

    }
    elseif(isset($_GET['delete'])){

        showConfirm('Удаление комментария', 'Вы действительно хотите удалить выбранный комментарий?', addGetParam('action','delete'), MODULE_URL);
    
    }    
    else{
        
        $tpl->load('main.html', MODULE_SKIN_DIR);
    
        $db->get_comments();
        
        $tpl->set_repeat_block('/\[comments\](.*)\[\/comments\]/sU');
        
        while($comment = $db->get_row()){
        
            $tpl->set('{autor}', $comment['autor']);
            $tpl->set('{news}', $comment['news']);
            $tpl->set('{url-news}', '/news/'.$comment['news_id'].'/');            
            $tpl->set('{text}', $comment['text']);
            $tpl->set('{date}', date('Y.m.d H:i',$comment['date']));
            $tpl->set('{delete-link}', addGetParam('delete', $comment['id']));
            
    
            $tpl->copy_repeat_block();
            
        }
    
        $tpl->save_repeat_block();
    
        $tpl->save('{content}');

    }

?>
