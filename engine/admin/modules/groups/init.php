<?php

$crumbs->add(Store::set('title', 'Группы пользователей'), MODULE_URL);

require_once ENGINE_DIR.'/includes/checkFeild.php';

// if($_GET['action'] == 'delete'){

//     if($db->remove_user($_GET['delete'])){
//         showSuccess('Пользователь удалён!','Выбраный пользователь успешно удалён!', MODULE_URL);
//     }
//     else showError('Ошибка удаления!', 'Неизвестная ошибка!', MODULE_URL);

// }
// elseif(isset($_GET['delete'])){

//     showConfirm('Удаление пользователя', 'Вы действительно хотите удалить выбранного пользователя?', addGetParam('action','delete'), MODULE_URL);

// }

if(isset($_GET['action'])){
    
    if($_GET['action'] == 'addnew'){

        $crumbs->add(Store::set('title', 'Добавление группы'), '');

        if(isset($_POST['add'])){

            $alerts->set_error_if(!CheckField::empty($_POST['group_name']), 'Ошибка добавления', 'Вы не ввели название группы', 203);
    
            if($alerts->is_empty()){
    
                if($db->reg_user($_POST['group'], $_POST['name'], $_POST['surname'], $_POST['login'], $_POST['email'], $_POST['password'])){
                    
                    return showSuccess('Пользователь добавлен!','Успешно добавлен пользователь!', MODULE_URL);
    
                }
                else $alerts->set_error('Ошибка добавления!', 'Неизвестная ошибка!', $db->error_num);
            }
    
        }

        $tpl->save('content', 'addnew', [], MODULE_SKIN_DIR);
    }

}
else{

    $tpl->save('content', 'main', [
        'groups' => $db->get_groups()
    ], MODULE_SKIN_DIR);

}
    

?>
