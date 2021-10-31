<?php

    $bcrumbs = '';
    for($i = 0; $i < $crumbs->count; $i++){
        if($i < $crumbs->count -1) $bcrumbs .= '<span><a href="'.$crumbs->crumbs[$i]['url'].'">'.$crumbs->crumbs[$i]['name'].'</a></span>';
        else $bcrumbs .= '<span>'.$crumbs->crumbs[$i]['name'].'</span>';
    }

    $tpl->save('breadcrumbs', "breadcrumbs", [
        'crumbs' => $bcrumbs,
        'title_page' => $crumbs->pop()['name'],
    ]);  

?>
