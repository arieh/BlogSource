<?php
require_once 'UserAtuhDba.class.php';

class UserAtuhDbaSession extends UserAtuhDba{
     const SESSION_NAME = 'user_atuh';
     public function insertKey($key){
         $_SESSION[self::SESSION_NAME] = $key;
     }
     
     public function getKey(){
         return $_SESSION[self::SESSION_NAME];
     }
}