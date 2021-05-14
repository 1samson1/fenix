<?php 
    class Router {

        public $request;        

        public function __construct($request){
            $this->request = $request;
        }

        public function finish($action){
            $this->default = $action;
        }

        public function urls($routes){
            foreach ($routes as $pattern => $action){

                $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/U';

                if (preg_match($pattern, $this->request, $params))
                {
                    array_shift($params);                    
                    return call_user_func_array($action, array_values($params));
                }

            }
            return call_user_func_array($this->default);

        }
    }
?>
