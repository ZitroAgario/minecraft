<?php

if($_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest")
    exit ("Error.");

session_start();
define('ROOT_DIR', substr(dirname(__FILE__), 0, -12));
define('LK_DIR', substr(dirname(__FILE__), 0, -4));
require_once(LK_DIR.'/view/View.php');	

class Groups extends View{
	
	public function fetch(){
		header("Content-type: text/html; charset=".$this->config->charset."");
		
		if($_GET['mode'] == 'buy'){
			
			$this->db->query("SELECT * FROM __groups WHERE id='".intval($_POST['id'])."'");
			$row = $this->db->get_row();
			if(!$row) return 'err2';
			else {
				
				if($this->balance >= $row['sum']){
					
					
					$this->db->query("INSERT INTO __history SET
					title='Покупка группы',
					text='Вы успешно приобрели группу ".$row['name']." за ".$row['sum']." рублей',
					sum='".$row['sum']."',
					date='".date('d.m.Y, H:i')."',
					type='groups',
					user='".$this->username."'
					");
					
					
					#Для серверов
			
					$this->db->query("SELECT table_groups FROM __servers WHERE id='{$row['server']}'");
					$row_s = $this->db->get_row();
			
					$pexdate=time()+2678400;	
					$expdate = date('d-m-Y H:i:s', $pexdate);
		
					$this->db->query("INSERT INTO __{$row_s['table_groups']} SET
					child='".$this->username."',
					parent='{$row['permgroup']}',
					type='1',
					world='NULL',
					value='$pexdate'
					");
			
					#Для серверов END
					
					$this->update_balance($this->username, '-', $row['sum']);
					return 'Вы успешно приобрели группу '.$row['name'].' стоимостью '.$row['sum'].' рублей';
					
				} else return 'err1';
				
			}
		}
		else {
		
		if(isset($_SESSION['server'])){
			$s_info = $this->server_info($_SESSION['server']);
			$title = $this->title('Сервер &raquo; '.$s_info['name'].' &raquo; Группы');
			
			$this->db->query("SELECT name,sum,text,id,server FROM __groups WHERE server='{$_SESSION['server']}' ORDER BY id DESC");
			ob_start();
			echo '<div class="title_lk">'.$title.'</div>';
			if($this->db->num_rows() > 0){
				while($row = $this->db->get_row()){
					$this->design->load('groups.html');
					$this->design->set('name', $row['name']);
					$this->design->set('text', $row['text']);
					$this->design->set('sum', $row['sum']);
					$this->design->set('id', $row['id']);
					$this->design->set('server', $row['server']);
					echo $this->design->result();
				}
				echo '
				<script>
				$(\'form.form_groups\').submit(function(){
				
				id = $(this).find(\'input[name=id]\').val();
				
				$.ajax({
					type: "POST",
					url: "'.$this->config->path.'ajax/Groups.php?mode=buy",
					data: \'id=\'+id+\'\',
					success: function(html){
						if(html == \'err1\') {
							$(\'#error_lk\').html("Не хватает денег");
							$(\'#error_lk\').css({\'display\' : \'block\'});
						}	
						else if(html == \'err2\') {
							$(\'#error_lk\').html("Ошибка");
							$(\'#error_lk\').css({\'display\' : \'block\'});
						}
						else {
							$(\'#error_lk_ok\').html(html);
							$(\'#error_lk_ok\').css({\'display\' : \'block\'});
						}	
				   }

				});
								
				return false;
				
				});
				</script>
				';
			} else echo 'Групп нет';	
		return ob_get_clean();
		} else return '<div id="error_lk" style="display:block;">Сервер не выбран</div>';
		}
	}
	
}	

$view = new Groups;
print $view->fetch();
	
?>