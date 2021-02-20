<?php 
    require_once "../config.php";

    header('Content-Type: applicatiom/json; charset=utf-8');    
    $response['status'] = 'bad';
      

    if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/',$_POST['date'],$matches) && strtotime($_POST['date']) > time()){
        $add_rec_doc = 'INSERT INTO `recdoctor` (`time`, `doctor_id`, `user_id`) VALUES (\''.strtotime($_POST['date']).'\', \''.htmlspecialchars($_POST['doctor_id']).'\', \''.$_SESSION['logined']['id'].'\')';
        $info_rec = 'SELECT `recdoctor`.* , `doctors`.`name` AS `doctor_name` , `doctors`.`kabinet` , `specialties`.`title` AS `specialty_title` FROM `recdoctor` JOIN `users` ON `recdoctor`.`user_id` = `users`.`id` JOIN `doctors` ON `recdoctor`.`doctor_id` = `doctors`.`id` JOIN `specialties` ON `doctors`.`specialty_id` = `specialties`.`id`  WHERE `time` = \''.strtotime($_POST['date']).'\' AND `doctor_id` = \''.htmlspecialchars($_POST['doctor_id']).'\'';
      
        $access = mysqli_query($conection,$add_rec_doc);

        if($access){            
            $access = mysqli_query($conection,$info_rec);
            if($row = mysqli_fetch_assoc($access)){
                $response['kabinet'] = $row['kabinet'];
                $response['doctor_name'] = $row['doctor_name'];                
                $response['specialty_title'] = $row['specialty_title'];
                $response['date'] = date('Y-m-d H:i',$row['time']);
                $response['status'] = 'good';
            }
            else{
                $response['num_error'] = mysqli_errno($conection);
            }
        }
        else{
            $response['num_error'] = mysqli_errno($conection);
        }
    }
    else{
        $response['num_error'] = 1; 
    }    

    echo json_encode($response);
?>