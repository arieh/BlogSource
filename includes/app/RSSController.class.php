<?php
class RSSController extends AbstractMainController{
    private $output = '';
    
    protected $env = 'rss';
    
    protected $default_controller = 'PostsController';
    
    public function __construct(Router $router, TFViewI $view, PancakeTF_DBAccessI $db=null){
        $this->db = ($db) ?  $db  : new PancakeTF_PDOAccess;
        
        parent::__construct($router,$view,$db);
    }

    public function generate(){
    	$this->view->assign('sub_controller',$this->sub_controller);
    	
    	return $this->sub_controller->generate();
    }
}