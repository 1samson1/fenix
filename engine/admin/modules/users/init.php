<?php

    $crumbs->add($head['title'] = 'Пользователи', MODULE_URL);

    require_once ENGINE_DIR.'/includes/functions.php';
    require_once ENGINE_DIR.'/includes/checkFeild.php';
    require_once ENGINE_DIR.'/includes/upload.php';

    if($_GET['action'] == 'delete'){

        if($db->remove_user($_GET['delete'])){
            showSuccess('Пользователь удалён!','Выбраный пользователь успешно удалён!', MODULE_URL);
        }
        else showError('Ошибка удаления!', 'Неизвестная ошибка!', MODULE_URL);

    }
    elseif(isset($_GET['delete'])){

        showConfirm('Удаление пользователя', 'Вы действительно хотите удалить выбранного пользователя?', addGetParam('action','delete'), MODULE_URL);
    
    }
    elseif(isset($_GET['id'])){

        $crumbs->add($head['title'] = 'Редактирование пользователя', '');

        $db->get_user_by_id($_GET['id']);

        if($user = $db->get_row()){

            if(isset($_POST['edit_user'])){
    
                $alerts->set_error_if(!CheckField::login($_POST['login']), 'Ошибка изменения данных пользователя!', 'Некорректный логин!', 201);

                $alerts->set_error_if(!CheckField::email($_POST['email']), 'Ошибка изменения данных пользователя!', 'Некорректный email!', 202);

                if(isset($_POST['password'][0]) || isset($_POST['repassword'][0])){
                    
                   $alerts->set_error_if(!CheckField::confirm_pass($_POST['password'],$_POST['repassword']), 'Ошибка изменения данных пользователя!', 'Пароль не совпадает с формой подтверждения!', 204);
                
                }
                
                $foto = new Upload_Image('foto', 'foto_'.$user['id'], 'avatars');

                $alerts->alerts_array = array_merge($alerts->alerts_array, $foto->errors);

                if(!isset($alerts->alerts_array[0])){

                    if($db->edit_user($user['id'], $_POST['group'], $_POST['name'], $_POST['surname'], $_POST['login'], $_POST['email'], $_POST['password'], $foto->filepath, isset($_POST['delete_foto']))){
                        
                        if(isset($_POST['delete_foto'])){
                            delete_file($user['foto']);
                        }
                                                
                        if($foto->filepath){
                            delete_file($user['foto']);
                            $foto->save();
                        }

                        return showSuccess('Данные профиля обновлены!','Данные профиля успешно обновлены!', MODULE_URL);
                    
                    }
                    else $alerts->set_error('Ошибка изменения данных пользователя!', 'Неизвестная ошибка!', $db->error_num);
                }	
            
            }

            $tpl->load('edit.html', MODULE_SKIN_DIR);

            $tpl->set('{login}', $user['login']);
            $tpl->set('{email}', $user['email']);
            $tpl->set('{group}', $user['group_name']);
            $tpl->set('{surname}', $user['surname']);
            $tpl->set('{name}', $user['name']);
            
            if($user['foto']) $foto = '/'.$user['foto'];
            else $foto = '{SKIN}/img/noavatar.png';
            $tpl->set('{foto}', $foto);

            $db->get_groups();
            while ($group = $db->get_row()) {
                $groups .= '<option value="'.$group['id'].'" '.($group['id'] == $user['group_id']?'selected':'').'>'.$group['group_name'].'</option>';
            }
            $tpl->set('{groups}', $groups);
            

            $tpl->save('{content}');
        }
        else $alerts->set_error('Oшибка', 'Такого пользователя не существует!', 404);

    }
    elseif($_GET['action'] == 'addnew'){

        $crumbs->add($head['title'] = 'Добавление пользователя', '');  

        if(isset($_POST['add_user'])){

            $alerts->set_error_if(!CheckField::login($_POST['login']), 'Ошибка регистрации', 'Некорректный логин', 201);

			$alerts->set_error_if(!CheckField::email($_POST['email']), 'Ошибка регистрации', 'Некорректный email', 202);

			$alerts->set_error_if(!CheckField::empty($_POST['password']), 'Ошибка регистрации', 'Вы не ввели пароль', 203);

			$alerts->set_error_if(!CheckField::confirm_pass($_POST['password'],$_POST['repassword']), 'Ошибка регистрации', 'Пароль не совпадает с формой подтверждения', 204);

			if(!isset($alerts->alerts_array[0])){

				if($db->reg_user($_POST['group'], $_POST['login'], $_POST['name'], $_POST['surname'], $_POST['login'], $_POST['email'], $_POST['password'])){
					
                   return showSuccess('Пользователь добавлен!','Успешно добавлен пользователь!', MODULE_URL);

				}
				else $alerts->set_error('Ошибка добавления!', 'Неизвестная ошибка!', $db->error_num);
			}

        }
        
        $tpl->load('addnew.html', MODULE_SKIN_DIR);

        $db->get_groups();
        while ($group = $db->get_row()) {
            $groups .= '<option value="'.$group['id'].'" '.($group['id'] == $config['reg_user_group']?'selected':'').'>'.$group['group_name'].'</option>';
        }
        $tpl->set('{groups}', $groups);

        $tpl->save('{content}');

    }
    else{
        $tpl->load('main.html', MODULE_SKIN_DIR);

        $db->get_users();
        
        $tpl->set_repeat_block('/\[users\](.*)\[\/users\]/sU');
        
        while($user = $db->get_row()){
        
            $tpl->set('{login}', $user['login']);
            $tpl->set_block_param('/\[groups=(.+)\](.*)\[\/groups=\1\]/Us', $user['group_id']);
            $tpl->set('{group}', $user['group_name']);
            $tpl->set('{surname}', $user['surname']);
            $tpl->set('{name}', $user['name']);
            $tpl->set('{date_reg}', date('Y.m.d H:i', $user['date_reg']));
            $tpl->set('{edit-link}',  addGetParam('id', $user['id']));
            $tpl->set('{delete-link}', addGetParam('delete', $user['id']));
            
    
            $tpl->copy_repeat_block();
            
        }
    
        $tpl->save_repeat_block();
    
        $tpl->set('{add_user_link}', addGetParam('action','addnew'));
    
        $tpl->save('{content}');

    }
    
?>
