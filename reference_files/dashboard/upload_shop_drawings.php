<?php
require_once("../phpauthent/phpauthent_core.php");
require_once("../phpauthent/phpauthent_config.php");
	include_once ("cfg_dashboard.php");
	
	/*////////////////////////////////////////////////////////////////////////////////
Page Protection
///////////////////////////////////////////////////////////////////////////////*/
	$usersArray = array("administrator");
	$groupsArray = array("admin","employee","payroll","supervisor","contractor");
	pageProtect($usersArray,$groupsArray);

	$user = trim($_COOKIE['USERNAME']);
	$masked_jobnumber = $_GET['j'];
	$jobnumber = xor_this($masked_jobnumber);
	$uid = $_GET['uid'];
	//$path=$project_path . "jobnumber/";
	$path="../files/projects/".$jobnumber."/";
	
	//Status List
	$status_list=array("Received","Completed","In Progress","In Change Order","Cancelled");
	
/** 
 * recursively create a long directory path
 */
function createPath($path) {
    if (is_dir($path)) return true;
    $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1 );
    $return = createPath($prev_path);
    return ($return && is_writable($prev_path)) ? mkdir($path) : false;
}
	
//////////////////////////////////////Process File Upload
		
		if (isset($_POST['submit'])){
		
		//connect to database
			$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
			if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
		
		//get posted variables
			 $id				= $_POST['id'];
			 $jobnumber			= $_POST['jobnumber'];
			 $path				= $_POST['path'];
			 $directory			= $_POST['directory'];
			 $filename			= $_FILES["file"]["name"];
			 $description		= $_POST['description'];
			 $upload_date		= date ("Y-m-d");
			 $document_date		= $_POST['document_date']; 
			 $division			= $_POST['division'];
			 $co_number			= $_POST['co_number'];
			 $revision			= $_POST['revision'];
			 //$count_download	= $_POST['count_download'];
			 $owner				= $user;
			 $vendor			= $_POST['vendor'];
			 
			 $notes				= $_POST['notes']; 
			 
			 
			 $received_from		= $_POST['received_from']; 
			 $uid				= md5($id.$jobnumber);
			 
			 
			 
		//Check if file previously uploaded
			 if ($filename==""){
			 $filename = $_POST['prev_file'];
			 $fname=$filename;
			 $upload="no";
			}
	 
		//validate entry	 
			if ($description == "" || $filename == ""){
				
				echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
				echo "<br><br><b><big>Hey! You forgot some information!</b></big><br>Fill in all of the fields marked with an *<br><br>";
				echo 	'<input type="Button" value="Back" onclick="history.go(-1)">';
				die();
			}
			
		//upload and save file
			if ($upload!="no"){
				createPath($path);
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
					  //$fname = $jobnumber. "_" . $description . "_".$revision . "." . $extension;
					  $fdate = date("Ymd");
					  $fname = $date. "_" . $filename . "_".$revision . "." . $extension;
					  echo $fname;
					  $full_path = $path . "/" . $directory;
					  move_uploaded_file($_FILES["file"]["tmp_name"],"$full_path" . $fname);
					  echo "<br>Stored in: " . "$full_path" . $fname ."\n\n";
					  $filename=$fname;
				  }
				//$files=$fname;
			}

		//open database and write data
			if ($id==""){
				$sql = "INSERT INTO filedata (
				 `jobnumber`,
				 `path`,
				 `directory`,
				 `filename`,
				 `description`,
				 `division`,
				 `co_number`,
				 `revision`,
				 `owner`,
				 `vendor`,
				 `notes`,
				 `upload_date`,
				 `document_date`,
				 `uid`
				)
				VALUES (
				 '$jobnumber',
				 '$path',
				 '$directory',
				 '$filename',
				 '$description',
				 '$division',
				 '$co_number',
				 '$revision',
				 '$owner',
				 '$vendor',
				 '$notes',
				 '$upload_date',
				 '$document_date',
				 '$uid'
				)";
			}
			else{
				$sql = "UPDATE filedata
				SET  	
				 jobnumber = '$jobnumber',
				 path = '$path',
				 directory = '$directory',
				 filename = '$filename',
				 description = '$description',
				 division = '$division',
				 co_number = '$co_number',
				 revision = '$revision',
				 owner = '$owner',
				 vendor = '$vendor',
				 notes = '$notes',
				 upload_date = '$upload_date',
				 document_date = '$document_date'
				 
				WHERE uid = '$uid'" ;	 
			}//end else
			
			$retval = $mysql_link->query($sql) or die($mysql_link->error);

		//Return
			$masked_jobnumber=xor_this($jobnumber);
			echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
			echo "<br><br><b><big>Successful</b></big><br><br>";
			echo '<input type="Button" value="Back" onclick="location.href=\'view_files.php?j='. $masked_jobnumber .'\'">';
			die();
		
		}//end if submit

/*//////////////////////////////////////////////////////////////////////////
Edit Form
/////////////////////////////////////////////////////////////////////////*/
			if ($uid !=""){
				//read form date 
					$sql = "SELECT * FROM filedata WHERE uid = '$uid'";
					$retval = $mysql_link->query($sql) or die($mysql_link->error);
				//return single value
					$row = $retval->fetch_assoc();
				//get variables
					$uid = $row['uid'];
					$jobnumber = $row['jobnumber'];
					$path = $row['path'];
					$directory = $row['directory'];
					$filename = $row['filename'];
					$description = $row['description'];
					$division = $row['division'];
					$co_number = $row['co_number'];
					$revision = $row['revision'];
					$owner = $row['owner'];
					$vendor = $row['vendor'];
					$notes = $row['notes'];
					$upload_date = $row['upload_date'];
					$document_date = $row['document_date'];
			}//end if id!=""
			else {//set default values
			
			}
			
			$selection_list = array("Drawings","Info","Vendor1..."   );
			$directory_select ='<select name="directory">';
			$directory_select .='<option></option>';
			foreach ($selection_list as $x){
				$directory_select .= "<option";
				if ($status == $x){$directory_select .= " selected>";} else {$directory_select .= ">";}
				$directory_select .= $x;
				$directory_select .= "</option>";
			}
			$directory_select .='</select>';
	
?>


<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Upload Files</title>
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
				<input type="hidden" name="jobnumber" value="<?php echo $jobnumber;?>">
				<input type="hidden" name="path" value="<?php echo $path;?>">
				<fieldset>
				<legend>Input Information</legend>
					<div class="element">
					<label>Directory</label>
					<?php echo $directory_select;?><br>
					</div>
					<div class="element">
						<label for="file">Attachment<span class="red">(required)</span></label>
						<input type="file" id="file" name="file" /><br>
						<label>Previous File</label><?php echo $filename;?>
						<input type="hidden" name="prev_file" value="<?php echo $filename;?>"/>
						<div style="clear: both;"></div>
					</div>
					<div class="element">
					<label>Description</label>
					<input name="description" type="text" value="<?php echo $description;?>" /><br>
					</div>
					<div class="element">
					<label>Division</label>
					<input name="division" type="text" value="<?php echo $division;?>" /><br>
					</div>
					<div class="element">
					<label>CO Number</label>
					<input name="co_number" type="text" value="<?php echo $co_number;?>" /><br>
					</div>
					<div class="element">
					<label>Revision</label>
					<input name="revision" type="text" value="<?php echo $revision;?>" /><br>
					</div>
					<div class="element">
					<label>Owner</label>
					<?php echo $user;?>
					</div>
					<div class="element">
					<label>Vendor</label>
					<input name="vendor" type="text" value="<?php echo $vendor;?>" /><br>
					</div>
					<div class="element">
						<label for="notes">Notes</label>
						<textarea name="notes"><?php echo $notes;?></textarea>
					</div>
					<div class="element">
					<label>Upload Date</label>
					<input name="upload_date" type="text" value="<?php echo $upload_date;?>" /><br>
					</div>
					<div class="element">
					<label>Document Date</label>
					<input name="document_date" type="text" value="<?php echo $document_date;?>" /><br>
					</div>
					
					

					<div class="entry">
						<button type="submit" name="submit" class="add">Add File</button> <button class="cancel">Cancel</button>
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
