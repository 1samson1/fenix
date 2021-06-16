<?php 
    http_response_code(404);

    $response->set_error('API Not found', 404);

    $response->send();

?>
