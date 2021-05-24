<?php 
    function showSuccess($title, $text, $url){
        global $tpl;

        $tpl->load('success.html');

        $tpl->set('{title}', $title);
        $tpl->set('{text}', $text);
        $tpl->set('{url}', $url);

        $tpl->save('{content}');
    }

    function showError($title, $text, $url){
        global $tpl;

        $tpl->load('error.html');

        $tpl->set('{title}', $title);
        $tpl->set('{text}', $text);
        $tpl->set('{url}', $url);

        $tpl->save('{content}');
    }

    function showConfirm($title, $text, $url_yes, $url_no){
        global $tpl;

        $tpl->load('confirm.html');

        $tpl->set('{title}', $title);
        $tpl->set('{text}', $text);
        $tpl->set('{url_yes}', $url_yes);
        $tpl->set('{url_no}', $url_no);

        $tpl->save('{content}');
    }

?>
