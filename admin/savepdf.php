<?php 
	ob_start();
	include_once("api/includes/DB_handler.php"); 
	include_once("api/includes/Config.php"); 
	include_once("api/includes/funcs.php"); 
?>
<?php

	//============================================================+
	// File name   : example_006.php
	// Begin       : 2008-03-04
	// Last Update : 2013-05-14
	//
	// Description : Example 006 for TCPDF class
	//               WriteHTML and RTL support
	//
	// Author: Nicola Asuni
	//
	// (c) Copyright:
	//               Nicola Asuni
	//               Tecnick.com LTD
	//               www.tecnick.com
	//               info@tecnick.com
	//============================================================+
	
	/**
	 * Creates an example PDF TEST document using TCPDF
	 * @package com.tecnick.tcpdf
	 * @abstract TCPDF - Example: WriteHTML and RTL support
	 * @author Nicola Asuni
	 * @since 2008-03-04
	 */
	
	// Include the main TCPDF library (search for installation path).
	require_once('api/includes/tcpdf/tcpdf.php');
	
	//get supplied data
	$db = new DbHandler();
		
	$student_id = $_GET['student_id'];
	$sch_id = $_GET['sch_id'];
	$reg_no = $_GET['reg_no'];
	$year = $_GET['year'];
	$term = $_GET['term'];
	$user_id = $_GET['user_id'];
	$item_type = $_GET['item_type'];
	
	//get student data
	$student_data = $db->getStudentData($reg_no, $sch_id, "", "", $student_id);
	
	$student_names = $student_data["student_full_names"];
	$student_reg_no = $student_data["reg_no"];
	$student_class = $student_data["current_class"];
	$student_sch_id = $student_data["sch_id"];
	$stream = $student_data["stream"];
	
	//get school name
	$school_name = $db->getSchoolName($student_sch_id);
	
	//which item are we dealing with?
	if ($item_type == "results") { $title = "STUDENT RESULTS"; $subject = "Student Results"; $filename_append = "results"; }
	if ($item_type == "fees") { $title = "STUDENT FEES"; $subject = "Student Fees"; $filename_append = "fees"; }
	
	$pdf_filename = "student_". $filename_append . "_" . $student_sch_id . "_" . $student_reg_no . "_" . $year . ".pdf";
	
	$author = "Pendomedia";
	$keywords = "Student data, kenya, school, pendoschools, $school_name";
	
	if ($school_image){
		$pdf_header_logo = $school_image;
	} else {
		$pdf_header_logo = SITE_LOGO;
	}
	
	//pdf configs
	$header_string = $school_name;
	$top_title = $title;
	
	
	// Extend the TCPDF class to create custom Header and Footer
	class MYPDF extends TCPDF {
	
		//Page header
		/*public function Header() {
			// Logo
			$image_file = SITE_LOGO;
			$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
			// Set font
			$this->SetFont('helvetica', 'B', 14);
			// Title
			$this->Cell(0, 15, $school_name, 0, false, 'C', 0, '', 0, false, 'M', 'M');
		}*/
		
		public function Footer() {
			$this->SetY(-15);
			$this->SetFont('helvetica', 'I', 8);
			// Setting Date ( I have set the date here )
			//$tDate=date('l \t\h\e jS');
			$tDate = date("F j, Y, g:i a");
			$this->Cell(0, 10, 'Printed on : '.$tDate . ". Pendoschools.", 0, false, 'C', 0, '', 0, false, 'T', 'M');
		}
		
	}

	
	// create new PDF document
	//$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor($author);
	$pdf->SetTitle($title);
	$pdf->SetSubject($subject);
	$pdf->SetKeywords($keywords);
	
	// set default header data
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $top_title, $header_string);
	
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		require_once(dirname(__FILE__).'/lang/eng.php');
		$pdf->setLanguageArray($l);
	}

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

$pdf->Ln();

//$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(84, 84, 84)));

// create some HTML content
if ($item_type == "results") { 
	
	$file_path = SITEPATH . "api/includes/results_pdf.php?student_id=" . $student_id . "&year=" . $year . "&term=" . $term . "&reg_no=" . $reg_no . "&sch_id=" . $sch_id;

} else if ($item_type == "fees") { 
	
	$file_path = SITEPATH . "api/includes/fees_pdf.php?student_id=" . $student_id . "&year=" . $year . "&reg_no=" . $reg_no . "&sch_id=" . $sch_id;
	
}

$html = file_get_contents($file_path);

//$html = utf8_decode($html);

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
//$pdf->Output($pdf_filename, 'I');
//$pdf->Output($_SERVER['DOCUMENT_ROOT'] . $pdf_filename, 'F'); //create pdf in specified dir
$pdf->Output($pdf_filename, 'D'); //download dialog for pdf

//============================================================+
// END OF FILE
//============================================================+
