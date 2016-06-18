<?php

require_once(LK_DIR.'/api/Api.php');	

class Database extends Api{
	
	public function __construct(){
		$this->connect();
	}
	
	function connect()
	{
		if(!$this->db_id = @mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_pass)) {
				$this->display_error(mysql_error(), mysql_errno());
		} 

		if(!@mysql_select_db($this->config->db_name, $this->db_id)) {
			$this->display_error(mysql_error(), mysql_errno());	
		}

		$this->mysql_version = mysql_get_server_info();



		mysql_query("SET NAMES ".$this->config->db_charset."");

		return true;
	}
	
	function query($query)
	{
		$time_before = $this->get_real_time();
		
		$query = str_replace('__', $this->config->db_pref, $query);

		if(!($this->query_id = @mysql_query($query, $this->db_id) )) {

			//$this->mysql_error = mysql_error();
			//$this->mysql_error_num = mysql_errno();

			
			//$this->display_error($this->mysql_error, $this->mysql_error_num, $query);
			
		}
			
		$this->MySQL_time_taken += $this->get_real_time() - $time_before;

		$this->query_num ++;

		return $this->query_id;
	}
	
	function get_row($query_id = '')
	{
		if ($query_id == '') $query_id = $this->query_id;

		return @mysql_fetch_assoc($query_id);
	}

	function get_affected_rows()
	{
		return mysql_affected_rows($this->db_id);
	}

	function get_array($query_id = '')
	{
		if ($query_id == '') $query_id = $this->query_id;

		return mysql_fetch_array($query_id);
	}
	
	
	function num_rows($query_id = '')
	{

		if ($query_id == '') $query_id = $this->query_id;

		return @mysql_num_rows($query_id);
	}
	
	function insert_id()
	{
		return mysql_insert_id($this->db_id);
	}

	function get_result_fields($query_id = '') {

		if ($query_id == '') $query_id = $this->query_id;

		while ($field = mysql_fetch_field($query_id))
		{
            $fields[] = $field;
		}
		
		return $fields;
   	}

	function safesql( $source )
	{
		return mysql_real_escape_string ($source);
	}

	function close()
	{
		@mysql_close($this->db_id);
	}

	function get_real_time()
	{
		list($seconds, $microSeconds) = explode(' ', microtime());
		return ((float)$seconds + (float)$microSeconds);
	}	

	function display_error($error, $error_num, $query = '')
	{
		if($query) {
			// Safify query
			$query = preg_replace("/([0-9a-f]){32}/", "********************************", $query); // Hides all hashes
		}

		$query = htmlspecialchars($query, ENT_QUOTES, 'ISO-8859-1');
		$error = htmlspecialchars($error, ENT_QUOTES, 'ISO-8859-1');

		$trace = debug_backtrace();

		$level = 0;
		if ($trace[1]['function'] == "query" ) $level = 1;
		if ($trace[2]['function'] == "super_query" ) $level = 2;

		$trace[$level]['file'] = str_replace(root_dir, "", $trace[$level]['file']);
		
		$err = <<<HTML
<style type="text/css">
.top_error_mysql {
font-family:Myriad Pro;
font-style: normal;
color: #fff;
font-size:17px;
font-weight: bold;
padding:10px 20px 8px 10px;
text-shadow: 0 1px 1px rgba(0, 0, 0, 0.75);
background-color: #AB2B2D;
background-image: -moz-linear-gradient(top, #00A8D3, #0093C6);
background-image: -ms-linear-gradient(top, #00A8D3, #0093C6);
background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#00A8D3), to(#0093C6));
background-image: -webkit-linear-gradient(top, #00A8D3, #0093C6);
background-image: -o-linear-gradient(top, #00A8D3, #0093C6);
background-image: linear-gradient(top, #00A8D3, #0093C6);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00A8D3', endColorstr='#0093C6',GradientType=0 ); 
background-repeat: repeat-x;
border-top:1px solid #91D9EC;
border-bottom: 1px solid #006283;
}
.box_error_mysql {
color:#7F7F7F;
font-family:Myriad Pro;
font-size:14px;
color: #000;
margin: 10px;
border-bottom:1px solid #CECECE;
}

.box_error_mysql div {
padding: 0px 4px 6px 4px;
}
</style>
<div style="width: 100%;margin: 20px auto 0 auto; border: 1px solid #0095BF; background-color: #F5F5F5;border-radius:3px;">
<div class="top_error_mysql"><div>MySQL Error!</div></div>
<div class="box_error_mysql"><div><b>MySQL error</b> in file: <b>{$trace[$level]['file']}</b> at line <b>{$trace[$level]['line']}</b></div></div>
<div class="box_error_mysql"><div>Error Number: <b>{$error_num}</b></div></div>
<div class="box_error_mysql"><div>The Error returned was: <b>{$error}</b></div></div>
<div class="box_error_mysql"><div><b>SQL query:</b><br />{$query}</div></div>
</div>		
HTML;
	die($err);
	
	}
	
}