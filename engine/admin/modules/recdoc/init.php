<?php

    require_once ENGINE_DIR.'/includes/checkFeild.php';
            
    $tpl->save('content', 'main', [
        'recdoc' => $db->table('recdoctor')
            ->select('recdoctor.*', 'users.name', 'users.surname', 'doctors.name as doctor_name', 'specialties.title as doctor_specialty')
            ->join('doctors', 'recdoctor.doctor_id', '=', 'doctors.id')
            ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
            ->join('users', 'recdoctor.user_id', '=', 'users.id')
            ->orderBy('recdoctor.time', 'desc')
            ->get()
    ], MODULE_SKIN_DIR);

?>
