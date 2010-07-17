<?php
require_once dirname(__FILE__) . '/../classes/ModelTester.php';

class ModelErrosTest extends ModelTester{
    public function testIsErrorFalse(){
        $this->setUpModel();
        $this->assertFalse($this->model->isError());
    }
    
    /**
     * @depends testIsErrorFalse
     */
    public function testIdErrorTrue(){
        $this->setUpModel(array('action'=>'set-error'));
        $this->model->execute();
        $this->assertTrue($this->model->isError());
    }
    
    /**
     * @dataProvider provideErrorNamesTrue
     */
    public function testIsErrorByNameTrue($error){
        $this->setUpModel(array('action'=>'set-errors'));
        $this->model->execute();
        $this->assertTrue($this->model->isError($error));
    }
    
    static public function provideErrorNamesTrue(){
        return array(
            array('varbus')
            , array('bar')
            , array('foo')
        );
    }
    
    /**
     * @depends testIsErrorByNameTrue
     * @dataProvider provideErrorNamesFalse
     */
    public function testIsErrorByNameFalse($error){
       $this->setUpModel(array('action'=>'set-errors'));
        $this->model->execute();
        $this->assertFalse($this->model->isError($error));
    }
    
    static public function provideErrorNamesFalse(){
        return array(
            array('alice')
            , array('bob')
            , array('greg')
        );
    }
    
    public function testGetError(){
        $this->setUpModel(array('action'=>'set-errors'));
        $this->model->execute();
        $this->assertEquals(array('varbus','bar','foo'),$this->model->getErrors());
    }
}