<?php

require_once(LK_DIR.'/view/View.php');	
	
class IndexView extends View{
	
	public function __construct($username){
		$_SESSION['name'] = $username;
	}
	
	public function fetch(){

		$ajax = '
		<script type="text/javascript" src="'.$this->config->path.'skins/js/jquery-2.0.3.min.js"></script>
		
		<script type="text/javascript">
			server_to = "";
			function loadContent(link)
			{
				
				$(\'#content_lk\').text(\'Загрузка...\');
				$(\'#content_lk\').load(link);
				$(\'#error_lk\').css({\'display\' : \'none\'});
				$(\'#error_lk_ok\').css({\'display\' : \'none\'});
				
				server_to = link;
				
				return false;
			}
			
			if(server_to == \'\') loadContent(\''.$this->config->path.'ajax/Home.php\');
			
			function loadServers(link){
				link1 = link+server_to;
				loadContent(link1);
				return false;
			}
			
		</script>
		
		';
		
		$servers = $this->servers();
		$this->design->load('index.html');
		$this->design->set('ajax', $ajax);
		$this->design->set('home_link', $this->link('Home'));
		$this->design->set('upload_link', $this->link('Upload'));
		$this->design->set('groups_link', $this->link('Groups'));
		$this->design->set('shop_link', $this->link('Shop'));
		$this->design->set('varia_link', $this->link('Varia'));
		$this->design->set('auction_link', $this->link('Auction'));
		$this->design->set('history_link', $this->link('History'));
		// $this->design->set('admin_link', $this->config->path.'admin.php');
		$this->design->set('servers', $servers);
		return $this->design->result();
	}
	
}
	
?>