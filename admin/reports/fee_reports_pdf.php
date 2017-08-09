<?php 

	ob_start();

	include_once "../api/includes/DB_handler.php";
	include_once "../api/includes/Config.php"; 

?>
    
	<?php 
    
        $db = new DbHandler();
        
        $sch_id = $_GET['sch_id'];
        $stream = $_GET['stream'];
		$start_date = $_GET['start_date'];
		$end_date = $_GET['end_date'];
		$current_class = $_GET['ccurrent_class'];
		$reg_no = $_GET['rreg_no'];
		$user_id = $_GET['user_id'];
		$admin = $_GET['admin'];
		
		//generate report filter
		$report_filter = "";
		if ($current_class) { $report_filter = "  Class: <strong>$current_class</strong>"; }
		if ($stream) { $report_filter .= "  Stream: <strong>$stream</strong>"; }
		if ($reg_no) { $report_filter .= "  Reg No: <strong>$reg_no</strong>"; }
		if ($start_date) { $report_filter .= "  From: <strong>$start_date</strong>"; }
		if ($end_date) { $report_filter .= "  To: <strong>$end_date</strong>"; }
		if (!$report_filter) { $report_filter .= "  <strong>All Records</strong>"; }
				
		    
    ?>
	
   <?php
		echo $sch_id;				        
		if (!$user_id) { $user_id = USER_ID; }
		//get orders data
		$item_data = $db->getFeesGridListing($sch_id, $current_class, $stream, $reg_no, "", "", $user_id, "", "", "", $admin, 1, $start_date, $end_date); 
		echo "$sch_id, $current_class, $stream, $reg_no, $user_id, $admin, $start_date, $end_date, $status";
		print_r($item_data);
		
		$total_sum = $item_data["totalSumFmt"];
		
			
	?>
    
    <table width="100%" cellpadding="2" class="table table-striped">
                    
            <tr>
                <td colspan="7">Report Filter: <?=$report_filter?></td>
            </tr>
            
            <tr>
                <td colspan="7"><hr></td>
            </tr>
        
            <tr>
                <td width="10%" align="left"><strong>Reg No.</strong></td>
                <td width="20%" align="left"><strong>Student</strong></td>
                <td width="15%" align="left"><strong>Class</strong></td>
                <td width="10%" align="left"><strong>Year</strong></td>
                <td width="15%" align="left"><strong>Paid At</strong></td>
                <td width="15%" align="left"><strong>By</strong></td>
                <td width="15%" align="right"><strong>Amount (Ksh)</strong></td>
            </tr>
            
            <?php
										                                                                        
                foreach ($item_data['rows'] as $key => $val) {
            
                    $reg_no = $val["reg_no"];
					$student_name = $val["name"];
                    $amount_fmt = $val["amount_fmt"];
					$current_class = $val["current_class"];
                    $year = $val["year"];
					$paid_at = $val["paid_at"];
					$paid_by = $val["paid_by"];
                    
            ?>
                    
                    <tr>
                        <td><?=$reg_no?></td>
                        <td><?=$student_name?></td>
                        <td><?=$current_class?></td>
                        <td><?=$year?></td>
                        <td><?=$paid_at?></td>
                        <td><?=$paid_by?></td>
                        <td align="right"><?=$amount_fmt?></td>
                    </tr>
                
            <?php
            
                }
                
            ?>
        
        <tr>
            <td colspan="7"> <hr></td>
        </tr>
        
        <tr>
            
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><strong>Totals</strong></td>
            <td align="right"><strong><?=$total_sum?></strong></td>
            
        </tr>
        
    </table>