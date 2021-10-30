<?php 
    Mail::set_headers(array(
        'MIME-Version' => '1.0',
        
        'Content-type' => 'text/html; charset=utf-8',

        'From' => Store::get('config.who_send_mail'),

        'Cc' => '', // копия сообщения на этот адрес

        'Bcc' => '' // скрытая копия сообщения на этот адрес
    ));
?>
