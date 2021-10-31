<?php
    // Глобальный конфиг для всего

    return [

        'host_url' => (isset($_SERVER['HTTPS']) ? 'https' : 'http')."://".$_SERVER['HTTP_HOST'],

        'reg_user_group' => 2,
		
        'registration_on' => true,

        'life_time_token' => 31536000,

        'timezone' => 'Europe/Moscow',
		
        'max_size_upload_img' => 2097152,

        'template' => 'Default',
        
        'count_news_on_page' => 10,

        'count_comments_on_page' => 20,

        'who_send_mail' => "admin@med.su",
    ];
?>
