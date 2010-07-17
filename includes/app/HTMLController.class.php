<?php
class HTMLController{
    private $css = array('reset','main');
    
    private $js = array('main');
    
    private $titles = array('Arieh.co.il');
    
    private $desc = 'some-desc';
    
    private $output = '';
    
    private $sub_controller = false;
    
    public function __construct(Router $router, Savant3 $view, PancakeTF_DBAccessI $db=null, $online=true){
        $this->router = $router;
        $this->view = $view;
        $this->db = ($db) ?  $db  : new PancakeTF_PDOAccess;
        
        $this->view->assign('action','posts-list');
        
        $this->getSubController();
        
        $this->setCSS();
        
        $this->setJS();
        $user = new User;
        
        $this->view->assign('css',$this->css);
        $this->view->assign('js',$this->js);
        $this->view->assign('titles',$this->titles);
        $this->view->assign('description',$this->desc);
        $this->view->assign('sub_controller',$this->sub_controller);
        $this->view->assign('online',(bool)$online);
        $this->view->assign('user',$user);
        $this->view->assign('menu',new Menu);
        
    }
    
    public function getSubController(){
        $sub_controller = false;
        switch ($this->router->getFolder(0)){
            case 'search':
                $sub_controller = new SearchController($this->router,$this->view);
            break;
            case 'tags':
                $sub_controller = new TagsController($this->router,$this->view);
            break;
            default:
                $sub_controller = new PostsController($this->router,$this->view);
            break;
        }
        
        if ($sub_controller instanceof AbstractSubController){
            $sub_controller->execute();
            $this->sub_controller = $sub_controller;
            array_unshift($this->titles,$sub_controller->getTitle());
            $this->desc = $sub_controller->getDescription();
            foreach ($sub_controller->getCSS() as $css) $this->css[]=$css;
            foreach ($sub_controller->getJS() as $js) $this->js[]=$js;
        }
    }
    
    public function setCSS(){}
    
    public function setJS(){}
    
    public function generate(){
       $this->output .= $this->view->fetch('/html/header.tpl.php');
       $this->output .= $this->view->fetch('/html/body.tpl.php');
       $this->output .= $this->view->fetch('/html/footer.tpl.php');
       
       return $this->output;
    }
}