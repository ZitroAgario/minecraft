
<?php 
include('config.php');
$id = mysql_real_escape_string($_POST[id]);
$pass = mysql_real_escape_string($_POST[pass]);
$ammount = mysql_real_escape_string($_POST[ammount]);
$idp = mysql_real_escape_string($_POST[idp]);
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
if (preg_match("|^[\d]+$|", $ammount)){
$amm = str_split($ammount);
if($amm[0] != '0'){
mysql_query('set names utf8'); 
$sql = mysql_query("SELECT * from shop where id='$idp'");
$row = mysql_fetch_array($sql);
$cost = $row['cost'];
$realid = $row['realid'];
$item = $row['name'];
$amm1 = $row['ammount'];
$back = $row['image'];
$cost1 = $cost/$amm1;
$realcost = $cost1*$ammount;
$sql = mysql_query("SELECT * from golostop where name='$name'");
$row = mysql_fetch_array($sql);
$money = $row['bonus'];
if($money > $realcost){
mysql_query("UPDATE golostop SET bonus=bonus-'$realcost' where name='$name'");
mysql_close($connect);
mysql_connect($db_host1, $db_user1, $db_pass1);
mysql_select_db($db_base1);
mysql_query('set names utf8'); 
mysql_query("INSERT INTO basket ( id , name , itemname , item , cost, ammount , background ) VALUES ( NULL ,  '$name', '$item' , '$realid', '$realcost' , '$ammount', '$back' )");

echo 'Добавлено в корзину';
}
else
{
echo "У вас недостаточно средств. Требуется ".round($realcost, 2)." Ev";
}
}
else {
echo "Введите число";
}
}
else 
{
echo "Введите число";
}