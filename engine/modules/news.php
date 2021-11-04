<?php 

    require_once ENGINE_DIR.'/modules/pagination.php';

    if(isset($_GET['param1'])){
        
        $db->get_full_news($_GET['param1']);
        if($news = $db->get_row()){

            if(isset($_POST['addcomment'])){

                $alerts->set_error_if(
                    !CheckField::empty($_POST['text']),
                    'Ошибка добавления комментария!',
                    'Вы не ввели текст комментария!',
                    246
                );
        
                if($alerts->is_empty()){
                    if($db->add_comment($_GET['param1'], Store::get('USER.id'), $_POST['text'], time())){
                        $alerts->set_success('Комментарий добавлен!', 'Ваш комментарий успешно добавлен!');
                    }
                    else $alerts->set_error('Ошибка добавления комментария!', 'Неизвестная ошибка!', $db->error_num);
                }
            }

            $pagination = new Pagination(
                function() use ($db){
                    $db->count_comments_for_news($_GET['param1']);
                },
                '/news/'.$_GET['param1'].'/',
                Store::get('config.count_comments_on_page')
            );
        
            $pagination->gen_tpl();
            
            $tpl->save('content', 'fullnews', [
                'story' => $news,
                'comments' => $db->get_comments_by_news_id(
                    $_GET['param1'],
                    Store::get('config.count_comments_on_page'),
                    $pagination->get_begin_item()
                ),
            ]);
            Store::set('title', $news['title']);

        }
        else {
            $alerts->set_error('Oшибка', 'Такой новости не существует!', 404);
            Store::set('title', 'Новость не найдена');
        }

    }
    else{

        $pagination = new Pagination(
            function() use ($db){
                $db->count_news();
            },
            '/news/',
            Store::get('config.count_news_on_page')
        );

        $pagination->gen_tpl();

        $tpl->save('content', 'news', [
            'news' => $db->get_short_news( 
                Store::get('config.count_news_on_page'),
                $pagination->get_begin_item()
            )
        ]);

        Store::set('title', 'Новости');
    }
?>
