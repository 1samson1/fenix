<?php 

    require_once ENGINE_DIR."/includes/mail.php";
    require_once ENGINE_DIR."/data/mailconfig.php";
    require_once ENGINE_DIR.'/includes/checkFeild.php';	   

    $head['title'] = "Восстановление пароля";    
    $step = 1;

    if(!Store::isset('USER')){

        $step = 1;

        if(isset($_GET['param1'])){

            $lostpass = $db->table('lostpassword')
                ->join('users', 'lostpassword.user_id', '=', 'users.id')
                ->where('token', '=', $_GET["param1"])
                ->first();

            if($lostpass){
                
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

                            $db->table('users')
                                ->where('id', '=', $lostpass['user_id'])
                                ->update([ 'password' => $db->hash($_POST['password']) ]);
                            
                            if($db->result){
                                $alerts->set_success('Востановления пароля', 'Пароль был успешно изменён.');
                                $db->table('lostpassword')->where('user_id', '=', $lostpass['id'])->delete();
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

            $user = $db->table('users')
                ->where('login', '=', $_POST['lostpassword'])
                ->orWhere('email', '=', $_POST['lostpassword'])
                ->first();

            if($user){

                $token = md5(time());

                $db->table('lostpassword')->where('user_id', '=', $user['id'])->delete();

                $db->table('lostpassword')->insert([
                    'user_id' => $user['id'],
                    'token' => $token,
                    'date' => time()
                ]);

                if($db->result){

                    $mail = new Mail('lostpassword.html', array(
                        'title' => $head['title'],
                        'user' => $user,
                        'url_lostpassword' => Store::get('config.host_url').'/lostpassword/'.$token,
                    ));

                    $mail->send(
                        $user['email'],
                        $head['title']
                    );

                    $alerts->set_success('Востановлениe пароля', 'На вашу почту отправлено письмо с ссылкой для изменения пароля.');

                }
                else $alerts->set_error('Ошибка востановления пароля', 'Неизвестная ошибка!', $db->error_num);

            } 
            else $alerts->set_error('Ошибка востановления пароля', 'Пользователя с такими данными не существует!', 526);

        }
        
        Store::set('title', 'Восстановление пароля');
        $tpl->save('content', 'lostpassword', [
            'step' => $step
        ]);

    
    }
	else $alerts->set_error('Ошибка', 'Востановление пароля не доступно', 233);

?>
