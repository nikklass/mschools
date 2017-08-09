<!DOCTYPE html>
<html>
	<head>
    <title></title>
    
	<?php 
    
        ob_start();
    
        include_once "../api/includes/DB_handler.php";
        include_once "../api/includes/Config.php"; 
        
        //include css
        include_once "../css/app/pdf_tables.php"; 
    
    ?>
    
	<?php 
    
        $db = new DbHandler();
        
        $result_id = $_GET['result_id'];

		//get offer data
		$student_data_rows = $db->getResultsGridListing("", "", "", "", "", "", $result_id);
		$student_data = $student_data_rows['rows'][0];
		
		/*echo "<pre>";
		print_r($student_data); 
		echo "</pre>";*/
		
		$student_full_names = $student_data["name"];
		$reg_no = $student_data["reg_no"];
		$current_class = $student_data["current_class"];
		$year = $student_data["year"];
		$term = $student_data["term"];
		$points = $student_data["points"];
		$grade = $student_data["grade"];
		$mean_score = $student_data["mean_score"];
		$total_score = $student_data["total_score"];
		$mean_grade = $student_data["grade"];
		$mean_points = $student_data["points"];
							
		//generate report filter
		$report_filter = "";
		if ($current_class) { $report_filter = "  Class: <strong>$current_class</strong>"; }
		if ($reg_no) { $report_filter .= "  &nbsp; Reg No: <strong>$reg_no</strong>"; }
		if ($year) { $report_filter .= "  &nbsp; Year: <strong>$year</strong>"; }
		if ($term) { $report_filter .= "  &nbsp; Term: <strong>$term</strong>"; }
				    
    ?>
	
   <?php
						        
		if (!$user_id) { $user_id = USER_ID; }
		//get orders data
		$item_data = $student_data["student_results"]; 	
		
		/*echo "<pre>";
		print_r($item_data); 
		echo "</pre>";*/
			
	?>
    </head>
    
    <body>
    
    <table width="100%" cellpadding="2" class="table table-striped">
                    
            <tr>
                <td colspan="3">Student: <strong><?=$student_full_names?></strong></td>
            </tr>
            
            <tr>
                <td colspan="3">
				
					<?=$report_filter?>
                    
                    <br><br>
                    
                    Total Score: <strong><?=$total_score?></strong> &nbsp;&nbsp; Mean Score: <strong><?=$mean_score?></strong> 
                    &nbsp;&nbsp; Mean Points: <strong><?=$mean_points?></strong> &nbsp;&nbsp; Mean Grade: <strong><?=$mean_grade?> </strong>
                
                </td>
            </tr>

            <tr class="heading">
                <td width="40%" align="left" class="heading"><strong>Subject</strong></td>
                <td width="30%" align="right" class="heading"><strong>Score</strong></td>
                <td width="30%" align="right" class="heading"><strong>Grade</strong></td>
            </tr>
            
            <?php
										                                                                        
                foreach ($item_data['rows'] as $key => $val) {
            
                    $subject = $val["name"];
					$score = $val["score"];
                    $grade = $val["grade"];
					$points = $val["points"];
                    
            ?>
                    
                    <tr>
                        <td><?=$subject?></td>
                        <td align="right"><?=$score?></td>
                        <td align="right"><?=$grade?></td>
                    </tr>
                
            <?php
            
                }
                
            ?>
        
        <tr>
            
            <td><strong>Total</strong></td>
            <td align="right"><strong><?=$total_score?></strong></td>
            <td align="right"><strong><?=$mean_grade?></strong></td>
            
        </tr>
        
    </table>
    
 </body>
 </html>