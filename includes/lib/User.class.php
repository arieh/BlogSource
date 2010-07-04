<?php
class User{
    const DEFAULT_ID = 1;
    
    const SESSION_NAME = 'blog-user';
    
    static private $info = array(
        'id' => self::DEFAULT_ID,
        'admin'=>false
    );
    
    public function __construct(){
        if (isset($_SESSION[self::SESSION_NAME])){
            self::$info = $_SESSION[self::SESSION_NAME];
        }else $this->setId(self::DEFAULT_ID);
    }
    
    public function setId($id){
        self::$info['id'] = $id;
        
        if ($id != self::DEFAULT_ID) self::$info['admin']=true;
        else self::$info['admin'] = false;;
        
        $_SESSION[self::SESSION_NAME] = self::$info;
    }
    
    public function isAdmin(){return self::$info['admin'];}
    
    public function isLoggedIn(){return self::$info['id']!=self::DEFAULT_ID;}
}