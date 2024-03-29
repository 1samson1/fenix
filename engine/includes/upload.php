<?php
	
    class Upload_Image{
        public $errors = array();
		public $file = false;
        public $filepath = false;
        
        public function __construct($file_name, $file_newname = false, $dir_save = false){

			$this->file = $_FILES[$file_name];
			
            if(!empty($this->file['name'])){				

				if($this->file['size'] < Store::get('config.max_size_upload_img')){
					if($this->file['error'] == 0){
						$type = getimagesize($this->file['tmp_name']);	

						if($type && ($type['mime'] == 'image/png' || $type['mime'] == 'image/gif' || $type['mime'] == 'image/jpeg')){
							
							if($file_newname) {
								preg_match('/\.\w+$/', $this->file['name'],$matches);
								$file_newname = $file_newname.$matches[0];
							}
							else{
								$file_newname = time().'_'.$this->file['name'];
							}

							if(!$dir_save) $dir_save = 'images';

							$this->filepath = 'uploads/'.$dir_save.'/'.$file_newname;
														
						}						
						else$this->set_error('Файл не является изображением!', 255);						
					}
					else $this->set_error('Ошибка загрузки файла на сервер!', 256);
				}
				else $this->set_error('Файл имеет слишком большой размер!', 257);
            }
        }

		public function save(){
			$path =ROOT_DIR.'/'.$this->filepath;

			if(!file_exists(dirname($path))){
				mkdir(dirname($path));
			}

			move_uploaded_file($this->file['tmp_name'], $path);			
		}

		public function set_error($text, $number){            
            $this->errors[]= array(
                'title' => "Ошибка загрузки файла",
                'text' => $text,
                'type' => 'error',
                'error_num' => $number,
            ); 
        }

    }

	function delete_file($path){
		if($path and file_exists(ROOT_DIR.'/'.$path)){
			unlink(ROOT_DIR.'/'.$path);
		}
	}
?>
