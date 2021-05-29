<?php 

    if(isset($_GET['param1'])){
        
        $db->get_full_news($_GET['param1']);
        if($news_item = $db->get_row()){
            require_once ENGINE_DIR.'/modules/comments.php';	
            
            $tpl->load('fullnews.html');

            $tpl->set('{title}', $news_item['title']);
            $tpl->set('{body}', $news_item['body']);
            $tpl->set('{date}', date('d.m.Y', $news_item['date']));
            $tpl->set('{autor}', $news_item['autor']);
            $tpl->set('{count_comments}', $news_item['count_comments']);
            
            $tpl->save('{content}');
            $head['title'] = $news_item['title'];

        }
        else {
            $alerts->set_error('Oшибка', 'Такой новости не существует!', 404);
		    $head['title'] = 'Новость не найдена';
        }

    }
    else{

        require_once ENGINE_DIR.'/modules/pagination.php';

        $pagination = new Pagination('news', '/news/', $config['count_news_on_page']);

        $tpl->load('shortnews.html');
    
        $db->get_short_news($config['count_news_on_page'], $pagination->get_begin_item());
        while($news_item = $db->get_row()){
            $tpl->set('{title}', $news_item['title']);
            $tpl->set('{body}', $news_item['body']);
            $tpl->set('{date}', date('d.m.Y', $news_item['date']));
            $tpl->set('{autor}', $news_item['autor']);
            $tpl->set('{count_comments}', $news_item['count_comments']);
            $tpl->set('{news-link}', '/news/'.$news_item['id'].'/');
    
            $tpl->copy_tpl();
        }
    
        $tpl->save_copy('{news}');

        $pagination->gen_tpl();

        $tpl->load('news.html');    
        $tpl->save('{content}');
        $head['title'] = 'Новости';
    }
?>
