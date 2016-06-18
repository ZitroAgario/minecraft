<?php
if($_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest")
    exit ("Error.");

session_start();
define('ROOT_DIR', substr(dirname(__FILE__), 0, -12));
define('LK_DIR', substr(dirname(__FILE__), 0, -4));
require_once(LK_DIR.'/view/View.php');

class Varia extends View{
		
	public function fetch(){
		header("Content-type: text/html; charset=".$this->config->charset."");
		
		if(isset($_POST['submit1'])) //Обмен
		{
			
			if($this->balance >= $_POST['monet']/$this->config->kurs_game_money)
			{
				$server = $this->server_info($_SESSION['server']);
				
				$this->db->query("SELECT balance FROM __{$server['table_iconomy']} WHERE username='".$this->username."'");
				$row = $this->db->get_row();
				
				if(!$row){
					$this->db->query("INSERT INTO __{$server['table_iconomy']} SET
					username='".$this->username."',
					balance='".$_POST['monet']."',
					status='0'
					");
				} else {
				
				$this->db->query("UPDATE __{$server['table_iconomy']} SET
				balance='".($row['balance']+$_POST['monet'])."'
				WHERE username='".$this->username."'");
				
				}
				$this->update_balance($this->username, '-', ($_POST['monet']/$this->config->kurs_game_money));
					return 'Вы успешно обменяли '.$_POST['monet']/$this->config->kurs_game_money.' рублей на '.$_POST['monet'].' монет';
				
			} else return 'err1';
		}

		else {
		$ajax = '<script>	
			$(\'form.formobmen\').submit(function(){
				monet = $(this).find(\'input[name=monet]\').val();
				
				$.ajax({
					type: "POST",
					url: "'.$this->config->path.'ajax/Varia.php",
					data: \'monet=\'+monet+\'&submit1=true\',
					success: function(html){
						if(html == "err1") {
							$(\'#error_lk\').html("Не хватает денег");
							$(\'#error_lk\').css({\'display\' : \'block\'});
						}
						else{
							$(\'#error_lk_ok\').html(html);
							$(\'#error_lk_ok\').css({\'display\' : \'block\'});
						}
				   }

				});
								
				return false;
				
			});
			
		</script>
		';
		
		$s_info = $this->server_info($_SESSION['server']);
		$title = $this->title('Сервер &raquo; '.$s_info['name'].' &raquo; Разное');
		
		$this->design->load('varia.html');
		$this->design->set('kurs', $this->config->kurs_game_money);
		$this->design->set('ajax', $ajax);
		$this->design->set('title', $title);
		return $this->design->result();
		}
	}	
		
}

$view = new Varia;
print $view->fetch();