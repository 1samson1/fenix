<?php 
    function showSuccess($title, $text, $url){
        global $tpl, $head;

        Store::set('title', $title);

        $tpl->save('content', 'success', [
            
            'title'=> $title,
            'text'=> $text,
            'url'=> $url,
        ]);
    }

    function showError($title, $text, $url){
        global $tpl, $head;

        Store::set('title', $title);

        $tpl->save('content', 'error', [
            
            'title'=> $title,
            'text'=> $text,
            'url'=> $url,
        ]);
    }

    function showConfirm($title, $text, $url_yes, $url_no){
        global $tpl, $head;

        Store::set('title', $title);

        $tpl->save('content', 'confirm', [
            
            'title'=> $title,
            'text'=> $text,
            'url_yes'=> $url_yes,
            'url_no'=> $url_no,
        ]);
    }

?>
