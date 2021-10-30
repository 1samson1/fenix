<?php     
    require_once ENGINE_DIR.'/includes/functions.php';
    require_once ENGINE_DIR.'/includes/checkFeild.php';
    require_once ENGINE_DIR."/includes/mail.php";
    require_once ENGINE_DIR."/data/mailconfig.php";

    Store::set('title','Запись к врачу');

    function checkRecording(){
        if(isset($_POST['rec'])){
            global $alerts,$db;
            
            $alerts->set_error_if(!CheckField::empty($_POST['date']), 'Ошибка!', 'Вы не выбрали дату!', 248);

            $alerts->set_error_if(!CheckField::empty($_POST['time']), 'Ошибка!', 'Вы не выбрали время!', 249);
            
            if($alerts->is_empty()){

                $appointment = genRandStr(6);

                if($db->recording($_GET['doctor'], Store::get('USER.id'), $appointment, $_POST['date'], $_POST['time'])){
                    
					return $appointment;
				}
				else $alerts->set_error('Ошибка записи на приём!', 'Выбраная дата занята!', $db->error_num);
            }
        }
        return false;
    }

    if ($appointment = checkRecording()){
        $step = 4;
        $db->get_doctor_by_id($_GET['doctor']);
        
        if ($doctor = $db->get_row()) {
            $mail = new Mail('recording.html', array(
                'title' => Store::get('title'),
                'user' =>  Store::get('USER'),
                'doctor' => $doctor,
                'recdoc-datatime' => $_POST['date'].' '.$_POST['time'],
            ));
            $mail->send(
                Store::get('USER.email'),
                Store::get('title')
            );

            $tpl->save('content', 'recdoc', [
                'step' => $step,
                'doctor' => $doctor,
                'recdoc-data' => $_POST['date'],
                'recdoc-time' => $_POST['time'],
                'appointment' => $appointment
            ]);
        }
    }
    else if(isset($_GET['doctor'])){
        
        $db->get_doctor_by_id($_GET['doctor']);
        
        if ($doctor = $db->get_row()) {
            $step = 3;

            $tpl->save('content', 'recdoc', [
                'step' => $step,
                'doctor' => $doctor,
                'script' => "
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
                ",
            ]);

        }
        else showSpecialties();	
    }
    else if(isset($_GET['specialty'])){
        $step = 2;

        $tpl->save('content', 'recdoc', [
            'step' => $step,
            'doctors' => $db->get_doctors_by_specialty($_GET['specialty']),
        ]);
    }
    else showSpecialties();

    function showSpecialties(){
        global $step,$tpl,$db;

        $step = 1;

        $tpl->save('content', 'recdoc', [
            'step' => $step,
            'specialties' => $db->get_specialties(),
        ]);
    }
    
?>
