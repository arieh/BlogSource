<?php
/**
 * This class has a few functions, all related to managing the global state of the application. 
 *  - Holding the basic routing data for the current page, spit into a folder stack
 *  - Handling the variables created by the reuqest - POST, GET etc.
 *  - A crud global registry.  
 * @author Arieh
 */
class Router {
	static private $stack = array();
	
	static private $input_corrected = false;
	
	static private $constructed = false;
	
	static private $_params = array();
	
	static private $base_path = '';
	
	/**
	 * @param string $base_path A base path to substract from the request uri 
	 *                          before parsing url. Used when domain root isn't it's request host
	 * @access public
	 * @return $this                         
	 */
	public function __construct($base_path=''){
		self::$base_path = $base_path;
	    
	    if (!self::$input_corrected){
			$this->correctInput($_GET);
			$this->correctInput($_POST);
			$this->correctInput($_COOKIE);
			self::$input_corrected = true;
		}
		
		if (!self::$constructed){
			$route = $_SERVER['REQUEST_URI'];
			$route = explode('?',$route);
			$route = $route[0];
			if ($base_path){
				$route = explode ($base_path,$route);
				$route = $route[1];
				if (strpos($route,'?') !== false){
					$route = explode('?',$route);
					$route = $route[0];
				}
			}
			if ($route && $route{0}=='/') $route = ($route=='/') ? '' : substr($route,1);
			self::$stack = explode('/',urldecode($route));
			self::$constructed = true;
		}
		
		return $this;
	}
	
	/**
	 * used to remove a folder from the folder stack
	 * @param int $int folder location
	 * @access public
	 * @return $this
	 */
	public function removeFolder($int){
	    array_splice(self::$stack,$int,1);
	    return $this;
	}
	
	/**
	 * Accessor to the Router paramaters. If no paramater was set, will attempt to search the global request stack
	 * @param string $name name of variable to look for
	 * @access public
	 * @return mixed|bool paramater value if exists, false if not
	 */
	public function __get($name){
		if (array_key_exists($name,self::$_params)) return self::$_params[$name];
		
		$inputs = array($_POST,$_GET);
		
		foreach ($inputs as $input) {
			if (array_key_exists($name,$input)) return $input[$name];
		}
		return false;
	}
	
	/**
	 * will set the Router's paramaters
	 * @param string $name paramater name to set
	 * @param mixed $value the new value for the paramater
	 * @access public
	 * @return $this
	 */
	public function __set($name,$value=''){
		if (is_string($name)) self::$_params[$name] = $value;
		else throw new TFRouteException('Bad Paramater Name');
		return $this;
	}
	
	/**
	 * checks whether a paramater was set, either by the Router's paramaters, or through the request stack
	 * @param string $name paramater name
	 * @access public
	 * @return bool
	 */
	public function isParamSet($name){
	    return (array_key_exists($name,self::$_params) || array_key_exists($name,$_POST) || array_key_exists($name,$_GET));
	}
	
	/**
	 * return the folder name of a specific location of the folder stack or pops the next name from it
	 * @param int $num if set, will return the folder in location $num
	 * @access public
	 * @return string|bool folder name if exists, false if not
	 */
	public function getFolder($num=false){
		if ($num === false) return array_pop(self::$stack);
	    return (isset(self::$stack[$num])) ? self::$stack[$num] : false;
	}
	
	/**
     * returns the base path of the site, relative to it's root address
     * @access public
     * @return string
	 */
	public function getBasePath(){return self::$base_path;}
	
	/**
	 * returns the base url for the site
	 * @access public
	 * @return string
	 */
	public function getBaseUrl(){
		return $_SERVER['HTTP_HOST'].'/'.$this->getBasePath();
	}
    
    private function correctInput(&$arr){
        $gpc = get_magic_quotes_gpc();
        foreach ($arr as $key=>&$var){
            if (is_array($var)) $this->correctInput($var);
            elseif (is_string($var)){
                if ($gpc) $var = stripslashes($var);
                $var = urldecode($var);
            }
        }
    }
}

class TFRouterException extends Exception {}
