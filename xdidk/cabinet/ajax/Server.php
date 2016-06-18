<?php

if($_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest")
    exit ("Error.");

session_start();
define('ROOT_DIR', substr(dirname(__FILE__), 0, -12));
define('LK_DIR', substr(dirname(__FILE__), 0, -4));
require_once(LK_DIR.'/view/View.php');

class Server extends View{
		
	public function fetch(){
		if(isset($_GET['id']) and isset($_GET['mod'])){
			
			$_SESSION['server'] = intval($_GET['id']); 
			
		}
		
		return header("Location: ". $_GET['mod']);
		//return $_GET['id'].$_GET['mod'];
	}	
		
}

$view = new Server;
print $view->fetch();