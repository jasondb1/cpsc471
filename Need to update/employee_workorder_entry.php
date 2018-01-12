<?php	
	
	//includes
	include_once ("auth.php");
	include_once ("authconfig.php");
	include_once ("check.php");
	include_once ("cfg_variables.php");
	
	  if (($check['team']=='Contractor') )
    {
        echo 'You are not allowed to access this page.';
		exit();
    }
	
	//get and set initial variables
	$user = $_COOKIE['USERNAME'];
	$edit_record=$_GET['edit_record'];
	$id=$_GET['id'];
	$jobnumber=$edit_record;
	$send_to=$service_email;

	//fail out if record is blank
	if ($edit_record == "" && $id ==""){
	die ("Error: No Record is selected");
	}
	
	//connect to database
	//open database
			$connection = mysql_connect($dbhost, $dbusername, $dbpass);
			$SelectedDB = mysql_select_db($dbname) or die('Could not connect: ' . mysql_error());	
			
//get employee hours
		$sql = "SELECT * FROM $db_timelog WHERE jobnumber='$jobnumber'";
		$hours = mysql_query($sql) or die(mysql_error());

		$total_hours="";
		$hour_breakdown = "";
		$employee_hours="";
		while ($row = mysql_fetch_array($hours)) {
		$total_hours += $row['hours'];
		$employee=$row['employee'];
		$employee_hours[$employee] += $row['hours'];
		}
		
		foreach ($employee_hours as $key=>$value){
			$hour_breakdown .= $key . " - " . $value. "<br>";
		}
		
//get purchase orders
		//$sql = "SELECT * FROM $db_purchase_order_items WHERE po_number LIKE '%$jobnumber%' ORDER BY `po_number`";
				$sql="SELECT purchase_order_items.*, purchase_order.po_number, purchase_order.vendor, purchase_order.date_entered, purchase_order.id FROM purchase_order_items LEFT JOIN purchase_order ON (purchase_order_items.po_number = purchase_order.po_number) WHERE purchase_order_items.po_number LIKE '%$jobnumber%' ORDER BY purchase_order.date_entered DESC, purchase_order.po_number DESC";
				$po_result = mysql_query($sql) or die(mysql_error());
				$po_cols=array ("po_number"=>"PO","date_entered"=>"Date Entered","vendor"=>"Vendor","description"=>"Description","price"=>"Price","division"=>"Div");
		
		//put results in table
		$po_table = '<table style="border:1px solid black;border-collapse:collapse;font-size: 80%;line-height:1;"><tr>';
		
		//headings
		foreach ($po_cols as $key=>$value){
			$po_table .= "<th>$value</th>";
		}
		//data
		$po_table .= '</tr>';
		while ($row = mysql_fetch_array($po_result)) {
		//print_r ($row);
		$id_number = $row['id'];
		$po_table .= '<tr>';
			foreach ($po_cols as $key=>$value){
					if ($key=="price"){ $po_table .= '<td>'.sprintf("$%01.2f", $row[$key]).'</td>';}
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
		
//get change orders
		$sql = "SELECT * FROM $db_changeorder_items WHERE co_number LIKE '%$jobnumber%' ORDER BY `co_number`,`id`";
		$co_result = mysql_query($sql) or die(mysql_error());
		$co_cols=array ("co_number"=>"CO","description"=>"Description","price"=>"Price","division"=>"Div","accepted"=>"Accepted");
		
		//put results in table
		$co_table = '<table style="border:1px solid black;border-collapse:collapse;font-size: 80%;line-height:1;"><tr>';
		
		//headings
		foreach ($co_cols as $key=>$value){
			$co_table .= "<th>$value</th>";
		}
		//data
		$co_table .= '</tr>';
		while ($row = mysql_fetch_array($co_result)) {
		$co_table .= '<tr>';
			foreach ($co_cols as $key=>$value){
				$co_table .= '<td>'. $row[$key] .'</td>';
			}	
		$co_table .= '</tr>';		
		}
		$co_table .= '</table>';
	
/////////////////////////////////////////////////Process Form
	if (isset($_POST['submit'])){
	//   process data entry - normal entry coding

		 $jobnumber 		= $_POST['jobnumber'];
		 $ready_to_invoice 	= $_POST['ready_to_invoice'];
		 $quote			 	= $_POST['quote'];
		 $quote_number		= $_POST['quote_number'];
		 $status		 	= $_POST['status'];
		 $description	 	=$_POST['description'];
		 $solution			=$_POST['solution'];
		 $date1				=$_POST['date1'];
		 $location			=$_POST['location'];
		 $customer			=$_POST['customer'];
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
$retval = mysql_query($sql) or die(mysql_error());

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
$retval = mysql_query($sql) or die(mysql_error());

// //write log
			// //$data=$lname . ":Timelog Entry for $employee ID,$id,jn:$jobnumber,$start_time,$end_time";
			// //write_log ($logfile,$data  );

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
$message .= "<br><hr>Expenses:"; 
if ($expense_1 !="" || $cost_1!="") {$message .= "<br>Expense: \$$cost_1 - $expense_1";}
if ($expense_2 !="" || $cost_2!="") {$message .= "<br>Expense: \$$cost_2 - $expense_2";}
if ($expense_3 !="" || $cost_3!="") {$message .= "<br>Expense: \$$cost_3 - $expense_3";}
if ($expense_4 !="" || $cost_4!="") {$message .= "<br>Expense: \$$cost_4 - $expense_4";}
$message .= "<br><hr>";
$message .= "Comments:<br>$comments";
$message .= "<br><hr>";
$message .= "Office Comments:<br>$notes_office";
$message .= "<br><hr>Submitted By: $user<br>Approved (Office):____________";

$message = stripslashes($message);

$headers = "From: Workorder <workorder.noreply@". $company_domain . ">" . "\r\n";
$headers .= "Content-type: text/html\r\n";
//$headers .= "Cc: " . $email. "\r\n";

	if ( $ready_to_invoice == "1"){
		if(mail($send_to,$subject,$message, $headers)) {
			echo "Your request was sent to $send_to with the subject: $subject";
		} 
		else {
			echo "There was a problem sending the request. Check your code and make sure that the e-mail address $to is valid";
		}
	}		
	else { 
		$subject = "Update Work Order: " . $job_number . " - " . $location;
		//if(mail($send_to,$subject,$message, $headers)) {
		//echo "Thank You - Data Saved";
		//} else {
		//echo "There was a problem sending the request. Check your code and make sure that the e-mail address $to is valid";
		//}
		}
	
echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head';
echo "<br><br><b><big>Successfully Submitted</b></big><br><br>";
echo 	'<input type="Button" value="Back" onclick="history.go(-2)">';
die();

}//end if submit
///////////////////////////////////////////////////////////////
//Get variables for editing records
	//read data from jobfile 
	$sql = "SELECT * FROM $db_table_jobfile WHERE jobnumber='$jobnumber'";
	$retval = mysql_query($sql) or die(mysql_error());
	//return single value
	$row = mysql_fetch_array($retval);

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
	$retval = mysql_query($sql);
	//return single value
	$row = mysql_fetch_array($retval);

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
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<head><title>Workorder</title>
<meta name="keywords" content="JDS Construction Mobile iPhone">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="style.css" rel="stylesheet" type="text/css">
<script src="./js/jquery.js"></script>
<script src="./js/anytime.js"></script>
<link rel="stylesheet" type="text/css" href="./css/anytime.css" />
<style type="text/css">
  #date1{
    background-image:url("images/calendar.png");
    background-position:right center;
    background-repeat:no-repeat; }
</style>
</head>

<body>
<?php include ("./header.inc"); ?>
<?php include("./menu.inc"); ?>

<div class="content">
<!-- Place Content Below -->
<span class="hangingHead">Workorders</span><br>

 <form class="formLayout" style="background-color: rgb(204, 204, 204);" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="workorder">

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
	<br>
	<div style="clear:both;"></div>
	<label><b>Quote:</b></label>
		<select name="quote">
			<option></option>
			<option value="1" <?php if ($quote==1){echo " selected";}?>>Yes</option>
			<option value="0" <?php if ($quote==0){echo " selected";}?>>No</option>
		</select>
		<div style="clear:both;"></div>
		<label>Quote No.:</label><input type="number" name="quote_number" placeholder="Quote Number" value="<?php echo $quote_number;?>">
		<div style="clear:both;"></div>
</fieldset>
<fieldset>
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
	<label>Finished:</label><input id="date1" name="date1" type="textbox" readonly="readonly" value="<?php echo date("Y-m-d"); ?>">
	<br>
	<script>
		var time_format = "%h:%i %p";
		AnyTime.picker( "date1",{ format: "%Y-%m-%d"} );
	</script>
</fieldset>
<fieldset>
	<label>Location:</label><input name="location" type="textbox" value="<?php echo $location; ?>">
	<label>Customer:</label><input name="customer" type="textbox" value="<?php echo $customer; ?>">
	<label>Bill To:</label><input name="bill_to" type="textbox" value="<?php echo $bill_to; ?>">	
</fieldset>
<fieldset>	
<legend>Data:</legend>	
	<b>Problem/Description:</b><br>
		<textarea cols="35" rows="5" name="description"><?php echo $description; ?></textarea><br> 
	<b>Solution:</b><br>
		<textarea cols="35" rows="5" name="solution"><?php echo $solution; ?></textarea><br> 
</fieldset>
<fieldset>
<fieldset>
<legend>Hours and Costs:</legend>	
	Total Hours:<b><?php echo $total_hours;?></b><br>
	<?php echo $hour_breakdown; ?>
	<br>
</fieldset>

<fieldset>
<legend>Purchase Orders - <a href="employee_po_entry.php?jobnumber=<?php echo$jobnumber;?>">Add</a></legend>
<?php echo $po_table; ?>

</fieldset>
<fieldset>
<legend>Change Orders - <a href="employee_change_order_entry.php?jobnumber=<?php echo$jobnumber;?>">Add</a></legend>
<?php echo $co_table; ?>

</fieldset>
<!--
<fieldset>
<legend>Other Expenses:</legend>
<table style="border-collapse:collapse;">

<tr>
	<td>1</td>
	<td><input name="expense_1" type="textbox" value="<?php //echo $expense_1; ?>"></td>
	<td>$</td>
	<td><input name="cost_1" type="textbox" value="<?php //echo $cost_1; ?>"></td>
</tr>
<tr>
	<td>2</td>
	<td><input name="expense_2" type="textbox" value="<?php //echo $expense_2; ?>"></td>
	<td>$</td>
	<td><input name="cost_2" type="textbox" value="<?php //echo $cost_2; ?>"></td>
</tr>
<tr>
	<td>3</td>
	<td><input name="expense_3" type="textbox" value="<?php //echo $expense_3; ?>"></td>
	<td>$</td>
	<td><input name="cost_3" type="textbox" value="<?php //echo $cost_3; ?>"></td>
</tr>
<tr>
	<td>4</td>
	<td><input name="expense_4" type="textbox" value="<?php //echo $expense_4; ?>"></td>
	<td>$</td>
	<td><input name="cost_4" type="textbox" value="<?php //echo $cost_4; ?>"></td>
</tr>

</table>

</fieldset>
-->
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
<!-- End of user content -->
</div>
<?php include("./footer.inc"); ?>
</body>
</html>