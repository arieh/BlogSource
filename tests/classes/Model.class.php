<?php
require_once dirname(__FILE__) . '/../../includes/lib/models/AbstractModel.class.php';

class Model extends AbstractModel{
    protected $_actions = array(
        'one' => 'one'
        , 'two' => 'two'
        , 'three' => 'oneTwo'
        , 'four' => 'fourThree'
        , 'set-error' =>'generateError'
        , 'set-errors' => 'generateErrors'
    );
    
    protected $_default_action = 'one';
    
    protected $_one = 1;
    
    protected $_two = 2;
    
    protected $_four = 4;
    
    protected $_five = 5;
    
    protected $_messages  = array('message1','message2');
    
    protected $_names = array('name1','name2');
    
    protected $_users = array('user1','user2');
    
    protected $_false = false;
    
    protected $_wanted = array();
    
    protected $_happy = '';
    
    protected $_sad = 0;
    
    protected $_arrays = array();
    
    protected function one(){
        $this->_one++;
    }
    
    protected function two(){
        $this->_two++;
    }
    
    protected function fourThree(){
        $this->_arrays = array(1,2);
    }
    
    protected function generateError(){
        $this->setError('varbus');
    }
    
    protected function generateErrors(){
        $this->setError('varbus');
        $this->setError('bar');
        $this->setError('foo');
    }
}