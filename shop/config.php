
<?php

	//+++++++++++++++++++++++��������� �����������+++++++++++++++++++++++++
	
	$db_host = ''; //���� ���� ������ � ���
	
	$db_user = ''; // ���� ���� ������ � ���
	
	$db_pass = ''; // ������ ���� ������ � ���
	
	$db_base = ''; // ���� ������ ���� � ���
	
	$domen = ""; // �����
	
    $folder = 'shop'; //����� �� ������
	
	$name = $member_id['name'];
	
	
	$db_host1 = ''; //���� ���� ������ �������
	
	$db_user1 = ''; // ���� ���� ������ �������
	
	$db_pass1 = ''; // ������ ���� ������ �������
	
	$db_base1 = ''; // ���� ������ ���� �������
	
	
	
	
	
	$connect1 = mysql_connect($db_host1, $db_user1, $db_pass1);
	$connect = mysql_connect($db_host, $db_user, $db_pass);
	mysql_select_db($db_base);
	$sq = mysql_query("SELECT * from golostop where name='$name'");
	$sq1 = mysql_query("SELECT * from dle_users where name='$name'");
	$ro1 = mysql_fetch_array($sq1);
	$group = $ro1['user_group'];
	$ro = mysql_fetch_array($sq);
	if($ro['bonus'] != ''){
	$balance = '������ - '.round($ro['bonus']).' Ev';
	}
	else{
	$balance = '������ - 0 Ev';
	}
	
	?>