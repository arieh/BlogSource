<?php
abstract class AbstractMainController{
    /**
     * the current Sub Controller
     * @var AbstractSubController
     */
	protected $sub_controller;
	
	/**
	 * view object
	 * @var TFViewI
	 */
	protected $view;
	
	/**
	 * router
	 * @var Router
	 */
	protected $router;
	
	/**
	 * db object
	 * @var PancakeTF_DBAccessI
	 */
	protected $db;
	
	/**
	 * current eviroment
	 * @var string
	 */
	protected $env;
    
	/**
	 * 
	 * @param Router $router 
	 * @param TFViewI $view
	 * @param PancakeTF_DBAccessI $db
	 */
	public function __construct(Router $router, TFViewI $view, PancakeTF_DBAccessI $db = null){
		$this->router = $router;
        $this->view = $view;
        $this->db = $db;
        $this->getSubController();
	}
	
	/**
	 * used to choose current sub controller
	 * @access protected
	 */
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
    
    /**
     * generates controller output
     * @access public
     * @return string
     */
    abstract public function generate();    
}