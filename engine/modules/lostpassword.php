<?php 

    require_once ENGINE_DIR."/includes/mail.php";
    require_once ENGINE_DIR."/data/mailconfig.php";
    require_once ENGINE_DIR.'/includes/checkFeild.php';	   

    $head['title'] = "Восстановление пароля";    
    $step = 1;

    if(!$_COOKIE['user_token']){

       if(isset($_GET['param1'])){
            
            $db->get_lostpassword($_GET["param1"]);

            if($lostpass = $db->get_row()){
                
                if($lostpass['date'] > time() - 86400){
                    $step = 2;

                    if(isset($_POST['change'])){
                        $alerts->set_error_if(!CheckField::empty(
                            $_POST['password']),
                            'Ошибка востановления пароля',
                            'Вы не ввели пароль',
                            203
                        );

                        $alerts->set_error_if(!CheckField::confirm_pass(
                            $_POST['password'],
                            $_POST['repassword']),
                            'Ошибка востановления пароля',
                            'Пароль не совпадает с формой подтверждения',
                            204
                        );

                        if(!isset($alerts->alerts_array[0])){
                            $step = 3;
                            if($db->change_password($lostpass['user_id'], $_POST['password'])){
                                $alerts->set_success('Востановления пароля', 'Пароль был успешно изменён.');
                                $db->remove_lostpassword($lostpass['user_id']);
                            }
                            else
                                $alerts->set_error('Ошибка востановления пароля', 'Неизвестная ошибка!', $db->error_num);
                            
                        }
                    }
                }   
                else $alerts->set_error('Ошибка востановления пароля', 'Срок действия ссылки истек. Попробуйте запросить ее еще раз', 529);
            }
            else $alerts->set_error('Ошибка востановления пароля', 'Неизвестная ошибка!', $db->error_num);

        }
        elseif(isset($_POST['reset'])){

            $db->get_user_by_email_or_login($_POST['lostpassword']);

            if($user = $db->get_row()){

                $token = md5(time());

                $db->remove_lostpassword($user['id']);

                if($db->add_lostpassword($user['id'], $token, time())){

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
                else $alerts->set_error('Ошибка востановления пароля', 'Неизвестная ошибка!', $db->error_num);

            } 
            else $alerts->set_error('Ошибка востановления пароля', 'Пользователя с такими данными не существует!', 526);

        }
        
        $tpl->load("lostpassword.html");

        $tpl->set_block_param('/\[step=(.+)\](.*)\[\/step=\1\]/Us', $step);

        $tpl->save('{content}');
    
    }
	else $alerts->set_error('Ошибка', 'Востановление пароля не доступно', 233);

?>
