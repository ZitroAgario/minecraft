<?php

if($_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest")
    exit ("Error.");

session_start();
define('ROOT_DIR', substr(dirname(__FILE__), 0, -12));
define('LK_DIR', substr(dirname(__FILE__), 0, -4));
require_once(LK_DIR.'/view/View.php');

class Auction extends View{
		
	public function fetch(){
		header("Content-type: text/html; charset=".$this->config->charset."");
		
		switch($_GET['mode']){
		
		case 'pre': 
			
			if(!$_GET['sum']) return 'err3'; else {
			
			if($this->balance >= $_GET['sum'])
			{
				
				
				$this->db->query("SELECT * FROM __auction WHERE id='".intval($_GET['id'])."'");
				$row = $this->db->get_row();
				
				if(!$row) return 'err3';
					else {
						if($_GET['sum'] >= ($row['sum']+$row['step'])){
							$this->db->query("UPDATE __auction SET
							last_user='".$this->username."',
							sum='".$_GET['sum']."'
							WHERE id='".$row['id']."'");
							return 'Ваша ставка принята'.$this->balance;
						}
						else return 'err2';
					}
				
			} else return 'err1';
			
			}
		break;
		
		default:
		
		$s_info = $this->server_info($_SESSION['server']);
		$title = $this->title('Сервер &raquo; '.$s_info['name'].' &raquo; Аукцион');
		
		$ajax = '<script>
			function pre(id){
				
				sum = $(\'#sum_\'+id).val();
				
				$.ajax({
					type: "GET",
					url: "'.$this->config->path.'ajax/Auction.php?mode=pre",
					data: \'id=\'+id+\'&sum=\'+sum+\'\',
					success: function(html){
						if(html == "err1"){
							$(\'#error_lk\').html("Не хватает денег");
							$(\'#error_lk\').css({\'display\' : \'block\'});
						}
						else if(html == "err2"){
							$(\'#error_lk\').html("Слишком мальнекая сумма");
							$(\'#error_lk\').css({\'display\' : \'block\'});
						}
						else if(html == "err3"){
							$(\'#error_lk\').html("Ошибка");
							$(\'#error_lk\').css({\'display\' : \'block\'});
						}	
						else{
							$(\'#error_lk_ok\').html(html);
							$(\'#error_lk_ok\').css({\'display\' : \'block\'});
						}
				   }

				});
				
			}
		</script>
		';
		
		$auctions = $this->auctions();
		$this->design->load('auction.html');
		$this->design->set('title', $title);
		$this->design->set('auctions', $auctions['auctions']);
		$this->design->set('navigation', $auctions['nav']);
		$this->design->set('ajax', $ajax);
		return $this->design->result();
		break;
		}
	}	
	
	function auctions(){
		$nav = $this->navigation("__auction WHERE server='".intval($_SESSION['server'])."' AND date > '".time()."'", $_GET['page'], $this->config->path.'ajax/Auction.php?page=', 20);
		$this->db->query("SELECT * FROM __auction WHERE server='".intval($_SESSION['server'])."' AND date > '".time()."' ORDER BY id DESC LIMIT {$nav['start']}, {$nav['num']}");
		ob_start();
			while($row = $this->db->get_row()){
				$this->design->load('auctions.html');
				$this->design->set('id', $row['id']);
				$this->design->set('name', $row['name']);
				$this->design->set('text', $row['text']);
				$this->design->set('img', $row['img']);
				$this->design->set('kol-vo', $row['kolvo']);
				$this->design->set('start_sum', $row['start_sum']);
				$this->design->set('pre_sum', $row['sum']);
				$this->design->set('sum1', $row['sum']+$row['step']);
				$this->design->set('step', $row['step']);
				$this->design->set('end_date', round(($row['date']-time())/60));
				echo $this->design->result();
			}
		$tmp = ob_get_clean();
		return array('nav' => $nav['nav'], 'auctions' => $tmp);
	}
	
}

$view = new Auction;
print $view->fetch();