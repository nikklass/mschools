<?php 
	ob_start();
	//require_once "../api/includes/DB_handler.php"; 
	require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "api/includes/DB_handler.php"; 
	require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "api/includes/Config.php"; 
?>

<?php

	/*
	mPDF: Generate PDF from HTML/CSS (Complete Code)
	*/
	
	require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "api/libs/mpdf/mpdf.php"; // Include mdpf
	
	$css_path = SITEPATH . 'admin/css/app/pdf_tables.css';
	$stylesheet = file_get_contents($css_path); // Get css content
	//echo "$stylesheet";
	
	//get supplied data
	$db = new DbHandler();
			
	$start_date = $_GET['start_date'];
	$end_date = $_GET['end_date'];
	$status = $_GET['status'];
	$stream = $_GET['stream'];
	$user_id = $_GET['user_id'];
	$admin = $_GET['admin'];
	$sch_id = $_GET['sch_id'];
	$current_class = $_GET['current_class'];
	$item_type = $_GET['item_type'];
	$reg_no = $_GET['reg_no'];
	$silent = $_GET['silent'];
	$year = $_GET['year'];
	$term = $_GET['term'];
	$student_id = $_GET['student_id'];
	$result_id = $_GET['result_id'];
	$fee_id = $_GET['fee_id'];
	
	
	if ($sch_id == UNDEFINED_TEXT){ $sch_id=""; }
	if ($stream == UNDEFINED_TEXT){ $stream=""; }
	if ($start_date == UNDEFINED_TEXT){ $start_date=""; }
	if ($end_date == UNDEFINED_TEXT){ $end_date=""; }
	if ($current_class == UNDEFINED_TEXT){ $current_class=""; }
	if ($reg_no == UNDEFINED_TEXT){ $reg_no=""; }
	if ($user_id == UNDEFINED_TEXT){ $user_id=""; }
	if ($admin == UNDEFINED_TEXT){ $admin=""; }
	if ($status == UNDEFINED_TEXT){ $status=""; }
	if ($item_type == UNDEFINED_TEXT){ $item_type=""; }
	if ($year == UNDEFINED_TEXT){ $year=""; }
	if ($term == UNDEFINED_TEXT){ $term=""; }
	if ($student_id == UNDEFINED_TEXT){ $student_id=""; }
	if ($result_id == UNDEFINED_TEXT){ $result_id=""; }
	if ($fee_id == UNDEFINED_TEXT){ $fee_id=""; }
		
	//get est data
	$sch_data_rows = $db->getSchoolGridListing("", $user_id, "", "", "", $sch_id, $admin);
	$sch_data_item = $sch_data_rows["rows"][0];
	$sch_name = $sch_data_item["name"];
	$sch_image = $sch_data_item["image"];
		
	$author = COMPANY_NAME;
	$keywords = "Pendo Schools, kenya, school, results, $sch_name";
	
	//echo "sch_name - $sch_name == author - $author == item_type - $item_type == fee_id - $fee_id"; exit; 
		
	//*********************** SELECT ITEM ***************************//
	if ($item_type == "fee_reports") { 
		
		$top_title = "Fee Report (" . $sch_name . ")"; 
		
		$pdf_filename = "fee report" . " " . $sch_name;
				
		if ($current_class) {
			$pdf_filename .=  " class " . $current_class;
		}
		
		if ($stream) {
			$pdf_filename .=  " stream " . $stream;
		}
		
		if ($reg_no) {
			$pdf_filename .=  " reg " . $reg_no;
		}
		
		if ($start_date) {
			$pdf_filename .=  " from " . $start_date;
		}
		
		if ($end_date) {
			$pdf_filename .=  " to " . $end_date;
		}
		
		$pdf_filename = $db->generate_seo_link($pdf_filename, $replace = '_', "", "");
	
		$pdf_filename .= ".pdf";
		
	}
	
	if ($item_type == "single_result") { 
		
		$top_title = "<h2>" . $sch_name . " &nbsp;&nbsp; - &nbsp;&nbsp; Student Result</h2>";
				
		$pdf_filename = "Student Result " . $sch_name;
				
		if ($result_id) {
			
			//get offer data
			$student_data_rows = $db->getResultsGridListing("", "", "", "", "", "", $result_id);
			$student_data = $student_data_rows['rows'][0];
			//print_r($student_data); 
			$student_full_names = $student_data["name"];
			$reg_no = $student_data["reg_no"];
			$current_class = $student_data["current_class"];
			$year = $student_data["year"];
			$term = $student_data["term"];

			$pdf_filename .=  " reg " . $reg_no . " term " . $term . " year " . $year . " class " . $current_class;
			
			$pdf_filename = $db->generate_seo_link($pdf_filename, $replace = '_', "", "");
	
			$pdf_filename .= ".pdf";
			
			//echo "$student_id - $pdf_filename - $item_type"; exit;
			
		}				 
		
	}
	
	//******************************* START SINGLE FEE REPORT ******************************************//
	
	if ($item_type == "single_fee") { 
		
		$top_title = "<h2>" . $sch_name . " &nbsp;&nbsp; - &nbsp;&nbsp; Student Fees</h2>";
				
		$pdf_filename = "Student Fees " . $sch_name;
				
		if ($fee_id) {
			
			//get fee data
			$fee_data_rows = $db->getFeesGridListing("", "", "", "", "", $fee_id, "", "", "", "", "", "", 1);
			$fee_data = $fee_data_rows['rows'][0];
			
			/*echo "<pre>";
			print_r($fee_data); 
			echo "</pre>";*/
			
			$student_full_names = $fee_data["name"];
			$reg_no = $fee_data["reg_no"];
			$current_class = $fee_data["current_class"];
			$year = $fee_data["year"];

			$pdf_filename .=  " reg " . $reg_no . " year " . $year . " class " . $current_class;
			
			$pdf_filename = $db->generate_seo_link($pdf_filename, $replace = '_', "", "");
	
			$pdf_filename .= ".pdf";
			
			//echo "$student_id - $pdf_filename - $item_type"; exit;
			
		}				 
		
	}
	
	//exit;
	
	//******************************* END PRODUCTS SOLD REPORT ******************************************//

	//*********************** END SELECT ITEM ***************************//		
	
	$pdf_header_logo = $est_image;
	
	//pdf configs
	$header_string = $est_name;
	
	// create HTML content
	if (($item_type == "sch_reports") && $sch_id) { 
		
		$file_path = SITEPATH . "admin/reports/fee_reports_items_pdf.php?sch_id=" . $est_id . "&offer_id=" . $offer_id;
	
	} else if ($item_type == "fee_reports") { 
		
		$file_path = SITEPATH . "admin/reports/fee_reports_pdf.php?sch_id=" . $sch_id . "&stream=" . $stream . "&start_date=" . $start_date . "&end_date=" . $end_date . "&status=" . $status . "&ccurrent_class=" . $current_class . "&user_id=" . $user_id . "&admin=" . $admin . "&rreg_no=" . $reg_no;
		
	} else if ($item_type == "single_result") { 
		
		$file_path = SITEPATH . "admin/reports/single_result.php?result_id=" . $result_id;
				
	} else if ($item_type == "single_fee") { 
		
		$file_path = SITEPATH . "admin/reports/single_fee.php?fee_id=" . $fee_id;
				
	} 
	
	//echo "file_path - $file_path";
	
	$html = $db->getWebpage($file_path);
	
	//echo "$html"; exit;
	
	// Setup PDF
	$tDate = $db->adjustDate("F j, Y, g:i a", time());
	$mpdf = new mPDF('utf-8', 'A4-L'); // New PDF object with encoding & page size
	$mpdf->setAutoTopMargin = 'stretch'; // Set pdf top margin to stretch to avoid content overlapping
	$mpdf->setAutoBottomMargin = 'stretch'; // Set pdf bottom margin to stretch to avoid content overlapping

	// PDF header content
	$mpdf->SetHTMLHeader('<div class="pdf-header">
							  <h3>' . $top_title . '</h3>
						  </div><hr>'); 
						  
	// PDF footer content                      
	$mpdf->SetHTMLFooter('<hr>
						  <div class="pdf-footer">
							<i>Printed on: ' . $tDate . ' &nbsp; - ' . COMPANY_NAME . '&nbsp;&nbsp;&nbsp; (<a href="' . CONTACT_WEBSITE . '">' . CONTACT_WEBSITE . '</a>)
							 &nbsp;&nbsp;<span class="right">Page: {PAGENO}</span>
							</i>
						  </div>'); 
	 
	//$mpdf->WriteHTML($stylesheet, 1); // Writing style to pdf
	$mpdf->WriteHTML($html); // Writing html to pdf
	
	//$mpdf->WriteHTML(file_get_contents('../css/app/invoice.html'));
	// FOR EMAIL
	//$content = $mpdf->Output('', 'S'); // Saving pdf to attach to email 
	//$content = chunk_split(base64_encode($content));
	
	//echo $mpdf; exit;
	//echo "$stylesheet  $html"; exit;

	$mpdf->Output($pdf_filename,'D'); // For Download
	exit;

?>