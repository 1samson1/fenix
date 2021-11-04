<?php
    
    require_once ENGINE_DIR.'/includes/checkFeild.php';
    require_once ENGINE_DIR.'/includes/upload.php';

    if(isset($_GET['action']) and $_GET['action'] == 'delete'){

        if($db->table('specialties')->where('id', '=', $_GET['delete'])->delete()){
            showSuccess('Cпециальность удалена!','Выбраный специальность успешно удалена!', MODULE_URL);
        }
        else showError('Ошибка удаления!', 'Неизвестная ошибка!', MODULE_URL);

    }
    elseif(isset($_GET['delete'])){

        showConfirm('Удаление специальности', 'Вы действительно хотите удалить выбранную специальность?', addGetParam('action','delete'), MODULE_URL);
    
    }
    elseif(isset($_GET['id'])){

        $crumbs->add(Store::set('title', 'Редактирование специальности'), '');

        if($specialty = $db->table('specialties')->where('id', '=', $_GET['id'])->first()){

            if(isset($_POST['edit'])){
    
                $alerts->set_error_if(!CheckField::empty($_POST['title']), 'Ошибка добавления!', 'Вы не ввели название', 249);
			
                $alerts->set_error_if(!CheckField::empty($_POST['description']), 'Ошибка добавления!', 'Вы не ввели описание', 250);

                $image = new Upload_Image('image', false, 'specialties');

                $alerts->merge($image->errors);

                if($alerts->is_empty()){

                    $fields = [
                        'title' => $_POST['title'],
                        'description' => $_POST['description']
                    ];
                    if($image->filepath)
                        $fields['image'] = $image->filepath;

                    if($db->table('specialties')->where('id', '=' , $specialty['id'])->update($fields)){
                        if($image->filepath){
                            delete_file($specialty['image']);
                            $image->save();
                        }
                        return showSuccess('Cпециальность изменина!','Успешно изменена специальность!', MODULE_URL);
                    }
                    else $alerts->set_error('Ошибка изменения!', 'Неизвестная ошибка!', $db->error_num);
                }	
            
            }

            $tpl->save('content', 'edit', [
                'specialty' => $specialty
            ], MODULE_SKIN_DIR);
        }
        else $alerts->set_error('Oшибка', 'Такой специальности не существует!', 404);

    }
    elseif(isset($_GET['action']) and $_GET['action'] == 'addnew'){

        $crumbs->add(Store::set('title', 'Добавление специальности'), '');

        if(isset($_POST['add'])){

			$alerts->set_error_if(!CheckField::empty($_POST['title']), 'Ошибка добавления!', 'Вы не ввели название', 249);
			
            $alerts->set_error_if(!CheckField::empty($_POST['description']), 'Ошибка добавления!', 'Вы не ввели описание', 250);

			$image = new Upload_Image('image', false, 'specialties');

            $alerts->merge($image->errors);

            if($alerts->is_empty()){

                $fields = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description']
                ];
                if($image->filepath)
                    $fields['image'] = $image->filepath;
               
				if($db->table('specialties')->insert($fields)){
                    if($image->filepath)
                        $image->save();

                    return showSuccess('Специальность добавлена!','Успешно добавлена специальность!', MODULE_URL);
                }

				else $alerts->set_error('Ошибка добавления!', 'Неизвестная ошибка!', $db->error_num);
			}

        }
        
        $tpl->save('content', 'addnew', [], MODULE_SKIN_DIR);

    }
    else{
        $tpl->save('content', 'main', [
            'specialties' => $db->table('specialties')->get()
        ], MODULE_SKIN_DIR);
    }
    
?>
