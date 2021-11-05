<?php 

    $static = $db->table('static')
        ->where('url', '=', (isset($_GET['param1']) ? $_GET['param1'] : null))
        ->first();
            
    if($static){
        $tpl->save('content', 'static', [
            'static' => $static
        ]);
        Store::set('title', $static['title']);
    }
    else {
		$alerts->set_error('Oшибка', 'Такой страницы или файла не существует!', 404);
        Store::set('title', 'Страница не найдена!');
	}
?>
