<?php
session_start();
define('ROOT_DIR', substr(dirname(__FILE__), 0 , -8));
define('LK_DIR', dirname(__FILE__));

// Если у вас не Dle то меняем 
$username = $member_id['name']; 
if(empty($username)) die("Username is not transferred!");

require_once(LK_DIR.'/view/IndexView.php');
$view = new IndexView($username);

header("Content-type: text/html; charset=".$view->config->charset."");
print $view->fetch();	