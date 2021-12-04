<?php 
    
    if(isset($_GET['doctor']) and isset($_GET['mindate'])  and isset($_GET['maxdate'])){
        $appointments = $db->table('appointments')
            ->select('time, doctor_id')
            ->where('doctor_id', '=', $_GET['doctor'])
            ->where('time', '>=', $_GET['mindate'])
            ->where('time', '<=', $_GET['maxdate'])
            ->get();

        $response->set($appointments);

    } elseif(isset($_GET['doctor']) and isset($_GET['time'])){

        $appointments = $db->table('appointments')
            ->select('time, doctor_id')
            ->where('doctor_id', '=', $_GET['doctor'])
            ->where('time', '>=', $_GET['time'])
            ->where('time', '<=', $_GET['time'] + 86400)
            ->get();

        $response->set($appointments);
    }

    $response->send();
?>
