<?php

    require_once ENGINE_DIR.'/includes/checkFeild.php';
            
    $tpl->save('content', 'main', [
        'appointments' => $db->table('appointments')
            ->select('appointments.*', 'users.name', 'users.surname', 'doctors.name as doctor_name', 'specialties.title as doctor_specialty')
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
            ->join('users', 'appointments.user_id', '=', 'users.id')
            ->orderBy('appointments.time', 'desc')
            ->get()
    ], MODULE_SKIN_DIR);

?>
