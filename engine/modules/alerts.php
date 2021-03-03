<?php 
    if(isset($alerts->alerts_array[0])){

        $tpl->load_tpl('alerts.html');

        foreach ($alerts->alerts_array as $alert) {            
            $tpl->set('{alert-title}', $alert['title']);
            $tpl->set('{alert-text}', $alert['text']);

            if($alert['type'] == 'success'){
                $tpl->set_block('/\[error\](.*)\[\/error\]/s','');
                $tpl->set('[success]', '');
                $tpl->set('[/success]', '');
            }
            else {
                $tpl->set_block('/\[success\](.*)\[\/success\]/s','');
                $tpl->set('[error]', '');
                $tpl->set('[/error]', '');
            }

            $tpl->copy_tpl();
        }

        $tpl->save_copy('{alerts}');
    }
    else $tpl->set('{alerts}', '');
    
?>
