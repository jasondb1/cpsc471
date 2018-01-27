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
	$user 				= trim (getUsername());
	$edit_record		=$_GET['edit_record'];
	$jobnumber			= $edit_record;
	$description		=$_GET['description'];
	$require_division	=$_GET['require_division'];
	$require_subdivision=$_GET['require_subdivision'];
	$customer			=$_GET['customer'];

	$id					=$_GET['id'];
	$start_time 		= date("h"). ":00 " . date("A");
	$end_time 			= $start_time;

	$days_back			= -5;//amount of time for employees to enter time
	//$days_back			=-15;//temporary to allow employee to enter more time
	
	if (isset($_GET['employee'])){
		$employee =trim ($_GET['employee']);
	}
	else{
		$employee 			= trim($user);
	}
		if (isset($_GET['date'])){
		$date1 		=$_GET['date'];
		$start_time	=$_GET['time_in'];
		$end_time	=$_GET['time_out'];
	}
	else{
		$date1 = date("Y-m-d");
	}
//Allow admin team to adjust anything back up to x hours (days * 24 hours)
	if (isEnabled(array("administrator"),array("admin","payroll"))){
		$days_back= -61;
	}

//fail out if record is blank
	if ($edit_record == "" && $id ==""){
	die ("Error: No Record is selected");
	}
	
	//connect to database
	//open database
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}

////////////////////////////////Find Change Order associated with jobnumber
			$where 		= " WHERE jobnumber LIKE '". $jobnumber ."%'";
			$order 		= " ORDER BY date_entered ASC";
			$sql 		="SELECT *	FROM changeorder". $where . $order;
			//$co_result = mysql_query($sql) or die('Could not retrieve data: ' . mysql_error());
			$co_result = $mysql_link->query($sql);
			$changeorder_list[]="0";
			while ($row = $co_result->fetch_assoc()) {
				$changeorder_list[]= $row['item_number'];
			}	
			$co_select = '<select id="sel_co" name="changeorder">';
			//$co_select .= '<option value="">00</option>';
			foreach ($changeorder_list as $key=>$value){
				$co_select .= '<option value="'. $key . '"';
				if( $key == $changeorder){ $co_select.= ' selected>';} else {$co_select.= '>';}
				$co_select .= $value;
				$co_select .= '</option>';
			}
			$co_select .= '</select>';
				
/*////////////////////////////////////////////////////////////////////////////////
Process Form
///////////////////////////////////////////////////////////////////////////////*/
	if (isset($_POST['submit'])){
	//   process data entry - normal entry coding
		 $date1 				=$_POST['date1'];
		 $time_in 				=$_POST['time1'];
		 $time_out 				=$_POST['time2'];
		 $jobnumber 			=$_POST['jobnumber'];
		 $comment 				=$_POST['comment'];
		 $employee 				=trim($_POST['employee']);
		 $subdiv				=$_POST['subdiv'];
		 $div					=$_POST['division'];
		 $approved  			=$_POST['approved'];
		 $require_division 		=$_POST['require_division'];
		 $require_subdivision 	=$_POST['require_subdivision'];
		 $change_order			=$_POST['change_order'];

	//Data Validation
		if ($_POST['date1'] == "" || $time_in =="" || $time_out =="" ||$jobnumber=="" || $employee=="") {
			echo "<b>Please fill in all information. If you have entered all information, you may need to log out and back into the website.<br><br>Press the back button on your browser to return.</b>";
			die();
		}	
		if ($require_division == "1" && $div=="" ) {
			echo "<b style='font-size:20;'>WARNING!</b><br><br>A division is required for this job. Please fill in division number. <br><br>Press the back button on your browser to return.</b>";
			die();
		}		

			  
	//format data to fill database  
		$start 		= strtotime($time_in);
		$end 		= strtotime($time_out);
		$hours 		= round (($end - $start) / 3600,2);
		$start_time	= date("H:i",strtotime($time_in));
		$end_time	=date("H:i",strtotime($time_out));

		if ($hours < 0 ) {
			echo "<b style='font-size:20;'>WARNING!</b><br><br>Negative Hours. Please go back and check your hours.<br><br>Press the back button on your browser to return.</b>";
			die();
		}
		
			
			if ($id==""){
				$uid=md5("employee"."date".time());			
				$sql= "INSERT INTO $db_timelog (`id`, `jobnumber`, `employee`, `date`, `time_in`, `time_out`, `hours`, `division`, `sub_division`, `comment`, `approved`, `change_order`,`uid`) VALUES (NULL, '$jobnumber', '$employee', '$date1', '$start_time', '$end_time', '$hours', '$div', '$subdiv', '$comment', NULL, '$change_order','$uid')";
			}
			else{
				$sql = "UPDATE $db_timelog 
				SET  
				 `jobnumber`  		= '$jobnumber',
				 `employee`			= '$employee',
				 `date`				='$date1',
				 `time_in`			='$start_time',
				 `time_out`			='$end_time',
				 `hours`			='$hours',
				 `division`			='$div',
				 `sub_division`		='$subdiv',
				 `comment`			='$comment',
				 `approved`			='$approved',
				 `change_order`		='$change_order'
				WHERE `uid` 		= '$id'" ;	 
			}//end else
		//echo $sql;
		//$retval = mysql_query($sql) or die(mysql_error());
		$retval = $mysql_link->query($sql);

		//write log
				$details="jn:$jobnumber,$date1,$start_time,$end_time,$uid";
				if ($id==""){ $event="Time Changed";} else { $event="Time Entered";}
				//write_log_file ($user,$event,$employee,$details);
				 
		echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
		echo "<br><br><b><big>Successfully Submitted</b></big><br><br>";
		//echo 	'<input type="Button" value="Back" onclick="history.go(-2)">';
		echo "<a href=\"employee_time_select_job.php?employee=$employee&date=$date1&time_in=$time_in&time_out=$time_out\" ><b>Back</b></a><br><hr>";
		die();

	}//end if submit
///////////////////////////////////////////////////////////////

		$employee_list = getEmployeeNames();//get employee names

	//Get variables for editing records
		if ($id !=""){
		//read form date 
			$sql = "SELECT * FROM $db_timelog WHERE `uid` = '$id'";
			//$retval = mysql_query($sql) or die(mysql_error());
			$retval = $mysql_link->query($sql);

		//return single value
			$row = $retval->fetch_assoc();
			$id 				= $row['id'];
			$edit_record		= $row['jobnumber'];
			$employee			= $row['employee'];
			$date1 				= $row['date'];
			$start_time			= $row['time_in'];
			$end_time 			= $row['time_out'];
			$div		 		= $row['division'];
			$sub_div 			= $row['sub_div'];
			$comment	 		= $row['comment'];
			$approved 			= $row['approved'];
			$change_order		= $row['change_order'];
			
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
					stepMinute: 15,
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
					stepMinute: 15,
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
										<h3>Time Entry</h3>
									</header>
									<div id="menu">
										<ul>
										<li><a href="employee_time_view.php" ><span class="button-menu"><i class="fa fa-arrow-circle-o-left fa-fw"></i>&nbsp; View Timelog</span></a></li>
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
													<?php echo "Job Number: $edit_record<br>" . $customer . " - " . $description ; ?>
													<hr>
													<label>Job Number:</label> <?php echo getJobList($edit_record); ?>
													<input type="hidden" name="id" value="<?php echo $id; ?>">
													<input type="hidden" name="approved" value="<?php echo $approved; ?>">
													<hr>
													<?php 
														if (isEnabled(array("administrator"),array("admin","payroll"))){
														echo '<label>Employee:</label>';
														echo '<select name="employee">';
														echo '<option></option>';
														foreach ($employee_list as $x){
															echo "<option";
															if ($x == $employee) {echo " selected";}
															echo ">$x</option>";
															}//end foreach
														echo '</select><hr>';
														}//end if check
														else{ echo '<input type="hidden" name="employee" value="'. $user.' ">';}
													?>
													<input type="hidden" name="require_division" value="<?php echo $require_division;?>">
													<input type="hidden" name="require_subdivision" value="<?php echo $require_subdivision;?>">
													<label>Date:</label>
													<input id="datepicker1" class="text" name="date1" type="textbox" readonly="readonly" value="<?php echo $date1; ?>">
												
													<label>Time in:</label>
													<input id="timepicker1" class="text" name="time1" readonly="readonly" type="textbox" value="<?php echo $start_time; ?>">
													
													<label>Time Out:</label>
													<input id="timepicker2" class="text" name="time2" readonly="readonly" type="textbox" value="<?php echo $end_time; ?>">
													<?php $datestamp = time() - ($hours_back * 60 * 60) ;?>
													<?php $earliest_date = date("Y-m-d",$datestamp);?>
													<hr>
													<label>Division:</label>
													<?php
														echo '<select name="division">';
														echo '<option value="">Select Division...</option>';
														foreach ($divisions as $key=>$value){
														echo '<option value="'. $key .'"';
														if ($key == $div){echo ' selected';}
														echo '>';
														echo $value;
														echo '</option>';
														}
														echo '</select>';
													?>
													<label>Sub-Division:</label><input class="text" name="subdiv" size="8" type="textbox" value="<?php echo $subdiv;?>">
													<label>Change Order:</label>
													<?php echo $co_select;?>
													<div style="clear:both;"></div>
													<hr>
													<textarea cols="30" rows="3" name="comment" placeholder="Comment"><?php echo $comment; ?></textarea>
													<br><br>
													<input value="Submit" name="submit" type="submit"><br>
											</div>

										</fieldset>
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