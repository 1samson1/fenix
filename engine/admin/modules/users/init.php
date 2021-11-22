<?php

    require_once ENGINE_DIR.'/includes/checkFeild.php';
    require_once ENGINE_DIR.'/includes/upload.php';
    require_once ENGINE_DIR.'/includes/pagination.php';

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

    } else {
        $query = $db->table('users')
            ->select('groups.*', 'users.*')
            ->join('groups', 'users.group_id', '=', 'groups.id')
            ->orderBy('groups.allow_adminpanel', 'desc')
            ->orderBy('groups.id')
            ->orderBy('users.date_reg', 'desc');

        if(isset($_POST['search'])) {
            
            if(isset($_POST['login'][0])){
                $query->where('users.login', 'like', '%'.$_POST['login'].'%');
            }     
    
            if(isset($_POST['email'][0])){
                $query->where('users.email', 'like', '%'.$_POST['email'].'%');
            }        
            
            if(isset($_POST['surname'][0])){
                $query->where('users.surname', 'like', '%'.$_POST['surname'].'%');
            }        
    
            if(isset($_POST['name'][0])){
                $query->where('users.name', 'like', '%'.$_POST['name'].'%');
            }        
    
            if(isset($_POST['patronymic'][0])){
                $query->where('users.patronymic', 'like', '%'.$_POST['patronymic'].'%');
            }        
    
            if(isset($_POST['phone'][0])){
                $query->where('users.phone', 'like', '%'.$_POST['phone'].'%');
            }        
    
            if(isset($_POST['group'][0]) and (bool) Store::get('USER.allow_groups')){
                $query->where('users.group_id', '=', $_POST['group']);
            }        
    
            if(isset($_POST['begin_birthday'][0])){
                $query->where('users.birthday', '>=', ''.strtotime($_POST['begin_birthday']));
            }  
    
            if(isset($_POST['end_birthday'][0])){
                $query->where('users.birthday', '<', ''.strtotime($_POST['end_birthday']));
            }    
    
            if(isset($_POST['begin_regdate'][0])){
                $query->where('users.date_reg', '>=', ''.strtotime($_POST['begin_regdate']));
            }  
    
            if(isset($_POST['end_regdate'][0])){
                $query->where('users.date_reg', '<', ''.strtotime($_POST['end_regdate']));
            }    
        }  

        if( !(bool) Store::get('USER.allow_groups'))
            $query->where('groups.allow_adminpanel', '=', false);

        isset($_POST['count_on_page']) ?: $_POST['count_on_page'] = 50;

        $count = $query->count();

        $pagination = new Pagination(
            function () use ($count) {
               return $count;
            },
            false,
            $_POST['count_on_page'],
            isset($_POST['page']) ? $_POST['page'] : 1
        );

        $pagination->gen_post_tpl();

        $tpl->save('content', 'main', [
            'search' => $_POST,
            'count' => $count,
            'groups' => $db->table('groups')->get(),
            'users' => $query
                ->offset($pagination->get_begin_item())
                ->limit($_POST['count_on_page'])
                ->get()
        ], MODULE_SKIN_DIR);
    }
    
?>
