
<?php 
include('config.php');
$id = mysql_real_escape_string($_POST[id]);
$pass = mysql_real_escape_string($_POST[pass]);
$connect;
mysql_select_db($db_base);
$sql = mysql_query("SELECT * from dle_users where user_id='$id'");
$row = mysql_fetch_array($sql);
$name = $row['name'];
$group = $row['user_group'];
if($name == ''){
echo "Перезагрузите страницу!";
exit;
}
$sql = mysql_query("SELECT * from golostop where name='$name'");
$row = mysql_fetch_array($sql);
if($row['bonus'] != ''){
	$balance = 'Баланс - '.round($row['bonus']).' Ev';
	}
	else{
	$balance = 'Баланс - 0 Ev';
	}
	echo $balance;
mysql_close();
?>
