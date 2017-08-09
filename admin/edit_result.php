<?php 
	include_once("api/includes/DB_handler.php"); 
	include_once("includes/funcs.php"); 
	include_once("api/includes/Config.php");
	
	if (!isset($_SESSION)) session_start();
	
	//$admin = true;
	//$show_bootstrap_dialog = true;
		
	$db = new DbHandler();
		
	$result_id = $arg_two;
	
	//get the statuses
	//$query = "SELECT id, full_names, reg_no, guardian_name, sch_id, student_profile, mobile1, mobile2, guardian_name, guardian_phone, guardian_address FROM sch_students WHERE id = ? ";
	$query = "SELECT sb.name, sri.score FROM sch_results_items sri JOIN sch_subjects sb ON sri.subject_code=sb.code WHERE sri.id = ? ";
	$stmt = $db->conn->prepare($query);
	$stmt->bind_param("i", $result_id);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($subject_name, $score);
	$stmt->fetch();
	
	$page_title = "Edit Result - " . $subject_name;
	
	$score = $db->format_num($score, 0);
		
?>

<?php 
	//if user has read permissions
	if (!(HAS_EDIT_RESULT_PERMISSION || SCHOOL_ADMIN_USER)) 
	{
		//user is not allowed to access page
		$page = SITEPATH."error";
		header("Location: $page"); 
		exit();
	}
?>	

<!DOCTYPE html>
<html>

<head>
    
	<?php include_once("includes/head_scripts.php"); ?>
    
</head>

<body>
    
        <form class="form-horizontal form-edit-result inputform">
                                                                  
            <div id="wrapper_form">
            
            	<div class="form-group">
                
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                        <h3>Edit Result</h3>
                    </div>
                
                </div>
                                                            
                <div class="form-group">
                    <label for="subject_name" class="col-sm-3 control-label">Subject</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control invisible-text-box" name="score" value="<?=$subject_name?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="score" class="col-sm-3 control-label">Score</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="score" value="<?=$score?>">
                    </div>
                </div>
               
                <div class="form-group">
                
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                    <button class="btn btn-info col-sm-12">Submit</button>
                    </div>
                    
                </div>
                
            </div>
                                                                        
        </form>


	<?php //include_once("includes/js_popup.php"); ?>

</body>

</html>