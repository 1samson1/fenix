<?php

    require_once ENGINE_DIR.'/includes/checkFeild.php';
    require_once ENGINE_DIR.'/includes/pagination.php';

    if(isset($_GET['user_id'])){
        $return_to = addGetParam('user_id', $_GET['user_id'], MODULE_URL);
    } else {
        $return_to = MODULE_URL;
    }

    if(isset($_GET['action']) and $_GET['action'] == 'delete'){

        if($db->table('appointments')->where('number', '=', $_GET['delete'])->delete()){
            showSuccess('Запись на приём удалена!','Выбраная запись успешно удалена!', $return_to);
        }
        else showError('Ошибка удаления!', 'Неизвестная ошибка!', $return_to);

    } elseif(isset($_GET['delete'])){

        showConfirm('Удаление записи на приём', 'Вы действительно хотите удалить выбранную запись?', addGetParam('action','delete'), $return_to);
    
    } elseif(isset($_GET['edit'])){

        $crumbs->add(Store::set('title', 'Редактирование приёма'), '');

        if($appointment = $db->table('appointments')->where('number', '=', $_GET['edit'])->first()){

            if(isset($_POST['save'])){
                $alerts->set_error_if(!CheckField::empty($_POST['time']), 'Ошибка записи!', 'Вы не выбрали дату и время!', 564);

                if($alerts->is_empty()){
                    $db->table('appointments')->where('number', '=', $appointment['number'])->update([
                        'doctor_id' => $_POST['doctor'],
                        'time' => substr($_POST['time'], 0, -3)
                    ]);            
    
                    if($db->result){
                        
                        return showSuccess('Редактирование приёма','Приём успешно отредактирован!', $return_to);
                    }
                    else return showError('Ошибка записи на приём!', 'Выбраная дата занята!', $_REQUEST);
                }
            }

            $doc = $db->table('doctors')->where('doctors.id', '=', $appointment['doctor_id'])->first();
            $doctors = $db->table('doctors')->where('doctors.specialty_id', '=', $doc['specialty_id'])->get();

            $tpl->save('content', 'edit', [
                'docspec' => $doc['specialty_id'],
                'specialties' => $db->table('specialties')->get(), 
                'doctors' => $doctors,
                'appointment' => $appointment,
                'appoint_time' => $appointment['time'].'000',
                'user' => $db->table('users')->where('users.id', '=', $appointment['user_id'])->first(),
                'script' => '<script src="/engine/js/makeappointment.js?doctor='.$appointment['doctor_id'].'"></script>',
            ], MODULE_SKIN_DIR);
        }
        else $alerts->set_error('Oшибка', 'Такой страницы не существует!', 404);

    } elseif(isset($_GET['action']) and $_GET['action'] == 'addnew' and isset($_GET['user_id'])){

        $crumbs->add(Store::set('title', 'Запись пользователя на приём'), '');

        if(isset($_POST['add'])){
            $alerts->set_error_if(!CheckField::empty($_POST['time']), 'Ошибка записи!', 'Вы не выбрали дату и время!', 564);
        
            if($alerts->is_empty()){
                $db->table('appointments ')->insert([
                    'doctor_id' => $_POST['doctor'],
                    'user_id' => $_GET['user_id'],
                    'reg_time' => time(),
                    'number' => genRandStr(6),
                    'time' => substr($_POST['time'], 0, -3)
                ]);            

                if($db->result){
                    
                    return showSuccess('Запись на приём','Пользователь успешно записан на приём!', $return_to);
                }
                else return showError('Ошибка записи на приём!', 'Выбраная дата занята!', $_REQUEST);
            }
        }       

        $tpl->save('content', 'addnew', [
            'specialties' => $db->table('specialties')->get(),
            'user' => $db->table('users')->where('users.id', '=', $_GET['user_id'])->first(),
            'script' => '<script src="/engine/js/makeappointment.js"></script>',
        ], MODULE_SKIN_DIR);

    } else {

        $query = $db->table('appointments')
            ->select('appointments.*', 'users.name', 'users.surname', 'users.patronymic')
            ->select('doctors.name as doctor_name', 'specialties.title as doctor_specialty')
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
            ->join('users', 'appointments.user_id', '=', 'users.id');
    
        if(isset($_GET['user_id'])){
            $crumbs->add(Store::set('title', 'Записи пользователя'), '');
            $query->where('users.id', '=', $_GET['user_id']);
        }
        
        if(isset($_POST['search'])) {
            
            if(isset($_POST['specialty'][0])){
                $query->where('doctors.specialty_id', '=', $_POST['specialty']);
            }        
    
            if(isset($_POST['begin_reg_appointment'][0])){
                $query->where('appointments.reg_time', '>=', ''.strtotime($_POST['begin_reg_appointment']));
            }  
    
            if(isset($_POST['end_reg_appointment'][0])){
                $query->where('appointments.reg_time', '<', ''.strtotime($_POST['end_reg_appointment']));
            }    
    
            if(isset($_POST['begin_appointment'][0])){
                $query->where('appointments.time', '>=', ''.strtotime($_POST['begin_appointment']));
            }  
    
            if(isset($_POST['end_appointment'][0])){
                $query->where('appointments.time', '<', ''.strtotime($_POST['end_appointment']));
            }    
        }  
    
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
            'search' => $_POST,
            'count' => $count,
            'user' => (isset($_GET['user_id']) ? $db->table('users')->where('id', '=', $_GET['user_id'])->first() : false),
            'specialties' => $db->table('specialties')->get(),
            'appointments' => $query
                ->orderBy('appointments.time', 'desc')
                ->offset($pagination->get_begin_item())
                ->limit($_POST['count_on_page'])
                ->get()
        ], MODULE_SKIN_DIR);
    }

?>
