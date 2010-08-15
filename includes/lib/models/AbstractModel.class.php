<?php
class AbstractModelException extends Exception{}

abstract class AbstractModel{
	/**
	 * @var array holder of paramater options
	 * @access protected
	 */
	protected $options = array();
	
	/**
	 * @var array holder of the model's inner errors
	 * @access protected
	 */
	protected $_errors = array();
	
	/**
	 * @param array holds all legal actions for the model and their paired methods
	 * @access protected
	 */
	protected $_actions = array();
	
	/**
	 * @param string holds default action for the model
	 * @access protected
	 */
	protected $_default_action = '';
	
	/**
	 * @param string current model action
	 * @access protected
	 */
	protected $_action = false;
	
	/**
	 * @param bool debug mode
	 * @access protected
	 */
	protected $_debug = false;
	
	protected $dao_name = '';
	
	protected $dao = false;
	
	/**
	 * a constructor method for the object. sets the databse holder and the model options
	 * 	@param array $options paramaters for the model
	 *
	 * @access public
	 * @return void
	 */
	public function __construct($options = array(), PancakeTF_DBAccessI $db=null){
		$this->db = $db ? $db : new PancakeTF_PDOAccess;
	    if ($this->dao_name) $this->dao = new $dao_name($this->db);
	    
	    foreach ($options as $name => $value){
			if (is_string($name)) $this->setOption($name,$value);
		}
		
		if ($this->isOptionSet('debug')){
			$this->_debug = (bool)($this->getOption('debug'));
			$this->setOption('debug',null);
		}
		$this->setAction();
	}
	
    /**
     * sets default getters and setters (getParamname(), setParamname())
     *
     * properties access by this method must be protected or public. if a property
     * is an array, and it's name is plural, accessing it with singular form will pop its first
     * variable. also, if that variable is an array, it will be passed as a model result.
     *
     * for get<ParamName>:
     *  @param bool whether to return singular with pop or with shift (default to shift)
     *
     * for set<ParamName>:
     *  if paramater is an array, all passed paramaters will be pushed
     */
    public function __call($name,$args){
        $action = substr($name,0,3);
        $pVar=array();
        if ($action === 'get'){
            $pVar = $this->explodeCase(substr($name,3));
        
        }elseif (substr($name,0,2)=='is'){
            $pVar = $this->explodeCase(substr($name,2));
            $action='is';
        
        }
        
        $pVar = "_".implode("_",$pVar);
        $sVar = $pVar.'s';
    
        $pVarExists = (isset($this->$pVar) || property_exists($this,$pVar));
        $sVarExists = (
            (isset($this->$sVar) || property_exists($this,$sVar))
            && is_array($this->$sVar)
        );
        
        if (!$pVarExists && !$sVarExists) throw new LogicException("Call to undefiend Method: $name");
    
        switch ($action){
            case 'get':
                if ($sVarExists){
                    $var = (isset($args[0]) && $args[0]) ? array_pop($this->$sVar) : array_shift($this->$sVar);
                    if ($var){
                         return $var;
                    }
                    return false;
                }
                return $this->$pVar;
            break;
            case 'is':
                return (bool)($this->$pVar);
            break;
            default:
             throw new LogicException("Call to undefiend Method: $name");
            break;
        }
    }
	
	public function __destruct(){
  		if (isset($this->_db) && method_exists($this->_db,'__destruct')) $this->_db->__destruct();
  	}
    
    /**
     * executes the model's logic
     * @access public
     */
    public function execute(){
        if ($this->checkPermission()==false){
            $this->setError('noPermission');
            return false;
        }
        
        $action = $this->getAction();
        
        if (!$action || !array_key_exists($action,$this->_actions))
          throw new AbstractModelException('No Valid Action Was Set');
        
        $action_method = $this->_actions[$this->getAction()];
        $this->$action_method();
    }
    
	/**
	 * returns a model paramater
	 * 	@param string $name a parameter name
	 * @access protected
	 * @return mixed|bool if paramter is set returns it, otherwise return false
	 */
	public function getOption($name){
	    if (array_key_exists($name,$this->options)) return $this->options[$name];
		return false;
	}
	
	/**
	 * sets a model paramater
	 * 	@param string $name paramater name
	 * 	@param mixed $value a paramter value
	 * @access public
	 */
	public function setOption($name,$value){
		$this->options[$name] = $value;
	}
	
	/**
	 * sets the action for the model
	 * 	@param string $action action name
	 * @access protected;
	 */
	public function setAction($action=false){
		$actions = array_keys($this->_actions);
		if ($action && in_array($action,$actions)){
			$this->_action = $action;
			return;
		}elseif (in_array($action,$this->_actions)){
			$this->_action = $action;
		}
		
		if ($action = $this->getOption('action')){
		    if (in_array($action,$actions)) $this->_action = $action;
			return;
		}
		$this->_action = $this->_default_action;
	}
	
	/**
	 * returns current action
	 * @access protected
	 * @return string
	 */
	 public function getAction(){
	    if (!$this->_action) return $this->_default_action;
	 	return $this->_action;
	 }
	
	/**
	 * returns a JSON representation of the object
	 * @access public
	 * @return string
	 */
	public function toJSON(){return '{}';}

	
    
    /**
     * checks if an error exists for the model.
     * if no name is specified, checks if any errors were set
     *  @param string $name error name to check
     * @access public
     * @return bool
     */
    public function isError($name=false){
        if ($name) return (bool) (isset($this->_errors[$name]) && $this->_errors[$name]);
        return (bool)($this->_errors);
    }
    
    /**
     * returns an array of errors
     * @access public
     * @return array
     */
    public function getErrors(){
        return array_keys($this->_errors);
    }
     
     /**
      * get a permission from the permission list
      * @return int permission id
      * @access protected
      */
     protected function getPermission(){
        if ($this->_permission_counter === count($this->_permissions)) return false;
        return $this->_permissions[$this->_permission_counter++];
     }
     
     /**
      * gets permission list
      * @access protected
      * @return array
      */
     protected function getPermissions(){
        return $this->_permissions;
     }
     
    /**
     * checks if a specific user has permission to access the page
     * @return bool
     */
    protected function checkPermission(){return true;}
    
    /**
     * sets an internal error
     *  @param string $name error name
     * @access protected
     */
    protected function setError($name,$value=true){
        $this->_errors[$name] = $value;
    }
    
    /**
     * unsets an internal error
     *  @param string $name erro name to unset
     * @access protected
     */
    protected function unsetError($name){
        if (isset($this->_errors[$name])) unset($this->_errors[$name]);
    }
    
    /**
     * checks if a specific option is set (good when an option is expected to be boolean)
     *  @param string $name option name
     * @return bool
     */
    protected function isOptionSet($name){
        return (array_key_exists($name,$this->options));
    }
    
    /**
     * check if in debug mode
     * @access protected
     * @return bool
     */
     protected function isDebug(){
        return ($this->_debug==true);
     }
    
    protected function explodeCase($string, $lower = true){
      // Split up the string into an array according to the uppercase characters
      $array = preg_split('/([A-Z][^A-Z]*)/', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
      
      // Convert all the array elements to lowercase if desired
      if ($lower) {
        $array = array_map('strtolower', $array);
      }
      
      // Return the resulting array
      return $array;
    }
}