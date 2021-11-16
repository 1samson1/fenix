<?php 
    if(isset($_GET['doctor'])){

        $doctor = $db->table('doctors')->where('id', '=', $_GET['doctor'])->first();

        if($doctor){
            $response->set($doctor);
        } else {
            $response->set_error('Нет такого доктора!');
        }
    
    }

    $response->send();
?>
