<?php
require_once dirname(__FILE__) .'/Model.class.php';
require_once dirname(__FILE__) .'/BlogTestCase.php';

abstract class ModelTester extends BlogTestCase{
    public function setUpModel($options=array(),$db=false){
        $this->db = $db ? $db : $this->getMock('PancakeTF_DBAccessI');
        $this->model = new Model($options,$this->db);
    }
}