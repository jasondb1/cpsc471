<?php
require_once("../phpauthent/phpauthent_core.php");
require_once("../phpauthent/phpauthent_config.php");
	include_once ("cfg_dashboard.php");
	
	/*////////////////////////////////////////////////////////////////////////////////
Page Protection
///////////////////////////////////////////////////////////////////////////////*/
	$usersArray = array("administrator");
	$groupsArray = array("admin","employee","payroll","supervisor");
	pageProtect($usersArray,$groupsArray);

	$masked_jobnumber = $_GET['j'];
	$jobnumber = xor_this($masked_jobnumber);
	$uid = $_GET['id'];
	$path=$si_path;
	
	//Status List
	$status_list=array("Sent","Completed","In Progress","Signed","Cancelled");
	
	
	//connect to database
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}	
	
//////////////////////////////////////Process Invoice
		
		if (isset($_POST['submit'])){
		
		//get posted variables
			 $id				= $_POST['id'];
			 $jobnumber			= $_POST['jobnumber'];
			 $contract_number	= $_POST['contract_number'];
			 $division			= $_POST['division'];
			 $subdivision		= $_POST['subdivision'];
			 $co_number			= $_POST['co_number'];
			 $vendor			= $_POST['vendor'];
			 $contract_date		= $_POST['contract_date'];
			 $date_entered		= date ("Y-m-d");
			 $description		= $_POST['description'];
			 $notes				= $_POST['notes'];
			 $status			= $_POST['status'];
			 $amount			= $_POST['amount'];
			 $filename			= $_FILES["file"]["name"];
			 $uid				= md5($contract_number.$jobnumber);
			 $sent_to			= $_POST['sent_to'];
			 $approved_by		= $_POST['approved_by'];
			 		
		//Check if file previously uploaded
			 if ($filename==""){
			 $filename = $_POST['prev_file'];
			 $fname=$filename;
			 $upload="no";
			}
	 
		//validate entry	 
		$amount= preg_replace('/[\$,]/', '', $amount); 
			if ($contract_number == "" || $filename == ""){
				
				echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
				echo "<br><br><b><big>Hey! You forgot some information!</b></big><br>Fill in all of the fields marked with an *<br><br>";
				echo 	'<input type="Button" value="Back" onclick="history.go(-1)">';
				die();
			}
			
		//upload and save file
			if ($upload!="no"){
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
					  $fname = "Vendor_Contract_" . $jobnumber . "_".$contract_number . "." . $extension;
					  echo $fname;
					  move_uploaded_file($_FILES["file"]["tmp_name"],"$path/" . $fname);
					  echo "<br>Stored in: " . "$path" . $fname ."\n\n";
					  $filename=$fname;
				  }
				//$files=$fname;
			}

		//open database and write data
			if ($uid==""){
				$sql = "INSERT INTO vendor_contracts (
				 `jobnumber`,
				 `contract_number`,
				 `division`,
				 `sub_division`,
				 `co_number`,
				 `vendor`,
				 `contract_date`,
				 `date_entered`,
				 `description`,
				 `notes`,
				 `status`,
				 `amount`,
				 `filename`,
				 `uid`,
				 `sent_to`,
				 `approved_by`
				 
				)
				VALUES (
				 '$jobnumber',
				 '$contract_number',
				 '$division',
				 '$sub_division',
				 '$co_number',
				 '$vendor',
				 '$contract_date',
				 '$date_entered',
				 '$description',
				 '$notes',
				 '$status',
				 '$amount',
				 '$filename',
				 '$uid',
				 '$sent_to',
				 '$approved_by'
				)";
			}
			else{
				$sql = "UPDATE site_instructions
				SET  
				 `jobnumber`		= '$jobnumber',
				 `contract_number`	= '$contract_number',
				 `division`			= '$division',
				 `sub_division`		= '$sub_division',
				 `co_number`		= '$co_number',
				 `vendor`			= '$vendor',
				 `contract_date`	= '$contract_date',
				 `date_entered`		= '$date_entered',
				 `description`		= '$description',
				 `notes`			= '$notes',
				 `status`			= '$status',
				 `amount`			= '$amount',
				 `filename`			= '$filename',
				 `sent_to`			= '$sent_to',
				 `approved_by`		= '$approved_by'
				 
				WHERE uid = '$uid'" ;	 
			}//end else
			
			$retval = $mysql_link->query($sql) or die($mysql_link->error);

		//Return
			$masked_jobnumber=xor_this($jobnumber);
			echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
			echo "<br><br><b><big>Successful</b></big><br><br>";
			echo '<input type="Button" value="Back" onclick="location.href=\'view_site_instruction.php?j='. $masked_jobnumber .'\'">';
			die();
		
		}//end if submit

		///////////////////////edit part of invoice
			if ($uid !=""){
			//read form date 
				$sql = "SELECT * FROM vendor_contracts WHERE uid = '$uid'";
				$retval = $mysql_link->query($sql) or die($mysql_link->error);

			//return single value
				$row = $retval->fetch_assoc();
			 
			 //get variables
				 $id				= $row['id'];
				 $jobnumber			= $row['jobnumber'];
				 $contract_number	= $row['contract_number']; 
				 $division			= $row['division'];
				 $subdivision		= $row['subdivision'];
				 $co_number			= $row['co_number'];
				 $vendor			= $row['vendor'];
				 $contract_date		= $row['contract_date'];
				 $description		= $row['description'];
				 $notes				= $row['notes'];
				 $status			= $row['status'];
				 $amount			= $row['amount'];
				 $filename			= $row['filename'];
				 $uid				= $row['uid'];
				 $sent_to			= $row['sent_to'];
				 $approved_by		= $row['approved_by'];
				 
			}//end if id!=""
			else {//set default values
			$status = "Sent";
			}
	
		//select status
			$status_select ='<select name="status">';
			$status_select .='<option></option>';
			foreach ($status_list as $x){
				$status_select .= "<option";
				if ($status == $x){$status_select .= " selected>";} else {$status_select .= ">";}
				$status_select .= $x;
				$status_select .= "</option>";
			}
			$status_select .='</select>';	
			
		//Select Vendor
			$sql = "SELECT Vendor FROM vendor_data";
			$result = $mysql_link->query($sql) or die($mysql_link->error);
		
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
		
		//Select Division
			$division_select = '<select id="sel_div" name="division">';
			$division_select .= '<option value="">Select Division...</option>';
			foreach ($divisions as $key=>$value){
				$division_select .= '<option value="'. $key . '"';
				if( $key == $division){ $division_select.= ' selected>';} else {$division_select.= '>';}
				$division_select .= $value;
				$division_select .= '</option>';
			}
			$division_select .= '</select>';

		////////////////////////////////Find Change Order associated with jobnumber
			$where 		= " WHERE jobnumber LIKE '". $jobnumber ."%'";
			$order 		= " ORDER BY date_entered ASC";
			$sql 		="SELECT *	FROM changeorder". $where . $order;
			$co_result = $mysql_link->query($sql) or die('Could not retrieve data: ' . $mysql_link->error);
			$changeorder_list[]="0";
			while ($row = mysql_fetch_array($co_result)) {
				$changeorder_list[]= $row['item_number'];
			}	
			$co_select = '<select id="co_number" name="co_number">';
			//$co_select .= '<option value="">00</option>';
			foreach ($changeorder_list as $key=>$value){
				$co_select .= '<option value="'. $key . '"';
				if( $key == $changeorder){ $co_select.= ' selected>';} else {$co_select.= '>';}
				$co_select .= $value;
				$co_select .= '</option>';
			}
			$co_select .= '</select>';

?>


<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Site Instructions</title>
	<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="css/navi.css" media="screen" />
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script src="../js/jquery.js"></script>
	<script src="../js/anytime.js"></script>
	<link rel="stylesheet" type="text/css" href="../css/anytime.css" />
	<script type="text/javascript">
	$(function(){
		$(".box .h_title").not(this).next("ul").hide("normal");
		$(".box .h_title").not(this).next("#home").show("normal");
		$(".box").children(".h_title").click( function() { $(this).next("ul").slideToggle(); });
	});
	</script>
	<style type="text/css">
		  #date1, #date2, #date3{
			background-image:url("images/calendar.png");
			background-position:right center;
			background-repeat:no-repeat; }
			label {
			float:left;
			width:130px;
			text-align:right;
			}
			select {
			float:left;
			text-align:left;
			}
	</style>
</head>

<body>
<div class="wrap">
	<div id="header">
		<div id="top">
			<div class="left">
				<!--<p>Welcome, <strong>Employee</strong> [ <a href="">logout</a> ]</p>-->
			</div>
			<div class="right">
				<div class="align-right">
					<!--<p>Last login: <strong>(Login Date)</strong></p>-->
				</div>
			</div>
		</div>

		<div id="nav">
		 Dashboard - Jobnumber: <?php echo $jobnumber;?>
		</div>

	</div>
	
	<div id="content">
		<div id="sidebar">
			<?php include ("cfg_menu.php");?>
		</div>
			
		<!-- //Main Area -->
		<div id="main">
			<div class="full_w">
				<div class="h_title">Upload Site Instruction</div>
				<form style="background-color:#d5c9b1;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="uid" value="<?php echo $uid;?>">
				<input type="hidden" name="id" value="<?php echo $id;?>">
				<fieldset>
				<legend>Input Information</legend>
					<div class="element">
						<label for="jobnumber">Jobnumber</label>
						<input id="jobnumber" readonly="readonly" name="jobnumber" value="<?php echo $jobnumber;?>" />
					</div>
					
			
					<div class="element">
						<label for="vendor"><a href="employee_vendor_add.php" target="_blank">Add </a> Vendor <span class="red">(required)</span></label>
						<?php echo $vendor_select; ?>
						<div style="clear: both;"></div>
					</div>
	
					
					<div class="element">
						<label for="contract_number">Contract Number <span class="red">(required)</span></label>
						<input id="contract_number" name="contract_number" value="<?php echo $contract_number;?>"/>
					</div>
					<div class="element">
						<label for="date1">Date <span class="red">(required)</span></label>
						<input id="date1" name="contract_date" type="textbox" readonly="readonly" size="14" value="<?php echo $contract_date;?>" />
						<script>
							var date_format = "%Y-%m-%d";
							AnyTime.picker( "date1",{ format: date_format} );
						</script>
					</div>
					<div class="element">
						<label for="description">Description <span class="red">(required)</span></label>
						<input id="description" name="description" class="text" value="<?php echo $description;?>"/>
					</div>
					<div class="element">
						<label for="description">Amount <span class="red">(required)</span></label>
						<input id="amount" name="amount" value="<?php echo $amount;?>"/>
					</div>
					<div class="element">
						<label for="division">Division</label>
						<?php echo $division_select; ?>
						<div style="clear: both;"></div>
					</div>
					<div class="element">
						<label for="co_number">Change Order</label>
						<?php echo $co_select; ?>
						<div style="clear: both;"></div>
					</div>
				
					<div class="element">
						<label for="notes">Notes</label>
						<textarea name="notes"><?php echo $notes;?></textarea>
					</div>
					<div class="element">
						<label for="file">Attachment<span class="red">(required)</span></label>
						<input type="file" id="file" name="file" /><br>
						<label>Previous File</label><?php echo $filename;?>
						<input type="hidden" name="prev_file" value="<?php echo $filename;?>"/>
						<div style="clear: both;"></div>
					</div>
					<div class="element">
						<label for="status">Status</label>
						<?php echo $status_select;?>
						<div style="clear: both;"></div>
					</div>

					<div class="entry">
						<button type="submit" name="submit" class="add">Submit Contract</button> <button class="cancel">Cancel</button>
					</div>

				</fieldset>
				</form>
			</div>	
		</div>
	</div>
			
			<div class="clear"></div>
</div>

</body>
</html>
