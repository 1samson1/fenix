<?php

    class DataBase {
        
        public $error;
        public $error_num;
        public $connect;
        public $result;
        public $queries = null;

        public function __construct($host, $user, $password, $db_name){
            $this->connect = mysqli_connect($host, $user, $password, $db_name) or die("Нет подключения к БД");
			$this->set_charset();
        }  
        
        public function query($query){
            $this->result = mysqli_query($this->connect,$query);
            $this->error = mysqli_error($this->connect);
			$this->error_num = mysqli_errno($this->connect);
            return $this->result;
        }

        public function table($table){
            return new Query($this, $table);
        }
        
        public function multi_query($query) {
            if( mysqli_multi_query($this->connect, $query) ) {
                while( mysqli_more_results($this->connect) && mysqli_next_result($this->connect) ){
                    ;
                }
            }
            $this->error = mysqli_error($this->connect);
            $this->error_num = mysqli_errno($this->connect);
        }
        
        public function add_query($query) {
            $this->queries .= $query;
        }

        public function add_query_begin($query){
            $this->queries = $query.$this->queries;
        }
        
        public function send_queries() {
            $this->queries = 'START TRANSACTION;'.$this->queries;
            $this->queries .= 'COMMIT;';
            $this->multi_query($this->queries);
            $this->queries = null;
        }

        public function num_rows($query = '') {
            if ($query == '') $query = $this->result;
    
            return mysqli_num_rows($query);
        }

        public function get_row($query = '') {
            if ($query == '') $query = $this->result; 
            return mysqli_fetch_assoc($query);
        } 

        public function get_row_noassoc($query = '') {
            if ($query == '') $query = $this->result;    
            return mysqli_fetch_row($query);
        } 

        public function last_id(){
            return mysqli_insert_id($this->connect);
        }

        public function get_array($query = ''){
            if ($query == '') $query = $this->result;    

            $results = [];
            
            while($row = $this->get_row()){
                $results[]= $row ;
            }
            return $results;
        }

        public function hash($value){
            return password_hash($value, PASSWORD_DEFAULT);
        }

        public function valid_value($value){
            if(is_bool($value))
                return $this->bool_to_sql($value);
            
            return $this->ecran($value);
        }

        public function ecran($value){
            return mysqli_real_escape_string($this->connect, stripslashes($value));
        }

        public function ecran_html($value){
            return $this->ecran(htmlspecialchars($value));
        }

        public function bool_to_sql($bool){
            return $bool ? 1 : 0;
        }   

        public function str_to_sql($str){
            return $str ? $str : '';
        }   
		
		public function set_charset($charset="utf8"){
            return mysqli_set_charset($this->connect, $charset);    
        }  
		
		public function __destruct(){
            mysqli_close($this->connect);
        }
    }

    class Query {
        private $db;
        private $table;
        private $_where = null;
        private $_orderby = null;
        private $_selectable = '*';
        private $_offset = null;
        private $_limit = null;
        private $_joins = null;

        public function __construct($db, $table) {
            $this->db = $db;
            $this->table = $table;
        }

        public function insert ($data){
            $properties = '';
            $values = '';
            foreach($data as $property => $value){
                if($properties){
                    $properties .= ', ';
                    $values .= ', ';
                }

                $properties .= $property;
                $values .= '"' .  $this->db->valid_value($value) . '"';
            }

            return $this->db->query('INSERT INTO ' .$this->table .' ('. $properties . ') VALUES (' . $values . ');');
        }

        public function update($data){
            $values = '';
            foreach($data as $property => $value){
                if($values)
                    $values .= ', ';
                
                $values .= $property.' = "' . $this->db->valid_value($value) . '"';
            }

            return $this->db->query('UPDATE '.$this->table .' SET ' . $values . $this->_where . ';');
        }

        public function delete(){
            return $this->db->query('DELETE FROM ' . $this->table . $this->_where . ';');
        }
        
        public function get(){
            return $this->db->get_array(
                $this->db->query( $this->_constructSelect())
            );
        }

        public function first(){
            return $this->db->get_row(
                $this->db->query( $this->_constructSelect())
            );
        }

        public function truncate(){
            return $this->db->query('TRUNCATE TABLE ' .$this->table. ';');
        }

        public function select($columns = ['*']){
            $columns = is_array($columns) ? $columns : func_get_args();

            foreach($columns as $value){
                if($this->_selectable == '*')
                    $this->_selectable = '';
                else
                    $this->_selectable .= ', ';

                
                $this->_selectable .= $value;
            }

            return $this;
        }

        public function where($column, $operator, $value, $boolean = 'and'){
            if (!$this->_where){
                $this->_where = ' WHERE ';
            } else {
                $this->_where .=  ' '.strtoupper($boolean).' ';
            }

            $this->_where .= $column . ' ' . $operator . ' "'.$this->db->valid_value($value).'"';

            return $this;
        }
        
        public function orderBy($column, $sort = 'asc'){
            if (!$this->_orderby){
                $this->_orderby = ' ORDER BY ';
            } else {
                $this->_orderby .=  ', ';
            }

            $this->_orderby .= $column . ' ' . strtoupper($sort);

            return $this;
        }

        public function join($table, $first, $operator = null, $second = null, $type = 'inner'){
            $this->_joins .= ' '.strtoupper($type) . ' JOIN ' . $table . ' ON ' . $first . ' ' . $operator . ' ' . $second;
        
            return $this;
        }

        public function leftJoin($table, $first, $operator = null, $second = null){
            return $this->join($table, $first, $operator, $second, 'left');
        }

        public function rightJoin($table, $first, $operator = null, $second = null){
            return $this->join($table, $first, $operator, $second, 'right');
        }

        public function offset($value){
            $value = max(0, $value);

            $this->_offset = ' OFFSET ' . $value;

            return $this;
        }

        public function limit($value){
            $value = max(0, $value);

            $this->_limit = ' LIMIT ' . $value;

            return $this;
        }

        private function _constructSelect(){
            return  'SELECT ' . $this->_selectable . ' FROM ' . $this->table 
                . $this->_joins 
                . $this->_where 
                . $this->_orderby 
                . $this->_limit 
                . ($this->_limit ? $this->_offset : null);
        }
    }
?>
