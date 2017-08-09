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
        
        $fee_id = $_GET['fee_id'];

		//get fee data
		$fee_data_rows = $db->getFeesGridListing("", "", "", "", "", $fee_id, "", "", "", "", "", "", 1);
		$fee_data = $fee_data_rows['rows'][0];
		
		//get fee payments
		$fee_items_data = $fee_data["fee_payments"];
		
		/*echo "<pre>";
		print_r($fee_data); 
		echo "</pre>";*/
		
		$student_full_names = $fee_data["name"];
		$reg_no = $fee_data["reg_no"];
		$current_class = $fee_data["current_class"];
		$year = $fee_data["year"];
		$fees_paid = $fee_data["fees_paid_fmt"];
		$fees_paid_fmt2 = $fee_data["fees_paid_fmt2"];
		$fees_bal = $fee_data["fees_bal_fmt2"];
		$total_fees = $fee_data["total_fees_fmt2"];
							
		//generate report filter
		$report_filter = "";
		if ($current_class) { $report_filter = "  Class: <strong>$current_class</strong>"; }
		if ($reg_no) { $report_filter .= "  &nbsp; Reg No: <strong>$reg_no</strong>"; }
		if ($year) { $report_filter .= "  &nbsp; Year: <strong>$year</strong>"; }
				    
    ?>
	
   <?php
						        
		if (!$user_id) { $user_id = USER_ID; }
		//get orders data
		//$item_data = $db->getStudentResultsGridListing("", "", "", "", "", "", "", "", "", "", "", $result_id); 	
			
	?>
    </head>
    
    <body>
    
    <table width="100%" cellpadding="2" class="table table-striped">
                    
            <tr>
                <td colspan="5">Student: <strong><?=$student_full_names?></strong></td>
            </tr>
            
            <tr>
                <td colspan="5">
				
					<?=$report_filter?>
                    
                    <br><br>
                    
                    Opening Balance: <strong><?=$total_fees?></strong> &nbsp;&nbsp; Fees Paid: <strong><?=$fees_paid_fmt2?></strong> 
                    &nbsp;&nbsp; Outstanding Balance: <strong><?=$fees_bal?></strong>
                
                </td>
            </tr>

            <tr class="heading">
                <td width="10%" align="left" class="heading"><strong>ID</strong></td>
                <td width="20%" align="left" class="heading"><strong>Mode</strong></td>
                <td width="20%" align="left" class="heading"><strong>Paid At</strong></td>
                <td width="20%" align="left" class="heading"><strong>Paid By</strong></td>
                <td width="30%" align="right" class="heading"><strong>Amount</strong></td>
            </tr>
            
            <?php
										                                                                        
                foreach ($fee_items_data['rows'] as $key => $val) {
            
                    $id = $val["id"];
					$amount = $val["payment_amount_fmt"];
                    $mode = $val["payment_mode"];
					$paid_at = $val["payment_paid_at_fmt"];
					$paid_by = $val["payment_paid_by"];
                    
            ?>
                    
                    <tr>
                        <td><?=$id?></td>
                        <td><?=$mode?></td>
                        <td><?=$paid_at?></td>
                        <td><?=$paid_by?></td>
                        <td align="right"><?=$amount?></td>
                    </tr>
                
            <?php
            
                }
                
            ?>
        
        <tr>
            
            <td colspan="4" align="right"><strong>Total</strong></td>
            <td align="right"><strong><?=$fees_paid?></strong></td>
            
        </tr>
        
    </table>
    
 </body>
 </html>