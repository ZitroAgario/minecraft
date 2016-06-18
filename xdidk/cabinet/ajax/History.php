<?php

if($_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest")
    exit ("Error.");

session_start();
define('ROOT_DIR', substr(dirname(__FILE__), 0, -12));
define('LK_DIR', substr(dirname(__FILE__), 0, -4));
require_once(LK_DIR.'/view/View.php');

class History extends View{
		
	public function fetch(){
		header("Content-type: text/html; charset=".$this->config->charset."");
		
		$history = $this->historys();
		$this->design->load('history.html');
		$this->design->set('history', $history['historys']);
		$this->design->set('navigation', $history['nav']);
		$this->design->set('type_money', $this->link('History', '?sort=money'));
		$this->design->set('type_shop', $this->link('History', '?sort=shop'));
		$this->design->set('type_groups', $this->link('History', '?sort=groups'));
		$this->design->set('type_d', $this->link('History', '?sort=d'));
		$this->design->set('type_all', $this->link('History'));
		$this->design->set('title', $this->title('История'));
		return $this->design->result();
		
	}	
	
	private function historys(){
		
		
		if(isset($_GET['sort']))
		{
			$nav = $this->navigation("__history", $_GET['page'], $this->config->path.'ajax/History.php?sort='.$_GET['sort'].'&page=', 10);
			$this->db->query("SELECT * FROM __history WHERE user='".$this->username."' AND type='".$_GET['sort']."' ORDER BY id DESC LIMIT {$nav['start']}, {$nav['num']}");
		}
		else {
			$nav = $this->navigation("__history", $_GET['page'], $this->config->path.'ajax/History.php?page=', 10);
			$this->db->query("SELECT * FROM __history WHERE user='".$this->username."' ORDER BY id DESC LIMIT {$nav['start']}, {$nav['num']}");
		}
		if($this->db->num_rows() > 0)
		{
			
			ob_start();
				while($row = $this->db->get_row()){
					$this->design->load('historys.html');
					$this->design->set('name', $row['title']);
					$this->design->set('text', $row['text']);
					$this->design->set('sum', $row['sum']);
					$this->design->set('date', $row['date']);
					echo $this->design->result();
				}
			$h = ob_get_clean();
			
		}
			else $h = '<tr><td colspan="4">История не найдена</td></tr>';
		
		return array('historys' => $h, 'nav' => $nav['nav']);
		
	}
	
}

$view = new History;
print $view->fetch();