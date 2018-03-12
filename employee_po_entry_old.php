<?php
	ini_set('display_errors',1);
	error_reporting(E_ALL);
/*////////////////////////////////////////////////////////////////////////////////
Includes
///////////////////////////////////////////////////////////////////////////////*/
	require('_config.php');
	require('Database.php');
	require('FormHtml.php');
	require_once("phpauthent/phpauthent_core.php");
	require_once("phpauthent/phpauthent_config.php");
	require_once("phpauthent/phpauthentadmin/locale/".$phpauth_language);
	require ('_functions_common.php');
	
/*////////////////////////////////////////////////////////////////////////////////
Page Protection
///////////////////////////////////////////////////////////////////////////////*/	
	$usersArray  = array();
	$groupsArray = array("admin","supervisor","employee");
	pageProtect($usersArray,$groupsArray);	

/*////////////////////////////////////////////////////////////////////////////////
Variables
///////////////////////////////////////////////////////////////////////////////*/
//get and set initial variables
	//$user 			= trim($_COOKIE['USERNAME']);
	$user 			= getUserName();
	$id				= $_GET['id'];
	$jobnumber 		= $_GET['edit_record'];
	$remove_item 	= $_GET['remove_item'];
	$remove_po		= $_GET['remove_po'];
	$refer_page 	= $_SERVER['HTTP_REFERER'];

/*////////////////////////////////////////////////////////////////////////////////
Get Additional Data
///////////////////////////////////////////////////////////////////////////////*/	

//connect to database
$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		
if ($mysql_link->connect_errno) {
	die($mysql_link->connect_error);
}


/*////////////////////////////////////////////////////////////////////////////////
Remove Item
///////////////////////////////////////////////////////////////////////////////*/

///add code for remove item
if ($remove_item!=""){
$refer_page 	= $_GET['refer_page'];
	$mysql_link->query("DELETE FROM $db_purchase_order_items WHERE id='$remove_item'" );
		
echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
echo "<br><b><span style=\"padding-left:2em;color:#f00;\"><big>Item Removed</big></span></b><br>";

}	
	

if ($remove_po != ""){
	$refer_page 	= $_GET['refer_page'];
		$mysql_link->query("DELETE FROM $db_purchase_order_items WHERE po_number='$remove_po'" );
		$mysql_link->query("DELETE FROM $db_purchase_order WHERE po_number='$remove_po'" );	
	echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
	echo "<br><br><b><big>Purchase Order - $remove_po<br>Successfully Removed</big></b><br><br><br>";
	echo 	'<input type="Button" value="Back" onclick="location.href=\''.$refer_page.'\';">';
	die();
}	
	
	
/*////////////////////////////////////////////////////////////////////////////////
Process Form
///////////////////////////////////////////////////////////////////////////////*/
if (isset($_POST['submit'])){
	//$_Post variables
	//print_r($_POST);
	$_POST = sanitize($_POST);

// process data entry - normal entry coding
	$id		 			= $_POST['id'];
	$jobnumber	 		= $_POST['jobnumber'];
	$po_number 			= $_POST['po_number'];
	$date_entered 		= $_POST['date_entered'];
	$vendor 			= $_POST['vendor'];
	$payment_method		= $_POST['payment_method'];
	$card_number 		= $_POST['card_number'];
	$notes 				= $_POST['notes'];
	$date_required 		= $_POST['date_required'];
	$invoice			= $_POST['invoice'];
	$date_received		= $_POST['date_received'];
	$approved 			= $_POST['approved'];
	$quantity			= $_POST['quantity'];
	$description		= $_POST['description'];
	$price				= $_POST['price'];
	$division			= $_POST['division'];
	$sub_division		= $_POST['sub_division'];
	$email_to			= $_POST['email_to'];
	$fob				= $_POST['fob'];
	$ship_via			= $_POST['ship_via'];
	$ship_street		= $_POST['ship_street'];
	$ship_city			= $_POST['ship_city'];
	$ship_province		= $_POST['ship_province'];
	$ship_postal_code	= $_POST['ship_postal_code'];
	$status				= $_POST['status'];
	$entered_by			= $user;
	$refer_page			= $_POST['refer_page'];

	if ($vendor==""){
		echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head';
		echo "<br><br><b><big>Enter all of the Information</b></big><br><br>";
		echo 	'<input type="Button" value="Back" onclick="history.go(-1)">';
		//print_r($_POST);
		die();
	}	
	
	if ($id==""){
		$date_entered		= date("Y-m-d");
		$sql = "INSERT INTO $db_purchase_order (
		 `jobnumber` ,
		 `po_number` ,
		 `date_entered` ,
		 `vendor` ,
		 `payment_method` ,
		 `card_number` ,
		 `notes` ,
		 `date_required` ,
		 `invoice` ,
		 `date_received` ,
		 `approved` ,
		 `email_to` ,
		 `fob`,
		 `ship_via`,
		 `ship_street`,
		 `ship_city`,
		 `ship_province`,
		 `ship_postal_code`,
		 `status`,
		 `entered_by`
		)
		VALUES (
		 '$jobnumber',
		 '$po_number',
		 '$date_entered',
		 '$vendor',
		 '$payment_method',
		 '$card_number',
		 '$notes',
		 '$date_required',
		 '$invoice',
		 '$date_received',
		 '$approved',
		 '$email_to',
		 '$fob',
		 '$ship_via',
		 '$ship_street',
		 '$ship_city',
		 '$ship_province',
		 '$ship_postal_code',
		 '$status',
		 '$entered_by'
		)";
	}
	else{
		$sql = "UPDATE $db_purchase_order
		SET  
		 jobnumber		='$jobnumber',
		 po_number		='$po_number',
		 vendor			='$vendor',
		 payment_method	='$payment_method',
		 card_number	='$card_number',
		 notes			='$notes',
		 date_required	='$date_required',
		 invoice		='$invoice',
		 date_received	='$date_received',
		 approved		='$approved',
		 email_to		='$email_to',
		 fob		 	='$fob',
		 ship_via		='$ship_via',
		 ship_street	='$ship_street',
		 ship_city		='$ship_city',
		 ship_province	='$ship_province',
		 ship_postal_code='$ship_postal_code',
		 status			='$status',
		 entered_by		='$entered_by'
		WHERE id = '$id'" ;	 
	}//end else
	//echo $sql;
	$retval = $mysql_link->query($sql);
		
		//write item data.
		$sql = "DELETE FROM $db_purchase_order_items WHERE po_number='$po_number';";
		$retval = $mysql_link->query($sql);
		$i=0;
			while($i<count($description))
			{
			
				$sql = "INSERT INTO $db_purchase_order_items (
				 `po_number` ,
				 `quantity` ,
				 `description` ,
				 `price` ,
				 `division` ,
				 `sub_division`
				)
				VALUES (
				 '$po_number',
				 '$quantity[$i]',
				 '$description[$i]',
				 '$price[$i]',
				 '$division[$i]',
				 '$sub_division[$i]'
				);";
				
				//echo $sql."<br>";
				$retval = $mysql_link->query($sql);
				  $i++;
			}//end while
			
	/////////////////////////////email po
	//change this to your email.
		$to = $email_to;
		
		//$to = "jasond@jdsservices.ca";
		$from = "noreply.po@" . $company_domain;
		$subject = "PO - ".$po_number;
		
	   $headers  = "From: $from\r\n";
		$headers .= "Content-type: text/html\r\n";
		//$headers .= "Cc: $accounting_email\r\n";
		$headers .= "Cc: $user_email\r\n";
		//options to send to cc+bcc
		//$headers .= "Cc: [email]jasond@jdsservices.ca[/email]";
		//$headers .= "Bcc: [email]jasond@jdsservices.ca[/email]";

		//begin of HTML message
	$message = '<html><head> 
	<style>
	table
	{
	border-collapse:collapse;
	}
	table,th, td
	{
	border: 1px solid black;
	padding: 3px;
	}
	</style>
	</head>
	<body bgcolor="#FFFFFF">';
	$message .= '<b>Purchase Order - '. $po_number .'</b><br>';
	$message .= 'Date:'. $date_entered. '<br><hr>';
	//$message .= 'Job Number:' . $job_number;

	$message .= '<div style="float:left; width:45%;">';

	$message .= "<b>Bill to:</b><br>$company_name<br>$company_street<br>$company_city, $company_province, $company_postal_code<br>P.$company_phone<br>F.$company_fax";
	$message .= '</div><div style="float:left; width:45%;">';
	$message .= "<b>Ship to:</b><br>$ship_name<br>$ship_street<br>$ship_city, $ship_province<br>$ship_postal_code";
	$message .= '</div><div style="clear:both;"></div><br><hr>';
	$message .= '</b>Vendor:<b>'. $vendor . "</b>";

	$message .= '<br><table width="85%"><tr><th>Date Reqd</th><th>Ship Via</th><th>Terms</th><th>FOB</th></tr></tr>';
	$message .= "<tr><td>$date_required</td><td>$ship_via</td><td>$payment_method</td><td>$fob</td></tr></table>";

	$message .= '<br><br><table width="85%">';
	$message .= '<tr><th width="10%">Item</th><th width="10%">Div</th><th width="10%">Sub-Div</th><th width="15%">Qty.</th><th width="35%">Description</th><th width="20%">Price</th>';
	$no_items = sizeof ($description) -1;
	for ($i = 0; $i <= $no_items; $i++) {
		$item = $i + 1;
		$message .= '<tr>';
		$message .= '<td>' . $item;
		$message .= '</td><td>'. $division[$i];
		$message .= '</td><td>'. $sub_division[$i];
		$message .= '</td><td>'. $quantity[$i];
		$message .= '</td><td>'. stripslashes($description[$i]);
		$amount = number_format($price[$i], 2, '.', ',');
		$message .= '</td><td>$'. $amount;
		//$message .= '<br><br>Total Price:$'. $total_price[$i];
		$message .= '</td></tr>';
			
	}
	$message .= '</table>';
	$message .= '<br>Notes:<br>'. $notes;
	$message .= '<br><hr>Requested By:'. $user;
		
		// now lets send the email.
		mail($to, $subject, $message, $headers);

		//echo "PO has been entered!";
		$total = 0;
		foreach ($price as $x){
			$total += $x;
		}
		if ($total > 250 && $payment_method == "Credit Card"){
			$amount = number_format($total, 2, '.', ',');
			$subject = "$user charged $amount to $card_number at $vendor";
			$message_alt = "$user charged $amount to $card_number.<br><br><hr>";
			$message_alt .= $message;
			//mail ($paul_email, $subject, $message_alt, $headers);
			mail ("accounting@jdsservices.ca", $subject, $message_alt, $headers);
		}
	//end email
	 
	echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
	echo "<br><br><b><big>Successfully Submitted</b></big><br><br>";
	echo 	'<input type="Button" value="Back" onclick="location.href=\''.$refer_page.'\';">';
	die();

}//end if submit

/*////////////////////////////////////////////////////////////////////////////////
Retrieve Variables for form editing
///////////////////////////////////////////////////////////////////////////////*/
if ($id !=""){
	//read form date 
	//echo $sql;
	$sql = "SELECT * FROM $db_purchase_order WHERE id = $id";
	$retval = $mysql_link->query($sql);

	//return single value
	$row = $retval->fetch_assoc();

	//$id		 			= $row['id'];
	$jobnumber	 		= $row['jobnumber'];
	$po_number 			= $row['po_number'];
	$vendor 			= $row['vendor'];
	$date_entered		= $row['date_entered'];
	$payment_method		= $row['payment_method'];
	$card_number 		= $row['card_number'];
	$notes 				= $row['notes'];
	$date_required 		= $row['date_required'];
	$invoice			= $row['invoice'];
	$date_received		= $row['date_received'];
	$approved 			= $row['approved'];
	$email_to			= $row['email_to'];
	 $fob				= $row['fob'];
	 $ship_via			= $row['ship_via'];
	 $ship_street		= $row['ship_street'];
	 $ship_city			= $row['ship_city'];
	 $ship_province		= $row['ship_province'];
	 $ship_postal_code	= $row['ship_postal_code'];
	 $status			= $row['status'];
	 $entered_by		= $row['entered_by'];

	//descriptions and details
		//read form date 
	$sql = "SELECT * FROM $db_purchase_order_items WHERE `po_number` = '$po_number'";
	//echo $sql;
	$retval = $mysql_link->query($sql);
		  while($row = $retval->fetch_assoc()){
		    $item_id[]		=$row['id'];
			$quantity[]		= $row['quantity'];
			$description[]	= $row['description'];
			$price[]		= $row['price'];
			$division[]		= $row['division'];
			$sub_division[]	= $row['sub_division'];
		  }//end while

}//end if id!=""
	else{
	//default form data
	$id		 			= "";
	$po_number 			= $jobnumber ."-". mt_rand(10000,99999);
	$vendor 			= "";
	$payment_method		= "";
	$card_number 		= "";
	$notes 				= "";
	$date_required 		= date("Y-m-d");
	$invoice			= "";
	$date_received		= "";
	$approved 			= "";
	$quantity			= "";
	$description[]		= "";
	$price[]			= "";
	$division			= "";
	$sub_division		= "";
	$email_to			= "";
	$fob				= "";
	 $ship_via			= "";
	 $ship_street		= "";
	 $ship_city			= "";
	 $ship_province		= "";
	 $ship_postal_code	= "";
	 $status			= "Open";
	 $entered_by		= $user;
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
										<h3>Purchase Order Entry</h3>
									</header>
									<div id="menu">
										<ul>
										<li><a href="<?php echo $refer_page;?>" ><span class="button-menu"><i class="fa fa-arrow-circle-o-left fa-fw"></i>&nbsp; Back</span></a></li>
										</ul>
									</div>
								</div>
							</div>
							<!-- /Menu Buttons -->
							<!-- Form -->
							<div class="row flush" style="padding:0em;">
								<div class="12u">
									<hr>
									<form class="formlayout" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="purchase_order">
										<div style="text-align: left;">
										<input type="hidden" name="date_entered" value="<?php echo $date_entered;?>">
										<input type="hidden" name="refer_page" value="<?php echo $refer_page;?>">
										<fieldset>
											<legend>Purchase Order Number<?php echo ": <b>$po_number</b>"; ?></legend>
											<label>Email PO to:</label>
											<input class="text" type="email" name="email_to" value="<?php echo $email_to; ?>">
											<div style="clear:both;"></div>
											*Please Note:<br>
											A copy will automatically be emailed to the office and to your email.
											<input type="hidden" name="id" value="<?php echo $id; ?>">
											<input type="hidden" name="jobnumber" value="<?php echo $jobnumber; ?>">
											<input type="hidden" name="po_number" value="<?php echo $po_number; ?>">
											<br>
											<input type="hidden" name="employee" value="<?php echo $user;?>">
										</fieldset>
										<fieldset>
											<legend>Info</legend>
											<label>Date Req'd:</label>
											<input class="text" id="datepicker1" name="date_required" type="textbox" readonly="readonly" placeholder="Date Req'd" size="14" value="<?php echo $date_required;?>">
											<div style="float:clear;"></div><br>
											<label>*Vendor:</label>
											<input class="text" name="vendor" type="text" placeholder="Vendor" size="20" value="<?php echo $vendor;?>">
											<label>*PMT Terms:</label>
											<select name="payment_method">
												<option selected>TBD</option>
												<option>Account</option>
												<option>Credit Card</option>
												<option>Cash</option>
												<option>Cheque</option>
												<option>Net 15</option>
												<option>Net 30</option>
												<option>Net 45</option>
											</select>
											<div style="clear:both;"></div>
											<br><hr>
											<?php
											/*<label>Last 4 digits:</label>
											<input type="number" name="card_number" size="10" placeholder="Last 4 digits" value="<?php echo $card_number;?>">
											*/
											?>
										<?php 
											$del_button =  '<a href="'.$_SERVER['PHP_SELF'].'?remove_po=' . $po_number . '&refer_page='. $refer_page .'" onclick="return confirm(\'Confirm Delete?\')" ><span style="padding-left:2em;color:#f00;"><i class="fa fa-minus-circle fa-lg fa-fw"></i>Delete Purchase Order</span></a>';
											if ($id) {echo $del_button;}
										?>
										</fieldset>
										<fieldset>
											<legend>Items</legend>
											<div style="float:clear;"></div>
											<div id="dynamicInput">
											<?php 
													$i=0;
													$var_count= count($description);
													if ($var_count<1){$var_count=1;}
													while($i<$var_count)
													{
														echo '<b>Item '. ($i + 1)  .'</b><br>';
														echo '<div id="form_data">';
														echo '<label>*Description:</label>';
														echo '<input class="text" name="description[]" type="text" placeholder="Description" size="20" value="'. $description[$i]. '">';
														echo '<label>Qty:</label>';
														echo '<input class="text" name="quantity[]" type="text" placeholder="Qty" size="4" value="'. $quantity[$i] .'">';
														echo '<label>Est Total:$</label>';		
														echo '<input class="text" name="price[]" type="text" placeholder="Est Total" size="10" value="'.$price[$i] .'">';
														echo '<div style="clear:both;"></div>';
														echo '<label>Division:</label>';

														$select = '<select id="sel_div" name="division[]">';
														$select .= '<option value="">Select Division...</option>';
														foreach ($divisions as $key=>$value){
															$select .= '<option value="'. $key . '"';
															if( $key == $division[$i]){ $select.= ' selected>';} else {$select.= '>';}
															$select .= $value;
															$select .= '</option>';
														}
														$select .= '</select>';
														echo $select;
														echo '<label>Sub-Div:</label>';	
														echo '<input class="text" name="sub_division[]" type="text" placeholder="Sub-Div" size="6" value="'. $sub_division[$i]. '">';
														echo '<div style="clear:both;"></div>';
														echo '</div>';
														if ($i>0 || $var_count>1){
															//echo '<a style="float:right;" href="'. $_SERVER['PHP_SELF']  .'?remove_item='.$item_id[$i] . '&id='. $id  .'">Remove<img src="images/delete.png" /></a>';
															echo '<a style="float:right;" href="'. $_SERVER['PHP_SELF']  .'?remove_item='.$item_id[$i] . '&id='. $id  .'&refer_page='.$refer_page.'"><img src="images/remove.png" />Remove Item</a>';
															}
														echo '<br><hr>';
														$i++;
											}//end while
											?>
											</div>
											<span id="writeroot"></span>
											<input class="submit" type="button" value="Add Another Item" onClick="addInput('dynamicInput');"><br>
											<input class="submit" value="Submit" type="submit" name="submit">
											<script>
												<?php echo 'var counter = ' . $var_count .';';  ?>
												var limit = 20;
												var formhtml = '<label>*Description:</label>';
														formhtml += '<input class="text" name="description[]" type="text" placeholder="Description" size="20" value="">';
														formhtml += '<label>Qty:</label>';
														formhtml += '<input class="text" name="quantity[]" type="text" placeholder="Qty" size="4" value="">';
														formhtml += '<label>Est Total:$</label>';		
														formhtml += '<input class="text" name="price[]" type="text" placeholder="Est Total" size="10" value="">';
														formhtml += '<div style="clear:both;"></div>';
														formhtml += '<label>Division:</label>';
														<?php
														echo "formhtml  += '<select id=\"sel_div\" name=\"division[]\">';\n";
														echo "formhtml  += '<option value=\"\">Select Division...</option>';\n";
														foreach ($divisions as $key=>$value){
															$val = str_replace("'", "\\'", $value);
															echo "formhtml  += '<option value=\"$key\">$val</option>';\n";
														}
														echo "formhtml  += '</select>';\n";
														?>
														formhtml += '<label>Sub-Div:</label>';
														formhtml += '<input class="text" name="sub_division[]" type="text" placeholder="Sub-Div" size="6" value="">';
														formhtml += '<div style="clear:both;"></div>';

												function addInput(divName){
													 if (counter == limit)  {
														  alert("You have reached the limit of adding " + counter + " items");
													 }
													 else { 
												  
													var div1 = document.createElement('div');  
												  
													// Get template data  
													div1.innerHTML = '<b>Item ' + (counter + 1) + '</b><br>';
													div1.innerHTML += formhtml; 
													div1.innerHTML += '<br><hr>'; 		
												  
													// append to our form, so that template data  
													//become part of form  
													document.getElementById(divName).appendChild(div1);  
													counter++;
													 }
											}
											</script>

											<div style="float:clear;"></div><br>
										</fieldset>
										<fieldset>
											<legend>Notes</legend>			
											<textarea cols="35" rows="5" name="notes"><?php echo $notes; ?></textarea> 
											<!--<label>Date Received:</label>	<input name="date_received" type="text" placeholder="date received"><br>-->
											<!--<label>Tracking:</label>		<input name="tracking" type="text" placeholder="tracking">-->
											<!--<label>Invoice Received:</label><input name="invoice_received" type="text" placeholder="invoice_received"><br>-->
											<!--<label>Paid:</label>			<input name="paid" type="text" placeholder="paid">-->
										</fieldset>
										<fieldset>
											<legend>Ship To</legend>
											<label>Street:</label>
											<input class="text" name="ship_street" type="text" placeholder="Street" size="20" value="<?php echo $ship_street;?>">
											<label>City:</label>
											<input class="text" name="ship_city" type="text" placeholder="City" size="20" value="<?php echo $ship_city;?>">
											<label>Prov.:</label>
											<input class="text" name="ship_province" type="text" placeholder="Province" size="20" value="<?php echo $ship_province;?>">
											<label>P. Code:</label>
											<input class="text" name="ship_postal_code" type="text" placeholder="Postal Code" size="20" value="<?php echo $ship_postal_code;?>">
											<label>Ship Via:</label>
											<input class="text" name="ship_via" type="text" placeholder="Ship Via" size="20" value="<?php echo $ship_via;?>">
											<label>FOB:</label>
											<input class="text" name="fob" type="text" placeholder="fob" size="20" value="<?php echo $fob;?>">
										</fieldset>

										<?php 
											if (isEnabled(array("administrator"),array("admin","payroll"))){
												echo '<fieldset><legend>Office</legend>';
												echo '<label>Status:</label>';
												echo '<select name="status"><option selected>Open</option>';
												echo '<option>Closed</option>';
												echo '<option>Cancelled</option>';
												echo '</select>';
												echo '<label>Invoice:</label><input class="text" type="text" name="invoice" value="'. $invoice  .'">';
												echo '<br>';
												echo '</fieldset>';
											}
											else{
												echo '<input type="hidden" name="status" value="'.$status . '">';
												echo '<input type="hidden" name="invoice" value="'.$invoice . '">';
											}
										?>

										<input class="submit" value="Submit" type="submit" name="submit"><br>
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
