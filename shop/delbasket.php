
<?php 
include('config.php');
$id = mysql_real_escape_string($_POST[id]);
$pass = mysql_real_escape_string($_POST[pass]);
$del = mysql_real_escape_string($_POST[del]);
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
mysql_connect($db_host1, $db_user1, $db_pass1);
mysql_select_db($db_base1);
$sql = mysql_query("SELECT * from basket WHERE id ='$del' AND name='$name'");
$row = mysql_fetch_array($sql);
$cost = $row['cost'];
mysql_query("DELETE FROM basket WHERE id ='$del' AND name='$name'");
mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_base);
mysql_query("UPDATE golostop set bonus=bonus+'$cost' where name='$name'");
?>
