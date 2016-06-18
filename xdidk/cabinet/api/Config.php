<?php

class Config {
	
	public function __construct(){
		
		include(LK_DIR.'/config/config.php');
		$this->vars = $config;
		
	}
		
	public function __get($name){
		return $this->vars[$name];
	}	
}