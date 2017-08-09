<?php 
	ob_start();
	require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "api/includes/DB_handler.php"; 
	//require_once "../api/includes/DB_handler.php"; 
	//require_once "../api/includes/Config.php"; 
	require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "api/includes/Config.php"; 


	$db = new DbHandler();
	
	/** PHPExcel */
	//require_once('../api/includes/PHPExcel/PHPExcel.php');
	require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "api/includes/PHPExcel/PHPExcel.php"; 
	
	//create phpexcel object
	$excel = new PHPExcel();
	
	//get submitted params
	$no_pagination = 1; //dont paginate results, fetch all data	
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
	
	if ($start_date == UNDEFINED_TEXT) { $start_date = ""; }
	if ($end_date == UNDEFINED_TEXT) { $end_date = ""; }
	if ($status == UNDEFINED_TEXT) { $status = ""; }
	if ($stream == UNDEFINED_TEXT) { $stream = ""; }
	if ($user_id == UNDEFINED_TEXT) { $user_id = ""; }
	if ($admin == UNDEFINED_TEXT) { $admin = ""; }
	if ($sch_id == UNDEFINED_TEXT) { $sch_id = ""; }
	if ($current_class == UNDEFINED_TEXT) { $current_class = ""; }
	if ($item_type == UNDEFINED_TEXT) { $item_type = ""; }
	if ($reg_no == UNDEFINED_TEXT) { $reg_no = ""; }
	if ($year == UNDEFINED_TEXT) { $year = ""; }
	if ($term == UNDEFINED_TEXT) { $term = ""; }
	if ($student_id == UNDEFINED_TEXT) { $student_id = ""; }
	if ($result_id == UNDEFINED_TEXT) { $result_id = ""; }
	if ($fee_id == UNDEFINED_TEXT) { $fee_id = ""; }
	
	//get school data
	$sch_data_rows = $db->getSchoolGridListing("", $user_id, "", "", "", $sch_id, $admin);
	$sch_data_item = $sch_data_rows["rows"][0];
	$sch_name = $sch_data_item["name"];
	$sch_image = $sch_data_item["image"];
	//end school data
	
	//document properties
	$excel->getProperties()->setCreator($sch_name);
	$excel->getProperties()->setLastModifiedBy($sch_name);
	
	
	//set active sheet
	$excel->setActiveSheetIndex(0);
	
		
	//**************************** start mpesa excel generation ***************************************************
	
	if ($item_type == "mpesa_reports") {
			
		$top_title = "MPESA report" . " - " . $sch_name;
		
		if ($start_date || $end_date) { $top_title .=  " ("; }
		
		if ($start_date) {
			$top_title .=  "from " . $start_date;
		}
		
		if ($end_date) {
			$top_title .=  "to " . $end_date;
		}
		
		if ($start_date || $end_date) { $top_title .=  ")"; }
		
		//document properties
		$excel->getProperties()->setTitle($top_title);
		$excel->getProperties()->setSubject($top_title);
		$excel->getProperties()->setDescription($top_title);
		
		$xls_filename = "mpesa report" . " " . $sch_name;
		
		if ($start_date) {
			$xls_filename .=  " from " . $start_date;
		}
		
		if ($end_date) {
			$xls_filename .=  " to " . $end_date;
		}
		
		$xls_filename = $db->generate_seo_link($xls_filename, $replace = '_', "", "");
	
		$xls_filename .= ".xls";
	
		//get mpesa data
		$item_data = $db->fetchMPESAInbox($sch_id, "", "", "", "", "", $user_id, $admin, $start_date, $end_date, "", "", "", "", $no_pagination);
		//print_r($item_data); exit;
		
		//loop thru data values, start from third row in excel file
		$i = 4;
		
		foreach ($item_data["rows"] as $key => $val) {
			
			$current_class = $val["current_class"] . " " . $val["stream"];
			
			$excel->getActiveSheet()
			
				->setCellValue('A' . $i, $val["name"])
				->setCellValue('B' . $i, $val["sender_no"])
				->setCellValue('C' . $i, $val["mpesa_code"])
				->setCellValue('D' . $i, $val["amount"])
				->setCellValue('E' . $i, $val["received_at_fmt"])
				->setCellValue('F' . $i, $val["reg_no"])
				->setCellValue('G' . $i, $val["student_full_names"])
				->setCellValue('H' . $i, $current_class);
				
			$i++;
			
		}
				
		//set widths
		$excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		
		//SET table HEADERS
		$excel->getActiveSheet()
			->setCellValue('A1',  $top_title)
			->setCellValue('A3', 'Sender')
			->setCellValue('B3', 'Sender No')
			->setCellValue('C3', 'MPESA COde')
			->setCellValue('D3', 'Amount')
			->setCellValue('E3', 'Received At')
			->setCellValue('F3', 'Reg No')
			->setCellValue('G3', 'Student Name')
			->setCellValue('H3', 'Class');
			
		//merge top title
		$excel->getActiveSheet()->mergeCells('A1:H1');
		
		//styling
		$excel->getActiveSheet()->getStyle('A1')->applyFromArray(
			array(
				'font' => array(
					'size' => 24,
				)
			)
		);
		
		$excel->getActiveSheet()->getStyle('A3:H3')->applyFromArray(
			array(
				'font' => array(
					'bold' => true,
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				)
			)
		);
			
	}	
	
	//**************************** end mpesa excel generation ***************************************************
	
	
	
	//**************************** start fee payments excel generation ***************************************************
	
	if ($item_type == "fee_reports") {
			
		$top_title = "Fees Payments report" . " - " . $sch_name;
		
		if ($start_date || $end_date) { $top_title .=  " ("; }
		
		if ($start_date) {
			$top_title .=  "from " . $start_date;
		}
		
		if ($end_date) {
			$top_title .=  "to " . $end_date;
		}
		
		if ($start_date || $end_date) { $top_title .=  ")"; }
		
		//echo $top_title;
		
		//document properties
		$excel->getProperties()->setTitle($top_title);
		$excel->getProperties()->setSubject($top_title);
		$excel->getProperties()->setDescription($top_title);
		
		$xls_filename = "fees payments report" . " " . $sch_name;
		
		if ($start_date) {
			$xls_filename .=  " from " . $start_date;
		}
		
		if ($end_date) {
			$xls_filename .=  " to " . $end_date;
		}
		
		$xls_filename = $db->generate_seo_link($xls_filename, $replace = '_', "", "");
	
		$xls_filename .= ".xls";
	
		//get fees payments data
		$item_data = $db->getStudentFeePayments("", "", "", $year, $user_id, $sch_id, $reg_no, "", $admin, "", "", $no_pagination, $start_date, $end_date, $status);
		
		//loop thru data values, start from third row in excel file
		$i = 4;
		
		foreach ($item_data["rows"] as $key => $val) {
			
			$current_class = $val["current_class"] . " " . $val["stream"];
			
			$excel->getActiveSheet()
			
				->setCellValue('A' . $i, $val["name"])
				->setCellValue('B' . $i, $current_class)
				->setCellValue('C' . $i, $val["payment_mode"])
				->setCellValue('D' . $i, $val["payment_paid_at_fmt"])
				->setCellValue('E' . $i, $val["payment_paid_by"])
				->setCellValue('F' . $i, $val["payment_amount"]);
				
			$i++;
			
		}
				
		//set widths
		$excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		
		//SET table HEADERS
		$excel->getActiveSheet()
			->setCellValue('A1',  $top_title)
			->setCellValue('A3', 'Student Name')
			->setCellValue('B3', 'Class')
			->setCellValue('C3', 'Mode')
			->setCellValue('D3', 'Paid At')
			->setCellValue('E3', 'Paid By')
			->setCellValue('F3', 'Amount');
			
		//merge top title
		$excel->getActiveSheet()->mergeCells('A1:F1');
		
		//styling
		$excel->getActiveSheet()->getStyle('A1')->applyFromArray(
			array(
				'font' => array(
					'size' => 24,
				)
			)
		);
		
		$excel->getActiveSheet()->getStyle('A3:F3')->applyFromArray(
			array(
				'font' => array(
					'bold' => true,
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				)
			)
		);
			
	}	
	
	//**************************** end fee payments excel generation ***************************************************
	
	
	
	
	//**************************** start results excel generation ***************************************************
	
	if ($item_type == "result_reports") {
		
			
		$top_title = "Results report" . " - " . $sch_name;
		
		if ($start_date || $end_date || $current_class || $stream || $term || $year) { 
			$top_title .=  " ("; 
		}
		
		if ($current_class) {
			$top_title .=  " Class " . $current_class . " ";
		}
		
		if ($stream) {
			$top_title .=  " Stream " . $stream . " ";
		}
		
		if ($term) {
			$top_title .=  " Term " . $term . " ";
		}
		
		if ($year) {
			$top_title .=  " Year " . $year . " ";
		}
				
		if ($start_date) {
			$top_title .=  " From " . $start_date . " ";
		}
		
		if ($end_date) {
			$top_title .=  " To " . $end_date . " ";
		}
		
		if ($start_date || $end_date || $current_class || $stream || $term || $year) { 
			$top_title .=  ")"; 
		}
		
		//echo $top_title;
		
		//document properties
		$excel->getProperties()->setTitle($top_title);
		$excel->getProperties()->setSubject($top_title);
		$excel->getProperties()->setDescription($top_title);
		
		$xls_filename = "results report" . " " . $sch_name;
		
		if ($current_class) {
			$xls_filename .=  " class " . $current_class;
		}
		
		if ($stream) {
			$xls_filename .=  " stream " . $stream;
		}
		
		if ($term) {
			$xls_filename .=  " term " . $term;
		}
		
		if ($year) {
			$xls_filename .=  " year " . $year;
		}	
	
		if ($start_date) {
			$xls_filename .=  " from " . $start_date;
		}
		
		if ($end_date) {
			$xls_filename .=  " to " . $end_date;
		}
		
		$xls_filename = $db->generate_seo_link($xls_filename, $replace = '_', "", "");
	
		$xls_filename .= ".xls";
	
		//get fees payments data
		$item_data = $db->getResultsGridListing($sch_id, $current_class, $stream, $reg_no, $year, $term, "", "", $user_id, "", "", "", "", $no_pagination, $start_date, $end_date);
		
		/*echo "<pre>";
		print_r($item_data); 
		echo "</pre>";	*/	
		
		//********************** get all unique subjects from results
		
		$unique_subjects_data = array();
		
		foreach ($item_data["rows"] as $result_item)
		{
			
			foreach ($result_item["student_results"]["rows"] as $result)
		  	{
			 
			   	$code = $result["code"];
				$name = $result["name"];
				
				$tmp = array();
			   	$tmp["code"] = $code;
			   	$tmp["name"] = $name;
			 
			   	array_push($unique_subjects_data, $tmp);
			 
		  	}			
		  
		}
		$unique_subjects_data = array_unique($unique_subjects_data, SORT_REGULAR);
		
		//********************** get all unique subjects from results
		
				
		//********************** get all students in school into array
		
		$students_data = array();
		 
		foreach ($item_data["rows"] as $student_item)
		{
		  
			$tmp = array();
			
			$tmp["name"] = $student_item["name"];
			$tmp["student_id"] = $student_item["student_id"];
			$tmp["total_score"] = $student_item["total_score"];
			$tmp["mean_score"] = $student_item["mean_score"];
			$tmp["grade"] = $student_item["grade"];
			$tmp["points"] = $student_item["points"];
			$tmp["reg_no"] = $student_item["reg_no"];
			
			array_push($students_data, $tmp);
		  
		}
		
		//********************** get all students in school into array
		
		
		//TABLE TAG
		/*echo "<table class='table table-striped'>";
		
		//TABLE HEADER
		echo "	<thead>";
		
		//print table header
		echo "		<tr>";
		
				//student name header
				echo "<th align='left'>Reg.</th>";
				echo "<th align='left'>Student Name</th>";
			
				//subjects header
				foreach ($unique_subjects_data as $subject)
				{
				 
					$code = $subject["code"];
					$name = $subject["name"];
					
					//print each column
					echo "<th align='left'>$name</th>";
				 
				}	
			
				//totals header
				echo "<th align='left'>Total</th>";
				echo "<th align='left'>Avg.</th>";
				echo "<th align='left'>Points</th>";
				echo "<th align='left'>Grade</th>";
			
		
		echo "		</tr>";
		
		//END TABLE HEADER
		echo "	</thead>";
		
		
		//TABLE BODY
		echo "	<tbody>";
		
		
		
				//students data
				foreach ($students_data as $student)
				{
					
					//print data 
					echo "<tr>";
			
					//student name data
					echo "	<td align='left'>" . $student["reg_no"] . "</td>";
					echo "	<td align='left'>" . $student["name"] . "</td>";
				
					//subjects data
					foreach ($unique_subjects_data as $subject)
					{
					 
						$code = $subject["code"];
						$student_id = $student["student_id"];
						//get student score for subject
						$score_data = $db->fetchSubjectScore("", $student_id, $code);
						$score = $score_data["score"];
						if ($score) { $score = $db->format_num($score, 0); } else { $score = "-"; }
						
						//print each column
						echo "<td align='left'>$score</td>";
					 
					}	
				
					//totals data
					echo "	<td align='left'>" . $student["total_score"] . "</td>";
					echo "	<td align='left'>" . $student["mean_score"] . "</td>";
					echo "	<td align='left'>" . $student["points"] . "</td>";
					echo "	<td align='left'>" . $student["grade"] . "</td>";
			
					echo "</tr>";
					
				}
		
		
		
		//END TABLE BODY
		echo "	</tbody>";
		
		//END TABLE TAG
		echo "</table>";*/
		
		//get number of columns
		/*$static_fields = 6;
		$dynamic_fields = count($unique_subjects_data);
		$total_fields = $dynamic_fields; 
		
		$first_letter = 65 + 2; // add to first 2 columns
		$last_letter = ($first_letter + $total_fields) - 1;
				
		for($i = $first_letter; $i <= $last_letter; $i++)
		{
		  echo "item - " . chr($i) . "<br>";
		}*/
		
		
		
		/*echo "<pre>";
		print_r($unique_subjects_data); 
		echo "</pre>";
		
		
		exit;*/
		
		
		//get last char
		$static_fields = 6;
		$dynamic_fields = count($unique_subjects_data);
		$total_fields = $static_fields + $dynamic_fields; 
		
		$first_letter = 65; // A
		$last_letter = ($first_letter + $total_fields) - 1;
		$last_char = chr($last_letter);
		//echo "last_char - $last_char"; exit;
		//end last char
		
				
		//SET COLUMN DATAS
		 
		//loop thru data values, start from third row in excel file
		$i = 4;
		
		$excel_obj = $excel->getActiveSheet();
		
		/*echo "<pre>";
		print_r($students_data); 
		echo "</pre>";
		
		
		exit;*/
		
		//students data
		foreach ($students_data as $student)
		{
			
			$first_letter = 65 + 2;//start from letter C (67)
			
			//student data
			//first two columns
			$excel_obj->setCellValue("A" . $i, $student["reg_no"]);
			//$resp .= "A $i == " . $student["reg_no"] . " \t";
			
			$excel_obj->setCellValue("B" . $i, $student["name"]);
			//$resp .= "B $i == " . $student["name"] . " \t";
			
			//subjects data
			//middle columns
			foreach ($unique_subjects_data as $subject)
			{
				
				$code = $subject["code"];
				$student_id = $student["student_id"];
				//get student score for subject
				$score_data = $db->fetchSubjectScore("", $student_id, $code);
				$score = $score_data["score"];
				if ($score) { $score = $db->format_num($score, 0); } else { $score = "-"; }
				
				//print each column
				$letter = chr($first_letter);
				$excel_obj->setCellValue("$letter" . $i, $score);
				
				//$resp .= "$letter $i == $score \t ";
								
				$first_letter++;
			 
			}	
			
			//last four columns		
			//totals data
			$letter = chr($first_letter++);
			$excel_obj->setCellValue("$letter" . $i, $student["total_score"]);
			//$resp .= "$letter $i == " . $student["total_score"] . " \t";
			
			$letter = chr($first_letter++);
			$excel_obj->setCellValue("$letter" . $i, $student["mean_score"]);
			//$resp .= "$letter $i == " . $student["mean_score"] . " \t";
			
			$letter = chr($first_letter++);
			$excel_obj->setCellValue("$letter" . $i, $student["points"]);
			//$resp .= "$letter $i == " . $student["points"] . " \t";
			
			$letter = chr($first_letter++);
			$excel_obj->setCellValue("$letter" . $i, $student["grade"]);
			//$resp .= "$letter $i == " . $student["grade"] . " \t";
			
			//$resp .= " |||| <br>";
			
			$i++;
			
		}
		
		
		
		//echo $resp;
		
		//exit;
		
		//END SET COLUMN DATAS
		

		
		//START SET COLUMN WIDTHS
		
		$first_letter = 65 + 2;//start from letter C (67)
		
		$excel_obj->getColumnDimension('A')->setAutoSize(true);
		$excel_obj->getColumnDimension('B')->setAutoSize(true);
		
		//subjects header	
		foreach ($unique_subjects_data as $subject)
		{
			
			//print each column
			$letter = chr($first_letter);
			$excel_obj->getColumnDimension("$letter")->setAutoSize(true);
			
			$first_letter++;
		 
		}	
		
		//last four columns
		$letter = chr($first_letter++);
		//$excel_obj->getColumnDimension("$letter")->setWidth(20);
		$excel_obj->getColumnDimension("$letter")->setAutoSize(true);
		
		$letter = chr($first_letter++);
		//$excel_obj->getColumnDimension("$letter")->setWidth(20);
		$excel_obj->getColumnDimension("$letter")->setAutoSize(true);
		
		$letter = chr($first_letter++);
		//$excel_obj->getColumnDimension("$letter")->setWidth(20);
		$excel_obj->getColumnDimension("$letter")->setAutoSize(true);
		
		$letter = chr($first_letter++);
		//$excel_obj->getColumnDimension("$letter")->setWidth(20);
		$excel_obj->getColumnDimension("$letter")->setAutoSize(true);
		
		//END SET COLUMN WIDTHS
		
		
		
		//SET TABLE HEADERS
		
		$excel_obj->setCellValue('A1',  $top_title);
		
		//start at row 3
		$i = 3;
		$first_letter = 65 + 2;//start from letter C (67)
		
		//set first two columns
		$excel_obj->setCellValue('A' . $i,  "Reg No");
		$excel_obj->setCellValue('B' . $i,  "Student Name");
		
		//subjects header	
		//dynamic middle columns
		foreach ($unique_subjects_data as $subject)
		{
			
			$code = $subject["code"];
			$name = $subject["name"];
			
			//print each column
			$letter = chr($first_letter);
			$excel_obj->setCellValue("$letter" . $i, $name);
			
			$first_letter++;
		 
		}	
		
		//set last four columns
		$letter = chr($first_letter++);
		$excel_obj->setCellValue("$letter" . $i, "Total Score");
		
		$letter = chr($first_letter++);
		$excel_obj->setCellValue("$letter" . $i, "Mean Score");
		
		$letter = chr($first_letter++);
		$excel_obj->setCellValue("$letter" . $i, "Points");
		
		$letter = chr($first_letter++);
		$excel_obj->setCellValue("$letter" . $i, "Grade");
		
		//END SET TABLE HEADERS	
			
		
		//AUTOSIZE MERGED COLUMNS
		
		/*$excel_obj->calculateColumnWidths();
		
		//SET COLUMN WIDTHS TO FALSE
		// Set setAutoSize(false) so that the widths are not recalculated
		foreach(range('A1', $last_char.'1') as $columnID) {
			$excel_obj->getColumnDimension($columnID)->setAutoSize(false);
		}*/
		//END SET COLUMN WIDTH TO FALSE
		
		//END AUTOSIZE MERGED COLUMNS
		
			
		//merge top title
		$excel_obj->mergeCells('A1:'.$last_char.'1');
		
		//styling
		$excel_obj->getStyle('A1')->applyFromArray(
			array(
				'font' => array(
					'size' => 24,
				)
			)
		);
		
		$excel_obj->getStyle('A3:'.$last_char.'3')->applyFromArray(
			array(
				'font' => array(
					'bold' => true,
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				)
			)
		);
			
	}	
	
	//**************************** end results excel generation ***************************************************
	
	
	
	
	
	//output an excel file
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="' . $xls_filename . '"');
	header('Cache-Control: max-age=0');
	$xlsWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel5');	
	$xlsWriter->save('php://output'); // Write file to the browser
	
	//redirect to browser (download)
	/*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="' . $fileName . '"');
	header('Cache-Control: max-age=0');
	$xlsxWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007'); //write to file
	$xlsxWriter->save('php://output'); //output to php output instead of filename*/
		
	
?>