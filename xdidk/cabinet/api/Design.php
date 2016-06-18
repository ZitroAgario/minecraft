<?php
	
require_once(LK_DIR.'/api/Api.php');
	
class Design extends Api{
	
	public function load($theme_file) {
	
		if(!$this->dir)
		$this->dir = LK_DIR.'/templates/'.$this->config->templates.'/';
		
        if(!file_exists($this->dir.$theme_file)) {
		die("Невозможно загрузить шаблон: ".$this->dir.$theme_file);
		return false;
		}
		
		$this->template = file_get_contents($this->dir ."/".$theme_file);
		
		
		if (strpos ( $this->template, "{include file=" ) !== false) {
			$this->template = preg_replace("#\\{include file=['\"](.+?)['\"]\\}#ies", "\$this->load_file('\\1')", $this->template);
		}
		
			$this->template = str_replace('{home}', '/', $this->template);
		
		if (strpos ( $this->template, "{theme}" ) !== false) {
			$this->template = str_replace('{theme}', $this->config->path.'templates/'.$this->config->templates.'', $this->template);
		}
		
		return true;
	}
	
	public function set($paramentr1, $paramentr2){
		$this->template = str_replace('{'.$paramentr1.'}', $paramentr2, $this->template);
	}

	public function set_block($paramentr1, $true_or_false){
		if($true_or_false)
		{
		$this->template = preg_replace("/\[{$paramentr1}\](.*?)\[\/{$paramentr1}\]/is", "$1", $this->template);
		} else {
			$this->template = preg_replace("/\[{$paramentr1}\](.*?)\[\/{$paramentr1}\]/is", "", $this->template);
		}
	}
	
	public function set_block_max($paramentr1, $paramentr2, $if){
		if($if)
		{
		$this->template = preg_replace("/\[{$paramentr1}\](.*?)\[\/{$paramentr1}\]/is", $paramentr2, $this->template);
		} else {
			$this->template = preg_replace("/\[{$paramentr1}\](.*?)\[\/{$paramentr1}\]/is", "", $this->template);
		}
	}

	private function load_file($name){
		ob_start();
			if(!file_exists($name)) echo 'Файл '.$name.' не найден';
			else include $name;
		return ob_get_clean();
	}
	
	public function result() {return $this->template; }
	
}
	
?>