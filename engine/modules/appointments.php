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

                $num_appointment = genRandStr(6);

                $db->table('appointments ')->insert([
                    'doctor_id' => $_GET['doctor'],
                    'user_id' => Store::get('USER.id'),
                    'reg_time' => time(),
                    'number' => $num_appointment,
                    'time' => strtotime($_POST['time'], strtotime( $_POST['date'] ))
                ]);

                if($db->result){
                    
					return $num_appointment;
				}
				else $alerts->set_error('Ошибка записи на приём!', 'Выбраная дата занята!', $db->error_num);
            }
        }
        return false;
    }

    if ($num_appointment = checkRecording()){
        $step = 4;
        $doctor = $db->table('doctors')
            ->select('doctors.*', 'specialties.title as specialty')
            ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
            ->where('doctors.id', '=', $_GET['doctor'])
            ->first();
        
        if ($doctor) {
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

            $tpl->save('content', 'appointments', [
                'step' => $step,
                'doctor' => $doctor,
                'recdoc-data' => $_POST['date'],
                'recdoc-time' => $_POST['time'],
                'number' => $num_appointment
            ]);
        }
    }
    else if(isset($_GET['doctor'])){
        
        $doctor = $db->table('doctors')
            ->select('doctors.*', 'specialties.title as specialty')
            ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
            ->where('doctors.id', '=', $_GET['doctor'])
            ->first();
        
        if ($doctor) {
            $step = 3;

            $tpl->save('content', 'appointments', [
                'step' => $step,
                'doctor' => $doctor,
                'script' => '<script src="/engine/js/recording.js?doctor='.$doctor['id'].'"></script>',
            ]);

        }
        else showSpecialties();	
    }
    else if(isset($_GET['specialty'])){
        $step = 2;

        $tpl->save('content', 'appointments', [
            'step' => $step,
            'doctors' =>  $db->table('doctors')
                ->select('doctors.*', 'specialties.title as specialty')
                ->join('specialties', 'doctors.specialty_id', '=', 'specialties.id')
                ->where('doctors.specialty_id', '=', $_GET['specialty'])
                ->get()
        ]);
    }
    else showSpecialties();

    function showSpecialties(){
        global $step,$tpl,$db;

        $step = 1;

        $tpl->save('content', 'appointments', [
            'step' => $step,
            'specialties' => $db->table('specialties')->get(),
        ]);
    }
    
?>
