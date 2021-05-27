<?php

    require_once ENGINE_DIR.'/includes/functions.php';
    require_once ENGINE_DIR.'/includes/checkFeild.php';
    
    if($_GET['action'] == 'delete'){

        if($db->remove_static($_GET['delete'])){
            showSuccess('Комментарий удалён!','Выбраная комментарий успешно удалён!', MODULE_URL);
        }
        else showError('Ошибка удаления!', 'Неизвестная ошибка!', MODULE_URL);

    }
    elseif(isset($_GET['delete'])){

        showConfirm('Удаление страницы', 'Вы действительно хотите удалить выбранный комментарий?', addGetParam('action','delete'), MODULE_URL);
    
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
