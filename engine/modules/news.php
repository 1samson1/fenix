<?php 

    if(isset($_GET['param1'])){

    }
    else{
        $tpl->load_tpl('shortnews.html');
    
        $db->get_short_news($config['count_news_on_page']);
        while($news_item = $db->get_row()){
            $tpl->set('{title}', $news_item['title']);
            $tpl->set('{body}', $news_item['body']);
            $tpl->set('{date}', date('d.m.Y', $news_item['date']));
            $tpl->set('{autor}', $news_item['autor']);
            $tpl->set('{news-link}', '/news/'.$news_item['url']);
    
            $tpl->copy_tpl();
        }
    
        $tpl->save_copy('{news}');
    
        $tpl->load_tpl('news.html');    
        $tpl->save('{content}');
        $head['title'] = 'Новости';
    }
?>
