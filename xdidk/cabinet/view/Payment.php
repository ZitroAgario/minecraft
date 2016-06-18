<?php
session_start();
define('ROOT_DIR', substr(dirname(__FILE__), 0, -12));
define('LK_DIR', substr(dirname(__FILE__), 0, -4));
require_once(LK_DIR.'/view/View.php');	

class Payment extends View{
	
	public function fetch(){
		header("Content-type: text/html; charset=".$view->config->charset."");
		if($_GET['robox'] == 'success') { 
			
			$out_summ = $_REQUEST["OutSum"];
			$inv_id = $_REQUEST["InvId"];
			$shp_item = $_REQUEST["Shp_item"];
			$crc = $_REQUEST["SignatureValue"];
			$shp_nick = $_REQUEST["Shp_nick"];
			$crc = strtoupper($crc);

			$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item:Shp_nick=$shp_nick"));

			if ($my_crc != $crc)
			{
				die("bad sign\n");
			}
				else{
	
					$this->update_balance($this->db->safesql($shp_nick), '+', $this->db->safesql($out_summ));
						
						$this->db->query("INSERT INTO __history SET
						title='Пополнения баланса',
						text='Вы успешно пополнили баланс на сумму ".$this->db->safesql($out_summ)." рублей',
						sum='".$this->db->safesql($ik_payment_amount)."',
						date='".date('d.m.Y, H:i')."',
						type='money',
						user='".$this->db->safesql($shp_nick)."'
						");
			
					echo 'Счёт успешно пополнен';

				}
			
		}
		elseif($_GET['robox'] == 'fail')
		{
			echo 'Платёж не удался '.$this->safesql_xss($_REQUEST["InvId"]);
		}
		elseif($_GET['inter'] == 'success')
		{
			// Проверяем контрольную подпись
			$ik_shop_id = $_REQUEST['ik_shop_id'];
			$ik_payment_amount = $_REQUEST['ik_payment_amount']; //Сумма
			$ik_payment_id = $_REQUEST['ik_payment_amount']; // Логин
			$ik_payment_desc = $_REQUEST['ik_payment_desc'];
			$ik_paysystem_alias = $_REQUEST['ik_paysystem_alias'];
			$ik_baggage_fields = $_REQUEST['ik_baggage_fields'];
			$ik_payment_state = $_REQUEST['ik_payment_state'];
			$ik_trans_id = $_REQUEST['ik_trans_id'];
			$ik_currency_exch = $_REQUEST['ik_currency_exch'];
			$ik_fees_payer = $_REQUEST['ik_fees_payer'];
			$ik_sign_hash = $_REQUEST['ik_sign_hash'];

			$secret_key = $this->config->secret_key;
			$ik_sign_hash_str = $ik_shop_id.':'.$ik_payment_amount.':'.$ik_payment_id.':'.$ik_paysystem_alias.':'.$ik_baggage_fields.':'.$ik_payment_state.':'.$ik_trans_id.':'.$ik_currency_exch.':'.$ik_fees_payer.':'.$secret_key;

			if(strtoupper($ik_sign_hash) !== strtoupper(md5($ik_sign_hash_str)))
				die('bad sign');
				else{
						
						$this->update_balance($this->db->safesql($ik_payment_id), '+', $this->db->safesql($ik_payment_amount));
						
						$this->db->query("INSERT INTO __history SET
						title='Пополнения баланса',
						text='Вы успешно пополнили баланс на сумму ".$this->db->safesql($ik_payment_amount)." рублей',
						sum='".$this->db->safesql($ik_payment_amount)."',
						date='".date('d.m.Y, H:i')."',
						type='money',
						user='".$this->db->safesql($ik_payment_id)."'
						");
			
					echo 'Счёт успешно пополнен';
		
				}
		}
		else {
			echo 'error';
		}
		
		return true;
		
	}

}

$view = new Payment;
print $view->fetch();