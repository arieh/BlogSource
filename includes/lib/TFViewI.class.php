<?php
interface TFViewI{    
    /**
     * adds a function to the escape stack
     * @param string|array $func same valid parameters as the ones passes to call_user_func
     * @access public
     * @return $this
     */
    public function addEscape($func);
    
	/**
     * assigns a parameter to be accessible inside the templates
     * @param string $name parameter name
     * @param mixed $value parameter value
     * @access public
     * @return $this 
     */
    public function assign($name,$value);
    
    /**
     * escapes a string using the Class's escaping stack
     * @param string $value a string to escape
     * @access public
     * @return string
     */
    public function escape($value);
    
    
    /**
     * renders a template and returns the result as a string
     * @param string $tpl path to the template file, relative to the templates folder
     * @access public
     * @return string
     */
    public function fetch($tpl);
    
    /**
     * checks if a parameter is set
     * @param string $name parameter name
     * @access public
     * @return bool
     */
    public function isKeySet($name);
    
    /**
     * accessor to the parameter stack
     * @param string $name parameter name
     * @access public
     * @return mixed|null parameter value or null if unset 
     */
    public function __get($name);
    
    /**
     * isset magic method
     * @see isKeySet
     */
    public function __isset($name);
}