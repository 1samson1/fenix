<?php     
    require_once ENGINE_DIR.'/includes/functions.php';


    if(isset($_GET['doctor'])){
        $step = 3;

        $db->get_doctor_by_id($_GET['doctor']);
        
        if ($doctor = $db->get_row()) {
            $tpl->set('{doctor-name}', $doctor['name']);
            $tpl->set('{doctor-foto}', $config['host_url'].'/'.$doctor['foto']);
            $tpl->set('{doctor-specialty}', $doctor['specialty']);
            $tpl->set('{doctor-kabinet}', $doctor['kabinet']);

            $db->get_doctor_schedule_by_id($_GET['doctor']);
            if ($schedule = $db->get_row_noassoc()){
                $endlines = "
                <script>
                    $('.daterec').datepicker({
                        inline:true,
                        minHours:9,
                        maxHours:17,
                        minutesStep:30,
                        minDate: new Date(),
                        maxDate: new Date(new Date().setDate(new Date().getDate() + 42)),
                        onRenderCell: function (date, cellType) {
                            if (cellType == 'day') {
                                var day = date.getDay(),days = ".json_encode($schedule)."
                                    isDisabled = days[day] == '0';
                    
                                return {
                                    disabled: isDisabled
                                }
                            }
                        }
                    })            
                </script> 
                ";
            }
        }
    }
    else if(isset($_GET['specialty'])){
        $step = 2;
        $tpl->load_tpl('doctors.html');
    
        $db->get_doctors_by_specialty($_GET['specialty']);
        
        while ($doctor = $db->get_row()) {
            $tpl->set('{doctor-name}', $doctor['name']);
            $tpl->set('{doctor-foto}', $config['host_url'].'/'.$doctor['foto']);
            $tpl->set('{doctor-specialty}', $doctor['specialty']);
            $tpl->set('{doctor-kabinet}', $doctor['kabinet']);
            $tpl->set('{doctor-link}', addGetParam('doctor',$doctor['id']));
    
            $tpl->copy_tpl();
        }
    
        $tpl->save_copy('{doctors}');
    }
    else{
        $step = 1;
        $tpl->load_tpl('specialties.html');
    
        $db->get_specialties();
        
        while ($specialty = $db->get_row()) {
            $tpl->set('{specialty-title}', $specialty['title']);
            $tpl->set('{specialty-image}', $config['host_url'].'/'.$specialty['image']);
            $tpl->set('{specialty-description}', $specialty['description']);
            $tpl->set('{specialty-link}', addGetParam('specialty',$specialty['id']));
    
            $tpl->copy_tpl();
        }
    
        $tpl->save_copy('{specialties}');
    }
    
    $tpl->load_tpl('recdoc.html');
    $tpl->set_block_param('/\[step=(.+)\](.*)\[\/step=\1\]/Us', $step);
    $tpl->template .= $endlines;
    $tpl->save('{content}');
    $head['title'] = 'Запись к врачу';
?>
