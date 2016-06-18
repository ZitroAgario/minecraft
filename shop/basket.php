<table class="bordered" id="borderedbask">
<tbody>
<thead id="bodystyle"><tr>
<th width="1%"><span id="selection_index2" class="selection_index"></span><div onclick="buy()" class="btn btn-success1">Приобрести</div></th>
<th width="10%"><span id="selection_index3" class="selection_index"></span><center>Название</center></th>
<th width="10%"><span id="selection_index4" class="selection_index"></span><center>Цена</center></th>
<th width="10%"><span id="selection_index5" class="selection_index"></span><center>Количество</center></th>
<th width="10%"><span id="selection_index5" class="selection_index"></span><center><div onclick="deletbask('all')" class="btn btn-danger">Удалить все</div></center></th>
<?php 
include('config.php');
$id = mysql_real_escape_string($_POST[id]);
$pass = mysql_real_escape_string($_POST[pass]);
$page = mysql_real_escape_string($_POST[page]);
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
$are = $page-1;
$b1 = 25*$are;
mysql_connect($db_host1, $db_user1, $db_pass1);
mysql_select_db($db_base1);
function page(){
global $name;
$row1 = mysql_query("SELECT COUNT(*) FROM basket where name='$name'");
$sql = mysql_result($row1, 0);
$str = $sql/25;
$i='0';
echo '<center><div class="navmenuEP">';
while($i < $str){
$btn = 'btn-success';
global $page;
if($i == $page-1){
$btn = 'btn-warning';
}
$i++;
echo "<b class=\"btn $btn\" id=\"navpage\" onclick='basket($i)'>$i</b>";
}

}
page();
echo '</div></center>';
mysql_query('set names utf8'); 
$sql = mysql_query("SELECT * from basket where name='$name' LIMIT $b1 , 25 ");
$row = mysql_fetch_array($sql);
if($row['name'] == ''){
echo "<tr><td colspan=\"5\"><center> <b><h2>Ваша корзина пуста!</h2></b></center></td></tr>";
}
$sql = mysql_query("SELECT * from basket where name='$name' LIMIT $b1 , 25 ");
while($row = mysql_fetch_array($sql)){
echo "<tr id=\"indexbask_".$row['id']."\">";
echo "<td><span id=\"selection_index".$i."\" class=\"selection_index\"><center><img src=\"".$row['background']."\" style=\"width: 32px;height: 32px;\"></center></span></td>";
$i++;
echo "<td><span id=\"selection_index".$i."\" class=\"selection_index\"><center style=\"padding-top: 10px;\"><b>".$row['itemname']."</b></center></span></td>";
$i++;
echo "<td><span id=\"selection_index".$i."\" class=\"selection_index\"><center style=\"padding-top: 10px;\"><b>".round($row['cost'], 2) ." Ev</b></center></span></td>";
$i++;
echo "<td><span id=\"selection_index".$i."\" class=\"selection_index\"><center style=\"padding-top: 10px;\"><b>".$row['ammount']." шт.</b></center></span></td>";
$i++;
echo "<td><center><span id=\"selection_index".$i."\" class=\"selection_index\"><div onclick=\"deletbask('".$row['id']."')\" class=\"btn btn btn-danger deletebasket\"><i class=\"icon-white icon-remove\"></div></span></td></td></center>";

echo "</tr>";
}
?>
</tbody></table>
<?php
page();
?>