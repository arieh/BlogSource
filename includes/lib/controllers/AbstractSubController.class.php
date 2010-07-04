<?php
abstract class AbstractSubController{
    
    protected $css = array();
    
    protected $js = array();
    
    protected $actions;
    
    protected $default_action;
    
    protected $model;
    
    protected $user;
    
    protected $env = 'xhtml';
    
    protected $action = '';
    
    protected $folder = '';
    
    protected $template;
    
    public function __construct(Router $router, Savant3 $savant, $env = 'xhtml'){
        $this->router = $router;
        $this->view   = $savant;
        $this->user   = new User;
        $this->env    = $env;
        $this->view->assign('tinymce',false);
        $temp_action = $router->getFolder(1);
        if (!$temp_action || !array_key_exists($temp_action,$this->actions)) $this->action = $this->default_action;
        else $this->action = $temp_action;
     }
     
     public function execute(){
         $action = $this->actions[$this->action];
         $this->{$action}();
     }
     
     protected function fetchTemplate($name){
         $file = dirname(__FILE__) . '/../templates/' . $this->folder . '/' . $this->env . "/$name.tpl.php";
         if (file_exists($file)){
             return $this->view->fetch($this->folder . "/{$this->env}/$name.tpl.php");
         }else return $this->view->fetch($this->folder . "/xhtml/$name.tpl.php");
     }
     
     protected function goHome(){
        global $paths;
         header("Location:{$paths[0]}");
     }
     
     abstract public function getTitle();
     
     abstract public function getDescription();
     
     public function generate(){
         return $this->fetchTemplate($this->template);
     }
     
     public function getCSS(){return $this->css;}
     public function getJS(){return $this->js;}
}