<?php

    class Pagination{
        public $count_pages;
        public $count_items_on_page;
        public $active;  
        public $start;
        public $end;   
        public $url;   


        public function __construct($table, $url, $count_items_on_page){
            global $db;

            $this->active = isset($_GET['page'])? $_GET['page']: 1;
            $this->count_items_on_pages = $count_items_on_page;    
            $this->url = $url;    

            $db->count_pages($table);
            if($table = $db->get_row()){
                
                if($table['count'] % $this->count_items_on_pages == 0) $this->count_pages = floor($table['count'] / $this->count_items_on_pages);
                else $this->count_pages = floor($table['count'] / $this->count_items_on_pages) + 1;

                if ( $this->count_pages > 1) {

                    $left = $this->active - 1;

                    $right =  $this->count_pages - $this->active;

                    if ($left < floor($this->count_items_on_pages / 2)) $start = 1;
                    else $this->start = $this->active - floor($this->count_items_on_pages / 2);                   

                    $this->end = $this->start + $this->count_items_on_pages;

                    if ($this->end >  $this->count_pages) {
                        $this->start -= ($this->end -  $this->count_pages);
                        $this->end =  $this->count_pages;
                        if ($this->start < 1) $this->start = 1;
                    }    
                }
            }
        }

        public function get_begin_item(){
            return $this->active? ($this->active - 1) * $this->count_items_on_pages: 0;
        }

        public function gen_tpl( ){
            global $tpl;

            if ( $this->count_pages > 1) {

                $tpl->load('navigation.html');

                if($this->active > 1){
                    $tpl->set('[prev-link]', '');
                    $tpl->set('[/prev-link]', '');
                    $tpl->set('{first-page}', $this->url);
                    $tpl->set('{prev-page}', $this->active == 2? $this->url: $this->url.'/page/'.($this->active - 1) );
                }
                else $tpl->set_block('/\[prev-link\](.*)\[\/prev-link\]/s','');

                if($this->active < $this->count_pages){
                    $tpl->set('[next-link]', '');
                    $tpl->set('[/next-link]', '');
                    $tpl->set('{next-page}', $this->url.'/page/'.($this->active + 1));
                    $tpl->set('{last-page}', $this->url.'/page/'.$this->count_pages);
                }
                else $tpl->set_block('/\[next-link\](.*)\[\/next-link\]/s','');
                
                for ($i = $this->start; $i <= $this->end; $i++) {
                    if ($i == $this->active){
                        $pages .= '<span>'.$i.'</span>'; 
                    }
                    else { 
                        $pages .= '<a href="'.($i == 1? $this->url : $this->url.'/page/'.$i).'">'.$i.'</a>';
                    }
                }

                $tpl->set('{pages}', $pages);

                $tpl->save('{navigation}');
            
            }
            else $tpl->set('{navigation}', '');
        }

    }

 ?>
