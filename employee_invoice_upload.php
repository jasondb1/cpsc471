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
	$user 			= getUsername();
	$user_email 	= getEmail(getUserId(),"phpauthent_users");
	$path="./files/vendor_invoices/";//change later as required
	//$path= $_SERVER['DOCUMENT_ROOT']."/files/vendor_invoices/";
	$uid = $_GET['id'];
	$invoice_date= date ("Y-m-d");
	$previous = $_SERVER['HTTP_REFERER'];
	
	$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
	if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}

/*////////////////////////////////////////////////////////////////////////////////
Process Form
///////////////////////////////////////////////////////////////////////////////*/
if (isset($_POST['submit'])){
	//get posted data
	 $id				= $_POST['id'];
	 $jobnumber			= $_POST['jobnumber'];
	 $invoice_number	= $_POST['invoice_number']; 
	 $invoice_amount	= $_POST['invoice_amount']; 
	 $invoice_date		= $_POST['invoice_date'];
	 $division			= $_POST['division']; 
	 $date_entered		= date ("Y-m-d"); 
	 $date_approved		= ""; 
	 $date_rejected		= ""; 
	 $status			= $_POST['status']; 
	 $notes				= $_POST['notes']; 
	 $filename			= $_FILES["file"]["name"];
	 $po_number			= $_POST['po_number'];
	 $uid				= $_POST['uid'];
	 $previous_page		= $_POST['previous_page'];
	 $vendor			= $_POST['vendor'];
 
	 //if file previously uploaded
	 if ($filename==""){
	 $filename = $_POST['prev_file'];
	 $fname=$filename;
	 }
	 
	 //validate entry	
		$invoice_amount= preg_replace('/[\$,]/', '', $invoice_amount); 
	// if($invoice_number =="" || $jobnumber=="" || $vendor=="" || $invoice_amount=="" || $filename==""){
		// //echo $invoice_number.$jobnumber,$vendor,$invoice_amount,$filename;
		// echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
		// echo "<br><br><b><big>Hey! You forgot some information!</b></big><br>Fill in all of the fields marked with an *<br><br>";
		// echo 	'<input type="Button" value="Back" onclick="history.go(-2)">';
		// //print_r ($_POST);
		// //echo "filename:".$filename;
		// die();
	// }
	
	//detemine if duplicate-Future only evaluate if not being edited
	if (!isset ($_POST['edit'])){
	
	$sql = "SELECT * FROM vendor_invoices WHERE `invoice_number` = '" . $invoice_number . "' AND `vendor` = '" . $vendor . "'";
		$query = $mysql_link->query($sql);
		// if (mysql_num_rows($query) > 0)
		// {
		 // echo "Invoice already exists. Please check invoice number or edit existing record";
		 
		 // echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
		// echo "<br><br><b><big>Duplicate Invoice</b></big><br><br>";
		// echo '<input type="Button" value="Back" onclick="location.href=\''. $_SERVER['PHP_SELF']    .'\'">';
		// die();
		// }
	}
		
	//upload and save file
	if ($_FILES["file"]["error"] > 0)
	  {
	  echo "Error: " . $_FILES["file"]["error"] . "<br>";
	  }
	else
	  {
	  echo "Upload: " . $_FILES["file"]["name"] . "<br>";
	  echo "Type: " . $_FILES["file"]["type"] . "<br>";
	  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
	  $path_parts = pathinfo($_FILES["file"]["name"]);
	  $extension = $path_parts['extension'];
	  $fname = $vendor . "_".stripslashes($invoice_number) . "." . $extension;
	  echo $fname;
	  move_uploaded_file($_FILES["file"]["tmp_name"],"$path/" . $fname);
	  echo "<br>Stored in: " . "$path" . $fname ."\n\n";
		  //}
	  }
	  $files=$fname;
	  
	  //open database and write data
	if (!isset ($_POST['edit'])){
		$uid = md5($vendor.$invoice_number);
		$sql = "INSERT INTO vendor_invoices (
		 `jobnumber`,
		 `invoice_number`,
		 `amount`,
		 `division`,
		 `status`,
		 `date_entered`,
		 `notes`,
		 `vendor`,
		 `filename`,
		 `uid`,
		 `po_number`,
		 `invoice_date`,
		 `sent_to`
		)
		VALUES (
		 '$jobnumber',
		 '$invoice_number',
		 '$invoice_amount',
		 '$division',
		 '$status',
		 '$date_entered',
		 '$notes',
		 '$vendor',
		 '$fname',
		 '$uid',
		 '$po_number',
		 '$invoice_date',
		 '$sent_to'
		)";
	}
	else{
		$sql = "UPDATE vendor_invoices
		SET  	
		 `jobnumber` 		= '$jobnumber',
		 `invoice_number`	= '$invoice_number',
		 `amount`			= '$invoice_amount',
		 `division`		 	= '$division',
		 `status` 			= '$status',
		 `notes` 			= '$notes',
		 `po_number`		= '$po_number',
		 `filename`			= '$fname',
		 `sent_to`			= '$sent_to'
		 
		WHERE `uid` = '$uid'" ;	 
	}//end else
	//echo $sql;
	$retval = $mysql_link->query($sql);
	  
//get email
	  //email approval to supervisor
		$sql = "SELECT jobdata.jobnumber,jobdata.supervisor,phpauthent_users.email 
		FROM jobdata 
		LEFT JOIN phpauthent_users ON jobdata.supervisor=phpauthent_users.username 
		WHERE jobdata.jobnumber = '$jobnumber'";
//echo $sql;
		$result = $mysql_link->query($sql);
		$row = $result->fetch_assoc();
		$email_to = $row['email'];
		//print_r ($row);
//echo "email:".$row['email'];
		//error if no email
			if($email_to == "" && $_POST['override_email']==""){
			 die("No Email is associated with this jobnumber");
			}
		
		//email approval to accounting	
				$to = $email_to;
				if ($_POST['override_email']!=""){
					$to = $_POST['override_email'];
				}
				//$to = "jasond@jdsservices.ca";//test email
				$from = "noreply.invoices@amcgroup.ca";
				$reply_to = "noreply.invoices@amcgroup.ca";
				$my_name = "AMC Group";
				$subject = "Vendor Invoice - $vendor - $invoice_number - \$$invoice_amount";

		//html message
			$message = '<head>
			<style>
			table {border-collapse: collapse; empty-cells: show ; border:1px solid black;} 
			td,th {border:1px solid black;} 
			</style> 
			</head>';
			$message .= '<html><body bgcolor="#FFFFFF">';
			$message .= '<b>Vendor Invoice</b><br><br>';

			$message .= 'Approve Invoice - ';
			$message .= '<a href="http://'.$company_web.'/employee_invoice_approve.php?ref='.$uid.'" >Link</a>';
			if (!isset ($_POST['edit'])){
			$message .= '<br><br>***Invoice Updated<br>';
			}
			$message .= '</body></html>';
			//end of message
				//echo "variables:".$files.$path.$to.$from.$my_name,$reply_to,$subject,$message;
				mail_attachment($files, $path, $to, $from, $my_name, $reply_to, $subject, $message);
//die();
			echo "<br><br>Received Invoice has been sent to:$to";	  
	  
	echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
	echo "<br><br><b><big>Successful</b></big><br><br>";
	//echo '<input type="Button" value="Back" onclick="location.href=\''. $_SERVER['PHP_SELF']    .'\'">';
	echo '<a href="'.$previous_page.'">Back</a>';
	die();
}// end submit processing form

/*////////////////////////////////////////////////////////////////////////////////
Information to edit
///////////////////////////////////////////////////////////////////////////////*/
if ($uid !=""){
	//read form date 
	$sql = "SELECT * FROM vendor_invoices WHERE `uid` = '$id'";
	//echo $sql;
	//echo "Here";
	$retval = $mysql_link->($sql);

	//return single value
	$row = $retval->fetch_assoc()	;
	 $id				= $row['id'];
	 $jobnumber			= $row['jobnumber'];
	 $invoice_number	= $row['invoice_number'];
	 $invoice_amount	= $row['amount']; 
	 $division			= $row['division']; 
	 $date_entered		= $row['date_entered'];
	 $date_approved		= $row['date_approved']; 
	 $date_rejected		= $row['date_rejected']; 
	 $status			= $row['status']; 
	 $notes				= $row['notes']; 
	 $filename			= $row['filename'];
	 $po_number			= $row['po_number'];
	 $vendor			= $row['vendor'];
	 $invoice_date		= $row['invoice_date'];
	 $uid				= $row['uid']; 
}//end if id!=""

/*////////////////////////////////////////////////////////////////////////////////
Get Data
///////////////////////////////////////////////////////////////////////////////*/
		foreach ($company_databases as $key=>$value){	
			$sql = "SELECT jobnumber FROM $key". $where .$order;
			$result = $mysql_link->query($sql);
			//output table
			//display jobs and link to time entry sheet	  
			  while($row = $result->fetch_assoc()){
				  $job_list[] = $row['jobnumber'];
				}	//end while
		}
			
		$jobselect = '<select name="jobnumber"><option></option>';
		foreach ($job_list as $value){
			if ($jobnumber==$value){$jobselect .= '<option selected>'. $value   .'</option>';}
			else{
			$jobselect .= '<option>'. $value   .'</option>';
			}
		}
		$jobselect .= '	</select>';
		
		$sql 	= "SELECT Vendor FROM vendor_data";
		$result = $mysql_link->query($sql);
	
		while ($row = $result->fetch_assoc()) {
			$vendor_list[]=$row['Vendor'];
		}
		sort ($vendor_list);
			
		$vendor_select = '<select name="vendor"><option></option>';
		foreach ($vendor_list as $value){
			if ($vendor==$value){$vendor_select .= '<option selected>'. $value   .'</option>';}
			else{
				$vendor_select .= '<option>'. $value   .'</option>';
			}
		}
		$vendor_select .= '	</select>';

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
										<h3>Invoice Entry</h3>
									</header>
									<div id="menu">
										<ul>
										<li><a href="employee_invoice_view.php" ><span class="button-menu"><i class="fa fa-arrow-circle-o-left fa-fw"></i>&nbsp; View Invoices</span></a></li>
										</ul>
									</div>
								</div>
							</div>
							<!-- /Menu Buttons -->
							<!-- Form -->
							<div class="row flush" style="padding:0em;">
								<div class="12u">
									<hr>
									<form class="formLayout" action="<?php echo $_SERVER['PHP_SELF'];  ?>" method="post" enctype="multipart/form-data">
									<?php
									if ($uid !=""){echo '<input type="hidden" name="edit" value="1" />';}
									if ($uid !=""){echo '<input type="hidden" name="uid" value="'. $uid .'" />';}
									?>
									<input type="hidden" name="previous_page" value="<?php echo $previous;?>" />
									<fieldset>
										<legend>*Vendor</legend>
										<label><a href="employee_vendor_add.php" target="_blank">Add</a> Vendor:</label><?php echo $vendor_select; ?><br>
									</fieldset>
									<fieldset>
										<legend>*File</legend>
										<label>Previous File</label><?php echo $filename;?>
										<label for="file">Filename:</label>
										<input type="hidden" name="prev_file" value="<?php echo $filename;?>"/>
										<input type="file" name="file" id="file" value="<?php echo $filename;?>">
										<a href="<? echo $path . $filename;?>" target="_blank" ><img src="dashboard/img/doc.png" /></a><br>
									</fieldset>
									<fieldset>
										<legend>Invoice Details</legend>
										<label>*Job no.:</label><?php echo $jobselect; ?><br>			
										<label>*Invoice no.:</label><input type="textbox" name="invoice_number" value="<?php echo $invoice_number;?>" /><br>
										<label>*Invoice Amt.:</label><input type="textbox" name="invoice_amount" value="<?php echo $invoice_amount;?>" /><br>
										<label>Invoice Date:</label><input id="datepicker1" name="invoice_date" readonly="readonly" type="textbox" value="<?php echo $invoice_date; ?>">
										<label>Division no.:</label>
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
										?><br>
										<label>PO. Number:</label><input type="textbox" name="po_number" value="<?php echo $po_number;?>" /><br>
										<label>Status:</label>
										<select name="status">
											<option selected="selected">Received</option>
											<option>Approved</option>
											<option>Rejected</option>
											<option>Partial Payment</option>
											<option>Paid in Full</option>
										</select>
										<br>
									</fieldset>			
									<fieldset>
										<legend>Notes</legend>
										<textarea name="notes" cols="45" rows="3"><?php echo $notes;?></textarea>
									</fieldset>
									<fieldset>
										<legend>Override Email</legend>
										<input type="email" name="override_email" />
									</fieldset>
									<br><br>
									<input class="submit" type="submit" name="submit" value="Submit"><br>
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