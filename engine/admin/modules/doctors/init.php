<?php

    require_once ENGINE_DIR.'/includes/checkFeild.php';
    require_once ENGINE_DIR.'/includes/upload.php';
    require_once ENGINE_DIR.'/includes/pagination.php';
    
    if(isset($_GET['action']) and $_GET['action'] == 'delete'){

        if($db->table('doctors')->where('id', '=', $_GET['delete'])->delete()){
            showSuccess('Врач удалён!','Выбраный врач успешно удалён!', MODULE_URL);
        }
        else showError('Ошибка удаления!', 'Неизвестная ошибка!', MODULE_URL);

    }
    elseif(isset($_GET['delete'])){

        showConfirm('Удаление врача', 'Вы действительно хотите удалить выбранного врача?', addGetParam('action','delete'), MODULE_URL);
    
    }
    elseif(isset($_GET['id'])){

        $crumbs->add(Store::set('title', 'Редактирование врача'), '');

        if($doctor = $db->table('doctors')->where('id', '=', $_GET['id'])->first()){

            if(isset($_POST['edit'])){
    
                $alerts->set_error_if(!CheckField::empty($_POST['name']), 'Ошибка добавления!', 'Вы не ввели ФИО!', 287);
			
                $alerts->set_error_if(!CheckField::empty($_POST['kabinet']), 'Ошибка добавления!', 'Вы не ввели кабинет!', 288);

                $foto = new Upload_Image('foto', false, 'doctors');

                $alerts->merge($foto->errors);

                if($alerts->is_empty()){

                    $fields = [
                        'name' => $_POST['name'],
                        'specialty_id' => $_POST['specialty'],
                        'qualification' => [
                            'html' => true,
                            'value' => $_POST['qualification']
                        ],
                        'kabinet' => $_POST['kabinet'],
                        'mon' => isset($_POST['mon']),
                        'tue' => isset($_POST['tue']),
                        'wed' => isset($_POST['wed']),
                        'thu' => isset($_POST['thu']),
                        'fri' => isset($_POST['fri']),
                        'sat' => isset($_POST['sat']),
                        'sun' => isset($_POST['sun'])
                    ];
                    if($foto->filepath)
                        $fields['foto'] = $foto->filepath;

                    if($db->table('doctors')->where('id', '=' , $doctor['id'])->update($fields)){
                        
                        if($foto->filepath){
                            delete_file($doctor['foto']);
                            $foto->save();
                        }

                        return showSuccess('Cпециальность изменина!','Успешно изменена специальность!', MODULE_URL);
                    
                    }
                    else $alerts->set_error('Ошибка изменения!', 'Неизвестная ошибка!', $db->error_num);
                }	
            
            }

            $tpl->save('content', 'edit', [
                'doctor' => $doctor,
                'specialties' => $db->table('specialties')->get()
            ], MODULE_SKIN_DIR);
            
        }
        else $alerts->set_error('Oшибка', 'Такого врача не существует!', 404);

    }
    elseif(isset($_GET['action']) and $_GET['action'] == 'addnew'){

        $crumbs->add(Store::set('title', 'Добавление врача'), '');

        if(isset($_POST['add'])){

			$alerts->set_error_if(!CheckField::empty($_POST['name']), 'Ошибка добавления!', 'Вы не ввели ФИО!', 287);
			
            $alerts->set_error_if(!CheckField::empty($_POST['kabinet']), 'Ошибка добавления!', 'Вы не ввели кабинет!', 288);

			$foto = new Upload_Image('foto', false, 'doctors');

            $alerts->merge($foto->errors);

            if($alerts->is_empty()){

                $fields = [
                    'name' => $_POST['name'],
                    'specialty_id' => $_POST['specialty'],
                    'kabinet' => $_POST['kabinet'],
                    'qualification' => [
                        'html' => true,
                        'value' => $_POST['qualification']
                    ],
                    'mon' => isset($_POST['mon']),
                    'tue' => isset($_POST['tue']),
                    'wed' => isset($_POST['wed']),
                    'thu' => isset($_POST['thu']),
                    'fri' => isset($_POST['fri']),
                    'sat' => isset($_POST['sat']),
                    'sun' => isset($_POST['sun'])
                ];
                if($foto->filepath)
                    $fields['foto'] = $foto->filepath;

                if($db->table('doctors')->insert($fields)){
                    if($foto->filepath)
                        $foto->save();
                    
                    return showSuccess('Врач добавлен!','Успешно добавлен врач!', MODULE_URL);
				}
				else $alerts->set_error('Ошибка добавления!', 'Неизвестная ошибка!', $db->error_num);
			}

        }

        $tpl->save('content', 'addnew', [
            'specialties' => $db->table('specialties')->get()
        ], MODULE_SKIN_DIR);

    }    
    else{
        $query =  $db->table('doctors')
            ->select('doctors.*', 'specialties.title as specialty')
            ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id');
        
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
            'doctors' => $query
                ->offset($pagination->get_begin_item())
                ->limit($_POST['count_on_page'])    
                ->get()
        ], MODULE_SKIN_DIR);
    }

?>
