<?php

session_start();
define('ROOT_DIR', substr(dirname(__FILE__), 0, -12));
define('LK_DIR', substr(dirname(__FILE__), 0, -4));
require_once(LK_DIR.'/view/View.php');	

class Home extends View{
	
	public function fetch(){
		header("Content-type: text/html; charset=".$this->config->charset."");
		
		if(isset($_GET['skin'])){$this->user_skin(); break;}
		elseif(isset($_GET['cloak'])){$this->user_cloak(); break;}
		
		
		$ajax = '
		<script type="text/javascript" src="'.$this->config->path.'skins/js/Three.js"></script>
		<script type="text/javascript" src="'.$this->config->path.'skins/js/skin3D.js"></script>
		';
		
		$s_info = $this->server_info($_SESSION['server']);
		$title = $this->title('Сервер &raquo; '.$s_info['name'].' &raquo; Главная');
		
		$this->design->load('main.html');
		$this->design->set('ajax', $ajax);
		$this->design->set('username', $this->username);
		$this->design->set('balance', $this->balance);
		$this->design->set('game', $this->iconomy_balace);
		$this->design->set('group', $this->user_group['name']);
		$this->design->set('avatar', $this->config->path.'ajax/Home.php?skin=true');
		$this->design->set('cloak', $this->config->path.'ajax/Home.php?cloak=true');
		$this->design->set('title', $title);
		$this->design->set('add_link', $this->link('Payment'));
		return $this->design->result();
				
	}
	
	
	private function user_skin(){
		$path = ROOT_DIR.$this->config->path_skin.$this->username.'.png';
		if(!file_exists($path))
			$skin_path = ROOT_DIR.$this->config->path_skin.'default.png';
		else $skin_path = $path;	
		
		$skin = imagecreatefrompng($skin_path);
		$info_skin = @getimagesize($skin_path);
		$tmp = imageCreateTrueColor(64, 32); 
		header('Content-Type: image/png');
		imageSaveAlpha($tmp, true); // сохранение альфа канала
		imageAlphaBlending($tmp, false); // отключение размера смешивания
		// копируем старую картинку в новую
		imageCopyResampled($tmp, $skin, 0, 0, 0, 0, 64, 32, $info_skin[0], $info_skin[1]);
		imagepng($tmp);
		imagedestroy($tmp);
		die();
		return true;
	}
	
	private function user_cloak(){
		$path = ROOT_DIR.$this->config->path_cloak.$this->username.'.png';
		if(!file_exists($path))
			$c_path = ROOT_DIR.$this->config->path_cloak.'default.png';
		else $c_path = $path;	
		
		$skin = imagecreatefrompng($c_path);
		$info_skin = @getimagesize($c_path);
		$tmp = imageCreateTrueColor(22, 17); 
		header('Content-Type: image/png');
		imageSaveAlpha($tmp, true); // сохранение альфа канала
		imageAlphaBlending($tmp, false); // отключение размера смешивания
		// копируем старую картинку в новую
		imageCopyResampled($tmp, $skin, 0, 0, 0, 0, 22, 17, $info_skin[0], $info_skin[1]);
		imagepng($tmp);
		imagedestroy($tmp);
		die();
		return true;	
	}
	
}

$view = new Home;
print $view->fetch();

	
?>