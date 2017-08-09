<?php
#	BuildNav for Dreamweaver MX v0.2
#              10-02-2002
#	Alessandro Crugnola [TMM]
#	sephiroth: alessandro@sephiroth.it
#	http://www.sephiroth.it
#	
#	Function for navigation build ::
function buildNavigation($pageNum_Recordset1,$totalPages_Recordset1,$prev_Recordset1,$next_Recordset1,$separator=" | ",$max_links=10, $show_page=true)
{
                GLOBAL $maxRows_rsResults,$totalRows_rsResults;
	$pagesArray = ""; $firstArray = ""; $lastArray = "";
	if($max_links<2)$max_links=2;
	if($pageNum_Recordset1<=$totalPages_Recordset1 && $pageNum_Recordset1>=0)
	{
		if ($pageNum_Recordset1 > ceil($max_links/2))
		{
			$fgp = $pageNum_Recordset1 - ceil($max_links/2) > 0 ? $pageNum_Recordset1 - ceil($max_links/2) : 1;
			$egp = $pageNum_Recordset1 + ceil($max_links/2);
			if ($egp >= $totalPages_Recordset1)
			{
				$egp = $totalPages_Recordset1+1;
				$fgp = $totalPages_Recordset1 - ($max_links-1) > 0 ? $totalPages_Recordset1  - ($max_links-1) : 1;
			}
		}
		else {
			$fgp = 0;
			$egp = $totalPages_Recordset1 >= $max_links ? $max_links : $totalPages_Recordset1+1;
		}
		if($totalPages_Recordset1 >= 1) {
			#	------------------------
			#	Searching for $_GET vars
			#	------------------------
			$_get_vars = '';			
			if(!empty($_GET) || !empty($HTTP_GET_VARS)){
				$_GET = empty($_GET) ? $HTTP_GET_VARS : $_GET;
				foreach ($_GET as $_get_name => $_get_value) {
					if ($_get_name != "pageNum_rsResults") {
						$_get_vars .= "&$_get_name=$_get_value";
					}
				}
			}
			$successivo = $pageNum_Recordset1+1;
			$precedente = $pageNum_Recordset1-1;
			$firstArray = ($pageNum_Recordset1 > 0) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_rsResults=$precedente$_get_vars\">$prev_Recordset1</a>" :  "$prev_Recordset1";
			# ----------------------
			# page numbers
			# ----------------------
			for($a = $fgp+1; $a <= $egp; $a++){
				$theNext = $a-1;
				if($show_page)
				{
					$textLink = $a;
				} else {
					$min_l = (($a-1)*$maxRows_rsResults) + 1;
					$max_l = ($a*$maxRows_rsResults >= $totalRows_rsResults) ? $totalRows_rsResults : ($a*$maxRows_rsResults);
					$textLink = "$min_l - $max_l";
				}
				$_ss_k = floor($theNext/26);
				if ($theNext != $pageNum_Recordset1)
				{
					$pagesArray .= "<a href=\"$_SERVER[PHP_SELF]?pageNum_rsResults=$theNext$_get_vars\">";
					$pagesArray .= "$textLink</a>" . ($theNext < $egp-1 ? $separator : "");
				} else {
					$pagesArray .= "$textLink"  . ($theNext < $egp-1 ? $separator : "");
				}
			}
			$theNext = $pageNum_Recordset1+1;
			$offset_end = $totalPages_Recordset1;
			$lastArray = ($pageNum_Recordset1 < $totalPages_Recordset1) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_rsResults=$successivo$_get_vars\">$next_Recordset1</a>" : "$next_Recordset1";
		}
	}
	return array($firstArray,$pagesArray,$lastArray);
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>

<?php require_once('../wireconn.php'); 
if(!isset($_SESSION))
	{session_start();}
?>

<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsResults = 10;
$pageNum_rsResults = 0;
if (isset($_GET['pageNum_rsResults'])) {
  $pageNum_rsResults = $_GET['pageNum_rsResults'];
}
$startRow_rsResults = $pageNum_rsResults * $maxRows_rsResults;

if (isset($_SESSION['MM_Username'])) {
  $colname_rsUsers = $_SESSION['MM_Username'];
}

mysql_select_db($database_wireconn, $wireconn);
$query_rsResults = sprintf("SELECT * FROM sch_%s_results", $colname_rsUsers);
$rsResults = mysql_query($query_rsResults, $wireconn) or die(mysql_error());
$row_rsResults = mysql_fetch_assoc($rsResults);
$totalRows_rsResults = mysql_num_rows($rsResults);

$colname_rsUsers = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUsers = $_SESSION['MM_Username'];
}
mysql_select_db($database_wireconn, $wireconn);
$query_rsUsers = sprintf("SELECT sch_id, sch_name, address, password FROM sch_ussd WHERE sch_id = %s", GetSQLValueString($colname_rsUsers, "int"));
$rsUsers = mysql_query($query_rsUsers, $wireconn) or die(mysql_error());
$row_rsUsers = mysql_fetch_assoc($rsUsers);
$totalRows_rsUsers = mysql_num_rows($rsUsers);

$queryString_rsResults = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsResults") == false && 
        stristr($param, "totalRows_rsResults") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsResults = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsResults = sprintf("&totalRows_rsResults=%d%s", $totalRows_rsResults, $queryString_rsResults);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Pendo Schools</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#apDiv1 {
	position:absolute;
	left:585px;
	top:32px;
	width:489px;
	height:33px;
	z-index:1;
	font-size: 24px;
	color: #FFF;
	font-family: "Times New Roman", Times, serif;
	font-weight: bold;
}
</style>
</head>
<body>
<div id="mainPan">
  <div id="leftPan">
    <div id="leftTopPan"> <a href="http://www.free-css.com/"><img src="images/logo.png" title="Pendo Schools" alt="Pendo Schools" width="145" height="87" border="0" /></a> </div>
    <ul><li><a href="index.php">Home</a></li>
    <li><a href="upload_stud.php">Add Students</a></li>
       <li><a href="upload.php">Upload Fees</a></li>
      <li><a href="upload_res.php">Upload Results</a></li>
      <li><a href="students.php">View Students</a></li>
      <li><a href="fees.php">View Fees</a></li>
      <li><a href="results.php">View Results</a></li>
     
      <li><a href="profile.php">School Profile</a></li> 
      <!-- <li><a href="password.php">Change Password</a></li>
      <li class="contact"><a href="contact.php">Contact</a></li>  -->
    </ul>
    <?php if ($totalRows_rsUsers == 0) { // Show if recordset empty ?>
  <form action="" method="post">
    <h2>Schools login</h2>
    
    Enter Pendo School Code:
    <input name="Your name" type="text" id="Yourname" />
    <label>Password:</label>
    <input name="password" type="password" id="password" />
    <input name="" type="submit" class="button" value="Login" />
  </form>
  <?php } // Show if recordset empty ?>
<h2>services</h2>
    <p><img src="images/school1.png" width="194" height="284" /></p>
  </div>
  <div id="rightPan">
    <table width="747" border="0" cellspacing="0" cellpadding="0" style="color:#FFF">
      <tr>
        <td colspan="2"><h1>Welcome&nbsp;&nbsp;to <?php echo $row_rsUsers['sch_name']; ?></h1></td>
      </tr>
      <tr>
        <td colspan="2"><?php echo $row_rsUsers['address']; ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <h2> Schools Results</h2>
    <p><div id="content">
				<!-- Box -->
				<div class="box">
					<!-- Box Head -->
					<div class="box-head"></div>
					<!-- End Box Head -->	

					<!-- Table -->
					<div class="table">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td>Reg No</td>
                          <td>Year</td>
                          <td>Term</td>
                          <td>Matds</td>
                          <td>Eng</td>
                          <td>Kis</td>
                          <td>Bio</td>
                          <td>Chem</td>
                          <td>Phy</td>
                          <td>Geo</td>
                          <td>His</td>
                          <td>RE</td>
                          <td>PE</td>
                          <td>BS</td>
                          <td>agr</td>
                          <td>HS</td>
                          <td>Arab</td>
                          <td>ger</td>
                          <td>music</td>
                          <td>Art</td>
                          <td>Comp</td>
                          <td>Mean_score</td>
                          <td>Grade</td>
                          <td>Control</td>
                        </tr>
                        <?php do { ?>
                          <tr>
                            <td><?php echo $row_rsResults['reg_no']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['year']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['term']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['mat']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['eng']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['kis']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['bio']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['chem']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['phy']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['geo']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['his']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['RE']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['PE']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['BS']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['agr']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['HS']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['ara']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['ger']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['mus']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['art']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['comp']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['mean_score']; ?>&nbsp; </td>
                            <td><?php echo $row_rsResults['grade']; ?>&nbsp; </td>
                            
                            <td><a href="result_edit.php?id=<?php echo $row_rsResults['res_id']; ?>" class="ico edit">Edit</a></td>
                          </tr>
                          <?php } while ($row_rsResults = mysql_fetch_assoc($rsResults)); ?>
                      </table>
                      <br />
                      <!-- Pagging -->
						<div class="pagging">
							<div class="left"><?php 
# variable declaration
$prev_rsResults = "« Previous";
$next_rsResults = "Next »";
$separator = "|";
$max_links = 10;
$pages_navigation_rsResults = @buildNavigation($pageNum_rsResults,$totalPages_rsResults,$prev_rsResults,$next_rsResults,$separator,$max_links,true); 

print $pages_navigation_rsResults[0]; 
?>
					    <?php print $pages_navigation_rsResults[1]; ?> <?php print $pages_navigation_rsResults[2]; ?></div>
						</div>
						<!-- End Pagging -->
						
				  </div>
					<!-- Table -->
					
				</div>
				<!-- End Box -->

				
				<!-- Box -->
	  <div class="box">
		  <!-- Box Head --><!-- End Box Head --></div>
				<!-- End Box -->

			</div>&nbsp;</p></p>
  </div>
</div>
<div id="footermainPan">
  <div id="footerPan">
    <ul>
      <li><a href="http://www.free-css.com/">Home</a>|</li>
      <li><a href="http://www.free-css.com/">About us</a> |</li>
      <li><a href="http://www.free-css.com/">Network</a>|</li>
      <li><a href="http://www.free-css.com/">Submission</a> |</li>
      <li><a href="http://www.free-css.com/">Archives</a> |</li>
      <li>Support|</li>
      <li><a href="http://www.free-css.com/">Contact</a></li>
    </ul>
    <p class="copyright">©gPendo Schools all right reaserved</p>
  </div>
</div>
</body>
</html>
<?php
mysql_free_result($rsResults);

mysql_free_result($rsUsers);
?>
