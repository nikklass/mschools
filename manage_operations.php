<?php

error_reporting(1);
@ini_set("display_errors", 1);
//error_reporting(-1);
ini_set('display_errors', 'On');

?>

<?php

	//check supplied tag
	if (isset($_REQUEST['tag']) && $_REQUEST['tag'] != ''){
	
		$tag = $_REQUEST['tag'];
				
	   /// require_once "../ussd/saf/utils.php"; 
		
		$db = new DBFunctions();
		
		$response = array ("tag" => $tag);
        
		//subscribe
		if ($tag == "send_email") {
	
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