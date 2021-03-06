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
//open database
	$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
	if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
		
/*////////////////////////////////////////////////////////////////////////////////
Variables
///////////////////////////////////////////////////////////////////////////////*/
//get and set initial variables
	$user 			= trim(getUsername());

/*////////////////////////////////////////////////////////////////////////////////
Process Form
///////////////////////////////////////////////////////////////////////////////*/
	if (isset($_POST['submit'])){
	// //write code for submitting
	$_POST = sanitize($_POST);

	 $jobnumber		= $_POST['jobnumber']; 
	 $description	= $_POST['description'];
	 $location		= $_POST['location'];
	 $customer		= $_POST['customer'];
	 $bill_to		= $_POST['bill_to'];
	 $supervisor	= $_POST['supervisor'];
	 $status		= $_POST['status'];
	 $start_date	= $_POST['start_date'];
	 $end_date		= $_POST['end_date'];
	 $quote_number	= $_POST['quote_number'];
	 $po_number		= $_POST['po_number'];
	 $notes			= $_POST['notes'];
	 $invoice_number= $_POST['invoice_number'];
	 $contact_name	= $_POST['contact_name'];
	 $contact_number= $_POST['contact_number'];
	 $opened_by		= $_POST['opened_by'];
	 $date_opened	= $_POST['date_opened'];
	 $date_invoiced	= $_POST['date_invoiced'];
	 $date_closed	= $_POST['date_closed'];
	 $last_modified = date("Y-m-d");
	 $require_div	= $_POST['require_div'];
	 $require_subdiv= $_POST['require_subdiv'];


//Data Validation	 
	if($customer =="" || $bill_to=="" || $description=="" || $location==""){
		echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
		echo "<br><br><b><big>Hey BOZO! You forgot some information!</b></big><br>Fill in all of the fields marked with an *<br><br>";
		echo 	'<input type="Button" value="Back" onclick="history.go(-1)">';
		die();
	}
	 
	if ($jobnumber==""){
		$sql = "INSERT INTO $db_table_jobfile (
		 `description` ,
		 `location` ,
		 `customer` ,
		 `bill_to` ,
		 `supervisor` ,
		 `status` ,
		 `start_date` ,
		 `end_date` ,
		 `quote_number` ,
		 `po_number` ,
		 `notes` ,
		 `invoice_number` ,
		 `contact_name` ,
		 `contact_number` ,
		 `opened_by` ,
		 `date_opened` ,
		 `date_invoiced` ,
		 `date_closed`,
		 `last_modified`,
		 `require_div`,
		 `require_subdiv`
		)
		VALUES (
		 '$description',
		 '$location',
		 '$customer',
		 '$bill_to',
		 '$supervisor',
		 '$status',
		 '$start_date',
		 '$end_date',
		 '$quote_number',
		 '$po_number',
		 '$notes',
		 '$invoice_number',
		 '$contact_name',
		 '$contact_number',
		 '$opened_by',
		 '$date_opened',
		 '$date_invoiced',
		 '$date_closed',
		 '$last_modified',
		 '$require_div',
		 '$require_subdiv'
		)";
	}
	else{
		$sql = "UPDATE $db_table_jobfile 
		SET  
		 description	='$description',
		 location		='$location',
		 customer		='$customer',
		 bill_to		='$bill_to',
		 supervisor		='$supervisor',
		 status			='$status',
		 start_date		='$start_date',
		 end_date		='$end_date',
		 quote_number	='$quote_number',
		 po_number		='$po_number',
		 notes			='$notes',
		 invoice_number	='$invoice_number',
		 contact_name	='$contact_name',
		 contact_number	='$contact_number',
		 opened_by		='$opened_by',
		 date_opened	='$date_opened',
		 date_invoiced	='$date_invoiced',
		 date_closed	='$date_closed',
		 last_modified	='$last_modified',
		 require_div	='$require_div',
		 require_subdiv	='$require_subdiv'
		WHERE jobnumber = '$jobnumber'" ;	 
	}//end else
	//echo $sql;

	//$retval = mysql_query($sql) or die(mysql_error());
	$retval = $mysql_link->query($sql);
			//write log
				$details="jn:$jobnumber,$date_opened,$customer,$description";
				if ($id==""){ $event="Job Entered/Changed";} else { $event="Time Entered";}
				write_log_file ($user,$event,$employee,$details);
				
	echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
	echo "<br><br><b><big>Successfully Submitted</b></big><br><br>";
	echo 	'<input type="Button" value="Back" onclick="location.href=\'employee_job_view.php\'">';
	die();
}//end if submit

///////////////////////////////////////////////////////////////

	$employee_list = getEmployeeNames();//get employee names

	$edit_record = $_REQUEST['edit_record'];

if ($edit_record !=""){
	//read form date 
	$sql = "SELECT * FROM $db_table_jobfile WHERE jobnumber = $edit_record";
	$retval = $mysql_link->query($sql);

	//return single value
	$row = $retval->fetch_assoc();

	$jobnumber 			= $row['jobnumber'];
	$description 		= $row['description'];
	$location 			= $row['location'];
	$customer 			= $row['customer'];
	$bill_to 			= $row['bill_to'];
	$supervisor 		= $row['supervisor'];
	$status 			= $row['status'];
	$start_date 		= $row['start_date'];
	$end_date 			= $row['end_date'];
	$quote_number 		= $row['quote_number'];
	$po_number 			= $row['po_number'];
	$notes 				= $row['notes'];
	$invoice_number 	= $row['invoice_number'];
	$contact_name		= $row['contact_name'];
	$contact_number 	= $row['contact_number'];
	$opened_by 			= $row['opened_by'];
	$date_opened 		= $row['date_opened'];
	$date_invoiced 		= $row['date_invoiced'];
	$date_closed 		= $row['date_closed'];
	$require_div		= $row['require_div'];
	$require_subdiv		= $row['require_subdiv'];
}
	else{
	//default form data
	$jobnumber 		= "";
	$description 	= "";
	$location 		= "";
	$customer 		= "";
	$bill_to 		= "";
	$supervisor 	= $user;
	$status 		= "In Progress";
	$start_date 	= date("Y-m-d");
	$end_date 		= "";
	$quote_number 	= "";
	$po_number 		= "";
	$notes 			= "";
	$invoice_number = "";
	$contact_name 	= "";
	$contact_number = "";
	$opened_by 		= $user;
	$date_opened 	= date("Y-m-d");
	$date_invoiced 	= "";
	$date_closed 	= "";
	$require_div	= "";
	$require_subdiv	= "";
}//END else

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
		  #datepicker1, #timepicker1, #timepicker2{
			background-position:right center;
			background-repeat:no-repeat; }
		   #timepicker1, #timepicker2 {background-image:url("images/clock.png");}
		   #datepicker1 {background-image:url("images/calendar.png");}
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
									<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" name="jobedit">
										<input class="submit" type="submit" name="submit" value="Submit"/>&nbsp;
										<input class="submit" type="submit" name="submit" value="Cancel" onclick="document.jobedit.action='employee_job_view.php';"/>&nbsp;
										<?php 
										if ($edit_record !=""){
										echo '<input type="submit" name="goto" value="Goto Workorder" onclick="document.jobedit.action=\'employee_workorder_entry.php?edit_record='.$jobnumber .'\';"/>';
										}
										?>
										
										<input type="hidden" name="opened_by" value="<?php echo $opened_by; ?>"  >
										<input type="hidden" name="date_opened" value="<?php echo $date_opened; ?>"  >
										<input type="hidden" name="date_invoiced" value="<?php echo $date_invoiced; ?>"  >
										<input type="hidden" name="date_closed" value="<?php echo $date_closed; ?>"  >
										<input type="hidden" name="jobnumber" value="<?php echo $jobnumber; ?>"  >


										<b>Job Entry</b>
												<fieldset>
													<legend>Information</legend>
													<label>Job Number:</label>
													<?php echo $jobnumber; ?><br>

													<label>*Description:</label>
													<input class="text" size="30" name="description" value="<?php echo $description; ?>"  />

													<label>*Location:</label>
													<input class="text" size="30" name="location" value="<?php echo $location; ?>">

													<label>*Customer:</label>
													<input class="text" size="30" name="customer" value="<?php echo $customer; ?>">

													<label>*Bill To:</label>
													<input class="text" size="30" name="bill_to" value="<?php echo $bill_to; ?>">
												</fieldset>
												<fieldset>
													<legend>Details</legend>
													<label>*Supervisor:</label>
													<select name="supervisor">
													<?php
														echo '<option>'.$supervisor.'</option>';
														foreach ($employee_list as $key=>$value){
														echo '<option>';
														echo $value;
														echo '</option>';
														}
													?>
													</select>

													<label>Status:</label>
													<select name="status">
														<option></option>
														<?php
															foreach ($status_list as $x){
															echo "<option";
															if ($status == $x){echo " selected>";} else {echo ">";}
															echo $x;
															echo "</option>";
															}
														?>
													</select>
													

													<label>Contact Name:</label>
													<input class="text" name="contact_name"  value="<?php echo $contact_name; ?>">

													<label>Contact Phone:</label>
													<input class="text" name="contact_number"  value="<?php echo $contact_number; ?>">

													<label>Start Date:</label>
													<input class="text" name="start_date"  value="<?php echo $start_date; ?>">

													<label>End Date:</label>
													<input class="text" name="end_date" value="<?php echo $end_date; ?>">

													<label>Quote #:</label>
													<input class="text" name="quote_number" value="<?php echo $quote_number; ?>">

													<label>PO #:</label>
													<input class="text" name="po_number"  value="<?php echo $po_number; ?>">

													<label>Invoice:</label>
													<input class="text" name="invoice_number" value="<?php echo $invoice_number; ?>">
												</fieldset>
												<fieldset>
													<legend>Options</legend>
													<label>Require Div:</label>
													<input type="checkbox" name="require_div" value="1" <?php if ($require_div==1){echo 'checked="checked"';}?>><br>

													<label>Require Sub-Div:</label>
													<input type="checkbox" name="require_subdiv" value="1" <?php if ($require_subdiv==1){echo 'checked="checked"';}?>>
												</fieldset>
												<fieldset>
													<legend>Notes</legend>
													<textarea cols="40" rows="2" name="notes" ><?php echo $notes; ?></textarea>
												</fieldset>
												<br>


										<br>
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