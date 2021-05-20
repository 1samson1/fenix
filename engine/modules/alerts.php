<?php 
    if(isset($alerts->alerts_array[0])){

        $tpl->load('alerts.html');

        foreach ($alerts->alerts_array as $alert) {            
            $tpl->set('{alert-title}', $alert['title']);
            $tpl->set('{alert-text}', $alert['text']);
            $tpl->set('{alert-type}', $alert['type']);

            $tpl->copy_tpl();
        }

        $tpl->save_copy('{alerts}');
    }
    else $tpl->set('{alerts}', '');
    
?>
