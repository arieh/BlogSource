<?php
abstract class AbstractMainController{
    
	protected $sub_controller;
	
	protected $view;
	
	protected $router;
	
	protected $db;
	
	protected $env;

	public function __construct(Router $router, Savant3 $view, PancakeTF_DBAccessI $db){
		$this->router = $router;
        $this->view = $view;
        $this->db = $db;
        $this->getSubController();
	}
	
	protected function getSubController(){
    	$folder = $this->router->getFolder(0);
    	$name = ucwords($folder).'Controller';
    	if (file_exists(dirname(__FILE__).'/../../app/controllers/'.$name.'.class.php')){
    		$sub_controller = new $name($this->router,$this->view,$this->env);
    	}elseif ($this->default_controller){
    		$name = $this->default_controller;
            $sub_controller = new $name($this->router,$this->view,$this->env);
        }
    	
        if ($sub_controller instanceof AbstractSubController){
            $sub_controller->execute();
            $this->sub_controller = $sub_controller;
        }
        
        return $this;
    }
    
    abstract public function generate();
    
}