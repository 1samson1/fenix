<?php 

    require_once ENGINE_DIR."/includes/mail.php";
    require_once ENGINE_DIR."/data/mailconfig.php";    

    $head['title'] = "Восстановление пароля";

    if(!$_COOKIE['user_token']){

        if(isset($_POST['reset'])){

            $db->get_user_by_email_or_login($_POST['lostpassword']);

            if($user = $db->get_row()){

                $token = md5(time());

                $db->remove_lostpassword($user['id']);

                if($db->add_lostpassword($user['id'], $token)){

                    $mail = new Mail('lostpassword.html', array(
                        'title' => $head['title'],
                        'user' => $user,
                        'url_lostpassword' => $config['host_url'].'/lostpassword/'.$token,
                    ));

                    $mail->send(
                        $user['email'],
                        $head['title']
                    );

                    $alerts->set_success('Востановления пароля', 'На вашу почту отправлено письмо с ссылкой для изменения пароля.');

                }
                else $alerts->set_error('Востановления пароля', 'Неизвестная ошибка!', $db->error_num);

            } 
            else $alerts->set_error('Ошибка востановления пароля', 'Пользователя с такими данными не существует!', 526);

        }
        
        $tpl->load("lostpassword.html");

        $tpl->save('{content}');
    
    }
	else $alerts->set_error('Ошибка', 'Востановление пароля не доступно', 233);

?>
