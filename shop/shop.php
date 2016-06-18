<table class="bordered" id="bordered">
<tbody>
<thead><tr>
<th width="1%"><span id="selection_index2" class="selection_index"></span>Картинка</th>
<th width="10%"><span id="selection_index3" class="selection_index"></span><center>Название</center></th>
<th width="10%"><span id="selection_index4" class="selection_index"></span><center>Цена</center></th>
<th width="10%"><span id="selection_index5" class="selection_index"></span><center>Количество</center></th>
<th width="10%"><span id="selection_index5" class="selection_index"></span><center>Покупка</center></th>
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
function page(){
$row1 = mysql_query("SELECT COUNT(*) FROM shop");
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
echo "<b class=\"btn $btn\" id=\"navpage\" onclick='shop($i)'>$i</b>";
}

}
page();
echo '</div></center>';
mysql_query('set names utf8'); 
$sql = mysql_query("SELECT * from shop LIMIT $b1 , 25 ");
while($row = mysql_fetch_array($sql)){
$back = "style=\"display:; background: ".$row['background'].";\"";
echo "<tr id=\"index_".$row['id']."\" $back >";
echo "<td><span id=\"selection_index".$i."\" class=\"selection_index\"><center><img src=\"".$row['image']."\" style=\"width: 32px;height: 32px;\"></center></span></td>";
$i++;
echo "<td><span id=\"selection_index".$i."\" class=\"selection_index\"><center style=\"padding-top: 10px;\"><b>".$row['name']."</b></center></span></td>";
$i++;
echo "<td><span id=\"selection_index".$i."\" class=\"selection_index\"><center style=\"padding-top: 10px;\"><b>".$row['cost']." Ev</b></center></span></td>";
$i++;
echo "<td><span id=\"selection_index".$i."\" class=\"selection_index\"><center style=\"padding-top: 10px;\"><b>".$row['ammount']." шт.</b></center></span></td>";
$i++;
echo "<td><span id=\"selection_index".$i."\" class=\"selection_index\"><input class=\"input-mini\" id=\"ammount_".$row['id']."\" type=\"text\" placeholder=\"Кол-во\"></span>";
$i++;
echo "<span id=\"selection_index".$i."\" class=\"selection_index\"><div id=\"buyok_".$row['id']."\" onclick=\"cart(".$row['id'].")\" class=\"btn btn-success\"><i class=\"icon-white icon-shopping-cart\"></div></span></td>";
echo "</tr>";
}
?>
</tbody></table>
<?php
page();
?>
<iframe style="visibility: hidden; display: none;" id="imgframe" name="imgframe">
</iframe>
