<?php
require_once dirname(__FILE__) . '/../classes/ModelTester.php';
class ModelGettersTest extends ModelTester{
    /**
     * @dataProvider provideGetValues
     */
    public function testGet($method,$value){
        $this->setUpModel();
        $this->assertEquals($value,$this->model->{$method}());
    }
    
    
    static public function provideGetValues(){
        return array(
            array('getOne' , 1)
            , array('getTwo',2)
            , array('getFour',4)
            , array('getFive',5)
        );
    }
    
    /**
     * @expectedException LogicException
     * @dataProvider provideCallInvalid
     */
    public function testCallInvalid($method){
        $this->setUpModel();
        $this->model->{$method}();
    }
    
    static function provideCallInvalid(){
        return array(
            array('getSix')
            ,array('getSeven')
            ,array('getEight')
        );
    }
    
    /**
     * @dataProvider provideCallMultiple
     */
    public function testCallMultiple($method,$expect){
        $this->setUpModel();
        $this->assertEquals($expect,$this->model->$method());
    }
    
    static function provideCallMultiple(){
        return array(
            array('getMessages',array('message1','message2'))
            , array('getNames',array('name1','name2'))
            , array('getUsers',array('user1','user2'))
        );
    }
    
    /**
     * @dataProvider provideCallSingular
     */
    public function testCallSingular($method,$expect){
        $this->setUpModel();
        foreach ($expect as $ex) $this->assertEquals($ex,$this->model->$method());
    }
    
    static function provideCallSingular(){
        return array(
            array('getMessage',array('message1','message2'))
            , array('getName',array('name1','name2'))
            , array('getUser',array('user1','user2'))
        );
    }
    
    /**
     * @dataProvider provideCallSingularPop
     */
    public function testCallSingularPop($method,$expect){
        $this->setUpModel();
        foreach ($expect as $ex) $this->assertEquals($ex,$this->model->$method(true));
    }
    
    static function provideCallSingularPop(){
        return array(
            array('getMessage',array('message2','message1'))
            , array('getName',array('name2','name1'))
            , array('getUser',array('user2','user1'))
        );
    }
    
    /**
     * @dataProvider provideCallBooleanTrue
     */
    public function testCallBooleanTrue($method){
        $this->setUpModel();
        $this->assertTrue($this->model->$method());
    }
    
    static function provideCallBooleanTrue(){
        return array(
            array('isUsers')
            , array('isOne')
            , array('isTwo')
        );
    }

    /**
     * @dataProvider provideCallBooleanFalse
     */
    public function testCallBooleanFalse($method){
        $this->setUpModel();
        $this->assertFalse($this->model->$method());
    }
    
    
    static function provideCallBooleanFalse(){
        return array(
            array('isFalse')
            , array('isWanted')
            , array('isHappy')
            , array('isSad')
        );
    }
}