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
	$path=$itb_path;
	$db_table="invitation_to_bid";
	
	//Status List
	$status_list=array("Open","Closed","Awarded","Extended","Cancelled");
	
	
//////////////////////////////////////Process Invoice
		
		if (isset($_POST['submit'])){
		
		//connect to database
			$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
			if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
		
		//get posted variables
			 $id				= $_POST['id'];
			 $jobnumber			= $_POST['jobnumber'];
			 $vendor			= $_POST['vendor']; 
			 $date_entered		= date ("Y-m-d"); 
			 $date_closing		= $_POST['date_closing'];
			 $date_awarded		= $_POST['date_awarded'];			 
			 $description		= $_POST['description'];
			 $notes				= $_POST['notes']; 
			 $status			= $_POST['status']; 
			 $uid				= md5($vendor.$description);
			 $filename			= $_FILES["file"]["name"];
			 $division			= $_POST['division'];
			 $sub_division		= $_POST['sub_division'];
			$contact_name		= $_POST['contact_name'];
			$email				= $_POST['email'];			 
		
		//Check if file previously uploaded
			 if ($filename==""){
			 $filename = $_POST['prev_file'];
			 $fname=$filename;
			 $upload="no";
			}
	 
		//validate entry	 
			if ($vendor == "" || $filename == ""){
				
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
					  $fname = "ITB_" . $jobnumber . "_".$vendor . "_". $date_closing    ."." . $extension;
					  echo $fname;
					  move_uploaded_file($_FILES["file"]["tmp_name"],"$path/" . $fname);
					  echo "<br>Stored in: " . "$path" . $fname ."\n\n";
					  $filename=$fname;
				  }
				//$files=$fname;
			}

		//open database and write data
			if ($id==""){
				$sql = "INSERT INTO $db_table (
				 `jobnumber`,
				 `vendor`,		 
				 `date_entered`,
				 `date_closing`,
				 `date_awarded`,
				 `notes`,
				 `description`,
				 `status`,
				 `uid`,
				 `filename`,
				 `awarded`,
				 `division`,
				 `sub_division`,
				 `contact_name`,
				 `email`
				)
				VALUES (
				 '$jobnumber',
				 '$vendor',
				 '$date_entered',
				 '$date_closing',
				 '$date_awarded',
				 '$notes',
				 '$description',
				 '$status',
				 '$uid',
				 '$filename',
				 '$awarded',
				 '$division',
				 '$sub_division',
				 '$contact_name',
				 '$email'
				)";
			}
			else{
				$sql = "UPDATE $db_table
				SET  	
				 `jobnumber` 		= '$jobnumber',
				 `vendor`			= '$vendor',
				 `date_closing`		= '$date_closing',
				 `date_awarded`		= '$date_awarded',
				 `description`	 	= '$description',
				 `status` 			= '$status',
				 `notes`			= '$notes',
				 `filename`			= '$filename',
				 `awarded`			= '$awarded',
				 `division`			= '$division',
				 `sub_division`		= '$sub_division',
				 `contact_name`		= '$contact_name',
				 `email`			= '$email'
				 
				WHERE id = '$id'" ;	 
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
				$sql = "SELECT * FROM $db_table WHERE uid = '$uid'";
				$retval = $mysql_link->query($sql) or die($mysql_link->error);

			//return single value
				$row = $retval->fetch_assoc();
			 
			 //get variables
				 $id				= $row['id'];
				 $jobnumber			= $row['jobnumber'];
				 $vendor			= $row['vendor']; 
				 $date_closing		= $row['date_closing'];
				 $date_awarded		= $row['date_awarded'];
				 $description		= $row['description'];
				 $notes				= $row['notes']; 
				 $status			= $row['status']; 
				 $uid				= $row['uid'];
				 $filename			= $row['filename'];
				 $awarded			= $row['awarded'];
				 $division			= $row['division']; 
				 $sub_division		= $row['sub_division'];
				 $contact_name		= $row['contact_name'];
				 $email				= $row['email'];
				 
			}//end if id!=""
			else {//set default values
			$status = "Open";
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
		
		//select division
		$select_division = '<select id="sel_div" name="division">';
		$select_division .= '<option value="">Select Division...</option>';
		foreach ($divisions_list as $key=>$value){
			$select_division .= '<option value="'. $key . '"';
		if( $key == $division[$i]){ $select_division.= ' selected>';} else {$select_division.= '>';}
			$select_division .= $value;
			$select_division .= '</option>';
		}
		$select_division .= '</select>';
	
?>


<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Invitation to Bid</title>
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
				<div class="h_title">Upload Invitation to Bid</div>
				<form style="background-color:#d5c9b1;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="uid" value="<?php echo $uid;?>">
				<input type="hidden" name="id" value="<?php echo $id;?>">
				<fieldset>
				<legend>Input Information</legend>
					<div class="element">
						<label for="jobnumber">Jobnumber</label>
						<input id="jobnumber" disabled="disabled" readonly="readonly" name="jobnumber" value="<?php echo $jobnumber;?>" />
					</div>
					<div class="element">
						<label for="si_number">Vendor <span class="red">(required)</span></label>
						<input id="vendor" name="vendor" value="<?php echo $vendor;?>"/>
					</div>
					<div class="element">
						<label for="date1">Closing Date <span class="red">(required)</span></label>
						<input id="date1" name="date_closing" type="textbox" readonly="readonly" size="14" value="<?php echo $date_closing;?>" />
						<script>
							var date_format = "%Y-%m-%d";
							AnyTime.picker( "date1",{ format: date_format} );
						</script>
					</div>
					<div class="element">
						<label for="date2">Award Date</label>
						<input id="date2" name="date_awarded" type="textbox" readonly="readonly" size="14" value="<?php echo $date_awarded;?>" />
						<script>
							var date_format = "%Y-%m-%d";
							AnyTime.picker( "date2",{ format: date_format} );
						</script>
					</div>
					<div class="element">
						<label for="description">Description <span class="red">(required)</span></label>
						<input id="description" name="description" class="text" value="<?php echo $description;?>"/>
					</div>
					<div class="element">
						<label for="division">Division </label>
						<?php echo $select_division; ?>
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
					
					<div class="element">
						<label for="contact_name">Contact Name </label>
						<input id="contact_name" name="contact_name" value="<?php echo $contact_name;?>"/>
					</div>
					<div class="element">
						<label for="email">email </label>
						<input id="email" name="email" value="<?php echo $email;?>"/>
					</div>

					<div class="entry">
						<button type="submit" name="submit" class="add">Submit Invitation to Bid</button> <button class="cancel">Cancel</button>
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
