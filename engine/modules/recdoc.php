<?php     
    require_once ENGINE_DIR.'/includes/functions.php';
    require_once ENGINE_DIR.'/includes/checkFeild.php';	

    function checkRecording(){
        if(isset($_POST['rec'])){
            global $alerts,$db;
            
            $alerts->set_error_if(!CheckField::empty($_POST['date']), 'Ошибка!', 'Вы не выбрали дату!', 248);

            $alerts->set_error_if(!CheckField::empty($_POST['time']), 'Ошибка!', 'Вы не выбрали время!', 249);
            
            if(!isset($alerts->alerts_array[0])){
                if($db->recording($_GET['doctor'], $_SESSION['user']['id'], $_POST['date'], $_POST['time'])){
					return true;
				}
				else $alerts->set_error('Ошибка записи на приём!', 'Выбраная дата занята!', $db->error_num);
            }
        }
        return false;
    }

    if (checkRecording()){
        $step = 4;
        $db->get_doctor_by_id($_GET['doctor']);
        
        if ($doctor = $db->get_row()) {
            $tpl->set('{doctor-name}', $doctor['name']);

            if($doctor['foto']) $foto = '/'.$doctor['foto'];
            else $foto = '{TEMPLATE}/img/noimage.jpg';
            $tpl->set('{doctor-foto}', $foto);

            $tpl->set('{doctor-specialty}', $doctor['specialty']);
            $tpl->set('{doctor-kabinet}', $doctor['kabinet']);
            $tpl->set('{datetime-recdoc}', $_POST['date'].' '.$_POST['time']);
        }
    }
    else if(isset($_GET['doctor'])){
        $step = 3;

        $db->get_doctor_by_id($_GET['doctor']);
        
        if ($doctor = $db->get_row()) {
            $tpl->set('{doctor-name}', $doctor['name']);

            if($doctor['foto']) $foto = '/'.$doctor['foto'];
            else $foto = '{TEMPLATE}/img/noimage.jpg';
            $tpl->set('{doctor-foto}', $foto);

            $tpl->set('{doctor-specialty}', $doctor['specialty']);
            $tpl->set('{doctor-kabinet}', $doctor['kabinet']);

            $endlines = "
            <script>
                $('.daterec').datepicker({
                    inline:true,
                    minHours:9,
                    maxHours:17,
                    minutesStep:30,
                    minDate:new Date(new Date().setDate(new Date().getDate() + 1)),
                    maxDate: new Date(new Date().setDate(new Date().getDate() + 42)),
                    onRenderCell: function (date, cellType) {
                        if (cellType == 'day') {
                            var day = date.getDay(),days = [".$doctor['sun'].",".$doctor['mon'].",".$doctor['tue'].",".$doctor['wed'].",".$doctor['thu'].",".$doctor['fri'].",".$doctor['sat']."]
                                isDisabled = days[day] == 0;
                
                            return {
                                disabled: isDisabled
                            }
                        }
                    }
                })   
                $('.timerec').timepicker()         
            </script> 
            ";
        }
        else {
            $alerts->set_error('Oшибка', 'Такого доктора не существует!', 282);
            showSpecialties();
        }	
    }
    else if(isset($_GET['specialty'])){
        $step = 2;
        $tpl->load('doctors.html');
    
        $db->get_doctors_by_specialty($_GET['specialty']);
        
        while ($doctor = $db->get_row()) {
            $tpl->set('{doctor-name}', $doctor['name']);

            if($doctor['foto']) $foto = '/'.$doctor['foto'];
            else $foto = '{TEMPLATE}/img/noimage.jpg';
            $tpl->set('{doctor-foto}', $foto);
            
            $tpl->set('{doctor-specialty}', $doctor['specialty']);
            $tpl->set('{doctor-kabinet}', $doctor['kabinet']);
            $tpl->set('{doctor-link}', addGetParam('doctor',$doctor['id']));
    
            $tpl->copy_tpl();
        }
    
        $tpl->save_copy('{doctors}');
    }
    else{
        showSpecialties();
    }

    function showSpecialties(){
        global $step,$tpl,$db;
        $step = 1;
        $tpl->load('specialties.html');
    
        $db->get_specialties();
        
        while ($specialty = $db->get_row()) {
            $tpl->set('{specialty-title}', $specialty['title']);

            if($specialty['image']) $image = '/'.$specialty['image'];
            else $image = '{TEMPLATE}/img/noimage.jpg';
            $tpl->set('{specialty-image}', $image);
            
            $tpl->set('{specialty-description}', $specialty['description']);
            $tpl->set('{specialty-link}', addGetParam('specialty',$specialty['id']));
    
            $tpl->copy_tpl();
        }
    
        $tpl->save_copy('{specialties}');
    }
    
    $tpl->load('recdoc.html');
    $tpl->set_block_param('/\[step=(.+)\](.*)\[\/step=\1\]/Us', $step);
    $tpl->append($endlines);
    $tpl->save('{content}');
    $head['title'] = 'Запись к врачу';
?>
