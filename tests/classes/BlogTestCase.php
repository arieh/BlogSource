<?php
require_once dirname(__FILE__) . '/../../includes/lib/DBAccess/PancakeTF_PDOAccess.class.php';

abstract class BlogTestCase extends PHPUnit_Framework_Testcase{
    protected $sql='';
    
    protected function getMock($originalClassName, $methods = array(), array $arguments = array(), $mockClassName = '', $callOriginalConstructor = false, $callOriginalClone = TRUE, $callAutoload = TRUE){
        return parent::getMock($originalClassName,$methods,$arguments,$mockClassName,$callOriginalConstructor,$callOriginalClone,$callAutoload);
    }
    
    protected $db = null;
    
    protected function setUpDB(){
        PancakeTF_PDOAccess::connect('mysql','localhost','blog','root','1234');
        $this->db = new PancakeTF_PDOAccess();
        
        if (!$this->sql) return;
        
        $sql = file_get_contents(dirname(__FILE__)."/../sql/{$this->sql}.sql");
        $sql = explode (';',$sql);
        foreach ($sql as $stmt){
            try{
                $this->db->update($stmt);
            }catch (Exception $e){}
        }
    }
}