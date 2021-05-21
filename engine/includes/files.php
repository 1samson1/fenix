<?php 
    class Files{
        public static function get_dirs($path){            
            if ($handle = opendir($path)) {
                while ($entry = readdir($handle)) {         
                    if (is_dir($path.$entry) && $entry != '.' && $entry != '..') {
                        $dirlist[] = $entry;
                    }
                }
                closedir($handle);
            }
            return $dirlist;
        }

        public static function get_files($path){
            if ($handle = opendir($path)) {
                while ($entry = readdir($handle)) {
                    if (is_file($path.$entry)) {
                        $filelist[] = $entry;
                    }
                }
                closedir($handle);
            }
            return $filelist;
        }
    }

?>
