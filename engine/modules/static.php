<?php 
    $db->get_static($_GET['param1']);
    if($static = $db->get_row()){
        $tpl->save('content', 'static', [
            'static' => $static
        ]);
    }
    else {
		$alerts->set_error('Oшибка', 'Такой страницы или файла не существует!', 404);
        $head['title'] = 'Страница не найдена!';
	}
?>
