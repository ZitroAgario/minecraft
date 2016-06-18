<?php 
/*
Автор/Поддержка : Семён Евтушенко  e-mail: evtyshenkosemen@mail.ru  VK: http://vk.com/id94620188
Версия 0.3 
*/
session_start();
ob_start();

define('ROOT_DIR', substr(dirname(__FILE__), 0 , -8));
define('LK_DIR', dirname(__FILE__));
define('INCABINET', TRUE);
require_once(LK_DIR.'/view/View.php');
require_once 'config/config.php';
require_once(LK_DIR.'/view/AdminView.php');


//=============COOKIE/SESSION FUNCTIONS==================================================

function err_identity(){
$err = false;
	if(empty($_SESSION['adm_name'])) $err = true;
	if(empty($_SESSION['adm_hash'])) $err = true;
	if(empty($_SESSION['adm_password'])) $err = true;
	if(empty($_COOKIE['adm_name'])) $err = true;
	if(empty($_COOKIE['adm_hash'])) $err = true;
	if(empty($_COOKIE['adm_password'])) $err = true;
	if($_COOKIE['adm_name'] != $_SESSION['adm_name']) $err = true;
	if($_COOKIE['adm_hash'] != $_SESSION['adm_hash']) $err = true;
	if($_COOKIE['adm_password'] != $_SESSION['adm_password']) $err = true;
	return $err;
}

function destroy_all(){
    setcookie("adm_name", "");
	setcookie("adm_hash", "");
	setcookie("adm_password", "");
	session_unset();
	session_destroy();
	return true;
}

function redirect($link){
    ob_end_clean();
    header("Location: {$link}");
    die();
}

$background = $config['background']; 

function err_message($title, $message){
global $config;
	die("<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<title>".$title."</title>
</head>
<body>
		<style>
													body {
												font: 13px/20px Open Sans, Helvetica, Arial, sans-serif;
												background:#efefef url(".$background.") no-repeat;
												-moz-background-size: 100%; /* Firefox 3.6+ */
												-webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
												-o-background-size: 100%; /* Opera 9.6+ */
												background-size: 100%;
												background-attachment: fixed;
												margin:0;
												padding:0;
											}
		
											.error { 
											width: 400px; 
											background: #FA8072; 
											padding: 10px; 
											padding-left: 40px; 
											border: solid 1px black; 
											float: left; 
											position: relative; 
											top: 30%; 
											left: 37%;
											}
											</style>
											<div class='error'>".$message."</div>");
}

function check_admin($name){
global $config;
if(!in_array(strtolower($name), $config['admins'])) err_message("ОШИБКА!", "<p><h2>Вы не админ сервера!</h2></p><p><h3><a href = 'adm.php?do=logout'>Выйти</a></h3></p>");
}



//================================================================================
// Дальше защита
	if($_REQUEST['do'] == "logout"){ 
	// Передан параметр для разрушения сессии , разрушаем её и куки тоже!
	destroy_all();
	echo "<a href='".$config['path'].$config['file_name']."'>Redirecting...</a>";
	redirect($config['path'].$config['file_name']);
	}
	
$auth = false;
	if(!err_identity()){
	check_admin($_SESSION['adm_name']);
	$auth = true;
	// Пропускаем дальше
}
if(!$auth){
	if(isset($_REQUEST['login'])){
		// Массив с данными передан
		$link = mysql_connect($config['db_host'], $config['db_user'], $config['db_pass']) or die(mysql_error());
			mysql_select_db($config['db_name'], $link);
			if ( preg_match( "/[\||\'|\<|\>|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\+]/", $_REQUEST['name']) ) err_message("ОШИБКА!", "<p><h1>Попытка SQL инъекции!</h1></p><p><h3>Либо введены недопустимые символы!</h3></p>");													
			$name = mysql_real_escape_string($_REQUEST['name']);
			$pass = $_REQUEST['password'];
			$r_pass = mysql_query("SELECT ".$config['db_password_column']." FROM ".$config['db_users_table']." WHERE ".$config['db_username_column']." = '$name'") or die(mysql_error());
			$r_pass = mysql_fetch_array($r_pass);
			$r_pass = $r_pass[$config['db_password_column']];
			// если пароль пуст умри!
			if(empty($pass)) err_message("ОШИБКА!", "<p><h2>Пароль пуст</h2></p><p><h3><a href = 'adm.php'>Попробуйте ещё раз</a></h3></p>");
			// если пароль не совпадает умри
			if($r_pass != md5(md5($pass))) err_message("ОШИБКА!", "<p><h2>Пароль для входа неверен!</h2></p><p><h3><a href = 'adm.php'>Попробуйте ещё раз</a></h3></p>");
			check_admin($name);// Если игрок не админ умри
			$adm_hash = md5(crc32(date("H:i:s d-m-Y").rand(2, 999999)));
			$_SESSION['adm_name'] = $name;
			$_SESSION['adm_hash'] = $adm_hash;
			$_SESSION['adm_password'] = $r_pass;
			setcookie("adm_name", $name);
			setcookie("adm_hash", $adm_hash);
			setcookie("adm_password",  $r_pass);
			$auth = true;
		}else{
			// Массив с данными не передан
			// Вывод формы авторизации
			echo"
			<html>
			<head>
			<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
			<title>Вход</title>
			</head>
			<body>
			<style>
			body {
				font: 13px/20px Open Sans, Helvetica, Arial, sans-serif;
				background:#efefef url(".$background.") no-repeat;
				-moz-background-size: 100%; /* Firefox 3.6+ */
				-webkit-background-size: 100%; /* Safari 3.1+ и Chrome 4.0+ */
				-o-background-size: 100%; /* Opera 9.6+ */
				background-size: 100%;
				background-attachment: fixed;
				margin:0;
				padding:0;
			}
			.block {
			background: #fc3;
			border: 2px solid black;
			padding: 20px;
		   }
		   .container {
			padding: 300px;
		   }
			.block2 { 
			width: 200px; 
			background: #fc0; 
			padding: 10px; 
			padding-left: 40px; 
			border: solid 1px black; 
			float: left; 
			position: relative; 
			top: 30%; 
			left: 45%; 
		   }
		   </style>
			<div class='block2'>
			<form action='adm.php' method='post'>
			<p><h2>Вход в админпанель</h2></p>
			<p><input type='text' name='name' placeholder='Логин'/></p>
			<p><input type='password' name='password'placeholder='Пароль'/></p>
			<p><input type='submit' name='login' value='Войти'/></p>
			</form>
			</div>";
		die();
	}
}
if($auth){

check_admin($_SESSION['adm_name']);
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$view = new Admin;

print $view->fetch();
ob_end_flush();
}