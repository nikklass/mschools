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
	<table cellpadding="3">
    
		
        
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
                <td colspan="4"><strong><?=$student_names?> &nbsp;&nbsp;&nbsp; Reg: <?=$student_reg_no?></strong></td>
            </tr>
            
            <tr>
                <td colspan="4">Class: <?=$student_class?> <?=$stream?></td>
            </tr>
            
            <tr>
                <td colspan="4">Year: <?=$year?></td>
            </tr>
            
            <tr>
                <td colspan="4"> <hr></td>
            </tr>
        
            <tr>
                <td width="25%" align="left"><strong>Amount</strong></td>
                <td width="25%" align="left"><strong>Payment Date</strong></td>
                <td width="25%" align="left"><strong>Payment Mode</strong></td>
                <td width="25%" align="left"><strong>Paid By</strong></td>
            </tr>
		
		<?php
            
            //get student fees summary
            $fees_summary = $db->getStudentFees($student_sch_id, $student_id, $year, $student_reg_no);
			
			$total_fees = $fees_summary['fees_summary']["total_fees"];
			$fees_bal = $fees_summary['fees_summary']["fees_bal"];
			$fees_paid = $fees_summary['fees_summary']["fees_paid"];
			
			
			//get student fees detils
            $fees = $db->getStudentFeePayments("", $student_id, "", $year, $user_id, $student_sch_id, $student_reg_no); 
                                                                    
            foreach ($fees as $key => $val) {
        
                $id = $val["payment_id"];
                $amount = $val["payment_amount"];
                $mode = $val["payment_mode"];
                $paid_by = $val["payment_paid_by"];
                $paid_at = $val["payment_paid_at"];
                
        ?>
                
                <tr>
                    <td><?=$amount?></td>
                    <td><?=$paid_at?></td>
                    <td><?=$mode?></td>
                    <td><?=$paid_by?></td>
                </tr>
            
        <?php
        
            }
			
        ?>
        
        <tr>
            <td colspan="4"> <hr></td>
        </tr>
        
        <tr>
            <td><strong>Total Paid</strong></td>
            <td colspan="3"><strong><?=$fees_paid?></strong></td>
        </tr>
        
        <tr>
            <td><strong>Total Fees</strong></td>
            <td colspan="3"><strong><?=$total_fees?></strong></td>
        </tr>
        
        <tr>
            <td><strong>Balance</strong></td>
            <td colspan="3"><strong><?=$fees_bal?></strong></td>
        </tr>
        
    </table>