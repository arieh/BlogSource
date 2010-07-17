<?php
require_once dirname(__FILE__) . '/../classes/BlogTestCase.php';
require_once dirname(__FILE__) . '/../../includes/app/models/Search.class.php';

class SearchTest extends BlogTestCase{
    private function setUpModel(){
        $this->setUpDB();
        $this->model = new Search();
    }
    
    public function testSetUp(){
        $this->setUpModel();
        
        $this->assertTrue($this->model instanceof Search);
        $this->assertFalse($this->model->isError());
    }
    
    public function testMatch(){
        $this->setUpModel();
        $this->model->setOption('value','basics');
        $this->model->execute();
        
        $this->assertFalse($this->model->isError());
        
        $post = $this->model->getPost();
        $this->assertTrue(is_array($post));
        
        $this->assertEquals(7,count($post));
        
        $this->assertEquals(2,$this->model->getCount());
    }
    
    public function testNoMatch(){
        $this->setUpModel();
        $this->model->setOption('value','and');
        $this->model->execute();
        
        $this->assertFalse($this->model->isError());
        
        $post = $this->model->getPost();
        $this->assertTrue(is_array($post));
        
        $this->assertEquals(7,count($post));
        
        $this->assertEquals(5,$this->model->getCount());
    }
}