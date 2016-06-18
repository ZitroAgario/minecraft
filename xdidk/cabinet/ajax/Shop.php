<?php

if($_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest")
    exit ("Error.");

session_start();
define('ROOT_DIR', substr(dirname(__FILE__), 0, -12));
define('LK_DIR', substr(dirname(__FILE__), 0, -4));
require_once(LK_DIR.'/view/View.php');	

class Shop extends View{
	
	public function fetch(){
		header("Content-type: text/html; charset=".$this->config->charset."");
		
		if($_GET['mode'] == 'product')
		{
			
			$ajax = '
			
			<script type="text/javascript">
			$(\'form.variants\').submit(function(){
				id = $(this).find(\'input[name=id]\').val();
				
				$.ajax({
					type: "POST",
					url: "'.$this->config->path.'ajax/Shop.php?mode=minicart",
					data: \'id=\'+id+\'\',
					success: function(html){
					$(\'#cart_informer\').html(html);
				   }

				});
								
				return false;
				
			});
			
			$(\'form.formsearch\').submit(function(){
				key = $(this).find(\'input[name=keyword]\').val();
				
				$(\'#content_lk\').text(\'Загрузка...\');
				
				$.ajax({
					type: "GET",
					url: "'.$this->config->path.'ajax/Shop.php?mode=search",
					data: \'keyword=\'+key+\'\',
					success: function(html){
					$(\'#content_lk\').html(html);
				   }

				});
								
				return false;
				
			});
			
			</script>
			';
			
			$cart = $this->mini_cart();
			
			$this->db->query("SELECT * FROM __shop_googs WHERE id='".intval($_GET['id'])."'");
			$row = $this->db->get_row();
			
			$server_i = $this->server_info($row['server']);
			$title = $this->title("Сервер &raquo; ".$server_i['name']." &raquo; Магазин &raquo; Товар &raquo; ".$row['name']."");
			
			$this->design->load('shop_goog.html');
			$this->design->set('ajax', $ajax);
			$this->design->set('cart', $cart);
			$this->design->set('name', $row['name']);
			$this->design->set('text', $row['text']);
			$this->design->set('img', $row['img']);
			$this->design->set('sum', $row['sum']);
			$this->design->set('sum2', $row['sum']*64);
			$this->design->set('id', $row['id']);
			$this->design->set('server', $row['server']);
			$this->design->set('title', $title);
			return $this->design->result();
		}
		elseif($_GET['mode'] == 'minicart'){
			
			if(isset($_POST['id']))
			{
				if($_SESSION['cart'] == '')
				{
					$_SESSION['cart'] = array($_POST['id'] => '1');
				} else {
					$NewCart = array();
					foreach($_SESSION['cart'] as $Id => $Value)
					{
						if($Id == (int)$_POST['id']) $Value = $Value+1;
						$NewCart[$Id] = $Value;
					}
					if(!$_SESSION['cart'][$_POST['id']]) $NewCart[$_POST['id']] = 1;
					$_SESSION['cart'] = $NewCart;
				}
			}
			
			return $this->mini_cart();
		
					
		}
		elseif($_GET['mode'] == 'cart'){
			
			if($_SESSION['cart'] != '')
			{
				foreach($_SESSION['cart'] as $Id => $Value)
				{
					$this->db->query("SELECT sum FROM __shop_googs WHERE id='".intval($Id)."'");
					$row99 = $this->db->get_row();	
					
					if(!$row99) unset($_SESSION['cart'][$Id]);
		
					$CountGoogs += $Value;
					$SumGoogs += $Value*$row99['sum'];
				}
	
			}
			
			if($_GET['op'] == 'amounts'){
				
				if(isset($_POST['amounts'])){	
					$Amounts = $_POST['amounts'];
					foreach($Amounts as $VId => $NewA) 
					{
						$_SESSION['cart'][intval($VId)] = intval($NewA);
					} 
				}	
				return header("Location: ".$this->config->path.'ajax/Shop.php?mode=cart');
					
			}
			elseif($_GET['op'] == 'delete')
			{	
				if($_GET['id'] != ''){
					unset($_SESSION['cart'][$_GET['id']]);
					if(count($_SESSION['cart']) == 0) unset($_SESSION['cart']);
				}
				return header("Location: ".$this->config->path.'ajax/Shop.php?mode=cart');
			}
			elseif($_GET['op'] == 'buy')
			{
				if($this->balance >= $SumGoogs)
				{
					
					
					foreach($_SESSION['cart'] as $id => $value){
						
						$this->db->query("SELECT * FROM __shop_googs WHERE id='".intval($id)."'");
						$row = $this->db->get_row();
						
						$this->db->query("INSERT INTO __history SET
						title='Покупка в магазине',
						text='Вы успешно приобрели товар ".$row['name']." за ".$row['sum']." рублей',
						sum='".$row['sum']."',
						date='".date('d.m.Y, H:i')."',
						type='shop',
						user='".$this->username."'
						");
						
						#Для серверов
			
						$this->db->query("SELECT table_shop FROM __servers WHERE id='{$row['server']}'");
						$row_s = $this->db->get_row();
			
						$this->db->query("INSERT INTO __{$row_s['table_shop']} SET
						sid='{$row['id']}',
						username='".$this->username."',
						iid='{$row['item_id']}',
						title='{$row['name']}',
						amount='".$value."'
						");
			
						#Для серверов END
						
					}
					
					$this->update_balance($this->username, '-', $SumGoogs);
					unset($_SESSION['cart']);
					
					return 'err2';
				} else return 'err1';
			}
			else{
			
			
			$ajax = '
			
			<script type="text/javascript">
			$(\'form.formcart\').submit(function(){
				
				$.ajax({
					type: "POST",
					url: "'.$this->config->path.'ajax/Shop.php?mode=cart&op=buy",
					data: \'buy=true\',
					success: function(html){
						if(html == \'err1\') {
							$(\'#error_lk\').html("Не хватает денег");
							$(\'#error_lk\').css({\'display\' : \'block\'});
						}	
						else if(html == \'err2\'){
							$(\'#error_lk_ok\').html("Покупка успешно совершена");
							$(\'#error_lk_ok\').css({\'display\' : \'block\'});
							$(\'#content_lk\').text(\'Корзина пуста\');
						}	
						else {
							alert(html);
						}
				   }

				});
								
				return false;
				
			});
	
	
			function amounts(id){
	
				amounts_new = $(\'#amount\'+id+\'\').val();

				$.ajax({
					type: "POST",
					url: "'.$this->config->path.'ajax/Shop.php?mode=cart&op=amounts",
					data: \'amounts[\'+id+\']=\'+amounts_new+\'\',

				});
				
				loadContent(\''.$this->config->path.'ajax/Shop.php?mode=cart\');
								
				return false;
		
			}
			</script>
			
			';
			
			
			$googs = $this->max_cart();
			
			
			
			$this->design->load('shop_cart.html');
			$this->design->set('googs_count', $CountGoogs);
			$this->design->set('sum', $SumGoogs);
			$this->design->set('ajax', $ajax);
			$this->design->set('googs', $googs);
			$this->design->set_block('cart_true', $_SESSION['cart'] != '');
			$this->design->set_block('cart_false', $_SESSION['cart'] == '');
			return $this->design->result();
			
		}
		}
		else {
		
			$googs = $this->googs();
			$category = $this->category();
			$cart = $this->mini_cart();
			
			$ajax = '
			<script type="text/javascript">
			$(\'form.variants\').submit(function(){
				id = $(this).find(\'input[name=id]\').val();
				
				$.ajax({
					type: "POST",
					url: "'.$this->config->path.'ajax/Shop.php?mode=minicart",
					data: \'id=\'+id+\'\',
					success: function(html){
					$(\'#cart_informer\').html(html);
				   }

				});
								
				return false;
				
			});
			
			$(\'form.formsearch\').submit(function(){
				key = $(this).find(\'input[name=keyword]\').val();
				
				$(\'#content_lk\').text(\'Загрузка...\');
				
				$.ajax({
					type: "GET",
					url: "'.$this->config->path.'ajax/Shop.php?mode=search",
					data: \'keyword=\'+key+\'\',
					success: function(html){
					$(\'#content_lk\').html(html);
				   }

				});

				return false;
				
			});
			
			</script>
			';
			
			$this->design->load('shop.html');
			$this->design->set('googs', $googs['googs']);
			$this->design->set('navigation', $googs['nav']);
			$this->design->set('ajax', $ajax);
			$this->design->set('cart', $cart);
			$this->design->set('category', $category);
			$this->design->set('title', $googs['title']);
			return $this->design->result();
		}
	}
	
	private function googs(){
		ob_start();
		
		if(!$_SESSION['server']) { echo "<div id=\"error_lk\" style=\"display:block;\">Сервер не выбран</div>"; die();}
			
			$s_info = $this->server_info($_SESSION['server']);
			
			if(isset($_GET['cat_id']) and $_GET['mode'] == 'category')
			{
				
				$name = $this->db->get_row($this->db->query("SELECT name FROM __shop_category WHERE id='".intval($_GET['cat_id'])."'"));
				$name = $name['name'];
				
				$nav = $this->navigation("__shop_googs WHERE category='".intval($_GET['cat_id'])."' AND server='".intval($_SESSION['server'])."'", $_GET['page'], $this->config->path.'ajax/Shop.php?mode=category&cat_id='.intval($_GET['cat_id']).'&page=', $this->config->limit_googs);
				$this->db->query("SELECT * FROM __shop_googs WHERE category='".intval($_GET['cat_id'])."' AND server='".intval($_SESSION['server'])."' ORDER BY id DESC LIMIT {$nav['start']}, {$nav['num']}");
				
				$title = $this->title('Сервер &raquo; '.$s_info['name'].' &raquo; Магазин &raquo; Категория &raquo; '.$name);
				$error = 'Товара нет';
			}	
				elseif(isset($_GET['keyword']) and $_GET['mode'] == 'search'){
					$nav = $this->navigation("__shop_googs WHERE server='".intval($_SESSION['server'])."' AND name LIKE '%".$this->db->safesql($_GET['keyword'])."%'", $_GET['page'], $this->config->path.'ajax/Shop.php?mode=search&keyword='.$_GET['keyword'].'&page=', $this->config->limit_googs);
					$this->db->query("SELECT * FROM __shop_googs WHERE server='".intval($_SESSION['server'])."' AND name LIKE '%".$this->db->safesql($_GET['keyword'])."%' ORDER BY id DESC LIMIT {$nav['start']}, {$nav['num']}");
					
					$title = $this->title('Сервер &raquo; '.$s_info['name'].' &raquo; Магазин &raquo; Пойск ');
					$error = 'По запросу '.$_GET['keyword'].' не чего не найдено';
				}
					else{
						$nav = $this->navigation("__shop_googs WHERE server='".intval($_SESSION['server'])."'", $_GET['page'], $this->config->path.'ajax/Shop.php?page=', $this->config->limit_googs);
						$this->db->query("SELECT * FROM __shop_googs WHERE server='".intval($_SESSION['server'])."' ORDER BY id DESC LIMIT {$nav['start']}, {$nav['num']}");
						
						$title = $this->title('Сервер &raquo; '.$s_info['name'].' &raquo; Магазин &raquo; Категория &raquo; Без категорий');
						$error = 'Товара нет';
					}

			if($this->db->num_rows() > 0){
				while($row = $this->db->get_row()){
				
					if(isset($_GET['keyword'])) $name = str_replace($_GET['keyword'], '<span style="background-color:#6FB3E0;">'.$_GET['keyword'].'</span>', $row['name']);
						else $name = $row['name'];
						
					$this->design->load('shop_googs.html');
					$this->design->set('name', $name);
					$this->design->set('img', $row['img']);
					$this->design->set('sum', $row['sum']);
					$this->design->set('sum2', $row['sum']*64);
					$this->design->set('id', $row['id']);
					$this->design->set('server', $row['server']);
					$this->design->set('url', $this->link('Shop', '?mode=product&id='.$row['id']));
					echo $this->design->result();
				}
			} else echo $error;
			$tmp = ob_get_clean();
		return array('nav' => $nav['nav'], 'googs' => $tmp, 'title' => $title);
	}
	
	private function category(){
			ob_start();
			$this->db->query("SELECT * FROM __shop_category WHERE server='".intval($_SESSION['server'])."' ORDER BY list ASC");
			while($row = $this->db->get_row()){
				$this->design->load('shop_category.html');
				$this->design->set('name', $row['name']);
				$this->design->set('url', $this->link('Shop', '?mode=category&cat_id='.$row['id']));
				echo $this->design->result();
			}
		return ob_get_clean();
	}
	
	private function mini_cart(){
	
		if($_SESSION['cart'] != '')
		{
			foreach($_SESSION['cart'] as $Id => $Value)
			{
				$this->db->query("SELECT sum FROM __shop_googs WHERE id='".intval($Id)."'");
				$row = $this->db->get_row();	
					
				if(!$row) unset($_SESSION['cart'][$Id]);
		
				$CountGoogs += $Value;
				$SumGoogs += $Value*$row['sum'];
			}
	
		}
		
		$this->design->load('shop_cart_mini.html');
		$this->design->set('googs', $CountGoogs);
		$this->design->set('sum_googs', $SumGoogs);
		$this->design->set('url', $this->link('Shop', '?mode=cart'));
		$this->design->set_block('cart_true', $_SESSION['cart'] != '');
		$this->design->set_block('cart_false', $_SESSION['cart'] == '');
		return $this->design->result();
	}
	
	private function max_cart(){
		ob_start();
			foreach($_SESSION['cart'] as $id => $value){
				$this->db->query("SELECT id,name,sum,img FROM __shop_googs WHERE id='".intval($id)."'");
				$row = $this->db->get_row();
				$this->design->load('shop_cart_googs.html');
				$this->design->set('delete_url', $this->link('Shop', '?mode=cart&op=delete&id='.$row['id'].''));
				$this->design->set('id', $row['id']);
				$this->design->set('name', $row['name']);
				$this->design->set('img', $row['img']);
				$this->design->set('sum', $row['sum']);
				$this->design->set('all_sum', $row['sum']*$value);
				$this->design->set('kol-vo', $value);
				$this->design->set('url', $this->link('Shop', '?mode=product&id='.$row['id']));
				echo $this->design->result();
			}
		return ob_get_clean();
	}
	
}

$view = new Shop;
print $view->fetch();

?>