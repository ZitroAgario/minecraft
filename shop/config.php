
<?php

	//+++++++++++++++++++++++НАСТРОЙКА ПОДКЛЮЧЕНИЯ+++++++++++++++++++++++++
	
	$db_host = ''; //хост базы данных с ДЛЕ
	
	$db_user = ''; // юзер базы данных с ДЛЕ
	
	$db_pass = ''; // пароль базы данных с ДЛЕ
	
	$db_base = ''; // база данных игры с ДЛЕ
	
	$domen = ""; // Домен
	
    $folder = 'shop'; //папка не менять
	
	$name = $member_id['name'];
	
	
	$db_host1 = ''; //хост базы данных сервера
	
	$db_user1 = ''; // юзер базы данных сервера
	
	$db_pass1 = ''; // пароль базы данных сервера
	
	$db_base1 = ''; // база данных игры сервера
	
	
	
	
	
	$connect1 = mysql_connect($db_host1, $db_user1, $db_pass1);
	$connect = mysql_connect($db_host, $db_user, $db_pass);
	mysql_select_db($db_base);
	$sq = mysql_query("SELECT * from golostop where name='$name'");
	$sq1 = mysql_query("SELECT * from dle_users where name='$name'");
	$ro1 = mysql_fetch_array($sq1);
	$group = $ro1['user_group'];
	$ro = mysql_fetch_array($sq);
	if($ro['bonus'] != ''){
	$balance = 'Баланс - '.round($ro['bonus']).' Ev';
	}
	else{
	$balance = 'Баланс - 0 Ev';
	}
	
	?>