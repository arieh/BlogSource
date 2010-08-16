<?php
/**
 * A simple View mechansm, providing scope separation for templates
 * Inpired By Savant3, this is a very striped down version, containing only necessary methods
 *  - template fetching
 *  - argument assignment
 *  - string escaping
 * @author Arieh
 */
class TFView implements TFViewI{
	private $tpl_folder = '';
	
	private $params = array();
	
	static private $escape = array('htmlspecialchars');
	
	/**
	 * @param string $tpl_folder path to template folder
	 * @access public
	 * @return $this
	 */
	public function __construct($tpl_folder){
	   $this->tpl_folder = $tpl_folder;
	   return $this;
	}
	
	/**
	 * assigns a parameter to be accessible inside the templates
	 * @param string $name parameter name
	 * @param mixed $value parameter value
	 * @access public
	 * @return $this 
	 */
	public function assign($name,$value){
		$this->params[$name] = $value;
		return $this;
	}
	
	/**
	 * accessor to the parameter stack
	 * @param string $name parameter name
	 * @access public
	 * @return mixed|null parameter value or null if unset 
	 */
	public function __get($name){
		if (array_key_exists($name,$this->params)) return $this->params[$name];
		return null;
	}
	
	/**
	 * checks if a parameter is set
	 * @param string $name parameter name
	 * @access public
	 * @return bool
	 */
	public function isKeySet($name){
		return array_key_exists($name,$this->params);
	}
	
	/**
	 * isset magic method
	 * @see isKeySet
	 */
	public function __isset($name){
		return $this->isKeySet($name);
	}
	
	/**
	 * renders a template and returns the result as a string
	 * @param string $tpl path to the template file, relative to the templates folder
	 * @access public
	 * @return string
	 */
	public function fetch($tpl){
		if (!file_exists($this->tpl_folder.$tpl)) throw new InvalidArgumentException("File: $tpl Not found in {$this->tpl_folder}$tpl}");
		
		extract(get_object_vars($this), EXTR_REFS);
		ob_start();
		require($this->tpl_folder.$tpl);
		
		return ob_get_clean();
	}
	
	/**
	 * escapes a string using the Class's escaping stack
	 * @param string $value a string to escape
	 * @access public
	 * @return string
	 */
	public function escape($value){
		foreach (self::$escape as $func){
		  if (is_string($func)) {
              $value = $func($value);
          } else {
              $value = call_user_func($func, $value);
          }
		}
		return $value;
	}
	
	/**
	 * adds a function to the escape stack
	 * @param string|array $func same valid parameters as the ones passes to call_user_func
	 * @access public
	 * @return $this
	 */
	public function addEscape($func){
		self::$escape[]=$func;
		return $this;
	}
}