<?php
//ini_set('display_errors',0); 
//error_reporting(0);
header("Cache-Control: private, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // A date in the past

/*////////////////////////////////////////////////////////////////////////////////
Includes
///////////////////////////////////////////////////////////////////////////////*/
	require('_config.php');
	require_once("phpauthent/phpauthent_core.php");
	require_once("phpauthent/phpauthent_config.php");
	require_once("phpauthent/phpauthentadmin/locale/".$phpauth_language);
	require_once("_functions_common.php");
	
/*////////////////////////////////////////////////////////////////////////////////
Page Protection
///////////////////////////////////////////////////////////////////////////////*/
	$usersArray = array("administrator");
	$groupsArray = array("admin","employee","payroll","supervisor");
	pageProtect($usersArray,$groupsArray);

/*////////////////////////////////////////////////////////////////////////////////
Variables
///////////////////////////////////////////////////////////////////////////////*/	
	$user = getUsername();
	
//get and set initial variables
	$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
	if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}

	$db_table="invoice_data";
	$page_title="Invoices Awaiting Approval";	
//Edit or upload page
	$edit_page="employee_invoice_upload.php";	

	$masked_jobnumber = $_GET['j'];
	$jobnumber = xor_this($masked_jobnumber);
	$delete_record=$_GET['delete_record'];
	
	//$jobnnumber=$_GET['jobnumber'];
	$path=$vi_path;
	$db_table="vendor_invoices";
	$page_title="Unapproved Invoices";
	
	//Edit or upload page
	$edit_page="./employee_invoice_upload.php";	
	
	//Sets the columns visible in the table format (column name=>display name) special ("edit,"amount","price","del","delete","filename")
	$columns=array("date_entered"=>"Date","vendor"=>"Vendor","invoice_number"=>"Inv #","amount"=>"Amount","division"=>"Div","status"=>"Status","supervisor"=>"supervisor","filename"=>"","approval_link"=>"Link");
	
	  // if (isEnabled(array("administrator"),array("admin","payroll")))
    // {
       	// $columns=array("edit"=>"Edit","date_entered"=>"Date","vendor"=>"Vendor","invoice_number"=>"Inv #","amount"=>"Amount","division"=>"Div","status"=>"Status","supervisor"=>"supervisor","filename"=>"","approval_link"=>"Link","del"=>"Del");
    // }

/*////////////////////////////////////////////////////////////////////////////////
Set View Options
///////////////////////////////////////////////////////////////////////////////*/		
//////////////////////////////////////Search and Order Criteria

			//search function
				 $search = $_GET['search'];
				 $filters=$_GET['filter'];	
				 $supervisor=$_GET['supervisor'];
				 // if (isset ($search) || isset ($filters) || isset ($supervisor)){
					// $where = " WHERE ";
				 // }
				
				$where = " WHERE supervisor='$user'";
				
				// $count=count($filters);
				// //print_r ($filters);
				// if ($count>0){
						// //$x = implode($filters,' , ');
						// $x = "'" . implode("','",$filters) . "'";
						// $where .= " $db_table.status IN ($x)";
						// $prior_where_statement=1;
					
				// }
			
				// if ($search!=""){
					// /* $where .= " WHERE vendor LIKE '%$search%'
								// OR notes LIKE '%$search%'
								// OR invoice_number LIKE '%$search%'
								// OR status LIKE '%$search%'
								// AND jobnumber = '$jobnumber'
					 // ";
					 // */
					 // if($prior_where_statement==1){
						// $where .= " OR";
					// }
					 // $where .= " $db_table.vendor LIKE '%$search%'
								// OR $db_table.notes LIKE '%$search%'
								// OR $db_table.invoice_number LIKE '%$search%'
								// OR $db_table.status LIKE '%$search%'
					 // ";
					 // $prior_where_statement=1;
				// }
				// //else{
						// //$where =" WHERE jobnumber = '$jobnumber'";
				// //}

				// if ($supervisor !="All" && $supervisor != ""){
					// if($prior_where_statement==1){
					// $where .= " AND";
					// }
					// $where .= " jobdata.supervisor = '$supervisor'";
					// $prior_where_statement=1;
				// }

			//Order if column clicked
				$order_by = $_GET['sort_by'];
				$direction = $_GET['direction'];

				if ($order_by != ""){
					 $order = " ORDER BY $order_by $direction";
				}
				else{
						$order =" ORDER BY date_entered DESC";
				}
		
/*/////////////////////////////////////////////////
Delete Record
/////////////////////////////////////////////////*/
			if ($delete_record!=""){
	
				$mysql_link->query("DELETE FROM $db_table WHERE uid='$delete_record'");
				 //write log
					$details="jn:$jobnumber,$date,$start_time,$end_time,$delete_record";
					write_log_file ($user,'Invoice Delete',$employee,$details);
				echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
				echo "<br><br><b><big>Successfully Deleted</b></big><br><br>";
				echo 	'<input type="Button" value="Back" onclick="location.href=\''.$_SERVER['PHP_SELF']  .'?j='.$masked_jobnumber  .'\'">';
				die();		
			}//end if delete

///////////////////////////////////////////////////////////////

	$employee_list = getEmployeeNames();//get employee names
	
//Check if any records exist
			$sql = "SELECT $db_table.*, jobdata.supervisor FROM `vendor_invoices` LEFT JOIN `jobdata` ON $db_table.jobnumber = jobdata.jobnumber" . $where;
			//echo $sql;
				$result = $mysql_link->query($sql);
				 $rows = $result->num_rows;
				 
			 if ($rows == 0) {
				 echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
				echo "<br><br><b><big>No records found</b></big><br><br>";
				echo 	'<input type="Button" value="Enter Document" onclick="location.href=\''. $edit_page . '?j=' . $masked_jobnumber  .'\'">';
				die();
			 }	
			 
/////////////////// Get data from table
			//$sql = "SELECT * FROM $db_table". $where . $order . $max;
			$sql = "SELECT $db_table.*, jobdata.supervisor FROM `vendor_invoices` LEFT JOIN `jobdata` ON $db_table.jobnumber = jobdata.jobnumber" . $where .$order . $max;
			$result = $mysql_link->query($sql);


/*/////////////////////////////////////////////////
Create Table
/////////////////////////////////////////////////*/
//table code
		$table = '<table id="dbtable" style="width: 100%;"><tr>';
			//Show Headings
				foreach ($columns as $x=>$y){
					$heading=$y;
					if ($direction =="ASC") {
						//$table .= "<th><b><a href=". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=DESC><b>$heading</b></a></b></th>";
						foreach ($filters as $value) { $string.="&filter[]=$value";}
						$table .= "<th><b><a href=\"". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=DESC&search=$search&supervisor=$supervisor".$string."\"><b>$heading</b></a></b></th>";
					}
					else {
						//$table .= "<th><b><a href='". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=ASC'><b>$heading</b></a></b></th>";
							$table .= "<th><b><a href=\"". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=ASC&search=$search&supervisor=$supervisor".$string."\"><b>$heading</b></a></b></th>";
					}
				  }
				$table .= '</tr>';
			//Output the data		
				$j=1;
				while($row = $result->fetch_assoc())
				{
					// Your while loop here
					// Color odd lines
					if ($j % 2 == 0){ 
						$table .= '<tr style="background-color: #f0f0f0;">'; 
					} 
					else { 
						$table .= '<tr style="background-color: #e0e0e0;">';
					}
					
					foreach ($columns as $i=>$value){
						$color = "000000";//basic color
							// foreach ($status_color as $status_key=>$color_value){
								// if ($row['status']==$status_key){
								// $color=$color_value;
								// }
							// }
						
						$table .=  '<td style="color:#' . $color . ';">';  
							
				//check if special cell and format set formatting conditions				
						if ($i == "edit") { 
							$id_number = $row['uid'];
							$table .= '<a href="'.$edit_page .'?id='. $id_number . '"><img src="dashboard/img/i_edit.png" /></a></td>';
						}
						elseif ($i == "filename"){
							$filename=$row[$i];
							$table .= '<a href="'.	$path . $filename.'" target="_blank" ><img src="dashboard/img/doc.png" /></a>';
						}
						elseif ($i=="amount" || $i=="price"){ 
							//$table .= sprintf("$%01.2f", $row[$i]).'</td>';
							$table .= money_format("%n",$row[$i]);
						}
						elseif ($i=="hours"){ 
							$table .= sprintf("%01.2f", $row[$i]).'</td>';
						}
						elseif ($i=="delete" || $i=="del"){ 
							//$table .=  '<a href="'.$_SERVER['PHP_SELF'].'?delete_record=' . $row['uid'] . '&j='. $masked_jobnumber . '"><img src="img/i_delete.png" /></a>';
							$table .=  '<a href="" onClick="checkDelete(\''.$row['uid'].'\')" ><img src="dashboard/img/i_delete.png" /></a>';
						}
						elseif ($i=="link" || $i=="approval_link"){ 
							//$table .=  '<a href="" onClick="checkDelete(\''.$row['uid'].'\')" ><img src="dashboard/img/i_delete.png" /></a>';
							$id_number = $row['uid'];
							$table .= '<a href="/employee_invoice_approve.php?ref='.$id_number.'" >Link</a>';
						}
				//Regular Cell	
						else{ 
							$table .= $row[$i] . "</td>"; 
						}
								
					}//end foreach for column

					// add extra columns (if required)	
					$table .= '</tr>';
					$j++;
				}//end while
			
		$table .='</table>';
		
?>
<!DOCTYPE HTML>
<!--
	TXT 2.5 by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title><? echo $company_name?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="<? echo $company_description ?>" />
		<meta name="keywords" content="<? echo $company_keywords ?>" />
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,700|Open+Sans+Condensed:700" rel="stylesheet" />
		<script src="js/jquery.min.js"></script>
		<script src="js/config.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-panels.min.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-desktop.css" />
			<link rel="stylesheet" href="css/font-awesome.css" />
		</noscript>
		
		<link rel="stylesheet" href="css/jquery-ui.min.css">
		<link rel="stylesheet" href="css/jquery-ui.theme.min.css">
		<link rel="stylesheet" href="css/jquery-ui.structure.min.css">
		<link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">

		<script src="js/jquery-ui.min.js"></script>
		<script src="js/jquery-ui-timepicker-addon.js"></script>
		<script>
			$(function() {
				$( "#datepicker1" ).datepicker(
					{
					dateFormat: "yy-mm-dd",
					showButtonPanel: true,
					changeMonth: true,
					changeYear: true,
					showWeek: true,
					firstDay: 1
					}
				);
				$( "#datepicker2" ).datepicker(
					{
					dateFormat: "yy-mm-dd",
					showButtonPanel: true,
					changeMonth: true,
					changeYear: true,
					showWeek: true,
					firstDay: 1
					}
				);
			});
		</script>
		<script type="text/javascript">
		<!--
			function toggle_visibility(id) {
			   var e = document.getElementById(id);
			   if(e.style.display == 'block')
				  e.style.display = 'none';
			   else
				  e.style.display = 'block';
			}
		//-->
		</script>
		<style type="text/css">
		  #datepicker1, #datepicker2{
			background-image:url("images/calendar.png");
			background-position:right center;
			background-repeat:no-repeat; }
		</style>
		
		<!--[if lte IE 9]><link rel="stylesheet" href="css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><link rel="stylesheet" href="css/ie8.css" /><![endif]-->
		<!--[if lte IE 7]><link rel="stylesheet" href="css/ie7.css" /><![endif]-->
	</head>
	<body class="homepage">

		<!-- Header -->
			<?php //include('_header.php');  ?>
		<!-- /Header -->

		<!-- Nav -->
			<?php include('_menu_employee.php');  ?>
		<!-- /Nav -->
		
		<!-- Banner -->
		<!-- /Banner -->

		<!-- Main -->
			<div id="main-wrapper">
				<div id="main" class="container">		
							<!-- Highlight -->
						<section class="is-page-content">
							<div class="row flush" style="padding:0em; padding-top:2em;">
								<div class="12u">
									<header>
										<h3><?php echo $page_title; ?></h3>
									</header>
						
											<div id="menu">
												<ul>
												<li>
												<a href="<?php echo $edit_page . "?j=" . $masked_jobnumber;?>" ><span class="button-menu"><i class="fa fa-plus fa-fw"></i>&nbsp; Add Invoice</span></a></li>
												</ul>
											</div>
								</div>
							</div>
					<div class="row flush" style="padding:0em;">
						<div class="9u" >
							<!--<input class="submit" id="Button1" type="button" value="Show/Hide Opts" onclick="toggle_visibility('opts');" />-->
						<!--<div id="opts" style="display:none; font-size: 70%;">-->
							
						<!--</div>-->
						</div>
						<div class="3u">					
						</div>								
					</div>
								<div class="row flush" style="padding:0em;">
									<div class="12u">	
										<b>Invoices for: <?php echo $user; ?></b>
										<?php echo $table;?>
									</div>
								</div>							
										
										
								</section>
							<!-- /Highlight -->
						</div>			
					</div>
					
					<div class="row">
						<div class="12u">
							<!-- Features -->							
							<!-- /Features -->
						</div>
					</div>
		<!-- /Main -->
		<!-- Footer -->
			<footer id="footer" class="container">
				<!-- Copyright -->
					<div id="copyright">
						&copy; <? echo $company_name; ?> | Template: <a href="http://html5up.net/">HTML5 UP</a>
					</div>
				<!-- /Copyright -->
			</footer>
		<!-- /Footer -->
	</body>
</html>