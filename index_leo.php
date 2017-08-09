<?php

error_reporting(1);
@ini_set(‘display_errors’, 1);
//error_reporting(-1);
ini_set('display_errors', 'On');

?>

<?php

if (isset($_REQUEST['tag']) && $_REQUEST['tag'] != ''){
	
	$tag = $_REQUEST['tag'];
	
	require_once "includes/DBFunctions.php";
	
	$db = new DBFunctions();
	
	$response = array ("tag" => $tag);
	
	if ($tag == "login") {

		$student_reg_no = trim($_REQUEST['student_reg_no']); 
		$school_id = trim($_REQUEST['school_id']); 
		
		$user = $db->getUserByUserNamePasswordSchool($student_reg_no, $school_id);
		
		if ($user != false){
			
			$response["error"]	 = false;
			$response["user"]["id"] = $user["id"];
			$response["user"]["reg_no"] = $user["reg_no"];
			$response["user"]["full_names"] = $user["full_names"];
			$response["user"]["mobile1"] = $user["mobile1"];
			$response["user"]["mobile2"] = $user["mobile2"];
			$response["user"]["student_profile"] = $user["student_profile"];
			$response["user"]["guardian_name"] = $user["guardian_name"];
			$response["user"]["guardian_address"] = $user["guardian_address"];
			$response["user"]["guardian_phone"] = $user["guardian_phone"];
			$last_updated = $db->php_date($user["last_updated"]);
			$last_updated = $db->friendly_date($last_updated);
			$response["user"]["last_updated"] = $last_updated;

			echo json_encode($response);
			
		} else {
			$response["error"]	= true;
			$response["error_msg"] = "Incorrect Details. Try again.";
			echo json_encode($response);
		}
	} else if ($tag == "register"){
		$fullnames = $_REQUEST['fullnames'];
		$dob = $_REQUEST['dob'];
		$id_num = $_REQUEST['id_num'];
		
		if ($fullnames=="" || $dob=="" || $id_num=="") {
			$response["error"]	 = true;
			$response["error_msg"] = "Please fil in all required data";
			echo json_encode($response);
		
		} else if ($db->isUserExisted($id_num)){
			$response["error"]	 = true;
			$response["error_msg"] = "User already exists";
			echo json_encode($response);	
		} else {
			
			$user = $db->storeUser($fullnames, $dob, $id_num);
			//print_r($user);
			if ($user){
				$response["error"]	 				= false;
				$response["user"]["pin"] 			= $user["PIN"];
				echo json_encode($response);	
			} else {
				$response["error"]	 = true;
				$response["error_msg"] = "Error occured in registration";
				echo json_encode($response);
			}
		}
		
	
	} else if ($tag == "get_student_marks"){
			
			if ($_REQUEST['reg_no']) { $reg_no = $_REQUEST['reg_no']; }
            if ($_REQUEST['school_id']) { $school_id = $_REQUEST['school_id']; }
			if ($_REQUEST['term']) { $term = $_REQUEST['term']; }
			if ($_REQUEST['year']) { $year = $_REQUEST['year']; }
			
			//main query
			$queryadd = "SELECT * FROM sch_results rs INNER JOIN sch_students st";
            $querryadd .= " ON rs.reg_no = st.reg_no WHERE rs.reg_no=$reg_no AND school_id=$school_id AND term=$term AND year=$year";
			
			$mresult = mysql_query($queryadd);
			
			$rows = array();
			
		   	$r = mysql_fetch_assoc($mresult);
			
			//initialize array
			$myArray = array();
			if ($r['mat']) { $mathsArray = array("subject" => 'Maths', "score" => $r['mat']); $myArray[] = $mathsArray; }
			if ($r['eng']) { $mathsArray = array("subject" => 'English', "score" => $r['eng']); $myArray[] = $mathsArray; }	
			if ($r['kis']) { $mathsArray = array("subject" => 'Kiswahili', "score" => $r['kis']); $myArray[] = $mathsArray; }
			
			if ($r['bio']) { $mathsArray = array("subject" => 'Biology', "score" => $r['bio']); $myArray[] = $mathsArray; }
			if ($r['chem']) { $mathsArray = array("subject" => 'Chemistry', "score" => $r['chem']); $myArray[] = $mathsArray; }	
			if ($r['phy']) { $mathsArray = array("subject" => 'Physics', "score" => $r['phy']); $myArray[] = $mathsArray; }
			
			if ($r['geo']) { $mathsArray = array("subject" => 'Geography', "score" => $r['geo']); $myArray[] = $mathsArray; }
			if ($r['his']) { $mathsArray = array("subject" => 'History', "score" => $r['his']); $myArray[] = $mathsArray; }	
			if ($r['RE']) { $mathsArray = array("subject" => 'Religious Education', "score" => $r['RE']); $myArray[] = $mathsArray; }
			
			if ($r['PE']) { $mathsArray = array("subject" => 'Physical Education', "score" => $r['PE']); $myArray[] = $mathsArray; }
			if ($r['BS']) { $mathsArray = array("subject" => 'Business Studies', "score" => $r['BS']); $myArray[] = $mathsArray; }	
			if ($r['agr']) { $mathsArray = array("subject" => 'Agriculture', "score" => $r['agr']); $myArray[] = $mathsArray; }
			
			if ($r['HS']) { $mathsArray = array("subject" => 'Home Science', "score" => $r['HS']); $myArray[] = $mathsArray; }
			if ($r['ara']) { $mathsArray = array("subject" => 'Arabic', "score" => $r['ara']); $myArray[] = $mathsArray; }	
			if ($r['ger']) { $mathsArray = array("subject" => 'German', "score" => $r['ger']); $myArray[] = $mathsArray; }
			
			if ($r['mus']) { $mathsArray = array("subject" => 'Music', "score" => $r['mus']); $myArray[] = $mathsArray; }
			if ($r['art']) { $mathsArray = array("subject" => 'Art', "score" => $r['art']); $myArray[] = $mathsArray; }	
			if ($r['comp']) { $mathsArray = array("subject" => 'Computer', "score" => $r['comp']); $myArray[] = $mathsArray; }	
				
			
			$rows['marks'] = $myArray;	
			$rows['mean_score'] = $r['mean_score'];
			$rows['grade'] = $r['grade'];
			$rows['year'] = $r['year'];
			$rows['term'] = $r['term'];		
			
			 print json_encode($rows);
			 	
	} else if ($tag == "get_student_fees"){
			
			if ($_REQUEST['reg_no']) { $reg_no = $_REQUEST['reg_no']; }
            if ($_REQUEST['school_id']) { $school_id = $_REQUEST['school_id']; }
			if ($_REQUEST['term']) { $term = $_REQUEST['term']; }
			if ($_REQUEST['year']) { $year = $_REQUEST['year']; }
			
			//main query
            $queryadd = "SELECT * FROM sch_fees sf INNER JOIN sch_students st";
            $querryadd .= " ON sf.reg_no = st.reg_no WHERE sf.reg_no=$reg_no AND school_id=$school_id AND term=$term AND year=$year";
			
			$mresult = mysql_query($queryadd);
			
			$rows = array();
			
		   	$r = mysql_fetch_assoc($mresult);

			$rows['sql'] = $querryadd;
			$rows['total_fees'] = $r['total_fees'];
			$rows['fees_bal'] = $r['fees_bal'];
			$rows['fees_paid'] = $r['fees_paid'];
			$rows['year'] = $r['year'];
			$rows['term'] = $r['term'];		
			
			 print json_encode($rows);	
			 
			 
	} else if ($tag == "get_school_list"){
		
			$page = 1;//default page
			$lperpage = 25; //default num records
			
			if ($_REQUEST['page']) { $page = $_REQUEST['page']; }
			if ($_REQUEST['recs']) { $lperpage = $_REQUEST['recs']; }
			if($_REQUEST['query']) {    $search_text = $_REQUEST['query']; }
			
			if($_REQUEST['school_name_sort']) { $school_name_sort = $_REQUEST['school_name_sort']; }
			if($_REQUEST['cat_sort']) { $cat_sort = $_REQUEST['cat_sort']; }
			if($_REQUEST['province_sort']) { $province_sort = $_REQUEST['province_sort']; }
						
			if($search_text) {
				//get search query from form
				$search_text = trim($_REQUEST['query']);
				$search_text = strtolower($search_text);

			}
			
			if ($search_text) {
				$search_text = $db->clean($search_text);
				$split_text = explode(" ",$search_text);
				$num_items = count($split_text);
				$full_search_text = "";
				for ($i=0;$i<$num_items;$i++) {
					$split_text[$i] = trim($split_text[$i]);
					$full_article_search_text .= " sch_name LIKE '%" . $split_text[$i] . "%' or province LIKE '%" . $split_text[$i] . "%' or category LIKE '%" . $split_text[$i] . "%' or";
				}
				//more than one search term i.e. spaces in between
				if ($num_items > 1){ 
					$full_article_search_text .= " sch_name LIKE '%" . $search_text . "%' or province LIKE '%" . $search_text . "%' or category LIKE '%" . $search_text . "%' or"; 
				} 
				//end more than one search term i.e. spaces in between
				$full_article_search_text = $db->removelastor($full_article_search_text);
			}			
			
			$searchcat= $_GET['searchcat'];
			$orderstyle= $_GET['orderstyle'];
		
			$offset = ($page - 1) * $lperpage;
			
			//main query
			$queryadd = "SELECT * FROM sch_ussd WHERE sch_name!='' AND sch_id!='' ";
			//set equivalent values to field submitted values
			
			//if search is done, add the query texts
			if ($search_text) { $queryadd .= " AND ($full_article_search_text) "; }

			if ($province_sort=='on') { $thecat="province"; }
			else if ($cat_sort=='on') { $thecat="category"; }
			else if ($school_name_sort=='on') { $thecat="sch_name"; }
			else { $thecat = "sch_name"; }
	
			$queryadd .= " ORDER BY $thecat "; 
			
			if ($orderstyle=='d') { $queryadd .= " DESC "; }

			$queryadd .= " LIMIT $offset,$lperpage";
			
			$sql = $queryadd ;
			
			$mresult = mysql_query($sql);
			
			$rows = array();
			
			//$rows['schools']['sql'] = $sql;
		   	while($r = mysql_fetch_assoc($mresult)) {
			 	$rows['schools'][] = $r;
		    }
			
			
			 print json_encode($rows);	
		
	} else if ($tag == "check_school_code"){
		
		$code = $_REQUEST['code'];
		
		$school = $db->getSchoolByCode($code);			
		
		if ($school != false){
			$response["error"] = false;
			$response["school"]["sch_name"] = $school["sch_name"];	
			$response["school"]["address"] = $school["address"];	
			$response["school"]["province"] = $school["province"];	
			$response["school"]["category"] = $school["category"];	
			$response["school"]["extra"] = $school["extra"];	
			$response["school"]["sch_profile"] = $school["sch_profile"];	
			$response["school"]["events_calender"] = $school["events_calender"];	
			$response["school"]["sms_welcome1"] = $school["sms_welcome1"];	
			$response["school"]["sms_welcome2"] = $school["sms_welcome2"];	
			$response["school"]["phone1"] = $school["phone1"];	
			$response["school"]["phone2"] = $school["phone2"];	
			$response["school"]["motto"] = $school["motto"];	
			$response["school"]["sch_id"] = $school["sch_id"];			
			echo json_encode($response);	
		} else {
			$response["error"] = true;
			$response["error_msg"] = "Incorrect Code";
			echo json_encode($response);
		}
		
	
		
	} else {
		$response["error"]	 = true;
		$response["error_msg"] = "Unknown 'tag'";
		echo json_encode($response);	
	}
	
	
} else {
	$response["error"] = true;;
	$response["error_msg"] = "Required parameter 'tag' is missing";	

	echo json_encode($response);
}

?>