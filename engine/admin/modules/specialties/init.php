<?php
    
    $crumbs->add($head['title'] = 'Специальности врачей', MODULE_URL);

    require_once ENGINE_DIR.'/includes/functions.php';
    require_once ENGINE_DIR.'/includes/checkFeild.php';
    require_once ENGINE_DIR.'/includes/upload.php';

    if($_GET['action'] == 'delete'){

        if($db->remove_specialty($_GET['delete'])){
            showSuccess('Cпециальность удалена!','Выбраный специальность успешно удалена!', MODULE_URL);
        }
        else showError('Ошибка удаления!', 'Неизвестная ошибка!', MODULE_URL);

    }
    elseif(isset($_GET['delete'])){

        showConfirm('Удаление специальности', 'Вы действительно хотите удалить выбранную специальность?', addGetParam('action','delete'), MODULE_URL);
    
    }
    elseif(isset($_GET['id'])){

        $crumbs->add($head['title'] = 'Редактирование специальности', '');

        $db->get_specialty_by_id($_GET['id']);

        if($specialty = $db->get_row()){

            if(isset($_POST['edit_specialty'])){
    
                $alerts->set_error_if(!CheckField::empty($_POST['title']), 'Ошибка добавления!', 'Вы не ввели название', 249);
			
                $alerts->set_error_if(!CheckField::empty($_POST['description']), 'Ошибка добавления!', 'Вы не ввели описание', 250);

                $image = new Upload_Image('image', 'specialty_'.$specialty['id'], 'specialties');

                $alerts->alerts_array = array_merge($alerts->alerts_array, $image->errors);

                if(!isset($alerts->alerts_array[0])){

                    if($db->edit_specialty($specialty['id'], $_POST['title'], $_POST['description'], $image->filepath)){
                        
                        if($image->filepath){
                            delete_file($specialty['image']);
                            $image->save();
                        }

                        return showSuccess('Cпециальность изменина!','Успешно изменена специальность!', MODULE_URL);
                    
                    }
                    else $alerts->set_error('Ошибка изменения!', 'Неизвестная ошибка!', $db->error_num);
                }	
            
            }

            $tpl->load('edit.html', MODULE_SKIN_DIR);

            $tpl->set('{title}', $specialty['title']);
            $tpl->set('{description}', $specialty['description']);
            
            if($specialty['image']) $image = '/'.$specialty['image'];
            else $image = '{SKIN}/img/noimage.jpg';
            $tpl->set('{image}', $image);

            $tpl->save('{content}');
        }
        else $alerts->set_error('Oшибка', 'Такой специальности не существует!', 404);

    }
    elseif($_GET['action'] == 'addnew'){

        $crumbs->add($head['title'] = 'Добавление специальности', '');

        if(isset($_POST['add_specialty'])){

			$alerts->set_error_if(!CheckField::empty($_POST['title']), 'Ошибка добавления!', 'Вы не ввели название', 249);
			
            $alerts->set_error_if(!CheckField::empty($_POST['description']), 'Ошибка добавления!', 'Вы не ввели описание', 250);

			if(!isset($alerts->alerts_array[0])){

				if($db->add_specialty($_POST['title'], $_POST['description'])){
					
                   return showSuccess('Специальность добавлена!','Успешно добавлена специальность!', MODULE_URL);

				}
				else $alerts->set_error('Ошибка добавления!', 'Неизвестная ошибка!', $db->error_num);
			}

        }
        
        $tpl->load('addnew.html', MODULE_SKIN_DIR);

        $tpl->save('{content}');

    }
    else{
        $tpl->load('main.html', MODULE_SKIN_DIR);

        $db->get_specialties_mod();
        
        $tpl->set_repeat_block('/\[specialties\](.*)\[\/specialties\]/sU');
        
        while($specialty = $db->get_row()){
        
            $tpl->set('{title}', $specialty['title']);
            $tpl->set('{edit-link}',  addGetParam('id', $specialty['id']));
            $tpl->set('{delete-link}', addGetParam('delete', $specialty['id']));
            
    
            $tpl->copy_repeat_block();
            
        }
    
        $tpl->save_repeat_block();
    
        $tpl->set('{add_specialty_link}', addGetParam('action','addnew'));
    
        $tpl->save('{content}');

    }
    
?>
