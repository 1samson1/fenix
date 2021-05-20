<?php 
    $db->get_static($_GET['param1']);
    if($static = $db->get_row()){
        $tpl->load('static.html');    
        $tpl->set('{title}', $static['title']);
        $tpl->set('{static}', $static['template']);
        $tpl->save('{content}');
        $head['title'] = $static['title'];
    }
    else {
		$alerts->set_error('Oшибка', 'Такой страницы или файла не существует!', 404);
        $head['title'] = 'Страница не найдена!';
	}
?>
