<?php
//ini_set('display_errors',0); 
//error_reporting(0);
header("Cache-Control: private, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // A date in the past

/*////////////////////////////////////////////////////////////////////////////////
Functions
///////////////////////////////////////////////////////////////////////////////*/


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
	//get and set initial variables
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		
		if ($mysql_link->connect_errno) {
			die($mysql_link->connect_error);
		}
	
		$db_table="$db_purchase_order";
		$page_title="View Purchase Orders";	
	//Edit or upload page
		$edit_page="employee_po_entry.php";	

/*////////////////////////////////////////////////////////////////////////////////
Set View Options
///////////////////////////////////////////////////////////////////////////////*/		
	//Sets the columns visible in the table
		$columns=array("po_number"=>"PO Number","date_entered"=>"Date Entered","vendor"=>"Vendor","notes"=>"Notes","status"=>"Status","entered_by"=>"Entered By");
	
		// if (isEnabled(array("administrator"),array("admin","payroll"))){
			// $columns=array("edit"=>"","jobnumber"=>"Job Number","employee"=>"Employee","date"=>"Date","time_in"=>"Time In","time_out"=>"Time Out", "hours"=>"Hours", "division"=>"Division", "sub_division"=>"Sub-Division", "comment"=>"Comment","del"=>"");
		// }
		
		$search = $_GET['search']; 
		if ($search!=""){
			 $where = " WHERE jobnumber LIKE '%$search%'
						OR notes LIKE '%$search%'
						OR po_number LIKE '%$search%'
						OR vendor LIKE '%$search%'
						OR date_entered LIKE '%$search%'
			 ";
		}
		else{
				//$where =" WHERE status = 'Open'";
		}

		$order_by = $_GET['sort_by'];
		$direction = $_GET['direction'];


		if ($order_by != ""){
			 $order = " ORDER BY $order_by $direction";
		}
		else{
				$order =" ORDER BY `date_entered` DESC";
		}
		
		$max = ' LIMIT 50';
		
/*/////////////////////////////////////////////////
Delete Record
/////////////////////////////////////////////////*/
			if ($delete_record!=""){
	
				$mysql_link->query("DELETE FROM $db_table WHERE uid='$delete_record'") or die($mysql_link->connect_error);  
				 //write log
					$details="jn:$jobnumber,$date,$start_time,$end_time,$delete_record";
					write_log_file ($user,'Time Delete',$employee,$details);
				echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
				echo "<br><br><b><big>Successfully Deleted</b></big><br><br>";
				echo 	'<input type="Button" value="Back" onclick="location.href=\''.$_SERVER['PHP_SELF']  .'?j='.$masked_jobnumber  .'\'">';
				die();		
			}//end if delete

///////////////////////////////////////////////////////////////

	//$employee_list = getEmployeeNames();//get employee names
	
	//get data
		$sql = "SELECT * FROM $db_table". $where . $order . $max;
		$retval = $mysql_link->query($sql) or die('Could not retrieve data: ' . $mysql_link->connect_error);


/*/////////////////////////////////////////////////
Create Table
/////////////////////////////////////////////////*/
//table code
		$table = '<table id="dbtable" style="width: 100%;"><tr>';
			//Show Headings
				foreach ($columns as $x=>$y){
					if ($direction =="ASC") {
						$table .= "<th><b><a style='color:#000;' href=". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=DESC>$y</a></b></th>";
					}
					else {
						$table .= "<th><b><a style='color:#000;' href='". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=ASC'>$y</a></b></th>";
					}
				  }
				$table .= '</tr>';
			//Output the data		
				$j=1;
				while($row = $retval->fetch_assoc())
				{
				// Color odd lines
					if ($j % 2 == 0){ 
						$table .= '<tr style="background-color: #f0f0f0;">'; 
					} 
					else { 
						$table .= '<tr style="background-color: #e0e0e0;">';
					}
					foreach ($columns as $i=>$value){
						
				//color code rows
						if ($row ['status'] == "Completed" && $row ['invoice_number'] !="" ){ $color = "0000ff";} 
							elseif ($row ['status'] == "Completed" ){ $color = "ff0000";} 
							elseif ($row ['status'] == "Recurring" ){ $color = "800080";} 
							elseif ($row ['status'] == "Pending" ){ $color = "707070";} 
							elseif ($row ['status'] == "On Hold" ){ $color = "707070";} 
							elseif ($row ['status'] == "In Progress" ){ $color = "000000";} 
						else  { $color = "000000";}	
						
						$table .=  '<td style="color:#' . $color . ';">';  
				//check if special cell and format set formatting conditions				
						if ($i == "edit") { 
								$id_number = $row['jobnumber'];
								$table .= '<a href="'.$edit_page .'?id='. $id_number . '">
								<span style="color:#092;">
								<i class="fa fa-edit fa-lg fa-fw"></i>
								</span></a></td>';
						}
						elseif ($i == "wo") { 
								$id_number = $row['jobnumber'];
								$table .= '<a href="employee_workorder_entry.php?edit_record='. $id_number . '">
								<span style="color:#092;">
								<i class="fa fa-flag fa-fw"></i>
								</span></a></td>';
						}
						elseif ($i == "po_number") { 
							$id_number = $row['id'];
							//echo "<a href=\"employee_po_entry.php?id=$row['id']\">$row[$i]</a></td>";
							$table .= '<a href="employee_po_entry.php?id='. $id_number . '">'. $row[$i] .'</a></td>';
						} 
						elseif ($i == "filename"){
							$filename=$row[$i];
							$table .= '<a href="'.	$path . $filename.'"><span style="color:#00f;"><i class="fa fa-file-o fa-fw"></i></span></a>';
						}
						elseif ($i=="amount" || $i=="price"){ 
							$table .= money_format("%n",$row[$i]);
						}
						elseif ($i=="hours"){ 
							$table .= sprintf("%01.2f", $row[$i]).'</td>';
						}
						elseif ($i == "time_in" or $i == "time_out") {
						$table .= date ("H:i",strtotime($row[$i]));  
						}
						elseif ($i=="delete" || $i=="del"){ 
							$table .=  '<a href="'.$_SERVER['PHP_SELF'].'?delete_record=' . $row['jobnumber'] . '&j='. $masked_jobnumber . '&date_current='. $date_current.'&date_end='.$date_end.'&employee='.$employee.'" onclick="return confirm(\'Confirm Delete?\')" ><span style="color:#f00;"><i class="fa fa-minus-circle fa-fw"></i></span></a>';
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
												<li><a href="employee_po_select_job.php" ><span class="button-menu"><i class="fa fa-plus fa-fw"></i>&nbsp; Add Purchase Order</span></a></li>
												</ul>
											</div>
									<hr>
								</div>
							</div>
					<div class="row flush" style="padding:0em;">
						<div class="9u" >
							<input class="submit" id="Button1" type="button" value="Show/Hide Opts" onclick="toggle_visibility('opts');" />
							<div id="opts" style="display:none; font-size: 70%;">
							<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="viewoptions">
							<input class="submit" type="submit" name="update" value="Update View"/>
							</form>
							</div>
						</div>
						<div class="3u">
							<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="viewoptions">
								<?php  
									echo '<input class="text" type="text" name="search" placeholder="Search" value="'.$search. '">';
									echo '<input type="submit" name="submit" value="Search"/>';
								?>
							</form>
						</div>
											
					</div>
								<hr>
								<div class="row flush" style="padding:0em;">
											<div class="12u">	
												<b>Purchase Orders</b>
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
			
		<!-- /Footer -->

	</body>
</html>
