<?php

    $crumbs->add($head['title'] = 'Врачи', MODULE_URL);

    require_once ENGINE_DIR.'/includes/functions.php';
    require_once ENGINE_DIR.'/includes/checkFeild.php';
    require_once ENGINE_DIR.'/includes/upload.php';
    
    if($_GET['action'] == 'delete'){

        if($db->remove_doctor($_GET['delete'])){
            showSuccess('Врач удалён!','Выбраный врач успешно удалён!', MODULE_URL);
        }
        else showError('Ошибка удаления!', 'Неизвестная ошибка!', MODULE_URL);

    }
    elseif(isset($_GET['delete'])){

        showConfirm('Удаление врача', 'Вы действительно хотите удалить выбранного врача?', addGetParam('action','delete'), MODULE_URL);
    
    }
    elseif(isset($_GET['id'])){

        $crumbs->add($head['title'] = 'Редактирование врача', '');

        $db->get_doctor_by_id($_GET['id']);

        if($doctor = $db->get_row()){

            if(isset($_POST['edit_doctor'])){
    
                $alerts->set_error_if(!CheckField::empty($_POST['name']), 'Ошибка добавления!', 'Вы не ввели ФИО!', 287);
			
                $alerts->set_error_if(!CheckField::empty($_POST['kabinet']), 'Ошибка добавления!', 'Вы не ввели кабинет!', 288);

                $foto = new Upload_Image('foto', 'doctor_'.$doctor['id'], 'doctors');

                $alerts->alerts_array = array_merge($alerts->alerts_array, $foto->errors);

                if(!isset($alerts->alerts_array[0])){

                    if($db->edit_doctor(
                        $doctor['id'],
                        $_POST['name'],
                        $_POST['specialty'],
                        $foto->filepath,
                        $_POST['kabinet'],
                        isset($_POST['mon']),
                        isset($_POST['tue']),
                        isset($_POST['wed']),
                        isset($_POST['thu']),
                        isset($_POST['fri']),
                        isset($_POST['sat']),
                        isset($_POST['sun'])
                    )){
                        
                        if($foto->filepath){
                            delete_file($doctor['foto']);
                            $foto->save();
                        }

                        return showSuccess('Cпециальность изменина!','Успешно изменена специальность!', MODULE_URL);
                    
                    }
                    else $alerts->set_error('Ошибка изменения!', 'Неизвестная ошибка!', $db->error_num);
                }	
            
            }

            $tpl->load('edit.html', MODULE_SKIN_DIR);

            $tpl->set('{name}', $doctor['name']);

            $db->get_specialties_mod();
            while ($specialty = $db->get_row()) {
                $specialties .= '<option value="'.$specialty['id'].'" '.($specialty['id'] == $doctor['specialty_id']?'selected':'').'>'.$specialty['title'].'</option>';
            }
            $tpl->set('{specialties}', $specialties);

            $tpl->set('{kabinet}', $doctor['kabinet']);

            if($doctor['mon']) $tpl->set('{mon}', 'checked');
            else $tpl->set('{mon}', '');
            if($doctor['tue']) $tpl->set('{tue}', 'checked');
            else $tpl->set('{tue}', '');
            if($doctor['wed']) $tpl->set('{wed}', 'checked');
            else $tpl->set('{wed}', '');
            if($doctor['thu']) $tpl->set('{thu}', 'checked');
            else $tpl->set('{thu}', '');
            if($doctor['fri']) $tpl->set('{fri}', 'checked');
            else $tpl->set('{fri}', '');
            if($doctor['sat']) $tpl->set('{sat}', 'checked');
            else $tpl->set('{sat}', '');
            if($doctor['sun']) $tpl->set('{sun}', 'checked');
            else $tpl->set('{sun}', '');
            
            if($doctor['foto']) $foto = '/'.$doctor['foto'];
            else $foto = '{SKIN}/img/noimage.jpg';
            $tpl->set('{foto}', $foto);

            $tpl->save('{content}');
        }
        else $alerts->set_error('Oшибка', 'Такого врача не существует!', 404);

    }
    elseif($_GET['action'] == 'addnew'){
        
        $crumbs->add($head['title'] = 'Добавление врача', '');

        if(isset($_POST['add_doctor'])){

			$alerts->set_error_if(!CheckField::empty($_POST['name']), 'Ошибка добавления!', 'Вы не ввели ФИО!', 287);
			
            $alerts->set_error_if(!CheckField::empty($_POST['kabinet']), 'Ошибка добавления!', 'Вы не ввели кабинет!', 288);

			if(!isset($alerts->alerts_array[0])){

				if($db->add_doctor(
                    $_POST['name'],
                    $_POST['specialty'],
                    $_POST['kabinet'],
                    isset($_POST['mon']),
                    isset($_POST['tue']),
                    isset($_POST['wed']),
                    isset($_POST['thu']),
                    isset($_POST['fri']),
                    isset($_POST['sat']),
                    isset($_POST['sun'])
                )){
					
                   return showSuccess('Врач добавлен!','Успешно добавлен врач!', MODULE_URL);

				}
				else $alerts->set_error('Ошибка добавления!', 'Неизвестная ошибка!', $db->error_num);
			}

        }
        
        $tpl->load('addnew.html', MODULE_SKIN_DIR);

        $db->get_specialties_mod();
        while ($specialty = $db->get_row()) {
            $specialties .= '<option value="'.$specialty['id'].'">'.$specialty['title'].'</option>';
        }
        $tpl->set('{specialties}', $specialties);

        $tpl->save('{content}');

    }    
    else{
        
        $tpl->load('main.html', MODULE_SKIN_DIR);
    
        $db->get_doctors();
        
        $tpl->set_repeat_block('/\[doctors\](.*)\[\/doctors\]/sU');
        
        while($doctor = $db->get_row()){
        
            $tpl->set('{name}', $doctor['name']);
            $tpl->set('{specialty}', $doctor['specialty']);
            $tpl->set('{kabinet}', $doctor['kabinet']);
            $tpl->set('{edit-link}',  addGetParam('id', $doctor['id']));
            $tpl->set('{delete-link}', addGetParam('delete', $doctor['id']));
            
    
            $tpl->copy_repeat_block();
            
        }
    
        $tpl->save_repeat_block();
    
        $tpl->set('{add_doctor_link}', addGetParam('action','addnew'));

        $tpl->save('{content}');

    }

?>
