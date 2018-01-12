<?php
// ini_set('display_errors', 1);
// ini_set('log_errors', 1);
// ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
// error_reporting(E_ALL);

	/*
		Place code to connect to your DB here.
	*/
	include_once ("auth.php");
	include_once ("authconfig.php");
	include_once ("check.php");
	include_once ("cfg_variables.php");
	$user = $_COOKIE['USERNAME'];

  if (($check['team']=='Contractor') )
    {
        echo 'You are not allowed to access this page.';
		exit();
    }
	
	//open database
			$connection = mysql_connect($dbhost, $dbusername, $dbpass);
			$SelectedDB = mysql_select_db($dbname);	
			
			if(! $connection ){
			die('Could not connect: ' . mysql_error());
			}	

			
// /////////////////////////Process Form when Submitted
if (isset($_POST['submit'])){
	// //write code for submitting
	//$_Post variables

	 $jobnumber		= $_POST['jobnumber']; 
	 $description	= $_POST['description'];
	 $location		= $_POST['location'];
	 $customer		= $_POST['customer'];
	 $bill_to		= $_POST['bill_to'];
	 $supervisor	= $_POST['supervisor'];
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


//validate entry	 
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
	$retval = mysql_query($sql) or die(mysql_error());
	echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
	echo "<br><br><b><big>Successfully Submitted</b></big><br><br>";
	echo 	'<input type="Button" value="Back" onclick="location.href=\'employee_job_database.php\'">';
	die();
}//end if submit

//get employee names
$sql = "SELECT uname FROM $db_employee WHERE status='active'";
$retval = mysql_query($sql) or die(mysql_error());

while ($row = mysql_fetch_array($retval)) {
    $employee_list[]=$row['uname'];
}

$edit_record = $_REQUEST['record_edit'];

if ($edit_record !=""){
	//read form date 
	$sql = "SELECT * FROM $db_table_jobfile WHERE jobnumber = $edit_record";
	$retval = mysql_query($sql) or die(mysql_error());

	//return single value
	$row = mysql_fetch_array($retval);

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
	
	
<html>
<head>
	<title>Jobfile Entry</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="DESCRIPTION" content="Jobfile Entry">
	<meta name="KEYWORDS" content="condominium, maintenance, repairs, general, renovations, flooding, construction, landscaping, snow removal, garbage cleanup, project, management, line painting, sweeping, excavating, rental">
	<link href="style.css" rel="stylesheet" type="text/css">
	
	
</head>

<body>
<?php include ("./header.inc"); ?>
<?php include("./menu.inc"); ?>

<div class="content">
<!-- Place Content Below -->

<form style="background-color: rgb(255, 255, 255);" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" name="jobedit">
<input type="submit" name="submit" value="Submit"/>&nbsp;
<input type="submit" name="submit" value="Cancel" onclick="document.jobedit.action='employee_job_database.php';"/>&nbsp;
<?php if ($edit_record !=""){
echo '<input type="submit" name="goto" value="Goto Workorder" onclick="document.jobedit.action=\'employee_workorder_entry.php?edit_record='.$jobnumber .'\';"/>';
}
?>
<br>
<br>
<input type="hidden" name="opened_by" value="<?php echo $opened_by; ?>"  >
<input type="hidden" name="date_opened" value="<?php echo $date_opened; ?>"  >
<input type="hidden" name="date_invoiced" value="<?php echo $date_invoiced; ?>"  >
<input type="hidden" name="date_closed" value="<?php echo $date_closed; ?>"  >
<input type="hidden" name="jobnumber" value="<?php echo $jobnumber; ?>"  >	
 
 
<b>Job Entry</b> 
 
<table class="formtablerequest" style="text-align: left; width: 100%;" border="0" cellpadding="1" cellspacing="1">
    <tbody>
	<tr>
        <th style="text-align: right; width: 120px;">Job Number:</th>
        <td><?php echo $jobnumber; ?></td>
      </tr>
      <tr>
        <th style="text-align: right;">*Description:</th>
        <td><input size="30" name="description" value="<?php echo $description; ?>"  ></td>
      </tr>
      <tr>
        <th style="text-align: right;">*Location:</th>
        <td><input size="30" name="location" value="<?php echo $location; ?>"></td>
      </tr>
	  <tr>
	  <td>&nbsp;</td><td></td>
	  </tr>
	  <tr>
        <th style="text-align: right;">*Customer:</th>
        <td><input size="30" name="customer" value="<?php echo $customer; ?>"></td>
      </tr>
	  <tr>
        <th style="text-align: right;">*Bill To:</th>
        <td><input size="30" name="bill_to" value="<?php echo $bill_to; ?>"></td>
      </tr>
      <tr>
        <th style="text-align: right;">*Supervisor:</th>
        		<td><select name="supervisor">
		<?php
		foreach ($employee_list as $key=>$value){
		echo '<option';
		if ($supervisor==$value){echo " selected>";}else {echo ">";}
		echo $value;
		echo '</option>';
		}
		?>
		</select></td>
		
      </tr>
	  
      <tr>
        <th style="text-align: right;">Status:</th>
        <td>
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
        <br>
        </td>
      </tr>
	  <tr>
	  <td>&nbsp;</td><td></td>
	  </tr>
	   <tr>
        <th style="text-align: right;">Contact Name:</th>
        <td><input name="contact_name"  value="<?php echo $contact_name; ?>"></td>
      </tr>
	  <tr>
        <th style="text-align: right;">Contact Phone:</th>
        <td><input name="contact_number"  value="<?php echo $contact_number; ?>"></td>
      </tr>
	    <tr>
	  <td>&nbsp;</td><td></td>
	  </tr>
      <tr>
        <th style="text-align: right;">Start Date:</th>
        <td><input name="start_date"  value="<?php echo $start_date; ?>"></td>
      </tr>
      <tr>
        <th style="text-align: right;">End Date:</th>
        <td><input name="end_date" value="<?php echo $end_date; ?>"></td>
      </tr>
      <tr>
        <th style="text-align: right;">Quote #:</th>
        <td><input name="quote_number" value="<?php echo $quote_number; ?>"></td>
      </tr>
      <tr>
        <th style="text-align: right;">PO #:</th>
        <td><input name="po_number"  value="<?php echo $po_number; ?>"></td>
      </tr>
	  <tr>
	  <td>&nbsp;</td><td></td>
	  </tr>
	   <tr>
        <th style="text-align: right;">Invoice:</th>
        <td><input name="invoice_number" value="<?php echo $invoice_number; ?>"></td>
      </tr>
	  <tr>
	  <td>&nbsp;</td><td></td>
	  </tr>
	  <tr>
        <th style="text-align: right;">Require Div:</th>
        <td><input type="checkbox" name="require_div" value="1" <?php if ($require_div==1){echo 'checked="checked"';}?>></td>
      </tr>
	  <tr>
        <th style="text-align: right;">Require Sub-Div:</th>
        <td><input type="checkbox" name="require_subdiv" value="1" <?php if ($require_subdiv==1){echo 'checked="checked"';}?>></td>
      </tr>
	    <tr>
	  <td>&nbsp;</td><td></td>
	  </tr>
      <tr>
        <th style="text-align: right;">Notes:</th>
        <td><textarea cols="40" rows="2" name="notes" ><?php echo $notes; ?></textarea><br>
        </td> 

    </tbody>
  </table>
		
<br>
</form>



<!-- End of user content -->
</div>

<?php include("./footer.inc"); ?>
</body>
</html>