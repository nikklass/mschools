<?php
	class DBConnect {
				
		function __contruct(){
			
		}
		
		function __destruct(){
			
		}
		
		public function connect(){
			require_once 'Config.php';
			
			$con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("db error occured");
			
			mysql_select_db(DB_DATABASE) or die(mysql_error());
			
			return $con;
			
		}
		
		public function close(){
			mysql_close();	
		}
		
		
	}
?>