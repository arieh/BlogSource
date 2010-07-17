<?php
require_once dirname(__FILE__) . '/../classes/ModelTester.php';

class ModelActionTest extends ModelTester{
    /**
     * @dataProvider provideGoodActions
     */
    public function testDefaultAction($name,$action){
        $this->setUpModel(array('action'=>$name));
        $this->assertEquals($action,$this->model->getAction());
    }
    
    static public function provideGoodActions(){
        return array(
            array('one','one')
            , array('two','two')
            , array('three','three')
            , array('four','four')
            , array('','one')
            , array('five','one')
        );
    }
    
    /**
     * @dataProvider provideActionResults
     */
    public function testActionResults($action,$getter,$before,$after){
        $this->setUpModel(array('action'=>$action));
        $this->assertEquals($before,$this->model->$getter());
        $this->model->execute();
        $this->assertEquals($after,$this->model->$getter());
    }
    
    static public function provideActionResults(){
        return array(
            array('one','getOne',1,2)
            , array('two','getTwo',2,3)
            , array('four','getArrays',array(),array(1,2))
        );
    }
}