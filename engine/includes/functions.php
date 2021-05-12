<?php 
    function addGetParam($name, $param){
        if(strpos($_SERVER['REQUEST_URI'], '?') === false){
            return $_SERVER['REQUEST_URI'].'?'.$name.'='.$param;
        }
        return $_SERVER['REQUEST_URI'].'&'.$name.'='.$param;     
    }   
?>