<?php 

    require_once ENGINE_DIR.'/includes/pagination.php';

    if(isset($_GET['param1'])){
        
        $story =$db->table('news')
            ->select('news.*', 'news.full_news AS body')
            ->select('users.login AS autor')
            ->join('users', 'news.autor', '=', 'users.id')
            ->where('news.id', '=', $_GET['param1'])
            ->first();

        if($story){

            /* ADDCOMMENTS BEGIN */
            if(isset($_POST['addcomment'])){

                $alerts->set_error_if(
                    !CheckField::empty($_POST['text']),
                    'Ошибка добавления комментария!',
                    'Вы не ввели текст комментария!',
                    246
                );

                $alerts->set_error_if(
                    strlen($_POST['text']) < 20,
                    'Ошибка добавления комментария!',
                    'Текст комментария слишком маленький!',
                    247
                );
        
                if($alerts->is_empty()){
                    $db->table('comments')->insert([
                        'news_id' => $_GET['param1'],
                        'user_id' => Store::get('USER.id'),
                        'text' => [
                            'html' => true,
                            'value' => $_POST['text'],
                        ],
                        'date' => time()
                    ]);
                    if($db->result){
                        $alerts->set_success('Комментарий добавлен!', 'Ваш комментарий успешно добавлен!');
                    }
                    else $alerts->set_error('Ошибка добавления комментария!', 'Неизвестная ошибка!', $db->error_num);
                }
            }

            /* PAGINATION FOR COMMENTS */

            $pagination = new Pagination(
                function() use ($db){
                    return $db->table('comments')
                        ->where('news_id', '=', $_GET['param1'])
                        ->count();
                },
                '/news/'.$_GET['param1'].'/',
                Store::get('config.count_comments_on_page'),
                isset($_GET['page'])? $_GET['page']: 1
            );
        
            $pagination->gen_tpl();
            
            /* PRINT STORY AND HER COMMENTS */
            $tpl->save('content', 'fullnews', [
                'story' => $story,
                'comments' => $db->table('comments')
                    ->select('users.*', 'comments.*')
                    ->join('users', 'comments.user_id', '=', 'users.id')
                    ->where('comments.news_id', '=', $_GET['param1'])
                    ->offset($pagination->get_begin_item())
                    ->limit(Store::get('config.count_comments_on_page'))
                    ->orderBy('comments.date', 'desc')
                    ->get()
            ]);
            Store::set('title', $story['title']);
        }
        else {
            $alerts->set_error('Oшибка', 'Такой новости не существует!', 404);
            Store::set('title', 'Новость не найдена');
        }

    }
    else{

        $pagination = new Pagination(
            function() use ($db){
                return $db->table('news')->count();
            },
            '/news/',
            Store::get('config.count_news_on_page'),
            isset($_GET['page'])? $_GET['page']: 1
        );

        $pagination->gen_tpl();

        $tpl->save('content', 'news', [
            'news' => $db->table('news')
                ->select('news.*', 'news.short_news AS body')
                ->select('users.login AS autor')
                ->select('(SELECT COUNT(*) FROM comments WHERE comments.news_id = news.id) AS count_comments')
                ->join('users', 'news.autor', '=', 'users.id')
                ->offset($pagination->get_begin_item())
                ->limit(Store::get('config.count_news_on_page'))
                ->orderBy('news.date', 'desc')
                ->get()
        ]);

        Store::set('title', 'Новости');
    }
?>
