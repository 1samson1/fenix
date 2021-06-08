<?php
    
    $crumbs->add($head['title'] = 'Записи на приём к врачам', MODULE_URL);

    require_once ENGINE_DIR.'/includes/functions.php';
    require_once ENGINE_DIR.'/includes/checkFeild.php';
            
    $tpl->load('main.html', MODULE_SKIN_DIR);

    $db->get_recdoc();
    
    $tpl->set_repeat_block('/\[recdoc\](.*)\[\/recdoc\]/sU');
    
    while($recdoc = $db->get_row()){
    
        $tpl->set('{doctor_name}', $recdoc['doctor_name']);
        $tpl->set('{doctor_specialty}', $recdoc['doctor_specialty']);                
        $tpl->set('{patient}', $recdoc['surname'].' '.$recdoc['name']);
        $tpl->set('{date}', date('Y.m.d H:i',$recdoc['time']));

        $tpl->copy_repeat_block();
        
    }

    $tpl->save_repeat_block();

    $tpl->save('{content}');   

?>
