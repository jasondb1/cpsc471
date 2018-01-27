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
Variables
///////////////////////////////////////////////////////////////////////////////*/
//get and set initial variables
	$user 			= $_COOKIE['USERNAME'];
	//$user_email = $check['email'];
	$id 			= $_GET['id'];
	$jobnumber 		= $_GET['jobnumber'];
	$remove_item 	= $_GET['remove_item'];
	$remove_co	 	= $_GET['remove_co'];
	$refer_page 	= $_SERVER['HTTP_REFERER'];

	//connect to database
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}

////////////////////////////////Process Remove item


///add code for remove item
if ($remove_item!=""){
	$refer_page 	= $_GET['refer_page'];
	$mysql_link->query("DELETE FROM $db_changeorder_items WHERE id='$remove_item'" );
		
	echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
	echo "<br><b><span style=\"padding-left:2em;color:#f00;\"><big>Item Removed</big></span></b><br>";
}		

if ($remove_co!=""){
	$refer_page 	= $_GET['refer_page'];
	$mysql_link->query("DELETE FROM $db_changeorder_items WHERE co_number='$remove_co'" );
	$mysql_link->query("DELETE FROM $db_changeorder WHERE co_number='$remove_co'" );
		
echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
echo "<br><br><b><big>Change Order - $remove_item<br>Successfully Removed</big></b><br><br><br>";
echo 	'<input type="Button" value="Back" onclick="location.href=\''.$refer_page.'\';">';
die();
}	

/*////////////////////////////////////////////////////////////////////////////////
Get Additional Data
///////////////////////////////////////////////////////////////////////////////*/
	
/*////////////////////////////////////////////////////////////////////////////////
Process Form
///////////////////////////////////////////////////////////////////////////////*/
	if (isset($_POST['submit'])){
	//write code for submitting
	$_POST = sanitize($_POST);

// process data entry - normal entry coding
	$id		 			= $_POST['id'];
	$jobnumber	 		= $_POST['jobnumber'];
	$item_number 		= $_POST['item_number'];
	$co_number 			= $_POST['co_number'];
	$date_entered 		= $_POST['date_entered'];
	$entered_by			= $_POST['entered_by'];
	$status		 		= $_POST['status'];
	$notes 				= $_POST['notes'];
	$date_of_completion	= $_POST['date_of_completion'];
	$invoice			= $_POST['invoice'];	
	$quantity			= $_POST['quantity'];
	$description		= $_POST['description'];
	$price				= $_POST['price'];
	$division			= $_POST['division'];
	$sub_division		= $_POST['sub_division'];
	$accepted			= $_POST['accepted'];
	$email_to			= $_POST['email_to'];
	$refer_page			= $_POST['refer_page'];

	if ($id==""){
		$date_entered		= date("Y-m-d");
		$sql = "INSERT INTO $db_changeorder (
		 `jobnumber` ,
		 `item_number` ,
		 `co_number` ,
		 `date_entered` ,
		 `entered_by` ,
		 `status` ,
		 `notes` ,
		 `date_of_completion` ,
		 `invoice`
		)
		VALUES (
		 '$jobnumber',
		 '$item_number',
		 '$co_number',
		 '$date_entered',
		 '$entered_by',
		 '$status',
		 '$notes',
		 '$date_of_completion',
		 '$invoice'
		);";
	}
	else{
		$sql = "UPDATE $db_changeorder
		SET  
		 jobnumber		='$jobnumber',
		 item_number	='$item_number',
		 co_number		='$co_number',
		 date_entered	='$date_entered',
		 entered_by		='$entered_by',
		 status			='$status',
		 notes			='$notes',
		 date_of_completion	='$date_of_completion',
		 invoice		='$invoice'
		WHERE id = '$id';" ;	 
	}//end else
//echo $sql;
$retval = $mysql_link->query($sql);
	
	//write item data
	$sql = "DELETE FROM $db_changeorder_items WHERE co_number='$co_number';";
	$retval =$mysql_link->query($sql);
	$i=0;
		while($i<count($description))
		{
			$sql = "						
			INSERT INTO $db_changeorder_items (
			 `co_number` ,
			 `quantity` ,
			 `description` ,
			 `price` ,
			 `division` ,
			 `sub_division`,
			 `accepted`
			)
			VALUES (
			 '$co_number',
			 '$quantity[$i]',
			 '$description[$i]',
			 '$price[$i]',
			 '$division[$i]',
			 '$sub_division[$i]',
			 '$accepted[$i]'
			);";
			
			//echo $sql."<br>";
			$retval = $mysql_link->query($sql);
			  $i++;
		}//end while
		
/////////////////////////////email po
//change this to your email.
    $to = $email_to;
	
	//$to = "jasond@jdsservices.ca";
    $from = "noreply.co@" . $company_domain;
    $subject = "CO - ".$co_number;
	
   $headers  = "From: $from\r\n";
    $headers .= "Content-type: text/html\r\n";
	$headers .= "Cc: $office_email\r\n";
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
$message .= '<b>Change Order - '. $co_number .'</b><br>';
$message .= 'Date:'. $date_entered. '<br><hr>';
//$message .= 'Job Number:' . $job_number;

$message .= '<div style="float:left; width:45%;">';

$message .= "<b>Bill to:</b><br>$company_name<br>$company_street<br>$company_city, $company_province, $company_postal_code<br>P.$company_phone<br>F.$company_fax";


$message .= '<br><br><table width="85%">';
$message .= '<tr><th width="10%">Item</th><th width="10%">Div</th><th width="10%">Sub-Div</th><th width="35%">Description</th><th width="20%">Price</th>';
$no_items = sizeof ($description) -1;
for ($i = 0; $i <= $no_items; $i++) {
	$item = $i + 1;
	$message .= '<tr>';
	$message .= '<td>' . $item;
	$message .= '</td><td>'. $division[$i];
	$message .= '</td><td>'. $sub_division[$i];
	//$message .= '</td><td>'. $quantity[$i];
	$message .= '</td><td>'. $description[$i];
	if (!$price[$i]){$price[$i]=0;}
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

    //echo "CO has been entered!";
//end email
		
  
echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
echo "<br><br><b><big>Change Order - $co_number <br>Successfully Created</big></b><br><br>";
echo 	'<input type="Button" value="Back" onclick="location.href=\''.$refer_page.'\';">';

die();

}//end if submit


/*////////////////////////////////////////////////////////////////////////////////
Retrieve Variables for form editing
///////////////////////////////////////////////////////////////////////////////*/
if ($id !=""){
	//read form date 
	//echo $sql;
	$sql = "SELECT * FROM $db_changeorder WHERE id = $id";
	$retval = $mysql_link->query($sql);

	//return single value
	$row = $retval->fetch_assoc();

	$jobnumber	 		= $row['jobnumber'];
	$item_number 		= $row['item_number'];
	$co_number 			= $row['co_number'];
	$date_entered 		= $row['date_entered'];
	$entered_by			= $row['entered_by'];
	$status		 		= $row['status'];
	$notes 				= $row['notes'];
	$date_of_completion	= $row['date_of_completion'];
	$invoice			= $row['invoice'];

	//descriptions and details
		//read form date 
	$sql = "SELECT * FROM $db_changeorder_items WHERE `co_number` = '$co_number' ORDER BY `id`";
	$retval = $mysql_link->query($sql);
		  while($row = $retval->fetch_assoc()){
		    $item_id[]		= $row['id'];
			//$quantity[]		= $row['quantity'];
			$description[]	= $row['description'];
			$price[]		= $row['price'];
			$division[]		= $row['division'];
			$sub_division[]	= $row['sub_division'];
			$accepted[]		= $row['accepted'];
		  }//end while

}//end if id!=""
	else{
	
		$q = "SELECT MAX(item_number) AS max_item FROM $db_changeorder WHERE jobnumber = '$jobnumber'";
	$result =$mysql_link->query($q);
	$data = $result->fetch_assoc();
	$item_number = $data['max_item']+1;
		//default form data
		$id		 			= "";
		$jobnumber			= $jobnumber;
		$item_number		= $item_number;
		$co_number 			= $jobnumber ."-". $item_number;
		$date_entered		= date("Y-m-d");
		$entered_by			= $user;
		$status		 		= "Open";
		$notes 				= "";
		$date_of_completion = "";
		$invoice			= "";
		$description[]		= "";
	
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
										<h3>Change Order Entry</h3>
									</header>
									<div id="menu">
										<ul>
										<li><a href="<? echo $refer_page; ?>" ><span class="button-menu"><i class="fa fa-arrow-circle-o-left fa-fw"></i>&nbsp; Back</span></a></li>
										</ul>
									</div>
								</div>
							</div>
							<!-- /Menu Buttons -->
							<!-- Form -->
							<div class="row flush" style="padding:0em;">
								<div class="12u">
									<hr>
									<form class="formLayout" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="change_order">
									<input type="hidden" name="date_entered" value=<?php echo $date_entered;?>>
									<input type="hidden" name="refer_page" value=<?php echo $refer_page;?>>
									<input type="hidden" name="co_number" value=<?php echo $co_number;?>>
									<input type="hidden" name="item_number" value=<?php echo $item_number;?>>
									<div style="text-align: left;">
													
									<fieldset>
									<legend>Change Order Number<?php echo ": <b>$co_number</b>"; ?>
									
									</legend>
									<br>
									<label>Email CO to:</label>
									<input class="text" type="email" name="email_to">
									<div style="clear:both;"></div>
									<br>
									*Please Note:<br>
									A copy will automatically be emailed to the office and to your email.
									<input type="hidden" name="id" value="<?php echo $id; ?>">
									<br>
									<input type="hidden" name="entered_by" value="<?php echo $entered_by;?>">
									</fieldset>

									<fieldset>
									<legend>Info</legend>
									<label>Job Number:</label><input class="text" type="text" readonly="readonly" name="jobnumber" value="<?php echo $jobnumber;?>"><br>
									<div style="float:clear;"></div>
									<label>Est. Date of Completion:</label><input class="text" id="datepicker1" name="date_of_completion" type="textbox" readonly="readonly" value="<?php echo $date_of_completion; ?>">
									<script>
										var date_format = "%Y-%m-%d";
										AnyTime.picker( "datepicker1",{ format: date_format} );
										</script>
									<br><hr>
									<?php $del_button =  '<a href="'.$_SERVER['PHP_SELF'].'?remove_co=' . $co_number . '&refer_page='. $refer_page .'" onclick="return confirm(\'Confirm Delete?\')" ><span style="padding-left:2em;color:#f00;"><i class="fa fa-minus-circle fa-lg fa-fw"></i>Delete Change Order</span></a>';
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
											while($i<count($description))
											{
									echo '<b>Item '. ($i + 1)  .'</b><br>';
									echo '<div id="form_data">';
									//echo '<label>Qty:</label>';
									//echo '<input name="quantity[]" type="text" placeholder="Qty" size="4" value="'. $quantity[$i] .'"><br>';
									echo '<label>*Description:</label>';	
									echo '<input class="text" name="description[]" type="text" placeholder="Description" size="35" value="'. $description[$i]. '">';
									echo '<br>';
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
									echo '<br><label>Sub-Div:</label>';	
									echo '<input name="sub_division[]" class="text" type="text" placeholder="Sub-Div" size="6" value="'. $sub_division[$i]. '">';
									//echo '<br>';
									echo '<div style="clear:both;"></div>';
									echo '<label>Accepted:</label>';
									echo '<input name="accepted[]" type="checkbox" value="'. $accepted[$i]. '">';

												echo '<div style="clear:both;"></div>';



									echo '</div>';
									echo '<a style="float:right;" href="'. $_SERVER['PHP_SELF']  .'?remove_item='.$item_id[$i] . '&id='. $id  .'&refer_page='.$refer_page.'">Remove Item<img src="images/remove.png" /></a>';
									echo '<br><hr>';

									$i++;
									}//end while
									?>
									</div>
									<span id="writeroot"></span>
									<input class="submit" type="button" value="Add Another Item" onClick="addInput('dynamicInput');">
									<input class="submit" value="Submit" type="submit" name="submit">
									<script>
										<?php echo 'var counter = ' . $var_count .';';  ?>
										var limit = 20;
										var formhtml = document.getElementById('form_data').innerHTML;

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
											div1.innerHTML += '</hr>'; 		
										  
											// append to our form, so that template data  
											//become part of form  
											document.getElementById(divName).appendChild(div1);  
											counter++;
											 }
									}
									</script>

									<div style="float:clear;"></div><br>
									</fieldset><fieldset>
									<legend>Notes</legend>			
									<textarea cols="35" rows="5" name="notes"><?php echo $notes; ?></textarea><br> 
									<br>
									</fieldset>
									<?php 
									if (isSessionUserInGroup($groupsArray))								
									{
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
									
										<input class="submit" value="Submit" type="submit" name="submit"><br><br>

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