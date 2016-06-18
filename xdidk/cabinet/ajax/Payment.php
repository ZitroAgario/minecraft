<?php
session_start();
define('ROOT_DIR', substr(dirname(__FILE__), 0, -12));
define('LK_DIR', substr(dirname(__FILE__), 0, -4));
require_once(LK_DIR.'/view/View.php');	

class Payment extends View{
	
	public function fetch(){
		header("Content-type: text/html; charset=".$this->config->charset."");
		
		if(isset($_POST['submit']))
		{
			
			if(strlen($_POST['sum']) < 1) $err = 'Введите сумму';
				elseif(!(int)$_POST['sum']) $err = 'Это не число или не челое число';
					else{
				
						if($_POST['variant'] == 'inter') { 
							return '
							<div style="display:none;">
							<form name="payment" action="https://interkassa.com/lib/payment.php" method="post" target="_blank" enctype="application/x-www-form-urlencoded" accept-charset="utf8">
							<input type="hidden" name="ik_shop_id" value="'.$this->config->ik_shop_id.'"/>
							<input type="hidden" name="ik_payment_amount" value="'.$_POST['sum'].'"/>
							<input type="hidden" name="ik_payment_id" value="'.$this->username.'"/>
							<input type="hidden" name="ik_baggage_fields" value="Пополения баланса пользовтеля - '.$this->username.'"/>
							<input type="hidden" name="ik_payment_desc" value="donate"/>
							<input type="submit" name="process" value="next"/>
							</form>
							</div>
							<a href="javascript:document.payment.submit();">Продолжить</a>
							<a href="#" '.$this->link('Payment').' class="button_link" style="float:right;">Назад</a>
							';
						}
						elseif($_POST['variant'] == 'robox') {
							
							$mrh_login = $this->config->robox_login;
							$mrh_pass1 = $this->config->robox_pass;
							// описание заказа
							$inv_desc = 'Пополнения баланса пользователя - '.$this->username;
							//Сумма
							$out_summ = $_POST['sum'];
							//Ник
							$shp_nick = $this->username;
							$shp_item = "2";
							// предлагаемая валюта платежа
							$in_curr = "";
							// язык
							$culture = "ru";
							// формирование подписи
							$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item:Shp_nick=$shp_nick");
							
							return '
							<div style="display:none;">
							<form action="http://test.robokassa.ru/Index.aspx" method="POST" target="_blank" name="payment">
							<input type="hidden" name="Shp_nick" value="'.$shp_nick.'"/>
							<input type="hidden" name="MrchLogin" value="'.$mrh_login.'"/>
							<input type="hidden" name="OutSum" value="'.$out_summ.'"/>
							<input type="hidden" name="Desc" value="'.$inv_desc.'"/>
							<input type="hidden" name="SignatureValue" value="'.$crc.'"/>
							<input type="hidden" name="Shp_item" value="'.$shp_item.'"/>
							<input type="hidden" name="IncCurrLabel" value="'.$in_curr.'"/>
							<input type="hidden" name="Culture" value="'.$culture.'"/>
							<input type="submit" value="next"/>
							</form>
							</div>
							<a href="javascript:document.payment.submit();">Продолжить</a>
							<a href="#" '.$this->link('Payment').' class="button_link" style="float:right;">Назад</a>
							';
						}
							else $err = 'Ошибка';
						}

			return $err;
			
		} else {
		$ajax = '<script>
			$(\'form.formpayment\').submit(function(){
				sum = $(this).find(\'input[name=sum]\').val();
				variant = $(\'#variant\').val();
				
				$.ajax({
					type: "POST",
					url: "'.$this->config->path.'ajax/Payment.php",
					data: \'sum=\'+sum+\'&variant=\'+variant+\'&submit=true\',
					success: function(html){
					$(\'#content_lk\').text("Загрузка");
					$(\'#content_lk\').html(html);
				   }

				});
								
				return false;
				
			});
		</script>
		';
		
		$this->design->load('payment.html');
		$this->design->set('title', $this->title('Пополения баланса'));
		$this->design->set('ajax', $ajax);
		$this->design->set_block('inter', $this->config->payment_inter == '1');
		$this->design->set_block('robox', $this->config->payment_robox == '1');
		return $this->design->result();
		}		
	}

	
}

$view = new Payment;
print $view->fetch();

	
?>