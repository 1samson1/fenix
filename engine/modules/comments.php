<?php 
    /* Block addcomments ================================================= */

    if(isset($_POST['addcomment'])){

        $alerts->set_error_if(!CheckField::empty($_POST['text']), 'Ошибка добавления комментария!', 'Вы не ввели текст комментария!', 246);

        if(!isset($alerts->alerts_array[0])){
            if($db->add_comment($_GET['param1'], $_SESSION['user']['id'], $_POST['text'], time())){
                $alerts->set_success('Комментарий добавлен!', 'Ваш комментарий успешно добавлен!');
            }
            else $alerts->set_error('Ошибка добавления комментария!', 'Неизвестная ошибка!', $db->error_num);
        }
    }

    $tpl->load('addcomments.html');
    $tpl->save('{addcomments}');

    /* Block comments ================================================= */
    $tpl->load('comments.html');

    $db->get_comments_by_news_id($_GET['param1']);
    
    while ($comment = $db->get_row()) {

        $tpl->set('{text}', $comment['text']);
        $tpl->set('{date}', date('d.m.Y', $comment['date']));
        $tpl->set('{name}', $comment['name']);
        $tpl->set('{surname}', $comment['surname']);

        if($comment['foto'])$foto = '/'.$comment['foto'];
        else $foto = '{TEMPLATE}/img/noavatar.png';
        $tpl->set('{foto}', $foto);

        $tpl->copy_tpl();
    }

    if(!$tpl->copy_template){
        $tpl->append('Пока комментариев нет. Вы можете стать первым.');
    }
        
    $tpl->save_copy('{comments}');
?>
