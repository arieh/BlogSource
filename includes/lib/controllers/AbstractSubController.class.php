<?php
/**
 * This class is a base class to derive all sub-page controllers from. It supplied an interface with which to create an
 * inner routring system via a list of actions and their methods
 * @author Arieh
 */
abstract class AbstractSubController{
    /**
     * holds a list of css file names to be used by the page
     * @var array
     * @access protected
     */
    protected $css = array();
    
    /**
     * holds a list of javascript file names to by the page 
     * @var array
     * @access protected
     */
    protected $js = array();
    
    /**
     * An associative list of action names and their paired methods (array('action-name'=>'action-method'))
     * @var array
     * @access protected
     */
    protected $actions;
    
    /**
     * a default action to use when no other action was set. Has to be a valid action that exists in the actions list
     * @var string
     * @access protected
     */
    protected $default_action;
    
    /**
     * a referance to the User object
     * @var User
     * @access protected
     */    
    protected $user;
    
    /**
     * the current used enviorment
     * @var string
     */
    protected $env = 'xhtml';
    
    /**
     * current template folder
     * @var string
     */
    protected $folder = '';
    
    /**
     * current template file
     * @var string
     */
    protected $template;

    private $action = '';
    
    /**
     * @param Router $router
     * @param TFViewI $savant
     * @param string $env
     */
    public function __construct(Router $router, TFViewI $view, $env = 'xhtml'){
        $this->router = $router;
        $this->view   = $view;
        $this->user   = new User;
        $this->env    = $env;
        $this->chooseAction();
     }
     
     /**
      * executes the current action
      */
     public function execute(){
     	$action = $this->actions[$this->action];
        $this->{$action}();
     }
     

     /**
      * returns the page's title
      * @return string
      */
     public function getTitle(){return $this->title;}
     
     /**
      * returns the page's description
      * @return string
      */
     public function getDescription(){return $this->desc;}
     
     /**
      * return's the page's outpug
      * @return string
      */
     public function generate(){
         return $this->fetchTemplate($this->template);
     }
     
     /**
      * returns the page's css stack
      * @return array
      */
     public function getCSS(){return $this->css;}
     
     /**
      * returns the page's JS stack
      * @return string
      */
     public function getJS(){return $this->js;}
     
     
     /**
      * sets the current action
      * @param string $action action name
      * @return $this 
      */
     public function setAction($action){
     	if (array_key_exists($action,$this->actions)) $this->action = $action;
     	else throw new InvalidArgumentsException("Action $action is not valid for this Class");
     	return $this;
     }
     
     /**
      * returns the current set action
      * @return string
      */
     public function getAction(){return $this->action;}
     
     /**
      * redirects the page within the site
      * @param string $path a path within the site
      */
     protected function redirect($path=''){
     	header("Location:".$this->router->getBaseUrl().$path);
     	die();
     }
     
     /**
      * fetches the current template
      * @param string $name template name
      * @return string the current template's output
      */
     protected function fetchTemplate($name){
         $file = dirname(__FILE__) . '/../templates/' . $this->folder . '/' . $this->env . "/$name.tpl.php";
         if (file_exists($file)){
             return $this->view->fetch($this->folder .'/'. $this->env."/".$name.".tpl.php");
         }else return $this->view->fetch($this->folder .'/'. $this->env."/".$name.".tpl.php");
     }
     
     /**
      * redirects the page to the site's base page 
      */
     protected function goHome(){
        $this->redirect();
     } 
     
    /**
     * chooses the current action, using the second folder of the url.
     */
     protected function chooseAction(){
        $temp_action = $this->router->getFolder(1);
        
        if ($this->env !='xhtml'){
            $env_action_folder = $this->env."_actions";
            $env_def_action = $this->env."_default_action";
            if (isset($this->$env_action_folder) && in_array($temp_action,$this->$env_action_folder)){
                $this->action = $temp_action;
            }elseif (isset($this->$env_def_action)) $this->action = $this->$env_def_action;
        }
        
        if ($this->action) return;
        
        if (!$temp_action || !array_key_exists($temp_action,$this->actions)) $this->action = $this->default_action;
        else $this->action = $temp_action;
     }
}