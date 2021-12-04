<?php 
    if(isset($_GET['doctor'])){

        $doctor = $db->table('doctors')->where('id', '=', $_GET['doctor'])->first();

        if($doctor){
            $response->set($doctor);
        } else {
            $response->set_error('Нет такого доктора!');
        }
    
    } elseif (isset($_GET['specialty'])){
        $doctors = $db->table('doctors')->where('specialty_id', '=', $_GET['specialty'])->get();

        if($doctors){
            $response->set($doctors);
        } else {
            $response->set_error('Нет такого доктора!');
        }
    }

    $response->send();
?>
