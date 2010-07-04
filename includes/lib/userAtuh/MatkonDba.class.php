<?php
require_once 'UserAtuhDbaSession.class.php';

class MatkonDba extends UserAtuhDbaSession{
    public function __construct(PancakeTF_DBAccessI $db=null){
        $this->db = $db ? $db : new PancakeTF_PDOAccess;
    }
    
    public function userExists($name){
        return (bool)$this->db->count('users',array('name'=>$name));
    }
    
    public function getPass($name){
        $res = $this->db->queryRow("SELECT `pass` FROM `users` WHERE  `name`=?",array($name));
        
        if (!$res) throw new InvalidArgumentException("User $name does not exists");
        
        return $res['pass'];
    }
}