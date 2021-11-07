<?php

    require_once ENGINE_DIR.'/includes/checkFeild.php';
    require_once ENGINE_DIR.'/includes/upload.php';

    if(isset($_GET['action']) and $_GET['action'] == 'delete'){

        if($db->table('users')->where('id', '=', $_GET['delete'])->delete()){
            showSuccess('Пользователь удалён!','Выбраный пользователь успешно удалён!', MODULE_URL);
        }
        else showError('Ошибка удаления!', 'Неизвестная ошибка!', MODULE_URL);

    }
    elseif(isset($_GET['delete'])){

        showConfirm('Удаление пользователя', 'Вы действительно хотите удалить выбранного пользователя?', addGetParam('action','delete'), MODULE_URL);
    
    }
    elseif(isset($_GET['id'])){

        $crumbs->add(Store::set('title', 'Редактирование пользователя'), '');

        if($user = $db->table('users')->where('id', '=', $_GET['id'])->first()){

            if(isset($_POST['edit'])){
    
                $alerts->set_error_if(!CheckField::login($_POST['login']), 'Ошибка изменения данных пользователя!', 'Некорректный логин!', 201);

                $alerts->set_error_if(!CheckField::email($_POST['email']), 'Ошибка изменения данных пользователя!', 'Некорректный email!', 202);

			    if(isset($_POST['password'][0]) or isset($_POST['repassword'][0])){
                    
                    $alerts->set_error_if(!CheckField::confirm_pass($_POST['password'],$_POST['repassword']), 'Ошибка изменения данных пользователя!', 'Пароль не совпадает с формой подтверждения!', 204);
                
                }
                
                $foto = new Upload_Image('foto', false, 'avatars');

                $alerts->merge($foto->errors);

                if($alerts->is_empty()){
                    $fields = [
                        'name' => $_POST['name'],
                        'surname' => $_POST['surname'],
						'patronymic' => $_POST['patronymic'],
                        'login' => $_POST['login'],
                        'email' => $_POST['email'],
						'phone' => $_POST['phone'],
						'birthday' => strtotime($_POST['birthday']),
						'gender' => $_POST['gender'],
						'adress' => $_POST['adress'],
                    ];

                    if(isset($_POST['password'][0]))
                        $fields['password'] = $db->hash($_POST['password']); 

                    if((bool) Store::get('USER.allow_groups'))
                        $fields['group_id'] = $_POST['group'];

                    if(isset($_POST['delete_foto']))
                        $fields['foto'] = '';
                    
                    if($foto->filepath)
                        $fields['foto'] = $foto->filepath;

                    if($db->table('users')->where('id', '=' , $user['id'])->update($fields)){
                        
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

            $tpl->save('content', 'edit', [
                'groups' => $db->table('groups')->get(),
                'user' => $user
            ], MODULE_SKIN_DIR);
        }
        else $alerts->set_error('Oшибка', 'Такого пользователя не существует!', 404);

    }
    elseif(isset($_GET['action']) and $_GET['action'] == 'addnew'){

        $crumbs->add(Store::set('title', 'Добавление пользователя'), '');

        if(isset($_POST['add'])){

            $alerts->set_error_if(!CheckField::login($_POST['login']), 'Ошибка регистрации', 'Некорректный логин', 201);

			$alerts->set_error_if(!CheckField::email($_POST['email']), 'Ошибка регистрации', 'Некорректный email', 202);

			$alerts->set_error_if(!CheckField::empty($_POST['password']), 'Ошибка регистрации', 'Вы не ввели пароль', 203);

			$alerts->set_error_if(!CheckField::confirm_pass($_POST['password'],$_POST['repassword']), 'Ошибка регистрации', 'Пароль не совпадает с формой подтверждения', 204);

            $foto = new Upload_Image('foto', false, 'avatars');

            $alerts->merge($foto->errors);

            if($alerts->is_empty()){
                $fields = [
                    'name' => $_POST['name'],
                    'surname' => $_POST['surname'],
                    'patronymic' => $_POST['patronymic'],
                    'login' => $_POST['login'],
                    'email' => $_POST['email'],
                    'phone' => $_POST['phone'],
                    'birthday' => strtotime($_POST['birthday']),
                    'gender' => $_POST['gender'],
                    'adress' => $_POST['adress'],
                    'password' => $db->hash($_POST['password']),
                    'date_reg' => time()
                ];

                if((bool) Store::get('USER.allow_groups'))
                    $fields['group_id'] = $_POST['group'];
                else
                    $fields['group_id'] = Store::get('config.reg_user_group');

                if($foto->filepath)
                    $fields['foto'] = $foto->filepath;

				if($db->table('users')->insert($fields)){
                    if($foto->filepath)
                        $foto->save();
                    
                    return showSuccess('Пользователь добавлен!','Успешно добавлен пользователь!', MODULE_URL);
                }
				else $alerts->set_error('Ошибка добавления!', 'Неизвестная ошибка!', $db->error_num);
			}

        }
        
        $tpl->save('content', 'addnew', [
            'groups' => $db->table('groups')->get()
        ], MODULE_SKIN_DIR);

    }
    else{
        $query = $db->table('users')
            ->select('groups.*', 'users.*')
            ->join('groups', 'users.group_id', '=', 'groups.id')
            ->orderBy('groups.allow_adminpanel', 'desc')
            ->orderBy('groups.id')
            ->orderBy('users.date_reg', 'desc');

        if( !(bool) Store::get('USER.allow_groups'))
            $query->where('groups.allow_adminpanel', '=', false);

        $tpl->save('content', 'main', [
            'users' => $query->get()
        ], MODULE_SKIN_DIR);
    }
    
?>
