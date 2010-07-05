<?php
class File{
    private $name ='';
    public function __construct($name,$dir = ''){
        $file = $_FILES[$name];
        $upload_dir = $dir ? $dir : dirname(__FILE__) .'/../../public/files/';
        if (file_exists($upload_dir . $file['name'])){
           $file['name'] = $this->getNewFileName($file['name'],$upload_dir);
        }
        
        move_uploaded_file($file['tmp_name'],$upload_dir . $file['name']);
        $this->name = $file['name'];
    }
    
    private function getNewFileName($name,$dir){
        $i =0;
        while (file_exists($dir . $i .$name)) $i++;
        return $i.$name;
    }
    
    public function __toString(){return $this->name;}
}