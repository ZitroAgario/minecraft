<?php
if(!defined("INCABINET")) die("Hacking attempt!");

class Admin extends View{
	
	public function __construct(){
		$this->design->dir = LK_DIR.'/templates/admin/';
	}
	
	public function fetch(){
	    global $config;
		header("Content-type: text/html; charset=".$this->config->charset."");
			switch($_GET['do']){
				
				case 'servers': $this->get_servers(); break;
				case 'groups': $this->get_groups(); break;
				case 'shop': $this->get_shop(); break;
				case 'auction': $this->get_auction(); break;
				default : 
					
					$this->title = $this->atitle('Главная');
					
					$this->db->query("SELECT COUNT(id) FROM __servers");
					$row_s = $this->db->get_array();
					
					$this->db->query("SELECT COUNT(id) FROM __shop_googs");
					$row_g = $this->db->get_array();
					
					$this->db->query("SELECT COUNT(id) FROM __history WHERE type='shop'");
					$row_g_buy = $this->db->get_array();
					
					$this->db->query("SELECT COUNT(id) FROM __history WHERE type='groups'");
					$row_gr = $this->db->get_array();
					
					$this->design->load('main.html');
					$this->design->set('servers', $row_s[0]);
					$this->design->set('googs', $row_g[0]);
					$this->design->set('googs_buy', $row_g_buy[0]);
					$this->design->set('groups', $row_gr[0]);
					$this->content = $this->design->result();
					
				break;
			}
			
			
			$this->design->load('index.html');
			$this->design->set('content', $this->content);
			$this->design->set('title', $this->title);
			$this->design->set('home_link', $this->alink(''));
			$this->design->set('add_group_link', $this->alink('?do=groups&op=add'));
			$this->design->set('add_shop_link', $this->alink('?do=shop&op=add'));
			$this->design->set('add_category_link', $this->alink('?do=shop&op=add_c'));
			$this->design->set('add_server_link', $this->alink('?do=servers&op=add'));
			$this->design->set('add_auction_link', $this->alink('?do=auction&op=add'));
			$this->design->set('groups_link', $this->alink('?do=groups'));
			$this->design->set('googs_link', $this->alink('?do=shop'));
			$this->design->set('category_link', $this->alink('?do=shop&op=list_c'));
			$this->design->set('servera_link', $this->alink('?do=servers'));
			$this->design->set('auction_link', $this->alink('?do=auction'));
			$this->design->set('logout_link', $this->alink('?do=logout'));
			$this->design->set('bg_url', $config['background']); 
			$this->design->set('body_styles', $config['body_style']); 
			$this->design->set('balance_link', $this->alink('?do=varia&op=balance'));
			$this->design->set('game_link', $this->alink('?do=varia&op=game_balance'));
			$this->design->set('logs_link', $this->alink('?do=varia&op=logs'));
			$this->design->set_block('error', isset($this->error1));
			$this->design->set_block('error2', isset($this->error2));
			$this->design->set('error', !$this->error1 ? $this->error2 : $this->error1);
			$this->design->set('dir_cab', $this->config->path);
			return $this->design->result();
	}	
	
	function get_servers(){
		
		switch($_GET['op']){
			
			case 'add':
				
				$this->title = $this->atitle('Сервера &raquo; Добавления сервера');
				
				if(isset($_POST['submit'])){
					
					if(strlen($_POST['name']) == 0) $this->error1 = 'Введите названия';
						elseif(strlen($_POST['table_groups']) == 0) $this->error1 = 'Введите таблицу групп';
							elseif(strlen($_POST['table_shop']) == 0) $this->error1 = 'Введите таблицу магазина';
								elseif(strlen($_POST['table_iconomy']) == 0) $this->error1 = 'Введите таблицу iconomy';
									else{
										
										$this->db->query("INSERT INTO __servers SET
										name='".$_POST['name']."',
										table_groups='".$_POST['table_groups']."',
										table_shop='".$_POST['table_shop']."',
										table_iconomy='".$_POST['table_iconomy']."',
										list='".$_POST['list']."'
										");
											
										
										//Создание таблицы для групп
										$this->db->query("CREATE TABLE IF NOT EXISTS `__{$_POST['table_groups']}` (
										`id` int(11) NOT NULL AUTO_INCREMENT,
										`child` varchar(50) NOT NULL,
										`parent` varchar(50) NOT NULL,
										`type` tinyint(1) NOT NULL,
										`world` varchar(50) DEFAULT NULL,
										`value` varchar(255) DEFAULT NULL,
										PRIMARY KEY (`id`),
										UNIQUE KEY `child` (`child`,`parent`,`type`,`world`),
										KEY `child_2` (`child`,`type`),
										KEY `parent` (`parent`,`type`)
										) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
										
										//Создание таблицы для магазина
					
										$this->db->query("CREATE TABLE  `__{$_POST['table_shop']}` (
										`id` INT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
										`sid` INT( 8 ) NOT NULL ,
										`username` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
										`iid` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
										`title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
										`amount` INT( 8 ) NOT NULL
										) ENGINE = MYISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
										
										//Создание таблицы для iconomy
										$this->db->query("CREATE TABLE IF NOT EXISTS `__{$_POST['table_iconomy']}` (
										`id` int(11) NOT NULL AUTO_INCREMENT,
										`username` varchar(100) NOT NULL,
										`balance` int(11) NOT NULL,
										`status` int(11) NOT NULL,
										PRIMARY KEY (`id`)
										) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
										
										header("Location: ".$this->alink('?do=servers'));	
									}
									
				}					
				
				$this->design->load('add_server.html');
				$this->content = $this->design->result();
			
			break;
			
			case 'edit': 
				
				$this->db->query("SELECT * FROM __servers WHERE id='".intval($_GET['id'])."'");
				$row = $this->db->get_row();
				if(!$row) return die('no server');
				
				$this->title = $this->atitle('Сервера &raquo; Реадактирования сервера &raquo; '.$row['name']);
				
				if(isset($_POST['submit'])){
					
					if(strlen($_POST['name']) == 0) $this->error1 = 'Введите названия';
						elseif(strlen($_POST['table_groups']) == 0) $this->error1 = 'Введите таблицу групп';
							elseif(strlen($_POST['table_shop']) == 0) $this->error1 = 'Введите таблицу магазина';
								elseif(strlen($_POST['table_iconomy']) == 0) $this->error1 = 'Введите таблицу iconomy';
									else{
										$this->db->query("UPDATE __servers SET
										name='".$_POST['name']."',
										table_groups='".$_POST['table_groups']."',
										table_shop='".$_POST['table_shop']."',
										table_iconomy='".$_POST['table_iconomy']."',
										list='".$_POST['list']."'
										WHERE id='".$row['id']."'");
										
										if($_POST['table_groups'] != $row['table_groups']){
											$this->db->query("ALTER TABLE __{$row['table_groups']} RENAME TO __{$_POST['table_groups']}");
										}
										
										if($_POST['table_shop'] != $row['table_shop']){
											$this->db->query("ALTER TABLE __{$row['table_shop']} RENAME TO __{$_POST['table_shop']}");
										}
										
										if($_POST['table_iconomy'] != $row['table_iconomy']){
											$this->db->query("ALTER TABLE __{$row['table_iconomy']} RENAME TO __{$_POST['table_iconomy']}");
										}
										
										header("Location: ".$this->alink('?do=servers&op=edit&id='.$row['id']));
										
									}
					
				}
				
				$this->design->load('edit_server.html');
				$this->design->set('name', $row['name']);
				$this->design->set('table_groups', $row['table_groups']);
				$this->design->set('table_shop', $row['table_shop']);
				$this->design->set('table_iconomy', $row['table_iconomy']);
				$this->design->set('list', $row['list']);
				$this->content = $this->design->result();
				
			break;
			
			case 'delete': 
				$query = $this->db->query("DELETE FROM __servers WHERE id='".$_GET['id']."'");
				if(!$query) return die('no server');
					else return header("Location: ".$this->alink('?do=servers'));
			break;
			case 'server': 
				$_SESSION['server_admin'] = intval($_GET['id']);
				return header("Location: ".$_SERVER['HTTP_REFERER']);
			break;
			default: 
				$this->title = $this->atitle('Сервера &raquo; Просмотр серверов');
				ob_start();
					$this->db->query("SELECT * FROM __servers ORDER BY id DESC");
					while($row = $this->db->get_row()){
						$this->design->load('servers.html');
						$this->design->set('name', $row['name']);
						$this->design->set('table_groups', $row['table_groups']);
						$this->design->set('table_shop', $row['table_shop']);
						$this->design->set('table_iconomy', $row['table_iconomy']);
						$this->design->set('edit', $this->alink('?do=servers&op=edit&id='.$row['id']));
						$this->design->set('delete', $this->alink('?do=servers&op=delete&id='.$row['id']));
						echo $this->design->result();
					}
				$servers = ob_get_clean();
				
				$this->design->load('server.html');
				$this->design->set('servers', $servers);
				$this->content = $this->design->result();
			break;
			
		}
		
	}
	
	function get_groups(){
		
		switch($_GET['op']){
			
			case 'add': 
				
				$this->title = $this->atitle('Группы &raquo; Добавления группы');
				
				if(isset($_POST['submit'])){
					if(strlen($_POST['name']) == 0) $this->error1 = 'Введите названия';
						elseif(strlen($_POST['sum']) == 0) $this->error1 = 'Введите цену';
							else{
								
								$this->db->query("INSERT INTO __groups SET
								name='".$_POST['name']."',
								text='".htmlspecialchars_decode($_POST['text'])."',
								sum='".$_POST['sum']."',
								upload_skin='".$_POST['upload_skin']."',
								upload_hd_skin='".$_POST['upload_hd_skin']."',
								upload_cloak='".$_POST['upload_cloak']."',
								permgroup='".$_POST['groupperm']."',
								server='".$_POST['server']."'
								");
								
								header("Location: ".$this->alink('?do=groups'));
								
							}
				}
				
				$this->design->load('add_group.html');
				$this->design->set('servers', $this->select_servers());
				$this->content = $this->design->result();
				
			break;
			
			case 'edit':
				
				$this->db->query("SELECT * FROM __groups WHERE id='".$_GET['id']."'");
				$row = $this->db->get_row();
				if(!$row) return die('no group');
				
				$this->title = $this->atitle('Группы &raquo; Редактирования группы &raquo; '.$row['name']);
				
				if(isset($_POST['submit'])){
					if(strlen($_POST['name']) == 0) $this->error1 = 'Введите названия';
						elseif(strlen($_POST['sum']) == 0) $this->error1 = 'Введите цену';
							else{
								
								$this->db->query("UPDATE __groups SET
								name='".$_POST['name']."',
								text='".htmlspecialchars_decode($_POST['text'])."',
								sum='".$_POST['sum']."',
								upload_skin='".$_POST['upload_skin']."',
								upload_hd_skin='".$_POST['upload_hd_skin']."',
								upload_cloak='".$_POST['upload_cloak']."',
								permgroup='".$_POST['groupperm']."',
								server='".$_POST['server']."'
								WHERE id='".$row['id']."'");
								
								header("Location: ".$this->alink('?do=groups&op=edit&id='.$row['id']));
								
							}
				}
				
				$this->design->load('edit_group.html');
				$this->design->set('name', $row['name']);
				$this->design->set('text', $row['text']);
				$this->design->set('sum', $row['sum']);
				$this->design->set('permgroup', $row['permgroup']);
				$this->design->set('upload_skin', $row['upload_skin'] == 1 ? 'Да' : 'Нет');
				$this->design->set('upload_hd_skin', $row['upload_hd_skin'] == 1 ? 'Да' : 'Нет');
				$this->design->set('upload_cloak', $row['upload_cloak'] == 1 ? 'Да' : 'Нет');
				$this->design->set('servers', $this->select_servers($row['server']));
				$this->content = $this->design->result();
				
			break;
			
			case 'delete':	
				
				$q = $this->db->query("DELETE FROM __groups WHERE id='".$_GET['id']."'");
				if(!$q) return die('no group');
					else return header("Location: ".$this->alink('?do=groups'));
				
			break;
			
			default:
				
				$this->title = $this->atitle('Группы &raquo; Просмотр групп');
				
				ob_start();
					$q = $this->db->query("SELECT * FROM __groups ORDER BY id DESC");
					while($row = $this->db->get_row($q)){
					
						$server_i = $this->server_info($row['server']);
						$server_name = $server_i['name'];
						
						$this->design->load('groups.html');
						$this->design->set('name', $row['name']);
						$this->design->set('sum', $row['sum']);
						$this->design->set('server', $server_name);
						$this->design->set('edit', $this->alink('?do=groups&op=edit&id='.$row['id']));
						$this->design->set('delete', $this->alink('?do=groups&op=delete&id='.$row['id']));
						echo $this->design->result();
					}
				$groups = ob_get_clean();
				
				$this->design->load('group.html');
				$this->design->set('groups', $groups);
				$this->content = $this->design->result();
				
			break;
			
		}
		
	}
	
	function get_shop(){
		
		switch($_GET['op']){
			
			case 'add':
				

				$this->title = $this->atitle('Магазин &raquo; Добавления товара');
				
				if(isset($_POST['submit'])){
					if(strlen($_POST['name']) == 0) $this->error1 = 'Введите названия';
						elseif(strlen($_POST['item_id']) == 0) $this->error1 = 'Введите id товара';
							elseif(strlen($_POST['sum']) == 0) $this->error1 = 'Введите сумму';
								else{
									
									if(isset($_FILES["img"]["name"])){
										if($_FILES['img']['type'] == "image/png"){
											
											$this->db->query("SHOW TABLE STATUS LIKE '__shop_googs'");
											$id = $this->db->get_array();
											$id = ($id['Auto_increment']+1);
											
											move_uploaded_file($_FILES["img"]["tmp_name"], LK_DIR.'/uploads/shop/'.$id.'.png');
											$img = $this->config->path.'uploads/shop/'.$id.'.png';
										}	
									}
									$this->db->query("INSERT INTO __shop_googs SET
									name='".$_POST['name']."',
									text='".htmlspecialchars_decode($_POST['text'])."',
									item_id='".$_POST['item_id']."',
									sum='".$_POST['sum']."',
									img='".$img."',
									category='".$_POST['category']."',
									server='".$_POST['server']."'
									");
									header("Location: ".$this->alink('?do=shop'));
								}
						
						
				}
				
				$this->design->load('add_goog.html');
				$this->design->set('servers', $this->select_servers());
				$this->design->set('category', $this->select_category());
				$this->content = $this->design->result();
				
			break;
			
			case 'edit':
				
				$this->db->query("SELECT * FROM __shop_googs WHERE id='".intval($_GET['id'])."'");
				$row = $this->db->get_row();
				
				$server_i = $this->server_info($row['server']);
				$this->title = $this->atitle('Сервер &raquo; '.$server_i['name'].' &raquo; Магазин &raquo; Редактирования товара &raquo; '.$row['name']);
				
				if(isset($_POST['submit'])){
					if(strlen($_POST['name']) == 0) $this->error1 = 'Введите названия';
						elseif(strlen($_POST['item_id']) == 0) $this->error1 = 'Введите id товара';
							elseif(strlen($_POST['sum']) == 0) $this->error1 = 'Введите сумму';
								else{
									
									$this->db->query("UPDATE __shop_googs SET
									name='".$_POST['name']."',
									text='".htmlspecialchars_decode($_POST['text'])."',
									item_id='".$_POST['item_id']."',
									sum='".$_POST['sum']."',
									img='".$_POST['img']."',
									category='".$_POST['category']."',
									server='".$_POST['server']."'
									WHERE id='".$row['id']."'");
									header("Location: ".$this->alink('?do=shop&op=edit&id='.$row['id']));
								}
						
						
				}		
				
				$this->design->load('edit_goog.html');
				$this->design->set('name', $row['name']);
				$this->design->set('item_id', $row['item_id']);
				$this->design->set('text', $row['text']);
				$this->design->set('sum', $row['sum']);
				$this->design->set('img', $row['img']);
				$this->design->set('servers', $this->select_servers($row['server']));
				$this->design->set('category', $this->select_category($row['category']));
				$this->content = $this->design->result();
				
			break;
			
			case 'delete':
				
				$q = $this->db->query("DELETE FROM __shop_googs WHERE id='".intval($_GET['id'])."'");
				if(!$q) return die('no goog');
					else return header("Location: ".$this->alink('?do=shop'));
			break;
			
			case 'add_c':
				
				$this->title = $this->atitle('Магазин &raquo; Категорий &raquo; Добавления категорий');
				
				if(isset($_POST['submit'])){
					if(strlen($_POST['name']) == 0) $this->error1 = 'Введите названия';
						else {
							
							$this->db->query("INSERT INTO __shop_category SET
							name='".$_POST['name']."',
							list='".$_POST['list']."',
							server='".$_POST['server']."'
							");
							
							header("Location: ".$this->alink('?do=shop&op=list_c'));
						}
				}
				
				$this->design->load('add_category.html');
				$this->design->set('servers', $this->select_servers());
				$this->content = $this->design->result();
				
			break;
			
			case 'edit_c':
				
				$this->db->query("SELECT * FROM __shop_category WHERE id='".intval($_GET['id'])."'");
				$row = $this->db->get_row();
				if(!$row) return die('no category');
				
				$this->title = $this->atitle('Магазин &raquo; Категорий &raquo; Редактирования категорий &raquo; '.$row['name']);
				
				if(isset($_POST['submit'])){
					if(strlen($_POST['name']) == 0) $this->error1 = 'Введите названия';
						else {
							
							$this->db->query("UPDATE __shop_category SET
							name='".$_POST['name']."',
							list='".$_POST['list']."',
							server='".$_POST['server']."'
							WHERE id='".$row['id']."'");
							
							header("Location: ".$this->alink('?do=shop&op=edit_c&id='.$row['id']));
						}
				}
				
				$this->design->load('edit_category.html');
				$this->design->set('name', $row['name']);
				$this->design->set('list', $row['list']);
				$this->design->set('servers', $this->select_servers($row['server']));
				$this->content = $this->design->result();
				
			break;
			
			case 'delete_c':
				$q = $this->query("DELETE FROM __shop_category WHERE id='".intval($_GET['id'])."'");
				if(!$q) return die('not cat');
					else return header("Location: ".$this->alink('?do=shop&op=list_c'));
			break;
			
			case 'list_c':
			
					
				$this->title = $this->atitle('Магазин &raquo; Категорий &raquo; Просмотр категорий');
				
				ob_start();
					$q = $this->db->query("SELECT * FROM __shop_category ORDER BY list ASC");
					while($row = $this->db->get_row($q)){
						
						$server_i = $this->server_info($row['server']);
						$server_name = $server_i['name'];
						
						$this->design->load('categorys.html');
						$this->design->set('name', $row['name']);
						$this->design->set('server', $server_name);
						$this->design->set('edit', $this->alink('?do=shop&op=edit_c&id='.$row['id']));
						$this->design->set('delete', $this->alink('?do=shop&op=delete_c&id='.$row['id']));
						echo $this->design->result();
					}
				$categorys = ob_get_clean();
				
				$this->design->load('category.html');
				$this->design->set('servers', $servers);
				$this->design->set('categorys', $categorys);
				$this->content = $this->design->result();
				
			break;
			
			default:
					
				$server_i = $this->server_info($_SESSION['server_admin']);	
				$servers = $this->aservers();	
					
				$this->title = $this->atitle('Сервер &raquo; '.$server_i['name'].' &raquo; Магазин &raquo; Просмотр товаров');
				
				ob_start();
					$this->db->query("SELECT * FROM __shop_googs WHERE server='".intval($_SESSION['server_admin'])."' ORDER BY id DESC");
					while($row = $this->db->get_row()){
						$this->design->load('googs.html');
						$this->design->set('name', $row['name']);
						$this->design->set('category', $row['category']);
						$this->design->set('sum', $row['sum']);
						$this->design->set('edit', $this->alink('?do=shop&op=edit&id='.$row['id']));
						$this->design->set('delete', $this->alink('?do=shop&op=delete&id='.$row['id']));
						echo $this->design->result();
					}
				$googs = ob_get_clean();
				
				$this->design->load('goog.html');
				$this->design->set('servers', $servers);
				$this->design->set('googs', !$_SESSION['server_admin'] ? '<div class="ErrorLk"><div>Выберите сервер</div></div>' : $googs);
				$this->content = $this->design->result();
				
			break;
			
		}
		
	}
	
	function get_auction(){
		
		switch($_GET['op']){
			
			case 'add':	
				
				$this->title = $this->atitle('Аукцион &raquo; Добавления лота');
				
				if(isset($_POST['submit'])){
					
					if(strlen($_POST['name']) == 0) $this->error1 = 'Введите названия';
						elseif(strlen($_POST['item_id']) == 0) $this->error1 = 'Введите id товара';
							elseif(strlen($_POST['start_sum']) == 0) $this->error1 = 'Введите стартовую сумму';
								elseif(strlen($_POST['kol-vo']) == 0) $this->error1 = 'Введите кол-во';
									elseif(strlen($_POST['date']) == 0) $this->error1 = 'Введите время аукциона';
										else{
										
											
											if(isset($_FILES["img"]["name"])){
												if($_FILES['img']['type'] == "image/png"){
											
													$this->db->query("SHOW TABLE STATUS LIKE '__auction'");
													$id = $this->db->get_array();
													$id = ($id['Auto_increment']+1);
											
													move_uploaded_file($_FILES["img"]["tmp_name"], LK_DIR.'/uploads/auction/'.$id.'.png');
													$img = $this->config->path.'uploads/auction/'.$id.'.png';
												}	
											}
									
											$date = time()+($_POST['date']*60);
											
											$this->db->query("INSERT INTO __auction SET
											item_id='".$_POST['item_id']."',
											name='".$_POST['name']."',
											text='".htmlspecialchars_decode($_POST['text'])."',
											img='".$img."',
											kolvo='".$_POST['kol-vo']."',
											start_sum='".$_POST['start_sum']."',
											sum='".$_POST['start_sum']."',
											step='".$_POST['step']."',
											date='".$date."',
											server='".$_POST['server']."'
											");
											
											
											header("Location: ".$this->alink('?do=auction'));
											
										}
				}	
				
				$this->design->load('add_auction.html');
				$this->design->set('servers', $this->select_servers());
				$this->content = $this->design->result();
				
			break;
			
			case 'edit':	
				
				$this->db->query("SELECT * FROM __auction WHERE id='".intval($_GET['id'])."'");
				$row = $this->db->get_row();
				
				$this->title = $this->atitle('Аукцион &raquo; Редактирования лота &raquo; '.$row['name']);
				
				if(isset($_POST['submit'])){
					
					if(strlen($_POST['name']) == 0) $this->error1 = 'Введите названия';
						elseif(strlen($_POST['item_id']) == 0) $this->error1 = 'Введите id товара';
							elseif(strlen($_POST['start_sum']) == 0) $this->error1 = 'Введите стартовую сумму';
								elseif(strlen($_POST['kol-vo']) == 0) $this->error1 = 'Введите кол-во';
									elseif(strlen($_POST['date']) == 0) $this->error1 = 'Введите время аукциона';
										else{
											
											$date = time()+($_POST['date']*60);
											
											$this->db->query("UPDATE __auction SET
											item_id='".$_POST['item_id']."',
											name='".$_POST['name']."',
											text='".htmlspecialchars_decode($_POST['text'])."',
											img='".$_POST['img']."',
											kolvo='".$_POST['kol-vo']."',
											start_sum='".$_POST['start_sum']."',
											sum='".$_POST['start_sum']."',
											step='".$_POST['step']."',
											date='".$date."',
											server='".$_POST['server']."'
											WHERE id='".$row['id']."'");
											
											
											header("Location: ".$this->alink('?do=auction&op=edit&id='.$row['id']));
											
										}
				}	
				
				$this->design->load('edit_auction.html');
				$this->design->set('name', $row['name']);
				$this->design->set('item_id', $row['item_id']);
				$this->design->set('text', $row['text']);
				$this->design->set('img', $row['img']);
				$this->design->set('kol-vo', $row['kolvo']);
				$this->design->set('start_sum', $row['start_sum']);
				$this->design->set('step', $row['step']);
				$this->design->set('date', round(($row['date']-time())/60));
				$this->design->set('servers', $this->select_servers($row['server']));
				$this->content = $this->design->result();
				
			break;
			
			case 'delete':
			
				$q = $this->query("DELETE FROM __auction WHERE id='".intval($_GET['id'])."'");
				if(!$q) return die('not auction');
					else return header("Location: ".$this->alink('?do=auction'));
					
			break;
			
			default :
				
				$this->title = $this->atitle('Аукцион &raquo; Просмотр лотов');
				
				ob_start();	
					
					$q = $this->db->query("SELECT * FROM __auction ORDER BY id DESC");
					while($row = $this->db->get_row($q)){
					
						$server_i = $this->server_info($row['server']);
						$server_name = $server_i['name'];
						
						$this->design->load('auctions.html');
						$this->design->set('name', $row['name']);
						$this->design->set('date', round(($row['date']-time())/60));
						$this->design->set('server', $server_name);
						$this->design->set('edit', $this->alink('?do=auction&op=edit&id='.$row['id']));
						$this->design->set('delete', $this->alink('?do=auction&op=delete&id='.$row['id']));
						echo $this->design->result();
					}
					
				$auction = ob_get_clean();
				
				$this->design->load('auction.html');
				$this->design->set('auctions', $auction);
				$this->content = $this->design->result();
				
			break;
			
		}
		
	}
	
	private function alink($link){
		global $config;
		return $this->config->path.$config['file_name'].$link;
	}
	
	private function atitle($title){
		return 'Админка &raquo; '.$title;
	}
	
	private function select_servers($id = ''){
		$this->db->query("SELECT id,name FROM __servers");
		ob_start();
			while($row = $this->db->get_row()){
				echo '<option value="'.$row['id'].'" '; if($id == $row['id']) echo ' selected '; echo '>'; echo $row['name'].'</option>';
			}
		return ob_get_clean();	
	}
	
	private function select_category($id = ''){
		$q = $this->db->query("SELECT id,name,server FROM __shop_category ORDER BY list ASC");
		ob_start();
			while($row = $this->db->get_row($q)){
				
				$info_server = $this->server_info($row['server']);
				$server_name = $info_server['name'];
				
				echo '<option value="'.$row['id'].'" '; if($id == $row['id']) echo ' selected '; echo '>'; echo $row['name'].' (Сервер: '.$server_name.')</option>';
			}
				echo '<option value="0" '; if($id == '0') echo ' selected '; echo '>'; echo 'Без категорий</option>';
		return ob_get_clean();
	}
	
	private function aservers(){
		$this->db->query("SELECT id,name FROM __servers ORDER BY id ASC");
		ob_start();
		while($row = $this->db->get_row())
		{
			echo '<a href="'.$this->alink('?do=servers&op=server&id='.$row['id']).'">'.$row['name'].'</a>';
		}
		return ob_get_clean();
	}
	
}