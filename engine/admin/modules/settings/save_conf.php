<?php 
    class Save_conf{

        public $path = null;
        public $file = null;

        public function __construct($path){
            if(file_exists($path)){
                $this->path = $path;
                $this->file = file_get_contents($path);
            }
            else die('File config not found!');
        }

        public function set_str_option($key, $value){
            $this->file = preg_replace("/(\"|')".$key."(\"|')\s*=>\s*(.+),/m", "'{$key}' => '{$value}',", $this->file);
        }

        public function set_int_option($key, $value){
            $value = intval($value);
            $this->file = preg_replace("/(\"|')".$key."(\"|')\s*=>\s*(.+),/m", "'{$key}' => {$value},", $this->file);
        }

        public function set_bool_option($key, $value){            
            $value = $value ? 'true' : 'false';
            $this->file = preg_replace("/(\"|')".$key."(\"|')\s*=>\s*(.+),/m", "'{$key}' => {$value},", $this->file);
        }

        public function save(){
            file_put_contents($this->path, $this->file);
        }
    }
?>