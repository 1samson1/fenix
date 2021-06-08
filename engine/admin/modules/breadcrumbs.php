<?php

    $tpl->load("breadcrumbs.html");

    for($i = 0; $i < $crumbs->count; $i++){
        if($i < $crumbs->count -1) $bcrumbs .= '<span><a href="'.$crumbs->crumbs[$i]['url'].'">'.$crumbs->crumbs[$i]['name'].'</a></span>';
        else $bcrumbs .= '<span>'.$crumbs->crumbs[$i]['name'].'</span>';
    }

    $tpl->set('{crumbs}', $bcrumbs);

    $tpl->set('{title_page}', $crumbs->pop()['name']);
    
    $tpl->save("{breadcrumbs}");

?>
