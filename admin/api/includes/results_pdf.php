<?php 
	ob_start();
	include_once("DB_handler.php"); 
	include_once("Config.php"); 
	include_once("funcs.php"); 
?>

<?php 

	$db = new DbHandler();
	
	$student_id = $_GET['student_id'];
	$sch_id = $_GET['sch_id'];
	$reg_no = $_GET['reg_no'];
	$year = $_GET['year'];
	$term = $_GET['term'];
	$user_id = $_GET['user_id'];

?>
	<table width="70%" cellpadding="2">
    
		
        
		<?php
			
			//get student data
			$student_data = $db->getStudentData($reg_no, $sch_id, "", "", $student_id);
			
			$student_names = $student_data["student_full_names"];
			$student_reg_no = $student_data["reg_no"];
			$student_class = $student_data["current_class"];
			$student_sch_id = $student_data["sch_id"];
			$stream = $student_data["stream"];
			
        ?>
        
            <tr>
                <td colspan="3"><strong><?=$student_names?> &nbsp;&nbsp;&nbsp; Reg: <?=$student_reg_no?></strong></td>
            </tr>
            
            <tr>
                <td colspan="3">Class: <?=$student_class?> <?=$stream?></td>
            </tr>
            
            <tr>
                <td>Year: <?=$year?></td>
                <td colspan="2">Term: <?=$term?></td>
            </tr>
            
            <tr>
                <td colspan="3"> <hr></td>
            </tr>
        
            <tr>
                <td width="50%" align="left"><strong>Subject</strong></td>
                <td width="30%" align="left"><strong>Score</strong></td>
                <td width="20%" align="left"><strong>Grade</strong></td>
            </tr>
		
		<?php
            
            //get student results
            $results = $db->getStudentResultsGridListing($sch_id, $reg_no, $year, $term, "", "", "", "", $student_id, $user_id); 
			
			$total_score = $results["total_score"];
			$mean_grade = $results["mean_grade"];
			$mean_points = $results["mean_points"];
                                                                    
            foreach ($results['rows'] as $key => $val) {
        
                $id = $val["id"];
                $score = $val["score"];
                $grade = $val["grade"];
                $points = $val["points"];
                $name = $val["name"];
                
        ?>
                
                <tr>
                    <td><?=$name?></td>
                    <td><?=$score?></td>
                    <td><?=$grade?></td>
                </tr>
            
        <?php
        
            }
			
        ?>
        
        <tr>
            <td colspan="3"> <hr></td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td><strong><?=$total_score?></strong></td>
            <td></td>
        </tr>
        <tr>
            <td><strong>Points</strong></td>
            <td><strong><?=$mean_points?></strong></td>
            <td></td>
        </tr>
        <tr>
            <td><strong>Mean Grade</strong></td>
            <td><strong><?=$mean_grade?></strong></td>
            <td></td>
        </tr>
        
    </table>