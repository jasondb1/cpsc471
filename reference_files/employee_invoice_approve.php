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
	require_once("_functions_common.php");
	include_once ("mailer.php");
	
/*////////////////////////////////////////////////////////////////////////////////
Page Protection
///////////////////////////////////////////////////////////////////////////////*/	
	$usersArray  = array();
	$groupsArray = array("admin","supervisor");
	pageProtect($usersArray,$groupsArray);	

/*////////////////////////////////////////////////////////////////////////////////
Variables
///////////////////////////////////////////////////////////////////////////////*/
//get and set initial variables
	$user = trim($_COOKIE['USERNAME']);
	$employee_list = getEmployeeNames();//get employee names
	$path="./files/vendor_invoices/";//change later as required
	$uid = $_GET['ref'];

/*////////////////////////////////////////////////////////////////////////////////
Get Additional Data
///////////////////////////////////////////////////////////////////////////////*/	
	//Connect to Database
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
	
	//view invoice
			$sql = "SELECT * FROM vendor_invoices WHERE uid = '$uid' ";
			$result = $mysql_link->query($sql);
			$row = $result->fetch_assoc();
			$link = $path.$row['filename'];
			$date = $row['date_entered'];
			$status_update = $row['status'];
			$jobnumber = $row['jobnumber'];
			$invoice_number = $row['invoice_number'];
			$invoice_amount = $row['amount'];
			$po_number = $row['po_number'];
			$vendor = $row['vendor'];
			$notes = $row['notes'];
			$division = $row['division'];
			$files = $row['filename'];
	
/*////////////////////////////////////////////////////////////////////////////////
Process Form
///////////////////////////////////////////////////////////////////////////////*/
	if (isset($_POST['submit'])){
	//get posted data
	 $jobnumber			= $_POST['jobnumber'];
	 $invoice_number	= $_POST['invoice_number']; 
	 $invoice_amount	= $_POST['invoice_amount']; 
	 $status_update		= $_POST['approved'];
	 $division			= $_POST['division']; 
	 $date_approved		= date ("Y-m-d"); 
	 $date_rejected		= date ("Y-m-d"); 
	 //$status			= $_POST['status']; 
	 $notes				= $_POST['notes']; 
	 $uid				= $_POST['uid'];
	 $files				= $_POST['files'];
	 $rejection_notes	= $_POST['rejection_notes'];
	 $alternate_email 	= $_POST['alt_email'];
	 
	 //See if division is required
	 $sql="SELECT `require_div` FROM `jobdata` WHERE `jobnumber`= '$jobnumber';"; 
	 $retval = $mysql_link->query($sql);
	 $row = $retval->fetch_assoc();	
	 $require_div = $row['require_div'];
	 
	 //validate entry	 
		if($status_update=="" || ($require_div=="1" AND $division=="")){
			echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
			echo "<br><br><b><big>Hey! You forgot some information!</b></big><br>Fill in all of the fields marked with an *<br><br>";
			echo 	'<input type="Button" value="Back" onclick="history.go(-1)">';
			die();
		}
	
	  //open database and write data - status, accepted
	if ($status_update=="Approved"){
		$sql = "UPDATE vendor_invoices
		SET  	
		 `division`		 	= '$division',
		 `status` 			= 'Approved',
		 `notes` 			= '$notes',
		 `date_approved`	= '$date_approved',
		 `approved_rejected_by` = '$email'
		 
		WHERE uid = '$uid';" ;	 
		$retval = $mysql_link->query($sql);
		
		//email approval to accounting	
				$to = $accounting_email;
				$to .= ",".$alternate_email;
				//$to = "jasond@jdsservices.ca";//test email
				$from = "noreply.invoices@jdsservices.ca";
				$reply_to = "noreply.invoices@jdsservices.ca";
				$my_name = "JDS Construction";
				$subject = "Vendor Invoice Accepted - $invoice_number";

				//html message
				$html_content = "";
				$html_content = '<b>Vendor Invoice</b><br><br>';
				$html_content .= "<b>Invoice $invoice_number is Accepted</b>";
				$html_content .= "<br><br>Notes:<br>" . $notes;
				$html_content .= "<br><br>";
				
				$message = $html_content;
				
				mail_attachment($files, $path, $to, $from, $my_name, $reply_to, $subject, $message);

			echo "<br><br>Approved Invoice has been sent to:$to";
////////
		}
	
	//status = rejected
	if ($status_update=="Rejected"){
		$reasons = $_POST['rejected_reason'];
		foreach ($reasons as $value){
			$notes .= " $value\n";
		}
		
		//Get Vendor Email
		$sql = "SELECT vendor_invoices.vendor,vendor_data.Vendor,vendor_data.Email FROM vendor_invoices LEFT JOIN vendor_data ON vendor_invoices.vendor=vendor_data.Vendor WHERE vendor_invoices.uid = '$uid'";
		$result = $mysql_link->query($sql)
		$row = $result->fetch_assoc();	
		$email=$row['Email'];
		//$to_email = $row['Email'];
		
		$sql = "UPDATE vendor_invoices
			SET  	
			 `division`		 	= '$division',
			 `status` 			= 'Rejected',
			 `notes` 			= '$notes',
			 `date_rejected`	= '$date_rejected',
			 `approved_rejected_by` = '$email'
			WHERE uid = '$uid'" ;	
			$retval = $mysql_link->query($sql);	
			
			//email rejection back to vendor	
				$to = $row['Email'];
				$to .= ",".$alternate_email;
				//$to = "jasond@jdsservices.ca";//test email
				$from = "noreply.invoices@jdsservices.ca";
				$reply_to = "noreply.invoices@jdsservices.ca";
				$my_name = "JDS Construction";
				$subject = "Vendor Invoice Rejected - $invoice_number";
				
				
				//plain text message
				// $html_content = "";
				// $html_content = 'Vendor Invoice\n\n';
				// $html_content .= "Invoice $invoice_number is Rejected for the following reasons:\n";
				// foreach ($reasons as $value){
					// $html_content .= "$value\n";
				// }
				// $html_content .= "\nPlease resubmit invoice with the required corrections or additions. This invoice will NOT be entered into our system for payment for the reasons given above.";

				//html message
				$html_content = "";
				$html_content .= '<b>Vendor Invoice Rejected</b><br><br>';
				$html_content .= "<b>Invoice $invoice_number</b> is Rejected for the following reasons:<br>";
				foreach ($reasons as $value){
					$html_content .= "$value<br>";
				}
				$html_content .= "<br>Notes:<br>$rejection_notes";
				$html_content .= "<br>Please resubmit invoice with the required corrections or additions. This invoice will <b>NOT</b> be entered into our system for payment for the reasons given above.";
				
				$message = $html_content;
				
				mail_attachment($files, $path, $to, $from, $my_name, $reply_to, $subject, $message);
				
				echo "<br><br>Rejected Invoice has been sent to:$to";
		}
	  
	echo '<html><head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
	echo "<br><br><b><big>Go Back</b></big><br><br>";
	echo 	'<input type="Button" value="Back" onclick="location.href=\'employee_invoice_view.php\'</html>">';
	die();
}//end if submit
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
										<h3>Approve Invoice</h3>
									</header>
									<form class="formLayout" style="background-color: rgb(240, 240, 240);" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
										<fieldset>
											<legend>Invoice</legend>
											<a href="<?php echo $link;?>" target="_blank">View Invoice<img src="images/doc.png" /></a><br>
											<input type="radio" name="approved" value="Approved"/>Approved<br>
											<input type="radio" name="approved" value="Rejected"/>Rejected<br>
										</fieldset>
										<fieldset>
											<legend>Details</legend>
											<input type="hidden" name="invoice_number" value="<?php echo $invoice_number; ?>"/>
											<input type="hidden" name="notes" value="<?php echo $notes; ?>"/>
											<input type="hidden" name="uid" value="<?php echo $uid; ?>"/>
											<input type="hidden" name="files" value="<?php echo $files; ?>"/>
											<?php
											echo "<label>Invoice Date:</label>".'<input class="text" name="placeholder" readonly=readonly value="'.$date.'">';
											echo "<label>Job Number:</label>".'<input class="text" name="placeholder" readonly=readonly value="'.$jobnumber.'">';			
											echo "<label>Vendor:</label>".'<input class="text" name="placeholder" readonly=readonly value="'.$vendor.'">';				
											echo "<label>Invoice #:</label>".'<input class="text" name="placeholder" readonly=readonly value="'.$invoice_number.'">';			
											echo "<label>Invoice Amount:</label>".'<input class="text" name="placeholder" readonly=readonly value="$'.money_format("%n",$invoice_amount).'">';		
											echo "<label>PO Number:</label>".'<input class="text" name="placeholder" readonly=readonly value="'.$po_number.'">';			
											echo "<label>Our Notes:</label>".'<textarea name="notes" cols="45" rows="3">'.	$notes .'</textarea>';
											?>
										</fieldset>	
										<fieldset>
											<legend>Other Info</legend>
											
											<label>Division</label> 
											<?php
											$select = '<select id="sel_div" name="division">';
											$select .= '<option value="">Select Division...</option>';
											foreach ($divisions as $key=>$value){
												$select .= '<option value="'. $key . '"';
												if( $key == $division){ $select.= ' selected>';} else {$select.= '>';}
												$select .= $value;
												$select .= '</option>';
											}
											$select .= '</select>';
											echo $select;
											?>
											<label>Alt. Email:</label><input class="text" type="email" name="alt_email" value=""/>
										</fieldset>	
										<fieldset>
											<legend>Reasons For Rejection</legend>
											<h5>All Materials and Subcontracts</h5>
											<input type="checkbox" name="rejected_reason[]" value="Invoice is not for this company"/>Invoice is not for this company<br>
											<input type="checkbox" name="rejected_reason[]" value="Amount Inaccurate"/>Amount is inaccurate<br>
											<input type="checkbox" name="rejected_reason[]" value="DateInaccurate"/>Date is inaccurate<br>
											<input type="checkbox" name="rejected_reason[]" value="PO Inaccurate or Missing"/>PO # inaccurate or missing<br>
											<input type="checkbox" name="rejected_reason[]" value="Work Material Not Satisfactory"/>Work/Material not satisfactory<br>
											<input type="checkbox" name="rejected_reason[]" value="Details are Missing or not Enough Detail is Present to Pay Invoice"/>Details are Missing or not Enough Detail is Present to Pay Invoice<br>
										
											<h5>Subcontracts Only</h5>
											<input type="checkbox" name="rejected_reason[]" value="WCB is Missing"/>WCB is Missing<br>
											<input type="checkbox" name="rejected_reason[]" value="10% Holdback Missing on Invoice"/>10% Holdback Missing on Invoice<br>
											
											<h5>Progress Invoices</h5>
											<input type="checkbox" name="rejected_reason[]" value="Progress Claim Number Missing"/>Progress claim number missing<br>
											<input type="checkbox" name="rejected_reason[]" value="Percentage Completed Missing or Inaccurate"/>Percentage of contract Missing or inaccurate<br>
											<input type="checkbox" name="rejected_reason[]" value="Statutory Declaration Missing"/>Statutory Declaration missing<br>
											<input type="checkbox" name="rejected_reason[]" value="Full Contract Amount Missing"/>Full contract amount missing<br>
											<input type="checkbox" name="rejected_reason[]" value="Previously Progress Invoiced Amount Missing"/>Previously invoiced amount not included or inaccurate<br>
											<h5>Comments to Vendor:</h5>
											<textarea name="rejection_notes" cols="45" rows="2"><?php echo $rejection_notes;?></textarea>
										</fieldset>
										<fieldset>
										<legend>Submit</legend>
										<input class="submit" type="submit" name="submit" value="Submit">
										</fieldset>
								</form>
								</div>
							</div>
							<!-- /Menu Buttons -->
							<!-- Form -->

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