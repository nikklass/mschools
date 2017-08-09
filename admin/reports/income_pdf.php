<?php 

	ob_start();

	include_once "../api/includes/DB_handler.php";
	include_once "../api/includes/Config.php"; 

?>
    
	<?php 
    
        $db = new DbHandler();
        
        $est_id = $_GET['est_id'];
        $offer_id = $_GET['offer_id'];
		$start_date = $_GET['start_date'];
		$end_date = $_GET['end_date'];
		$status = $_GET['status'];
		$client_id = $_GET['client_id'];
		$product_category_id = $_GET['product_category_id'];
		$product_id = $_GET['product_id'];
		$user_id = $_GET['user_id'];
		$admin = $_GET['admin'];
		$gender = $_GET['gender'];
		$min_age = $_GET['min_age'];
		$max_age = $_GET['max_age'];
    
    ?>
	
   <?php
		
		//get report title
		$report_title = "";   
				
		if ($offer_id) {
			
			//get offer data
			$offer_data = $db->getOffers("", "", "", "", "", "", $offer_id); 
			$offer_data_item = $offer_data["rows"][0];
			$offer_name = $offer_data_item["name"];

			$report_title .=  " " . $offer_name;
			
		}

		if ($client_id) {
			$report_title .=  " client - " . $client_id;
		}
		
		if ($product_category_id) {
			$product_data_rows = $db->getProductCategories("", $product_category_id); 
			$product_cat_data = $product_data_rows["rows"][0];
			$product_cat_name = $product_cat_data["name"];

			$report_title .=  " category - " . $product_cat_name;
		}
		
		if ($product_id) {
			$product_data_rows = $db->getEstProductsListing($product_id, $est_id); 
			$product_data = $product_data_rows["rows"][0];
			$product_name = $product_data["name"];
			
			$report_title .=  " product - " . $product_name;
		}
		
		if ($start_date) {
			$report_title .=  " from - " . $start_date;
		}
		
		if ($end_date) {
			$report_title .=  " to - " . $end_date;
		}
		
		if ($min_age) {
			$report_title .=  " from - " . $min_age;
		}
		
		if ($max_age) {
			$report_title .=  " to - " . $max_age;
		}
		//end report title
						        
		//get income data
		$item_data = $db->getIncomeReports($est_id, $offer_id, "", "", "", "", $user_id, "", $start_date, $end_date, $client_id, $product_category_id, $admin, $gender, $min_age, $max_age); 
		//echo "data - $est_id - $offer_id - $user_id - $start_date, $end_date, $client_id, $product_category_id, $admin, $gender, $min_age, $max_age == ";
		//print_r($item_data); 
		
		$est_name = $item_data["est_name"];
		$offer_name = $item_data["offer_name"];
		if (!$offer_id) { $offer_name = "All Offers"; }
		$qtySumFmt = $item_data["qtySumFmt"];
		$totalSumFmt = $item_data["totalSumFmt"];
		$totalCommissionSumFmt = $item_data["commissionTotalSumFmt"];
		$totalClubSumFmt = $item_data["clubTotalSumFmt"];
		$expired_at_edit = $item_data["expired_at_edit"];
			
	?>
    
    <table width="100%" cellpadding="2" class="table table-striped">
            
           <?php if ($report_title) { ?>
            
                    <tr>
                        <td colspan="12"><strong><?=$report_title?></strong></td>
                    </tr>
                    
                    <tr>
                        <td colspan="12"><hr></td>
                    </tr>
            
            <?php } ?>
        
            <tr>
            
                <td  align="left"><strong>Product</strong></td>
                <td  align="left"><strong>Category</strong></td>
                <td  align="left"><strong>Order ID</strong></td>
                <td  align="left"><strong>Order Date</strong></td>
                <td  align="left"><strong>Offer Name</strong></td>
                <td  align="left"><strong>Client ID</strong></td>
                <td  align="left"><strong>Gender</strong></td>
                <td  align="left"><strong>Age</strong></td>
                <td width="8%" align="right"><strong>Qty</strong></td>
                <td width="8%" align="right"><strong>Total (Ksh)</strong></td>
                <td width="8%" align="right"><strong>Comm. (Ksh)</strong></td>
                <td width="8%" align="right"><strong>Est Total (Ksh)</strong></td>                
                
            </tr>
            
            <?php
										                                                                        
                foreach ($item_data['rows'] as $key => $val) {
            
                    $order_id = $val["order_id"];
					$client_id = $val["client_id"];
                    $status_name = $val["status_name"];
					$gender = $val["gender"];
					$offer_name = $val["offer_name"];
					$order_date = $val["order_date"];
					$product_name = $val["product_name"];
					$product_category_name = $val["product_category_name"];
					$qty_fmt = $val["qty_fmt"];	
					$age = $val["age"];					
					$product_total_fmt = $val["total_fmt"];
					$club_total_fmt = $val["club_total_fmt"];
					$commission_fmt = $val["commission_fmt"];
                    $submitted_at_edit = $val["submitted_at_edit"];
                    
            ?>
                    
                    <tr>
                        <td><?=$product_name?></td>
                        <td><?=$product_category_name?></td>
                        <td><?=$order_id?></td>
                        <td><?=$submitted_at_edit?></td>
                        <td><?=$offer_name?></td>
                        <td><?=$client_id?></td>
                        <td><?=$gender?></td>
                        <td><?=$age?></td>
                        <td align="right"><?=$qty_fmt?></td>
                        <td align="right"><?=$product_total_fmt?></td>
                        <td align="right"><?=$commission_fmt?></td>
                        <td align="right"><?=$club_total_fmt?></td>
                    </tr>
                
            <?php
            
                }
                
            ?>
        
        <tr>
            <td colspan="12"> <hr></td>
        </tr>
        
        <tr>
            
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="3" align="right"><strong>Totals</strong></td>
            <td align="right"><strong><?=$qtySumFmt?></strong></td>
            <td align="right"><strong><?=$totalSumFmt?></strong></td>
            <td align="right"><strong><?=$totalCommissionSumFmt?></strong></td>
            <td align="right"><strong><?=$totalClubSumFmt?></strong></td>
            
        </tr>
        
    </table>