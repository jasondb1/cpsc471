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
Variables
///////////////////////////////////////////////////////////////////////////////*/	
//get and set initial variables
	$user 		= getUsername();
	$activity	= '';
	$location	= '';
	$timelog_id	= '';
	$id			= '';
	$_GET['id'] = '';
	$salt_bags	= '';
	$loads_hauled = '';
	
//get and set initial variables
	if (isset($_GET['employee'])){
		$employee =trim ($_GET['employee']);
	}
	else{
		$employee = trim($user);
	}
	
	$id=$_GET['id'];
	$start_time = date("h:i A");
	$end_time = $start_time;
	$date1= date("Y-m-d");
	
	
	$days_back=-5;//needs to be a negative number
	//Allow admin team to adjust anything back up to x hours (days * 24 hours)
	if (isEnabled(array("administrator"),array("admin","payroll"))){
		$days_back= -31;
	}
	//connect to database
	//open database
	$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
	if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
			
/*////////////////////////////////////////////////////////////////////////////////
Process Form
///////////////////////////////////////////////////////////////////////////////*/
	if (isset($_POST['submit'])){
	//   process data entry - normal entry coding

		 $date1 			= $_POST['date1'];
		 $time_in 			= $_POST['time1'];
		 $time_out 			= $_POST['time2'];
		 $jobnumber 		= $_POST['jobnumber'];
		 $location 			= $_POST['location'];
		 $employee 			= trim($_POST['employee']);
		 $activity			=$_POST['activity'];
		 $loads_hauled		=$_POST['loads_hauled'];
		 $salt_bags			=$_POST['salt_bags'];
		 $timelog_id		=$_POST['timelog_id'];
		 $uid				= md5(time().$employee);

			//make sure required data is entered
			if ($_POST['date1'] == "" || $time_in =="" || $time_out =="" ||$location=="" || $employee=="") {
				echo "<b>Please fill in all information. You may need to log in or out of the website if everything has been entered.<br><br>Press the back button on your browser to return.</b>";
			die();
			}
			  
			//format data to fill database  
			$start 		= strtotime($time_in);
			$end 		= strtotime($time_out);
			$hours 		= round (($end - $start) / 3600,2);
			$start_time	= date("H:i",strtotime($time_in));
			$end_time	=date("H:i",strtotime($time_out));
			
			if ($hours < 0 ) {
				echo "<b>Negative Hours. Please check your entries.<br><br>Press the back button on your browser to return.</b>";
			die();
			}
			  
			
			if ($id==""){
		//write timelog data
				$sql= "INSERT INTO $db_timelog (`id`, `jobnumber`, `employee`, `date`, `time_in`, `time_out`, `hours`, `division`, `sub_division`, `comment`, `approved`, `uid`) VALUES (NULL, '$snow_job_number', '$employee', '$date1', '$start_time', '$end_time', '$hours', NULL, NULL, '$location', NULL, '$uid')";
				$retval = $mysql_link->query($sql);
				$timelog_id = $mysql_link->insert_id;
		
		//write snowlog data
		$sql = "INSERT INTO $db_snowlog (
		 `id` ,
		 `location` ,
		 `employee` ,
		 `date` ,
		 `time_in` ,
		 `time_out` ,
		 `hours` ,
		 `activity` ,
		 `salt_bags` ,
		 `loads_hauled` ,
		 `approved` ,
		 `timelog_id` 
		)
		VALUES (
		 NULL ,'$location','$employee','$date1','$start_time','$end_time','$hours','$activity','$salt_bags','$loads_hauled',NULL ,'$timelog_id'
		)";

	}
	else{
		
	//update data to timelog
		$sql = "UPDATE $db_timelog 
		SET  
		 `employee`	= '$employee',
		 `date`		='$date1',
		 `time_in`	='$start_time',
		 `time_out`	='$end_time',
		 `hours`	='$hours',
		 `comment`	='$location',
		 `approved`	='$approved'
		WHERE `id` = $timelog_id" ;
		$retval = $mysql_link->query($sql);
	//update snowlog
		$sql = "UPDATE $db_snowlog 
		SET  
		 `location`  	='$location',
		 `employee`		='$employee',
		 `date`			='$date1',
		 `time_in`		='$start_time',
		 `time_out`		='$end_time',
		 `hours`		='$hours',
		 `activity`		='$activity',
		 `salt_bags`	='$salt_bags',
		 `loads_hauled`	='$loads_hauled',
		 `approved`		='$approved',
		 `timelog_id`	='$timelog_id'
		 WHERE `timelog_id` = $id" ;	 
	}//end else
//echo '<br>' .$sql . '<br>';
$retval = $mysql_link->query($sql);

//write log
			//$data=$lname . ":Timelog Entry for $employee ID,$id,jn:$jobnumber,$start_time,$end_time";
			//write_log ($logfile,$data  );
		 

backButton ("Successfully Submitted","employee_snow_view.php?employee=$employee");

}//end if submit
///////////////////////////////////////////////////////////////

		$employee_list = getEmployeeNames();//get employee names

	//Get variables for editing records
		if ($id !=""){
	//read form date 
	$sql = "SELECT * FROM $db_snowlog WHERE timelog_id = '$id'";
	$retval = $retval = $mysql_link->query($sql);

	//return single value
	$row = $retval->fetch_assoc();

	$id 				= $row['id'];
	$location			= $row['location'];
	$employee			= trim($row['employee']);
	$date1 				= $row['date'];
	$start_time			= $row['time_in'];
	$end_time 			= $row['time_out'];
	$activity	 		= $row['activity'];
	$salt_bags			= $row['salt_bags'];
	$loads_hauled	 	= $row['loads_hauled'];
	$approved 			= $row['approved'];
	$timelog_id			= $row['timelog_id'];
	
	$start_time= date("h:i A",strtotime($start_time));
	$end_time= date("h:i A",strtotime($end_time));
}
	
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
					minDate: <?php echo $days_back;?>
					}
				);
				$( "#timepicker1" ).timepicker(
					{
					controlType: 'select',
					timeFormat: 'hh:mm tt',
					stepHour: 1,
					stepMinute: 1,
					//hourGrid: 6,
					//minuteGrid: 15,
					//addSliderAccess: true,
					//sliderAccessArgs: { touchonly: false }
					}
				);
				$( "#timepicker2" ).timepicker(
					{
					//controlType: 'select',
					controlType: 'select',
					timeFormat: 'hh:mm tt',
					stepHour: 1,
					stepMinute: 1,
					//hourGrid: 6,
					//minuteGrid: 15,
					//addSliderAccess: true,
					//sliderAccessArgs: { touchonly: false }
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
										<h3>Snow Entry</h3>
									</header>
									<div id="menu">
										<ul>
										<li><a href="employee_snow_view.php" ><span class="button-menu"><i class="fa fa-arrow-circle-o-left fa-fw"></i>&nbsp; View Snow Log</span></a></li>
										</ul>
									</div>
								</div>
							</div>
							<!-- /Menu Buttons -->
							<!-- Form -->
							<div class="row flush" style="padding:0em;">					
								<div class="12u">
									<hr>
									<form class="formLayout" style="" action="<?php $_SERVER['PHP_SELF']; ?>" method="post" name="timelog">
										 <fieldset>
											<legend>Enter Time</legend>
												<div style="text-align: left;">
													
													<input type="hidden" name="id" value="<?php echo $id; ?>">
													<input type="hidden" name="timelog_id" value="<?php echo $timelog_id; ?>">
													<?php 
													if (isEnabled(array("administrator"),array("admin","payroll"))){
													echo '<label>Employee:</label>';
													echo '<select name="employee">';
													echo '<option>All</option>';
													foreach ($employee_list as $x){
														echo "<option";
														if ($x == $employee) {echo " selected";}
														echo ">$x</option>";
														}//end foreach
													echo '</select><br><hr>';
													}//end if check
													else{
													?>
													<input type="hidden" name="employee" value="<?php echo $employee;?>">
													<?php
													}
													?>
													
													<label>Date:</label><input id="datepicker1" name="date1" type="textbox" readonly="readonly" value="<?php echo $date1; ?>">
													<br>
													<label>Time in:</label><input id="timepicker1" name="time1" readonly="readonly" type="textbox" value="<?php echo $start_time; ?>">

													<br>
													<label>Time Out:</label><input id="timepicker2" name="time2" readonly="readonly" type="textbox" value="<?php echo $end_time; ?>">
													</fieldset>
													<fieldset>
														<legend>Work Details</legend>
														<label>Activity</label>
														<select name="activity">
														<?php
														//print_r($snow_activity);
														foreach ($snow_activity as $value){
														echo '<option';
														if ($activity == ($value)) {echo ' selected>';} else{ echo '>';}
														echo $value;
														echo '</option>';
														}
														?>
														</select>
														<div style="clear:both;"></div>
														<label>Location:</label>
														<select name="location" placeholder="Location"><option value="">Location...</option>
														<?php foreach ($snow_sites as $x){
														echo '<option';
														if ($location == $x) {echo ' selected>';}else{ echo '>';}
														echo $x.'</option>';
														}
														?>
														</select>
														<br>

														<hr>
														<label>Salt Bags:</label>
														<input type="number" name="salt_bags" size="5" placeholder="# Salt Bags" value="<?php echo $salt_bags; ?>"><br>
														<label>Loads Hauled:</label>
														<input type="number" name="loads_hauled" size="5" step=0.25 placeholder="# Loads" value="<?php echo $loads_hauled; ?>"><br>
													</fieldset>
															<input value="Submit" name="submit" type="submit"><br>
												</div>									
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