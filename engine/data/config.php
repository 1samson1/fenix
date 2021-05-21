<?php
    // Глобальный конфиг для всего

    $config = array(

        'host_url' => "http://".$_SERVER['HTTP_HOST'],
        
        'admin_group' => 1,

        'reg_user_group' => 2,
		
		'registration_on' => true,

        'life_time_token' => 31536000,

		'timezone' => 'Europe/Moscow',
		
        'max_size_upload_img' => 2097152,

        'template' => 'Default',
        
        'count_news_on_page' => 5,
    )
?>
