<?php

error_reporting(1);
@ini_set("display_errors", 1);
//error_reporting(-1);
ini_set('display_errors', 'On');

?>

<?php

	log_this(print_r($_REQUEST,true));
	
	function getconfig($param_name){
			
			$qry = "SELECT param_text from sms_gw.sysconfig where param_name='$param_name' limit 0,1";
			$value = "";
			$res = do_qry($qry);
			
			while ($row = mysql_fetch_object($res)){
					$value = $row->param_text;
			}
	
			return $value;
			
	}


  	function do_qry($sql,$logmsg=true){
        
		if($logmsg) {
			log_this($sql);    
		}
	   
		return mysql_query($sql);
	   		
	}
        
	function log_this($lmsg)
	{  
	  
		$flog = sprintf("/data/log/pendo_school_%s.log",date("Ymd"));
	  	$tlog = sprintf("\n%s%s",date("Y-m-d H:i:s T: "),$lmsg);
	   	$f = fopen($flog, "a");
	  	fwrite($f,$tlog);
	  	fclose($f);
		
	}

	function sdp_que_out_app($dest, $msg, $cat, $sid,$delay=0)  // With Minutes Delay HACK
	{
		$source_qry= sprintf("SELECT * FROM sms_gw.sdp_service_map where service_id='%s'",$sid);
	    $rw1 = mysql_fetch_object(do_qry($source_qry));
	    $source = $rw1->service_code ;
		$msg = str_replace('\n', '\r\n', $msg);
		$sql=sprintf("INSERT INTO sms_gw.sdp_outgoing_que(dest,msg_text,category,target_date,route,charge,msg_type,service_id,priority) VALUES('%s','%s','%s',DATE_ADD(NOW(),INTERVAL $delay MINUTE),'%s','%s','%s','%s','0')",  $dest,$msg,$cat,$source,'0','text',$sid);
	    do_qry($sql);
	   
	    return true ;                          
	   
	}


	function mpesa_que_checkout($msisdn,$mid,$amount,$pid){
		
	 	$qry = sprintf("INSERT INTO mpesa.checkout_que(target_date,msisdn,amount,merchant_id,passkey,prod_id) VALUES(NOW(),'$msisdn','$amount','$mid',DEFAULT,'$pid')");   
		do_qry($qry);
		
		return true ;
		
	}

	function sdp_que_sub_app($msisdn,$pid,$delay=0)  // With Delay HACK
    {
            
	  	$spid = getconfig("sdp_sp_id"); 
		$sp_passwd = getconfig("sdp_passwd");
	   	$sql=sprintf("INSERT INTO sms_gw.sdp_sub_man_que(msisdn,date_stamp,target_date,prod_id,sp_id,sp_passwd,priority) VALUES('%s',NOW(),DATE_ADD(NOW(),INTERVAL $delay SECOND),'%s','%s','%s','0')",  $msisdn,$pid,$spid,$sp_passwd);
	   	do_qry($sql);
	   
	   	return true ;
		                          
	}

	//check supplied tag
	if (isset($_REQUEST['tag']) && $_REQUEST['tag'] != ''){
	
		$tag = $_REQUEST['tag'];
		
		require_once "includes/DBFunctions.php";
		
	   /// require_once "../ussd/saf/utils.php"; 
		
		$db = new DBFunctions();
		
		$response = array ("tag" => $tag);
        
		//subscribe
		if ($tag == "subscribe") {
	
			$mobile= trim($_REQUEST['mobile']); 
	  
			if (sdp_que_sub_app($mobile,"MDSP2000075075") != false){
				$response["error"]     = false;
			   
				$response["user"]["mobile"] = $mobile ;
			   
	
				echo json_encode($response);
			} else {
				$response["error"]    = true;
				$response["error_msg"] = "Incorrect Details. Try again.";
				echo json_encode($response);
			}
		}
    
		//checkout
		else if ($tag == "checkout") {
	
			$mobile= trim($_REQUEST['mobile']); 
			$mid = trim($_REQUEST['mid']);
			$amount = trim($_REQUEST['amount']);
			$pid = trim($_REQUEST['pid']);  
			
			if (mpesa_que_checkout($mobile,$mid,$amount,$pid) != false){
				$response["error"]     = false;
			   
				$response["user"]["mobile"] = $mobile ;
	
				echo json_encode($response);
				
			} else {
				
				$response["error"]    = true;
				$response["error_msg"] = "Incorrect Details. Try again.";
				echo json_encode($response);
				
			}
		}
    
		//send sms
		else if ($tag == "send_sms") {
	
			$mobile= trim($_REQUEST['mobile']); 
			
			$msg = trim($_REQUEST['msg']); 
		   
		 	//6013952000077398     
	  		//6013952000078328     
			
			if (sdp_que_out_app($mobile,$msg,"PMApp","6013952000077398") != false){
				$response["error"]     = false;
			   
				$response["user"]["mobile"] = $mobile ;
			   
	
				echo json_encode($response);
			} else {
				$response["error"]    = true;
				$response["error_msg"] = "SMS Submission FAILED. Please Try Again";
				echo json_encode($response);
			}
     
		//default
		} else {
			$response["error"]	 = true;
			$response["error_msg"] = "Unknown 'tag'";
			echo json_encode($response);	
		}
	
	//no tag sent
	} else {
		$response["error"] = true;;
		$response["error_msg"] = "Required parameter 'tag' is missing";	
	
		echo json_encode($response);
	}

?>