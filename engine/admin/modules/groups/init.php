<?php

    $crumbs->add(Store::set('title', 'Группы пользователей'), MODULE_URL);

    require_once ENGINE_DIR.'/includes/checkFeild.php';

    if(isset($_GET['action']) and $_GET['action'] == 'delete'){

        if($db->table('groups')->where('id', '=', $_GET['delete'])->delete()){
            showSuccess('Группа удалёна!','Выбраная группа успешно удалёна!', MODULE_URL);
        }
        else showError('Ошибка удаления!', 'Неизвестная ошибка!', MODULE_URL);

    }
    elseif(isset($_GET['delete'])){

        showConfirm('Удаление пользователя', 'Вы действительно хотите удалить выбранную группу?', addGetParam('action','delete'), MODULE_URL);

    }
    elseif(isset($_GET['id'])){

        $crumbs->add(Store::set('title', 'Редактирование группы'), '');

        if($group = $db->table('groups')->where('id', '=', $_GET['id'])->first()){

            if(isset($_POST['edit'])){
    
                $alerts->set_error_if(!CheckField::empty($_POST['group_name']), 'Ошибка добавления', 'Вы не ввели название группы', 203);

                if($alerts->is_empty()){
                    $db->table('groups')->where('id', '=' , $group['id'])->update([
                        'group_name' => $_POST['group_name'],
                        'cant_delete' => false,
                        'allow_adminpanel' => isset($_POST['allow_adminpanel']),
                        'allow_settings' => isset($_POST['allow_settings']),
                        'allow_static' => isset($_POST['allow_static']),
                        'allow_groups' => isset($_POST['allow_groups']),
                        'allow_users' => isset($_POST['allow_users']),
                        'allow_news' => isset($_POST['allow_news']),
                        'allow_comments' => isset($_POST['allow_comments']),
                        'allow_specialties' => isset($_POST['allow_specialties']),
                        'allow_doctors' => isset($_POST['allow_doctors']),
                        'allow_recdoc' => isset($_POST['allow_recdoc']),
                    ]);

                    if($db->result)
                        return showSuccess('Группа пользователей изменина!','Успешно изменина группа пользователей!', MODULE_URL);

                    else $alerts->set_error('Ошибка изменения!', 'Неизвестная ошибка!', $db->error_num);
                }
            }

            $tpl->save('content', 'edit', [
                'group' => $group
            ],MODULE_SKIN_DIR);
        }
        else $alerts->set_error('Oшибка', 'Такой группы не существует!', 404);
        
    }
    elseif(isset($_GET['action']) and $_GET['action'] == 'addnew'){

        $crumbs->add(Store::set('title', 'Добавление группы'), '');

        if(isset($_POST['add'])){

            $alerts->set_error_if(!CheckField::empty($_POST['group_name']), 'Ошибка добавления', 'Вы не ввели название группы', 203);

            if($alerts->is_empty()){

                $db->table('groups')->insert([
                    'group_name' => $_POST['group_name'],
                    'cant_delete' => false,
                    'allow_adminpanel' => isset($_POST['allow_adminpanel']),
                    'allow_settings' => isset($_POST['allow_settings']),
                    'allow_static' => isset($_POST['allow_static']),
                    'allow_groups' => isset($_POST['allow_groups']),
                    'allow_users' => isset($_POST['allow_users']),
                    'allow_news' => isset($_POST['allow_news']),
                    'allow_comments' => isset($_POST['allow_comments']),
                    'allow_specialties' => isset($_POST['allow_specialties']),
                    'allow_doctors' => isset($_POST['allow_doctors']),
                    'allow_recdoc' => isset($_POST['allow_recdoc']),
                ]);

                if($db->result)
                    return showSuccess('Группа пользователей добавлена!','Успешно добавлена группа пользователей!', MODULE_URL);

                else $alerts->set_error('Ошибка добавления!', 'Неизвестная ошибка!', $db->error_num);
            }

        }

        $tpl->save('content', 'addnew', [], MODULE_SKIN_DIR);
    }
    else{

        $tpl->save('content', 'main', [
            'groups' => $db->table('groups')->orderBy('allow_adminpanel', 'desc')->get()
        ], MODULE_SKIN_DIR);

    }
?>
