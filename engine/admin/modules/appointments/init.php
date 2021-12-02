<?php

    require_once ENGINE_DIR.'/includes/pagination.php';

    $query = $db->table('appointments')
        ->select('appointments.*', 'users.name', 'users.surname', 'users.patronymic')
        ->select('doctors.name as doctor_name', 'specialties.title as doctor_specialty')
        ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
        ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
        ->join('users', 'appointments.user_id', '=', 'users.id');

    if(isset($_GET['user_id'])){
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
        'specialties' => $db->table('specialties')->get(),
        'appointments' => $query
            ->orderBy('appointments.time', 'desc')
            ->offset($pagination->get_begin_item())
            ->limit($_POST['count_on_page'])
            ->get()
    ], MODULE_SKIN_DIR);

?>
