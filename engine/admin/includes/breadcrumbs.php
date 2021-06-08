<?php

class BreadCrumbs{

    public $crumbs = array();
    public $count;

    public function __construct($crumb, $crumb_url){
        $this->crumbs[] = array(
            'name' => $crumb,
            'url' => $crumb_url,
        ); 
        $this->count++;
    }

    public function add($crumb, $crumb_url){
        $this->crumbs[] = array(
            'name' => $crumb,
            'url' => $crumb_url,
        ); 
        $this->count++;
    }

    public function pop(){
        $this->count--;
        return array_pop($this->crumbs);
    }

}

?>
