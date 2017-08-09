<?php

/*
error_reporting(1);
@ini_set(‘display_errors’, 1);
error_reporting(-1);
ini_set('display_errors', 'On');
*/

?>

<?php
	class DBFunctions {
		
		private $db;
		
		function __construct(){
			require_once 'DBConnect.php';	
			
			$this->db = new DBConnect();
			$this->db->connect();
		}
		
		function __destruct(){
			
		}
		
		function removelastor($str) {
		  $startpos = strlen($str) - 2;	
		  $getstring = substr($str,$startpos);	
		  if ($getstring == "or") {	
			  return substr($str,0,$startpos);	
		  } else {	
			  return $str;	
		  }
		}
		
		function clean($value) {
				//function to check for both sql injection and cross site scripting
				 //Trim the value
				 $value = trim($value);
				// Stripslashes
				if (get_magic_quotes_gpc()) {
					$value = stripslashes($value);
				}
				 // Convert all &lt;, &gt; etc. to normal html and then strip these
				 $value = strtr($value,array_flip(get_html_translation_table(HTML_ENTITIES)));
				 // Strip HTML Tags
				 $value = strip_tags($value);
				// Quote the value
				$value = mysql_real_escape_string($value);
				$value = htmlspecialchars ($value);
				return $value;
		}
		
		public function storeUser($fullnames,$dob,$id_num){
			//$pin = random_number_generator(4);
			$digits = 4;
			srand ((double) microtime() * 10000000);	
			//Array of alphabets	
			$input = array (1,2,3,4,5,6,7,8,9);
			$random_generator="";// Initialize the string to store random numbers		
				for($i=1;$i<$digits+1;$i++){ // Loop the number of times of required digits	
					if(rand(1,2) == 1){// to decide the digit should be numeric or alphabet	
					// Add one random alphabet 
					$rand_index = array_rand($input);
					$random_generator .=$input[$rand_index]; // One char is added
					}else{
					// Add one numeric digit between 1 and 10
					$random_generator .=rand(1,10); // one number is added
					} // end of if else
				} // end of for loop 
			$pin = trim($random_generator);
			
			//$pin = $this->hashSSHA($pin);	
			//$hash = $this->hashSSHA($pin);	
			//$encrypted_pin = $pin["encrypted"];
			//$salt = $hash["salt"];
			//$qry = "UPDATE aar_users SET fullnames='$fullnames', PIN='$pin', dob='$dob'";
			//$qry .= ", created_at=NOW() WHERE id_num=$id_num";
			
			$qry = "INSERT INTO aar_users (fullnames,PIN, dob, id_num, created_at) VALUES ('$fullnames', '$pin', '$dob', '$id_num', NOW())";
			
			$result = mysql_query($qry);
			//echo $qry;
			
			if ($result){
				$uid = mysql_insert_id();
				$nresult = mysql_query("SELECT PIN FROM aar_users WHERE id=$uid");	
				return mysql_fetch_array($nresult); 
			} else {
				return false;	
			}
		}
		
		public function getUserByPIN($pin){
			$user_query = "SELECT u.id as id, PIN, fullnames, dob, points_total, points_bal, u.created_at as created_at";
			$user_query .= ", u.updated_at as updated_at, cover_start_at, cover_end_at, ic.name as cover_name  ";
			$user_query .= " FROM aar_users u LEFT JOIN aar_user_covers uc ON u.id=uc.user_id ";
			$user_query .= " LEFT JOIN aar_ins_covers ic ON uc.cover_id=ic.id ";
			$user_query .= " WHERE PIN='$pin'";
			$result = mysql_query($user_query);
			$no_of_rows = mysql_num_rows($result);
			if ($no_of_rows > 0){
				$result = mysql_fetch_array($result);
				return $result;
			} else {
				return false;	
			}
		}
		
		public function getUserByPhoneNumberPassword($phone_number, $password){
			//$password = md5($password);
			$user_query = "SELECT * FROM clients WHERE phone_number='$phone_number' AND password='$password'";
			$result = mysql_query($user_query);
			$no_of_rows = mysql_num_rows($result);
			if ($no_of_rows > 0){
				$result = mysql_fetch_array($result);
				return $result;
			} else {
				return false;	
			}
		}
		
		public function getAccountByUserID($user_id){
			//$password = md5($password);
			$query = "SELECT * FROM cash_account WHERE client_id = $user_id ";
			$result = mysql_query($query);
			$no_of_rows = mysql_num_rows($result);
			if ($no_of_rows > 0){
				$result = mysql_fetch_array($result);
				return $result;
			} else {
				return 0;	
				//$current_balance = -1;
				//$result = array("current_balance" => $current_balance);
				//return $result;
			}
		}
		
		
		public function getSchoolByCode($code){
			$query = "SELECT * FROM sch_ussd WHERE sch_id=$code";
			$result = mysql_query($query);
			$no_of_rows = mysql_num_rows($result);
			if ($no_of_rows > 0){
				$result = mysql_fetch_array($result);
				return $result;
			} else {
				return false;	
			}
		}
		
		public function getPointsUserById($id){
			$sql = "SELECT au.fullnames as fullnames, uc.points_balance as pointsbalance";
			$sql .= ", ic.name as covername FROM aar_ins_covers ic ";
			$sql .= " JOIN aar_user_covers uc ON ic.id=uc.cover_id ";
			$sql .= " JOIN aar_users au ON au.id=uc.user_id ";
			$sql .= " WHERE au.id=$id";
			$result = mysql_query($sql);
			$no_of_rows = mysql_num_rows($result);
			if ($no_of_rows > 0){
				$result = mysql_fetch_array($result);
				return $result;
			} else {
				return false;	
			}
		}
		
		public function getSchoolList(){
			$sql = "SELECT * FROM sch_ussd ORDER BY sch_id";
			$result = mysql_query($sql);
			$no_of_rows = mysql_num_rows($result);
			if ($no_of_rows > 0){
				$result = mysql_fetch_array($result);
				return $result;
			} else {
				return false;	
			}
		}
		
		public function getCenterVisits($id, $year){
			$sql = "SELECT ac.name as centerName, ac.points_used as pointsUsed";
			$sql .= ", cu.visit_at as visitDate FROM aar_cover_usage cu ";
			$sql .= " JOIN aar_centers ac ON ac.id=cu.center_id ";
			$sql .= " JOIN aar_user_covers uc ON uc.id=cu.user_cover_id ";
			$sql .= " JOIN aar_users au ON au.id=uc.user_id ";
			$sql .= " WHERE SUBSTR(cu.visit_at,1,4) = '$year' AND au.id=$id";
			$result = mysql_query($sql);
			$no_of_rows = mysql_num_rows($result);
			if ($no_of_rows > 0){
				return mysql_fetch_assoc($result);
			} else {
				return false;	
			}
		}
		
		function isUserExisted($id_num){
			$result = mysql_query("SELECT * FROM aar_users WHERE id_num='$id_num'");
			$no_of_rows = mysql_num_rows($result);
			if ($no_of_rows > 0){
				return true;
			} else {
				return false;	
			}
		}
		
		function random_number_generator($digits){
			srand ((double) microtime() * 10000000);	
			//Array of alphabets	
			$input = array (1,2,3,4,5,6,7,8,9);
			$random_generator="";// Initialize the string to store random numbers		
				for($i=1;$i<$digits+1;$i++){ // Loop the number of times of required digits	
					if(rand(1,2) == 1){// to decide the digit should be numeric or alphabet	
					// Add one random alphabet 
					$rand_index = array_rand($input);
					$random_generator .=$input[$rand_index]; // One char is added
					}else{
					// Add one numeric digit between 1 and 10
					$random_generator .=rand(1,10); // one number is added
					} // end of if else
				} // end of for loop 
			return trim($random_generator);
		
		} 
		
		function hashSSHA($pin){
			$salt = sha1(rand());	
			$salt = substr($salt,0,10);
			$encrypted = base64_encode(sha1($pin.$salt, true).$salt);
			$hash = array("salt" => $salt, "encrypted" => $encrypted);
			
			return $hash;
		}
		
		function checkHashSSHA($salt, $pin){
			$hash = base64_encode(sha1($pin.$salt, true).$salt);
			
			return $hash;
		}
		
		function mysql_date($thedate) {
			return date( 'Y-m-d H:i:s', $thedate );
		}
		
		function php_date($thedate) {
			return strtotime( $thedate );
		}
		function friendly_date($timestamp){
			return date( 'd-M-Y', $timestamp );
		}
		function format_num($num) {
			return number_format($num,0, '.', ',');
		}
		
		// format the date
		function adjustDate($format=false, $timestamp=false, $timezone=false)
		{
			if (!$format){ $format = 'd-M-Y, h:ia'; } 
			if (!$timezone){ $timezone = 'Africa/Nairobi'; }
			$userTimezone = new DateTimeZone(!empty($timezone) ? $timezone : 'GMT');
			$gmtTimezone = new DateTimeZone('GMT');
			$myDateTime = new DateTime(($timestamp!=false?date("r",(int)$timestamp):date("r")), $gmtTimezone);
			$offset = $userTimezone->getOffset($myDateTime);
			return date($format, ($timestamp!=false?(int)$timestamp:$myDateTime->format('U')) + $offset);
		}
	}
?>