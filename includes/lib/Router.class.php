<?php
class Router {
	static private $stack = array();
	
	static private $input_corrected = false;
	
	static private $constructed = false;
	
	static private $_params = array();
	
	static private $base_path = '';
	
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
	}
	
	public function removeFolder($int){
	    array_splice(self::$stack,$int,1);
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
	
	public function __get($name){
		if (array_key_exists($name,self::$_params)) return self::$_params[$name];
		
		$inputs = array($_POST,$_GET);
		
		foreach ($inputs as $input) {
			if (array_key_exists($name,$input)) return $input[$name];
		}
		return false;
	}
	
	public function __set($name,$value=''){
		if (is_string($name)) self::$_params[$name] = $value;
		else throw new TFRouteException('Bad Paramater Name');
	}
	
	public function isParamSet($name){
	    return (array_key_exists($name,self::$_params) || array_key_exists($name,$_POST) || array_key_exists($name,$_GET));
	}
	
	public function getFolder($num=false){
		if ($num === false) return array_pop(self::$stack);
	    return (isset(self::$stack[$num])) ? self::$stack[$num] : false;
	}
	
	public function getBasePath(){return self::$base_path;}
}

class TFRouterException extends Exception {}
