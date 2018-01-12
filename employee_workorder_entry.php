<?php
	//ini_set('display_errors',1);
	//error_reporting(E_ALL);
/*////////////////////////////////////////////////////////////////////////////////
Includes
///////////////////////////////////////////////////////////////////////////////*/
	require('_config.php');
	require_once("phpauthent/phpauthent_core.php");
	require_once("phpauthent/phpauthent_config.php");
	require_once("phpauthent/phpauthentadmin/locale/".$phpauth_language);
	require ('_functions_common.php');
	
/*////////////////////////////////////////////////////////////////////////////////
Page Protection
///////////////////////////////////////////////////////////////////////////////*/	
	$usersArray  = array();
	$groupsArray = array("admin","supervisor");
	pageProtect($usersArray,$groupsArray);	

/*////////////////////////////////////////////////////////////////////////////////
Functions
///////////////////////////////////////////////////////////////////////////////*/

/*////////////////////////////////////////////////////////////////////////////////
Variables
///////////////////////////////////////////////////////////////////////////////*/
//get and set initial variables
	//$user = trim($_COOKIE['USERNAME']);
	$user 			= getUserName();
	$edit_record=$_GET['edit_record'];
	$id=$_GET['id'];
	$jobnumber=$edit_record;
	$send_to=$email_service;
	$employee_list = getEmployeeNames();//get employee names

//fail out if record is blank
	if ($edit_record == "" && $id ==""){
		die ("Error: No Record is selected");
	}

/*////////////////////////////////////////////////////////////////////////////////
Get Additional Data
///////////////////////////////////////////////////////////////////////////////*/	
	//connect to database
	//open database
		//$connection = mysql_connect($dbhost, $dbusername, $dbpass);
		//$SelectedDB = mysql_select_db($dbname) or die('Could not connect: ' . mysql_error());
		
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		
		if ($mysql_link->connect_errno) {
			die($mysql_link->connect_error);
		}

//get employee hours
		$sql = "SELECT * FROM $db_timelog WHERE jobnumber='$jobnumber'";
		$hours = $mysql_link->query($sql);

		$total_hours="";
		$hour_breakdown = "";
		$employee_hours="";
		$comment_display = '<table style="border:1px solid black;border-collapse:collapse;font-size: 80%;line-height:1;">';
		while ($row = $hours->fetch_array(MYSQLI_ASSOC)) {
			$total_hours += $row['hours'];
			$employee=$row['employee'];
			$employee_hours[$employee] += $row['hours'];
			//added per Jamie request 15 april
			$date_temp = $row['date'];
			$comment_temp = $row['comment'];
			$employee_temp = $row['employee'];
			
			if ($comment_temp != ""){
				$comment_display .= "<tr><td>" . $date_temp . "</td><td>".$employee_temp."</td><td>" . $comment_temp . "</td></tr>";
				
			}
		}
		$comment_display .= "</table>";
		
		//print_r($employee_hours);
		if ($employee_hours!=""){
			$hour_breakdown = '<table style="border:1px solid black;border-collapse:collapse;font-size: 80%;line-height:1;">';
			foreach ($employee_hours as $key=>$value){
				//$hour_breakdown .= $key . " - " . $value. "<br>";
				$hour_breakdown .= "<tr><td>" . $key . "</td><td>" . $value . "</td></tr>";
			}
			$hour_breakdown .= "</table>";
		}
		
//get purchase orders
		//$sql = "SELECT * FROM $db_purchase_order_items WHERE po_number LIKE '%$jobnumber%' ORDER BY `po_number`";
				$sql="SELECT purchase_order_items.*, purchase_order.po_number, purchase_order.vendor, purchase_order.date_entered, purchase_order.id FROM purchase_order_items LEFT JOIN purchase_order ON (purchase_order_items.po_number = purchase_order.po_number) WHERE purchase_order_items.po_number LIKE '$jobnumber%' ORDER BY purchase_order.date_entered DESC, purchase_order.po_number DESC";
				$po_result = $mysql_link->query($sql);
				$po_cols=array ("po_number"=>"PO","date_entered"=>"Date Entered","vendor"=>"Vendor","description"=>"Description","price"=>"Price","division"=>"Div");
		$po_total = "";
		//put results in table
		$po_table = '<table style="border:1px solid black;border-collapse:collapse;font-size: 80%;line-height:1;"><tr>';
		
		//headings
		foreach ($po_cols as $key=>$value){
			$po_table .= "<th>$value</th>";
		}
		//data
		$po_table .= '</tr>';
		while ($row = $po_result->fetch_assoc()) {
		//print_r ($row);
		$id_number = $row['id'];
		$po_table .= '<tr>';
			foreach ($po_cols as $key=>$value){
					if ($key=="price"){ $po_table .= '<td>'.sprintf("$%01.2f", $row[$key]).'</td>'; $po_total += $row[$key];}
					elseif ($key=="po_number"){
						$po_table .= '<td><a href="employee_po_entry.php?id='. $id_number . '">'. $row[$key] .'</a></td>';
					}
					else{
						$po_table .= '<td>'. $row[$key] .'</td>';
					}
					}		
		$po_table .= '</tr>';		
		}
		$po_table .= '</table>';
		$po_table .= '<b>Total:</b>'.$po_total;
		
//get change orders

		$sql = "SELECT changeorder_items. * , changeorder.id AS co_id
				FROM changeorder_items
				JOIN changeorder ON ( changeorder.co_number = changeorder_items.co_number )
				WHERE changeorder_items.co_number LIKE '%$jobnumber%'
				GROUP BY changeorder_items.id
				ORDER BY changeorder.co_number";
		//$sql = "SELECT * FROM $db_changeorder_items WHERE co_number LIKE '%$jobnumber%' ORDER BY `co_number`,`id`";
		$co_result = $mysql_link->query($sql);
		$co_cols=array ("co_number"=>"CO","description"=>"Description","price"=>"Price","division"=>"Div","accepted"=>"Accepted");
		
		//put results in table
		$co_table = '<table style="border:1px solid black;border-collapse:collapse;font-size: 80%;line-height:1;"><tr>';
		
		//headings
		foreach ($co_cols as $key=>$value){
			$co_table .= "<th>$value</th>";
		}
		//data
		$co_table .= '</tr>';
		while ($row = $co_result->fetch_assoc()) {

				$id_number = $row['co_id'];
				
		//id is found above - $id_number = $row['id'];
		$co_table .= '<tr>';
			foreach ($co_cols as $key=>$value){
				if ($key=="price"){ $co_table .= '<td>'.sprintf("$%01.2f", $row[$key]).'</td>'; $po_total += $row[$key];}
					elseif ($key=="co_number"){
						$co_table .= '<td><a href="employee_co_entry.php?id='. $id_number . '">'. $row[$key] .'</a></td>';
					}
					else{
						$co_table .= '<td>'. $row[$key] .'</td>';
					}
				
				
				
			}	
		$co_table .= '</tr>';		
		}
		$co_table .= '</table>';	
	
/*////////////////////////////////////////////////////////////////////////////////
Process Form
///////////////////////////////////////////////////////////////////////////////*/
	if (isset($_POST['submit'])){
	//   process data entry - normal entry coding
		  $_POST = sanitize($_POST);

		 $jobnumber 		= $_POST['jobnumber'];
		 $ready_to_invoice 	= $_POST['ready_to_invoice'];
		 $quote			 	= $_POST['quote'];
		 $quote_number		= $_POST['quote_number'];
		 $status		 	= $_POST['status'];
		 $description	 	= $_POST['description'];
		 $solution			= $_POST['solution'];
		 $date1				= $_POST['date1'];
		 $location			= $_POST['location'];
		 $supervisor		= $_POST['supervisor'];
		 $customer			= $_POST['customer'];
		 $bill_to			=$_POST['bill_to'];
		 $expense_1			=$_POST['expense_1'];
		 $expense_2			=$_POST['expense_2'];
		 $expense_3			=$_POST['expense_3'];
		 $expense_4			=$_POST['expense_4'];
		 $cost_1			=$_POST['cost_1'];
		 $cost_2			=$_POST['cost_2'];
		 $cost_3			=$_POST['cost_3'];
		 $cost_4			=$_POST['cost_4'];
		 $notes_office		=$_POST['notes_office'];
		 $comments			=$_POST['comments'];

			//make sure required data is entered
			if ($_POST['ready_to_invoice']=="") {
			echo "<b>Please indicate whether this is ready to invoice. <br><br>Press the back button on your browser to return.</b>";
			die();
			}
//write to workorder database
$sql = "INSERT INTO $db_workorder
SET `jobnumber`='$jobnumber', 
`ready_to_invoice`='$ready_to_invoice', 
`quote`='$quote', 
`solution`='$solution', 
`expense_1`='$expense_1', 
`expense_2`='$expense_2', 
`expense_3`='$expense_3', 
`expense_4`='$expense_4',
`cost_1`='$cost_1', 
`cost_2`='$cost_2', 
`cost_3`='$cost_3', 
`cost_4`='$cost_4',
`comments`='$comments'
ON DUPLICATE KEY UPDATE 
`ready_to_invoice`='$ready_to_invoice', 
`quote`='$quote', 
`solution`='$solution', 
`expense_1`='$expense_1', 
`expense_2`='$expense_2', 
`expense_3`='$expense_3', 
`expense_4`='$expense_4',
`cost_1`='$cost_1', 
`cost_2`='$cost_2', 
`cost_3`='$cost_3', 
`cost_4`='$cost_4',
`comments`='$comments'
";

//echo $sql;
$retval = $mysql_link->query($sql);

//write to jobfile database
		$sql = "UPDATE $db_table_jobfile 
		SET  
		 description	='$description',
		 location		='$location',
		 customer		='$customer',
		 bill_to		='$bill_to',
		 status			='$status',
		 supervisor		='$supervisor',
		 quote_number	='$quote_number',
		 po_number		='$po_number',
		 end_date		='$date1',
		 notes			='$notes_office'
		WHERE `jobnumber` = '$jobnumber'
		" ;
//echo $sql;
$retval = $mysql_link->query($sql);

// //write log
//write log
		$details="$jobnumber,$date1,";
		write_log_file ($user,'Work Order Edit',$employee,$details);

if ($quote ==1){$quote="Yes";}			
//send email
$today = date("Y-m-d");
$subject = "Work Order Completion: " . $jobnumber . " - " . $location;

$message = "---Work Order Completion--- Job No:<b>$jobnumber</b><br><br>" ;
$message .= "Date:". $today;
$message .= "<div style='float:right;'>Ready to Invoice:"; 
if ($ready_to_invoice=="1"){$message .="Yes";}else {$message .="No";}
$message .= "</div><br><hr>Location:$location"; 
$message .= "<br>Customer:$customer"; 
$message .= "<br>Bill to:$bill_to"; 
$message .= "<br><hr>"; 

$message .= "<br>Quoted Job: $quote Quote No:$quote_number";
 
$message .= "<br><hr><b>Problem:</b>$description"; 
$message .= "<br><b>Solution:</b>$solution";
$message .= "<br><hr>";
$message .= "Status: $status <div style='float:right;'>Supervisor:$supervisor</div>";
$message .= "<div style='float:right;'><br>Date Completed: $date1</div>";
 
$message .= "Total Hours:$total_hours hrs<br>"; 
$message .= "$hour_breakdown"; 
$message .= "<br><hr>"; 
 $message .= "Purchase Orders:<br>"; 
  $message .= "$po_table"; 
 $message .= "<br><hr>Change Orders:<br>"; 
  $message .= "$co_table";  
$message .= "<br><hr>";
$message .= "Comments:<br>$comments";
$message .= "<br><hr>";
$message .= "Office Comments:<br>$notes_office";
$message .= "<br><hr>Submitted By: $user<br>Approved (Office):____________";

$message = $message;

$headers = "From: Workorder <workorder.noreply@". $company_domain . ">" . "\r\n";
$headers .= "Content-type: text/html\r\n";
//$headers .= "Cc: " . $email. "\r\n";

	if ( $ready_to_invoice == "1"){
		if(mail($send_to,$subject,$message, $headers)) {
			echo "Your request was sent to $send_to with the subject: $subject";
		} 
		else {
			echo "There was a problem sending the request to $send_to. Check your code and make sure that the e-mail address $send_to is valid";
		}
	}		
	else { 
		$subject = "Update Work Order: " . $job_number . " - " . $location;
		//if(mail($send_to,$subject,$message, $headers)) {
		echo "Thank You - Data Saved";
		//} else {
		//echo "There was a problem sending the request. Check your code and make sure that the e-mail address $to is valid";
		//}
		}
	
echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
echo "<br><br><b><big>Successfully Submitted</b></big><br><br>";
echo 	'<input type="Button" value="Back" onclick="history.go(-2)">';
die();

}//end if submit

/*////////////////////////////////////////////////////////////////////////////////
Retrieve Variables for form editing
///////////////////////////////////////////////////////////////////////////////*/
if ($edit_record !=""){
	//read data from jobfile 
	$sql = "SELECT * FROM $db_table_jobfile WHERE jobnumber='$jobnumber'";
	$retval = $mysql_link->query($sql);
	//return single value
	$row = $retval->fetch_assoc();

	$location			= $row['location'];
	$quote_number		= $row['quote_number'];
	$status				= $row['status'];
	$description		= $row['description'];
	$end_date			= $row['end_date'];
	$notes_office		= $row['notes'];
	$supervisor			= $row['supervisor'];
	$customer			= $row['customer'];
	$bill_to			= $row['bill_to'];
	
//read data from workorder 
	$sql = "SELECT * FROM $db_workorder WHERE jobnumber='$jobnumber'";
	//$retval = mysql_query($sql) or die(mysql_error());
	$retval = $mysql_link->query($sql);
	//return single value
	$row = $retval->fetch_assoc();

	$ready_to_invoice	= $row['ready_to_invoice'];
	$quote				= $row['quote'];
	$solution			= $row['solution'];
	$comments			= $row['comments'];
	$expense_1			= $row['expense_1'];
	$expense_2			= $row['expense_2'];
	$expense_3			= $row['expense_3'];
	$expense_4			= $row['expense_4'];
	$cost_1				= $row['cost_1'];
	$cost_2				= $row['cost_2'];
	$cost_3				= $row['cost_3'];
	$cost_4				= $row['cost_4'];
}

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
					}
				);

			});
		</script>
		<style type="text/css">
		  #datepicker1, #datepicker2, #datepicker3{
			background-position:right center;
			background-repeat:no-repeat; 
			background-image:url("images/calendar.png");}
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


					<!-- Page Content -->
					<div class="row">
						<div class="12u">
						<section class="is-page-content">
							<!-- Menu Buttons -->
							<div class="row flush" style="padding:0em; padding-top:2em;">
								<div class="12u">
									<header>
										<h3>Job Entry</h3>
									</header>
									<div id="menu">
										<ul>
										<li><a href="employee_job_view.php" ><span class="button-menu"><i class="fa fa-arrow-circle-o-left fa-fw"></i>&nbsp; View Job Database</span></a></li>
										</ul>
									</div>
								</div>
							</div>
							<!-- /Menu Buttons -->
							<!-- Form -->
							<div class="row flush" style="padding:0em;">
								<div class="12u">
									<hr>
									<form class="formLayout" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="workorder">

										<fieldset>
										<legend>Workorder - <?php echo "$jobnumber<br>"; ?></legend>

											 <div style="text-align: left;">

											<input type="hidden" name="jobnumber" value="<?php echo $jobnumber; ?>">
											<input type="hidden" name="supervisor" value="<?php echo $supervisor; ?>">
											
											<label><b>***Invoice:</b></label>
												<select name="ready_to_invoice">
													<option></option>
													<option value="1">yes</option>
													<option value="0">no</option>
												</select>    
											<div style="clear:both;"></div>
											<label><b>Quote:</b></label>
												<select name="quote">
													<option></option>
													<option value="1" <?php if ($quote==1){echo " selected";}?>>Yes</option>
													<option value="0" <?php if ($quote==0){echo " selected";}?>>No</option>
												</select>
												<div style="clear:both;"></div>
												<label>Quote No.:</label>
												<input class="text" type="number" name="quote_number" placeholder="Quote Number" value="<?php echo $quote_number;?>">
												<div style="clear:both;"></div>
										</fieldset>
										<fieldset>
											<legend>Status</legend>
											<label>Status:</label>
											<select name="status">
												<option></option>
												<?php
													foreach ($status_list as $x){
														echo "<option";
														if ($x == "Completed"){echo " selected>";} else {echo ">";}
														echo $x;
														echo "</option>";
													}
												?>
											</select>	
											<label>Finished:</label>
											<input class="text" id="datepicker1" name="date1" type="textbox" readonly="readonly" value="<?php echo date("Y-m-d"); ?>">
										</fieldset>
										<fieldset>
										<legend>Customer Details</legend>
											<label>Location:</label>
											<input class="text" name="location" type="textbox" value="<?php echo $location; ?>">
											<label>Customer:</label>
											<input class="text" name="customer" type="textbox" value="<?php echo $customer; ?>">
											<label>Bill To:</label>
											<input class="text" name="bill_to" type="textbox" value="<?php echo $bill_to; ?>">	
										</fieldset>
										<fieldset>	
										<legend>Data</legend>	
											<b>Problem/Description:</b><br>
												<textarea cols="35" rows="5" name="description"><?php echo $description; ?></textarea><br> 
											<b>Solution:</b><br>
												<textarea cols="35" rows="5" name="solution"><?php echo $solution; ?></textarea><br> 
										</fieldset>
										<fieldset>
										<legend>Details</legend>
										<fieldset>
										<legend>Hours and Costs - <a href="employee_report_time.php?j=<?php echo $jobnumber;?>">Show Time Breakdown</a></legend>	
											Total Hours:<b><?php echo $total_hours;?></b><br>
											<?php echo $hour_breakdown; ?>
										</fieldset>

										<fieldset>
										<legend>Purchase Orders - <a href="employee_po_entry.php?edit_record=<?php echo$jobnumber;?>">Add</a></legend>
											<?php echo $po_table; ?>
										</fieldset>
										<fieldset>
											<legend>Change Orders - <a href="employee_co_entry.php?jobnumber=<?php echo$jobnumber;?>">Add</a></legend>
											<?php echo $co_table; ?>
										</fieldset>
										<fieldset>
											<legend>Timelog Comments</legend>
											<?php echo $comment_display; ?>
										</fieldset>
										<fieldset>
											<legend>Comments:</legend>
											<textarea cols="35" rows="5" name="comments"><?php echo $comments; ?></textarea><br> 
										</fieldset>
										<fieldset>
											<legend>Office Comments:</legend>
											<textarea cols="35" rows="5" name="notes_office"><?php echo $notes_office; ?></textarea><br> 
										</fieldset>
											<input value="Submit" name="submit" type="submit"><br>
										</div>
									</form>

								</div>
							</div>
							<!-- /Form -->

						</section>
				<!-- /Page Content -->

						</div>
					</div>
				</div>
			</div>
		<!-- /Main -->
				<!-- Copyright -->
					<div id="copyright">
						&copy; <? echo $company_name; ?> | Template: <a href="http://html5up.net/">HTML5 UP</a>
					</div>
				<!-- /Copyright -->
	</body>
</html>