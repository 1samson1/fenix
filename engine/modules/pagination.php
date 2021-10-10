<?php

    class Pagination{
        public $count_pages;
        public $count_items;
        public $count_items_on_page;
        public $active;  
        public $start;
        public $end;   
        public $url;   
        public $max_size_range = 10;


        public function __construct($counter, $url, $count_items_on_page){
            global $db;

            $this->active = isset($_GET['page'])? $_GET['page']: 1;
            $this->count_items_on_pages = $count_items_on_page;    
            $this->url = $url;    

            call_user_func($counter);
            if($table = $db->get_row()){

                $this->count_items = $table['count'];
                
                // Вычисление количества страниц
                if($this->count_items % $this->count_items_on_pages == 0) $this->count_pages = floor($this->count_items / $this->count_items_on_pages);
                else $this->count_pages = floor($this->count_items / $this->count_items_on_pages) + 1; 

                if ( $this->count_pages > 1) {

                    // Вычисляем сколько отступили от левой границы списка
                    $left = $this->active - 1;

                    // Вычисление начала диапазона страниц
                    if ($left < floor($this->max_size_range / 2)) $this->start = 1;
                    else $this->start = $this->active - floor($this->max_size_range / 2); 
                    
                    
                    // Вычисление конца диапазона страниц
                    $this->end = $this->start + $this->max_size_range;

                    // Смещение диапазона страниц если конец диапазона выходит за количество страниц
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
                    $tpl->set('{prev-page}', $this->active == 2? $this->url: $this->url.'page/'.($this->active - 1).'/');
                }
                else $tpl->set_block('prev-link', '', 's');

                if($this->active < $this->count_pages){
                    $tpl->set('[next-link]', '');
                    $tpl->set('[/next-link]', '');
                    $tpl->set('{next-page}', $this->url.'page/'.($this->active + 1).'/');
                    $tpl->set('{last-page}', $this->url.'page/'.$this->count_pages.'/');
                }
                else $tpl->set_block('next-link', '', 's');                
                
                for ($i = $this->start; $i <= $this->end; $i++) {                    
                    if ($i == $this->active){
                        $pages .= '<span>'.$i.'</span>'; 
                    }
                    else { 
                        $pages .= '<a href="'.($i == 1? $this->url : $this->url.'page/'.$i.'/').'">'.$i.'</a>';
                    }
                }

                $tpl->set('{pages}', $pages);

                $tpl->save('{navigation}');
            
            }
            else $tpl->set('{navigation}', '');
        }

    }

 ?>
