<?php 

    require_once ENGINE_DIR.'/includes/upload.php';

    if( (bool) Store::get('USER.allow_upload_files')){

        $image = new Upload_Image('file');

        if($image->filepath){
            $image->save();
            $response->set($image->filepath);
        }
        else {
            $response->set_error($image->errors[0]['text'], $image->errors[0]['error_num']);
            http_response_code(406);
        }
    } else {
        $response->set_error('Ошибка доступа!', 'У вас не достаточно прав!', 403);
        http_response_code(403);
    }

    $response->send();
?>
