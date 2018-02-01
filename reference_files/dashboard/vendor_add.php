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

	$user = trim($_COOKIE['USERNAME']);
	$masked_jobnumber = $_GET['j'];
	$jobnumber = xor_this($masked_jobnumber);
	$uid = $_GET['uid'];
	
	//Status List
	$status_list=array("Active","Inactive");
	$team_list = array('4'=>"contractor");
		
//////////////////////////////////////Process File Upload
		
		if (isset($_POST['submit'])){
		
		echo "Submitted";
		$password = generatePassword(8);
		echo $password;
		
		$p_username = $_POST['login'];
		//$p_password = $_POST['password'];
		$p_password = $password;
		$p_realname = $_POST['name'];
		$p_email    = $_POST['email'];
		if ((empty($p_username)) || (empty($p_password)) || (empty($p_email)) ) {
			header ("Location: vendor_add.php?err=001");
		}
		
		//$_Post variables
			//$team = $_POST['team'];
			$status = $_POST['status'];
			$company = $_POST['company'];
			$email = $_POST['email'];
			$phone = $_POST['phone'];
			$cell = $_POST['cell'];
			$street = $_POST['street'];
			$city = $_POST['city'];
			$postal_code = $_POST['postal_code'];
			$notes = $_POST['notes'];
			$start_date = $_POST['start_date'];
		
	//connect to database
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}	
			
		$query = "INSERT INTO ".$db_tbl_users." (".$db_fld_users_username." , ".$db_fld_users_password." , ".$db_fld_users_realname." , ".$db_fld_users_email." , ".$db_fld_users_creationdate.") VALUES ('".$p_username."', '".encrypt($p_password,$phpauthent_enckey)."', '".$p_realname."', '".$p_email."', '".date("Y-m-d")."')";
		//DEBUG 
		echo "query : <b>".$query."</b>";
		$r_query = mysql_query($query);
		if (mysql_affected_rows() <> 1) {
			header ("Location: vendor_add.php?err=002");
		} else {
			// Insert successful
			//header ("Location: index.php?msg=001");
			echo "entry successful";
		}
		
		$user_id = mysql_insert_id();
		
		if (1){//enter constraint
			$sql = "INSERT INTO user_data (
			`user_id`,
			`vendor`,
			`status`,
			`phone`,
			`cell`,
			`notes`,
			`start_date`
			)
			VALUES (
			'$user_id',
			'$vendor',
			'$status',
			'$phone',
			'$cell',
			'$notes',
			'$start_date'
			)";
			}
			else{
			$sql = "UPDATE user_data SET
			`user_id`,
			`vendor`,
			`status`,
			`phone`,
			`cell`,
			`notes`,
			`start_date`
			";}//end else

			$retval = $mysql_link->query($sql) or die(mysql_error());

	//email password to user
		//change this to your email.
			//$to = $p_email;
				
			$to = "jasond@amcgroup.ca";
			$from = "noreply.useradmin@" . $company_domain;
			$subject = "Login Information";
			
			$headers  = "From: $from\r\n";
			$headers .= "Content-type: text/html\r\n";
			//$headers .= "Cc: $accounting_email\r\n";
			//$headers .= "Cc: $user_email\r\n";

		//beginning of HTML message
			$message = '<html><head></head><body bgcolor="#FFFFFF">';
			$message .= '<p>Your Login Information for the AMC Group Website (<a href="www.amcgroup.ca/dashboard/contractor_dashboard.php">www.amcgroup.ca</a>) is as follows:<br>';
			$message .= "Username:$p_username<br>password:$p_password<br><br><hr>If you have any questions please contact us at office@amcgroup.ca";
			$message .= "</p></body></html>";				
		// now lets send the email.
			mail($to, $subject, $message, $headers);
		
			
		//Return
			$masked_jobnumber=xor_this($jobnumber);
			echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
			echo "<br><br><b><big>Successful</b></big><br><br>";
			echo '<input type="Button" value="Back" onclick="location.href=\'vendor_add.php?j='. $masked_jobnumber .'\'">';
			die();
		
		}//end if submit

/*//////////////////////////////////////////////////////////////////////////
Edit Form
/////////////////////////////////////////////////////////////////////////*/
			// if ($uid !=""){
				// //read form date 
					// $sql = "SELECT * FROM filedata WHERE uid = '$uid'";
					// $retval = $mysql_link->query($sql) or die(mysql_error());
				// //return single value
					// $row = $retval->fetch_assoc();
				// //get variables
					// $uid 			= $row['uid'];
					// $jobnumber 		= $row['jobnumber'];
					// $path 			= $row['path'];
					// $directory 		= $row['directory'];
					// $filename 		= $row['filename'];
					// $description 	= $row['description'];
					// $division 		= $row['division'];
					// $co_number 		= $row['co_number'];
					// $revision 		= $row['revision'];
					// $owner 			= $row['owner'];
					// $vendor 		= $row['vendor'];
					// $notes 			= $row['notes'];
					// $upload_date 	= $row['upload_date'];
					// $document_date 	= $row['document_date'];
			// }//end if id!=""
			// else {//set default values
			
			// }
			
//////////Auxilarry code snippets

		//select status
			$status_select ='<select name="status">';
			$status_select .='<option>'.$status.'</option>';
			foreach ($status_list as $x){
				$status_select .= "<option";
				if ($status == $x){$status_select .= " selected>";} else {$status_select .= ">";}
				$status_select .= $x;
				$status_select .= "</option>";
			}
			$status_select .='</select>';
			
		//get group list
			
		//select status
		$team = "contractor";
			$team_select ='<select name="status">';
			$team_select .='<option value=4>'.$team.'</option>';
			foreach ($team_list as $x=>$y){
				$team_select .= "<option value=$x";
				if ($status == $y){$team_select .= " selected>";} else {$team_select .= ">";}
				$team_select .= $y;
				$team_select .= "</option>";
			}
			$team_select .='</select>';	
			
			
		//get job list
	/////////////////////////////////////// Get List of Jobs

		$sql = "SELECT * FROM dashboard_project;";
		$retval = $mysql_link->query($sql) or die(mysql_error());
		while ($row = $retval->fetch_assoc()) {
			$job_list[]=$row['jobnumber'];
		}	
		$jobselect = '<select name="jobnumber"><option></option>';
		foreach ($job_list as $value){
			if ($jobnumber==$value){$jobselect .= '<option selected>'. $value   .'</option>';}
			else {$jobselect .= '<option>'. $value   .'</option>';}
		}
		$jobselect .= '	</select>';	
	
?>


<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Add Vendor</title>
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
				<div class="h_title">Add Vendor/Contractor</div>
				<form style="background-color:#d5c9b1;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="uid" value="<?php echo $uid;?>">
				<input type="hidden" name="id" value="<?php echo $id;?>">
				<input type="hidden" name="jobnumber" value="<?php echo $jobnumber;?>">
				<fieldset>
				<legend>Input Information</legend>
					<div class="element">
					<div class="entry">
						<button type="submit" name="submit" class="add">Add</button> <button class="cancel">Cancel</button>
					</div>
					</div>
					
					<div class="element">
					<label>Username</label>
					<input name="username" type="text" value='<?php echo $username; ?>' /><br>
					</div>
					<div class="element">
					<label>Real Name</label>
					<input name="realname" type="text" value='<?php echo $realname; ?>' /><br>
					</div>
					<div class="element">
					<label>Status</label>
					<input name="status" type="text" value='<?php echo $status; ?>' /><br>
					</div>
					

				</fieldset>	
				<fieldset>
				<legend>Contact Information</legend>
					<div class="element">
					<label>Company/Vendor (Select from Dropdown?)</label>
					<input name="company" type="text" value='<?php echo $company; ?>' /><br>
					</div>
					<div class="element">
					<label>Email</label>
					<input name="email" type="text" value='<?php echo $email; ?>' /><br>
					</div>
					<div class="element">
					<label>Phone</label>
					<input name="phone" type="text" value='<?php echo $phone; ?>' /><br>
					</div>
					<div class="element">
					<label>Cell</label>
					<input name="cell" type="text" value='<?php echo $cell; ?>' /><br>
					</div>
										<div class="element">
					<label>Notes</label>
					<input name="notes" type="text" value='<?php echo $notes; ?>' /><br>
					</div>
					</fieldset>	
				<fieldset>
				<legend>Address Information (Should this go in the vendor info?)</legend>
					<div class="element">
					<label>Street</label>
					<input name="street" type="text" value='<?php echo $street; ?>' /><br>
					</div>
					<div class="element">
					<label>City</label>
					<input name="city" type="text" value='<?php echo $city; ?>' /><br>
					</div>
					<div class="element">
					<label>Postal Code</label>
					<input name="postal_code" type="text" value='<?php echo $postal_code; ?>' /><br>
					</div>

					
					<div class="entry">
						<button type="submit" name="submit" class="add">Add</button> <button class="cancel">Cancel</button>
					</div>

				</fieldset>

				<fieldset>
				<legend>Group</legend>
					<div class="element">
					<label>Team (Select Here Vendor/owner, architect)</label>
					<?php echo $team_select; ?><br>
					</div>
				</fieldset>
				<fieldset>
				<legend>Project Permissions</legend>
					<div class="element">
					<label>Projects (Checkboxes Here)</label>
					<?php echo $jobselect; ?><br>
					</div>
				</fieldset>
					<div class="entry">
						<button type="submit" name="submit" class="add">Add</button> <button class="cancel">Cancel</button>
					</div>
				
				
				
				</form>
			</div>	
		</div>
	</div>
			
			<div class="clear"></div>
</div>

</body>
</html>
