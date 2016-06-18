<?php
session_start();
define('ROOT_DIR', substr(dirname(__FILE__), 0, -12));
define('LK_DIR', substr(dirname(__FILE__), 0, -4));
require_once(LK_DIR.'/view/View.php');	

class Upload extends View{
	
	public function fetch(){
		header("Content-type: text/html; charset=".$this->config->charset."");
			
			if($_GET['mode'] == 'form'){
			
			$main = '
			<link rel="stylesheet" href="'.$this->config->path.'templates/'.$this->config->templates.'/css/style.css" />
			<script src="'.$this->config->path.'skins/js/jquery-2.0.3.min.js"></script>
			';
				
			if(isset($_POST['submit_upload'])){

			$imageinfo = @getimagesize ($_FILES['file']['tmp_name']);
			if($_FILES['file']['name'] == '') $err = 'Файл не выбран';
				elseif($_FILES['file']['type'] != "image/png" and $imageinfo['mime'] != 'image/png') $err = 'Формат файла не png';
					else{
						
						if($imageinfo[0] == 64 and $imageinfo[1] == 32) {
							if($this->balance >= $this->config->sum_skin or $this->user_group['upload_skin'] == 1){
								move_uploaded_file($_FILES["file"]["tmp_name"], ROOT_DIR.'/'.$this->config->path_skin.$this->username.'.png');
								
								if($this->user_group['upload_skin'] != '1') $this->update_balance($this->username, '-', $this->config->sum_skin);
								$err2 = 'Скин успешно загружен';
							}
						}
						elseif($imageinfo[0] == 1024 and $imageinfo[1] == 512){
							if($this->balance >= $this->config->sum_skin or $this->user_group['upload_hd_skin'] == 1){
								move_uploaded_file($_FILES["file"]["tmp_name"], ROOT_DIR.'/'.$this->config->path_skin.$this->username.'.png');
								
								if($this->user_group['upload_hd_skin'] != '1') $this->update_balance($this->username, '-', $this->config->sum_skin);
								$err2 = 'Скин успешно загружен';
							}
						}
						elseif($imageinfo[0] == 22 and $imageinfo[1] == 17){
							if($this->balance >= $this->config->sum_cloak or $this->user_group['upload_cloak'] == 1){
								move_uploaded_file($_FILES["file"]["tmp_name"], ROOT_DIR.'/'.$this->config->path_cloak.$this->username.'.png');
								
								if($this->user_group['upload_cloak'] != '1') $this->update_balance($this->username, '-', $this->config->sum_cloak);
								$err2 = 'Плащ успешно загружен';
							}
						}
						else $err = 'Не верные размеры изображений';
					}
				
				if(isset($err)) $ajax[] = "$('#error_lk').html('$err');\n$('#error_lk').css({'display' : 'block', 'width' : '720px'});\n";
				if(isset($err2)) $ajax[] = "$('#error_lk_ok').html('$err2');\n$('#error_lk_ok').css({'display' : 'block', 'width' : '720px'});\n";
				$ajax_ = "\n<script type=\"text/javascript\">\n".implode("\n",$ajax)."</script>";
		}
				
				$this->design->load('upload.html');
				$this->design->set('sum_skin', $this->config->sum_skin);
				$this->design->set('sum_cloak', $this->config->sum_cloak);
				$this->design->set('title', $this->title('Загрузка'));
				return $main.'<div id="error_lk"></div><div id="error_lk_ok"></div>'.$this->design->result().$ajax_;
			} else return '<iframe src="'.$this->config->path.'ajax/Upload.php?mode=form" width="100%" height="300px" style="border:0;"></iframe>';	
	}
	
	
}

$view = new Upload;
print $view->fetch();

?>