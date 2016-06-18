<?php

class Api
{

	private $classes = array(
		'config'     => 'Config',
		'design' 	 => 'Design',
		'db'         => 'Database',
	);
	

	private static $objects = array();
	

	public function __construct()
	{
		
	}


	public function __get($name)
	{
		
		if(isset(self::$objects[$name]))
		{
			return(self::$objects[$name]);
		}
		

		if(!array_key_exists($name, $this->classes))
		{
			return null;
		}
		

		$class = $this->classes[$name];
		
		include_once(LK_DIR.'/api/'.$class.'.php');
		
		self::$objects[$name] = new $class();
		
		return self::$objects[$name];
	}
}