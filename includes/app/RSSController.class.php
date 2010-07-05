<?php
class RSSController{
    private $output = '';
    
    private $sub_controller = false;
    
    public function __construct(Router $router, Savant3 $view, PancakeTF_DBAccessI $db=null, $online=true){
        $this->router = $router;
        $this->view = $view;
        $this->db = ($db) ?  $db  : new PancakeTF_PDOAccess;
        $this->getSubController();

        $this->view->assign('sub_controller',$this->sub_controller);
    }
    
    public function getSubController(){
        $sub_controller = false;
        switch ($this->router->getFolder(0)){
            case 'tags':
                $sub_controller = new TagsController($this->router,$this->view,'rss');
            break;
            default:
                $sub_controller = new PostsController($this->router,$this->view,'rss');
            break;
        }
        
        if ($sub_controller instanceof AbstractSubController){
            $sub_controller->execute();
            $this->sub_controller = $sub_controller;
        }
    }
    
    public function setCSS(){}
    
    public function setJS(){}
    
    public function generate(){
       return $this->sub_controller->generate();
    }
}