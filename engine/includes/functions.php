<?php 
    function addGetParam($name, $param, $url = false){
        if(!$url){
            $url = $_SERVER['REQUEST_URI'];
        }

        if(strpos($url, '?') === false){
            return $url.'?'.$name.'='.$param;
        }
        return $url.'&'.$name.'='.$param;     
    }

    function webPath($path){
        return   str_replace('\\', '/', '/' . str_replace(ROOT_DIR, '', $path));
    }

    function genRandStr($length)
    {
        return substr(
            bin2hex( openssl_random_pseudo_bytes($length) ),
            0,
            $length
        );
    }

    function debug_trace($value){
        echo "BEGIN TRACE <br>";
        foreach(debug_backtrace() as $trace){
            echo '<pre>';
            echo '<b>File: '.$trace['file'].'</b> ';
            echo ' Func: '.$trace['function'].'';
            echo ' Line: '.$trace['line'].'';
            echo '</pre>';
        }
        echo "END TRACE <br>";
        debug($value);
    }
    
    function debug($value){
        echo '<pre>';
        var_dump($value);
        echo '</pre>';
    }    
?>
