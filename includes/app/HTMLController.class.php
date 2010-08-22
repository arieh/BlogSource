<?php
class HTMLController extends AbstractMainController{
    private $css = array('reset','main_1');
    
    private $js = array('main_1','placeholder-min');
    
    private $titles = array('Arieh.co.il');
    
    private $desc = 'some-desc';
    
    private $output = '';
    
    protected $sub_controller = false;
    
    private $css_token = '2';
    
    private $js_token = '2';
    
    private $online = true;
    
    protected $env = 'xhtml';
    
    protected $default_controller = 'PostsController';
    
    public function __construct(Router $router, TFViewI $view, PancakeTF_DBAccessI $db=null, $online=true){
        $this->db = ($db) ?  $db  : new PancakeTF_PDOAccess;
        $view->assign('action','posts-list'); 
        $view->assign('nojs',false);   
        
        parent::__construct($router,$view,$db);  
         
        $this->online = (bool)$online;
    }
    
    protected function getSubController(){
        parent::getSubController();
        if ($this->sub_controller){
            array_unshift($this->titles,$this->sub_controller->getTitle());
            $this->desc = $this->sub_controller->getDescription();
            
            for ( $i=0
                  ,$css = $this->sub_controller->getCSS()
                  ,$l=count($css); 
                 
                   $i<$l; 
                   $i++){
            	$this->css[]=$css[$i];
            }
            
            for ( $i=0
                  ,$js = $this->sub_controller->getJS()
                  ,$l=count($js); 
                 
                   $i<$l; 
                   $i++){
                $this->js[]=$js[$i];
            }
            
        }
    }
        
    public function generate(){
        $this->view->assign('css',$this->css);
        $this->view->assign('css_token',$this->css_token);
        
        $this->view->assign('js',$this->js);
        $this->view->assign('js_token',$this->js_token);
        
        $this->view->assign('titles',$this->titles);
        $this->view->assign('description',$this->desc);
        
        $this->view->assign('sub_controller',$this->sub_controller);
        $this->view->assign('online',$this->online);
        
        $this->view->assign('user',new User);
        $this->view->assign('menu',new Menu);
    	
    	$this->output .= $this->view->fetch('/html/header.tpl.php');
        $this->output .= $this->view->fetch('/html/body.tpl.php');
        $this->output .= $this->view->fetch('/html/footer.tpl.php');
       
        return $this->output;
    }
}