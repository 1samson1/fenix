<?php 
    Store::set('title', 'Главная');
    $tpl->save( 'content', 'main', [
        'specialties' => $db->table('specialties')->get(),
        'news' => $db->table('news')
            ->select('news.*', 'news.short_news AS body')
            ->orderBy('news.date', 'desc')
            ->limit(2)
            ->get()
    ]);
?>
