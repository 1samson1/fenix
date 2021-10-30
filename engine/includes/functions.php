<?php 
    function addGetParam($name, $param){
        if(strpos($_SERVER['REQUEST_URI'], '?') === false){
            return $_SERVER['REQUEST_URI'].'?'.$name.'='.$param;
        }
        return $_SERVER['REQUEST_URI'].'&'.$name.'='.$param;     
    }

    function webPath($path){
        return   str_replace('\\', '/', '/' . str_replace(ROOT_DIR, '', $path));
    }
    
    function debug($value){
        echo '<pre>';
        var_dump($value);
        echo '</pre>';
    }    
?>
