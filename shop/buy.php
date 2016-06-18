
<?php 
include('config.php');
$id = mysql_real_escape_string($_POST[id]);
$pass = mysql_real_escape_string($_POST[pass]);
$connect;
mysql_select_db($db_base);
$sql = mysql_query("SELECT * from dle_users where user_id='$id'");
$row = mysql_fetch_array($sql);
$name = $row['name'];
if($name == ''){
echo "Перезагрузите страницу!";
exit;
}
mysql_connect($db_host1, $db_user1, $db_pass1);

mysql_select_db($db_base1);

$sql = mysql_query("SELECT * from basket where name = '$name'");
$row = mysql_fetch_array($sql);
if($row['itemname'] != ''){
$sql = mysql_query("SELECT * from basket where name = '$name'");
while($row = mysql_fetch_array($sql)){
$sq = mysql_query("SELECT * from shopcart where item='".$row['item']."' AND player='$name'");
$ro = mysql_fetch_array($sq);
if($ro['item'] == $row['item']){
 mysql_query("DELETE FROM basket WHERE id='".$row['id']."'");
 mysql_query("UPDATE  shopcart SET amount =amount+'".$row['ammount']."' WHERE  id = '".$ro['id']."'");
 mysql_query("INSERT INTO history (id, name, itemname, cost, ammount, background) VALUES (NULL, '$name', '".$row['itemname']."', '".$row['cost']."', '".$row['ammount']."', '".$row['background']."')");
 }
else{
 mysql_query("DELETE FROM basket WHERE id='".$row['id']."'");
mysql_query("INSERT INTO shopcart (id, type, player, item, extra, amount) VALUES (NULL, 'item', '$name', '".$row['item']."', NULL, '".$row['ammount']."')");
  mysql_query("INSERT INTO history (id, name, itemname, cost, ammount, background) VALUES (NULL, '$name', '".$row['itemname']."', '".$row['cost']."', '".$row['ammount']."', '".$row['background']."')");
}
}
echo "Спасибо за покупку!";
}
else
{
echo "Ваша корзина пуста!";
}
