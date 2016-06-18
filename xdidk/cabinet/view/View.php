<?php
require_once(LK_DIR.'/api/Api.php');	

class View extends Api{
	
	public $username;
	
	public function __construct(){
		
		$_GET = $this->safesql_xss($_GET);
		$_POST = $this->safesql_xss($_POST);
		$_SESSION['name'] = $this->safesql_xss($_SESSION['name']);
		$_SESSION['cart'] = $this->safesql_xss($_SESSION['cart']);
		
		$this->username = $_SESSION['name'];
		$this->balance = $this->realmoney();
		$this->user_group = $this->group_info();
		$this->iconomy_balace = $this->iconomymoney();
		$this->task();
	}
	
	public function group_info(){
		
		$server = $this->server_info($_SESSION['server']);
		
		$standart = array(
		'name' => $this->config->group_name,
		'upload_skin' => $this->config->group_upload_skin,
		'upload_hd_skin' => $this->config->group_upload_hd_skin,
		'upload_cloak' =>  $this->config->group_upload_cloak,
		);
		
		if(isset($_SESSION['server']))
		{
			$this->db->query("SELECT * FROM __{$server['table_groups']} WHERE child='".$this->username."'");
			$row = $this->db->get_row();
			if(!$row) return $standart;
				else {
					
					$this->db->query("SELECT * FROM __groups WHERE server='".$_SESSION['server']."' AND permgroup='".$row['parent']."'");
					$row2 = $this->db->get_row();
					
					if(!$row2) return $standart; else {
						return array(
						'name' => $row2['name'],
						'upload_skin' => $row2['upload_skin'],
						'upload_hd_skin' => $row2['upload_hd_skin'],
						'upload_cloak' => $row2['upload_cloak'],
						);
					}
				}
		}
		else return $standart;
		
	}
	
	public function server_info($id){
		
		$q = $this->db->query("SELECT * FROM __servers WHERE id='".intval($id)."'");
		return $this->db->get_row($q);
		
	}
	
	public function realmoney(){

		$this->db->query("SELECT * FROM __realmoney WHERE user='".$this->username."'");
		$row = $this->db->get_row();
		if(!$row) {
			$this->db->query("INSERT INTO __realmoney SET user='".$this->username."', value='0'");
			return 0;
		}	
		else {
			return $row['value'];
		}
	} 
	
	public function iconomymoney(){
		
		$server = $this->server_info($_SESSION['server']);
		$this->db->query("SELECT balance FROM __{$server['table_iconomy']} WHERE username='".$this->username."'");
		$row = $this->db->get_row();
		if(!$row) {
			return '0.00';
		}	
		else {
			return $row['balance'];
		}
	} 
	
	public function link($link, $prm = ''){
		$path = $this->config->path.'ajax/';
		return " onclick=\"return loadContent('$path$link.php$prm');\" ";
	}
	
	public function servers(){
		$path = $this->config->path.'ajax/';
		
		$this->db->query("SELECT * FROM __servers ORDER BY list ASC");
		ob_start();
			while($row = $this->db->get_row())
			{
				$this->design->load('servers.html');
				$this->design->set('name', $row['name']);
				$this->design->set('url', ' onclick="return loadServers(\''.$path.'Server.php?id='.$row['id'].'&mod=\');" ');
				echo $this->design->result();
			}
		return ob_get_clean();	
	}
	
	public function navigation($from, $page_, $url, $num_default = 10){
		$url_nav = $url;
		$num = $num_default;
		$page = intval($page_);
		$posts = $this->db->get_array($this->db->query("SELECT COUNT(id) FROM $from"));
		$posts = $posts[0];
		$total = intval((($posts - 1) / $num) + 1);
		if(empty($page) or $page < 0) $page = 1;
		if($page > $total) $page = $total;
		if($posts == 0) $start = 0; else $start = $page * $num - $num;
		
		if ($page != 1) $pages_perv = '<a href="#" onclick="return loadContent(\''.$url_nav.'1\');">&lt;&lt;</a> <a href="#" onclick="return loadContent(\''.$url_nav.($page - 1).'\');">&lt;</a>'; if ($page != $total) $pages_next = '<a href="#" onclick="return loadContent(\''.$url_nav.($page + 1).'\');">&gt;</a> <a href="#" onclick="return loadContent(\''.$url_nav.$total.'\');">&gt;&gt;</a>';
	
	
		if ($total > 1)
		{
			for($i=1;$i<6;$i++){
				if($page - $i > 0) $pages_left[$page - $i] = ' <a href="#" onclick="return loadContent(\''.$url_nav.($page - $i).'\');">'.($page - $i).'</a> ';
				if($page + $i <= $total) $pages_right[$page + $i] = ' <a href="#" onclick="return loadContent(\''.$url_nav.($page + $i).'\');">'.($page + $i).'</a>';
			}

			if(is_array($pages_left)) {ksort($pages_left); $pages_left = implode("\r\n", $pages_left);}
			if(is_array($pages_right)) {ksort($pages_right); $pages_right = implode("\r\n", $pages_right);}
			
			$pages = '<a href="#" onclick="return loadContent(\''.$url_nav.$page.'\');"><b>'.$page.'</b></a>';
		
			$nav = $pages_perv.$pages_left.$pages.$pages_right.$pages_next;
		
		}
		
		return array('start' => $start, 'num' => $num, 'nav' => $nav);
	}
	
	public function update_balance($user, $oper, $sum){
		
		$user = $this->db->safesql($user);
		$sum = $this->db->safesql($sum);
		
		$this->db->query("SELECT * FROM __realmoney WHERE user='$user'");
		$row = $this->db->get_row();
		
		switch($oper){
			
			case '-': 
				$this->db->query("UPDATE __realmoney SET value='".($row['value']-$sum)."' WHERE id='".$row['id']."'") or die(mysql_error());
			break;
			
			case '+':	
				$this->db->query("UPDATE __realmoney SET value='".($row['value']+$sum)."' WHERE id='".$row['id']."'") or die(mysql_error());
			break;
		}
		
	}
	
	
	public function safesql_xss($var){
		
		if(is_array($var)){
			
			foreach($var as $k => $v){
				
				if(is_array($k))
					$res[$this->safesql($k)] = str_replace("'","\'",htmlspecialchars($v));
				elseif(is_array($v))	
					$res[str_replace("'","\'",htmlspecialchars($k))] = $this->safesql_xss($v);
				elseif(is_array($k) and is_array($v))	
					$res[$this->safesql_xss($v)] = $this->safesql_xss($k);
					else
						$res[str_replace("'","\'",htmlspecialchars($k))] = str_replace("'","\'",htmlspecialchars($v));
			}
			
		}
		else $res = str_replace("'","\'",htmlspecialchars($var));
		return $res;
	}
	
	public function title($title){
		return 'Личный кабинет &raquo; '.$title;
	}
	
	private function task(){
		//Задачки, удаления групп у которых закончился срок, аукционов
	
		$unixtime = time();
		$this->db->query("SELECT table_groups FROM __servers");
		while($row = $this->db->get_row()){
			$this->db->query("DELETE FROM __{$row['table_groups']} WHERE value < '$unixtime'");
		}
		
		$q = $this->db->query("SELECT item_id,name,last_user,kolvo,sum FROM __auction WHERE date < '".time()."'");
		while($row2 = $this->db->get_row($q)){
			
			if($row2['last_user'] != '')
			{
			$this->db->query("SELECT table_shop FROM __servers WHERE id='{$row2['server']}'");
			$row_s = $this->db->get_row();
			
			$this->db->query("INSERT INTO __{$row_s['table_shop']} SET
			sid='{$row2['id']}',
			username='".$row2['last_user']."',
			iid='{$row2['item_id']}',
			title='{$row2['name']}',
			amount='{$row2['kolvo']}'
			");
			
			$this->db->query("INSERT INTO __history SET
			title='Вы выиграли аукцион',
			text='Вы успешно выиграли аукцион и преобрели товар ".$row2['name']." в кол-во {$row2['kolvo']} за {$row2['sum']} руб',
			sum='".$row2['sum']."',
			date='".date('d.m.Y, H:i')."',
			type='d',
			user='".$row2['last_user']."'
			");
			
			$this->update_balance($row2['last_user'], '-', $row2['sum']);
			}
		}
		
		$this->db->query("DELETE FROM __auction WHERE date < '".time()."'");
	}
	
}