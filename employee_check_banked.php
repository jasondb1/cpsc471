<?php
//ini_set('display_errors',0); 
//error_reporting(0);
header("Cache-Control: private, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // A date in the past

/*////////////////////////////////////////////////////////////////////////////////
Functions
///////////////////////////////////////////////////////////////////////////////*/

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
	$usersArray = array("administrator");
	$groupsArray = array("admin","payroll");
	pageProtect($usersArray,$groupsArray);

/*////////////////////////////////////////////////////////////////////////////////
Variables
///////////////////////////////////////////////////////////////////////////////*/	
	//get and set initial variables
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
				
		$db_table="timelog";
		$page_title="Check Banked Hours";


	//Edit or upload page
		//$edit_page="employee_time_entry.php";	
	
///////////////////////////////////////////////////////////////

	$employee_list = getEmployeeNames();//get employee names
	
//$employee = "jdeboer";
$user 			= getUsername();

$employee = $_POST['employee'];
$banked_time = $_POST['banked_time'];

if(isset($age_cutoff)){
$age_cutoff = $_POST['age_cutoff'];
} else{
$age_cutoff=90;
}

if ($_POST['banked_time'] > 0){
	$x= time();
	$oneday = (24*60*60);
	$k = 0;
	///////////////////////////////////////Table 2 - Calculate Summary by Day
	//date start to date end
	$output="";
	$output .= "<table><tr><th>Date</th><th>Banked Hours</th><th>Balance</th><th>Age</th></tr>";
	while ($k < $banked_time){
		
		$d1= date ("Y-m-d",$x);
		$where1 = " WHERE (`date` = '$d1') AND employee = '$employee'";
		$sql = "SELECT SUM(hours) AS totalhours FROM $db_timelog" . $where1;
		//echo $sql."<br>";
		
		$sum = $mysql_link->query($sql);	
			while($row=$sum->fetch_assoc())	{
					$hours_this_day = $row['totalhours'];
					//$hours_this_day = 10;
					if ($hours_this_day > 8){
					    $ot_hours = $hours_this_day - 8;
						$k += $ot_hours;
						$number_days_old = (round ((($x - time() ) / $oneday),0) * -1);
						if ($number_days_old <= $age_cutoff){
							$hours_current += $ot_hours;
						}
						//$output .= date ("Y-m-d",$x) . " - ". round($ot_hours,2) ." hrs :: Balance - $k hrs<br>";
						$output .= "<tr><td>".date ("Y-m-d",$x) . "</td><td>". round($ot_hours,2) ."</td><td>$k</td><td>$number_days_old</td></tr>";
						//echo $k;
					}	
				}
			$x -= $oneday;
		}
		
			$output .= "</table>";
			
			$output .= "<h5>Current Banked Hours within the last ". $age_cutoff   ." days: ". round ($hours_current,2)  ."</h5>";
			if( $k > $hours_current){
				$output .= "<h5>Overdue Banked Hours greater then ". $age_cutoff   ." days: ". round (($k - $hours_current),2)  . "</h5>";
			}
			$output .= "<h5>Date of Oldest Banked Hours: ".   $d1 . " (".(-1 * round ((($x + 1  - time() ) / $oneday),0))   ." days old)</h5>";
			//$output .= "<h5>Oldest Banked Hour: ". (-1 * round ((($x + 1  - time() ) / $oneday),0))   ." days old</h5>";
		}
	


// /////////////////////////////////email submitted timelog	

// if (isset($_REQUEST['final'])) { // if finalize timesheet is selected
		// $to = $email_payroll;
	// //$to = "jasond@jdsservices.ca";
		// $from = "noreply.timesheet@amcgroup.ca";
		// $subject = "Timesheet - ".$employee.' - ' . $date_end;
	// //begin of HTML message
		// $message = '<head>
		// <style>
		// table {border-collapse: collapse; empty-cells: show ; border:1px solid black;} 
		// td,th {border:1px solid black;} 
		// </style> 
		// </head>';
		// $message = '<html><body bgcolor="#FFFFFF">';
		// $message .= '<b>Timesheet</b><br><br>';
		// $message .= 'Employee:'.$employee;
		// $message .= '<br>Pay Period:'. $date_start . " to " . $date_end;
		// $message .= '<br><hr>';
		// $table1 = str_replace ("><",">\n<",$table);
		// $message .= $table1;
		// $message .= $table2;
		// $message .= $table3;
		// $message .= '</body></html>';
	// //end of message
			// $headers  = "From: $from\r\n";
			// $headers .= "Cc: $user_email\r\n";
			// $headers .= "Content-type: text/html\r\n";   
	// // Send the email.
		// //print_r ($message);
		// mail($to, $subject, $message, $headers);

		// echo $table2;
		// echo $table3;
		
		// echo "Timesheet has been sent to:$to and cc: $user_email....!";
						// // $data=$lname . ":$employee Timelog Submitted";
						// // write_log ($logfile,$data  );	
		// echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
		// echo "<br><br><b><big>Successfully Submitted</b></big><br><br>";
		// echo '<input type=button value="Back" onClick="location.href=\'employee_time_view.php\'">';
// die ();
// }//end final	
	
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
					showWeek: true,
					firstDay: 1
					}
				);
				$( "#datepicker2" ).datepicker(
					{
					dateFormat: "yy-mm-dd",
					showButtonPanel: true,
					changeMonth: true,
					changeYear: true,
					showWeek: true,
					firstDay: 1
					}
				);
			});
		</script>
		<style type="text/css">
		  #datepicker1, #datepicker2{
			background-image:url("images/calendar.png");
			background-position:right center;
			background-repeat:no-repeat; }
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
							<!-- Highlight -->
						<section class="is-page-content">
							<div class="row flush" style="padding:0em; padding-top:2em;">
								<div class="12u">
									<header>
										<h3>Current Banked Hours</h3>
									</header>
						
											<div id="menu">
												<ul>
												<?php 
													//if (isEnabled(array("administrator"),array("admin","payroll"))){
													//echo '<li><a href="employee_time_summary.php" ><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; View Overall Summary</span></a></li>';}
												?>
												<?php 
												//if (isEnabled(array("administrator"),array("admin","payroll","supervisor"))){
													//echo '<li><a href="employee_time_approve.php" ><span class="button-menu"><i class="fa fa-check-square-o fa-fw"></i>&nbsp; Approve Time</span></a></li>';}
												?>
												<li><a href="_employee_menu.php" ><span class="button-menu"><i class="fa fa-back fa-fw"></i>&nbsp; Back</span></a></li>
												</ul>
											</div>
								</div>
							</div>

					<div class="row flush" style="padding:0em;">
											<div class="12u" style="border-right:1px;">
												<form style="" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" name="viewoptions">
													<label>Employee</label>
													<?
													echo '<select name="employee">';
														echo '<option>$user</option>';
														foreach ($employee_list as $x){
															echo "<option";
															if ($x == $employee) {echo " selected";}
															echo ">$x</option>";
														}
														echo '</select>';?>
													<br>
													<label>Banked Hours:</label>
													<input class="text" name="banked_time" type="textbox" value="<?php echo $banked_time; ?>"><br>
													<label>Age Cutoff:</label>
													<input class="text" name="age_cutoff" type="textbox" value="<?php echo $age_cutoff; ?>"><br>
													<input class="submit" type="submit" name="submit" value="Update View";/>
												</form>
											</div>	
			
					</div>
								
							
								<div class="row flush" style="padding:0em;">
											<div class="12u">
												<hr>											
												<b><h5>List of Outstanding Banked Hours</h5></b><br>
												<?php echo $output;?>
											</div>
								</div>								
										
										
								</section>
							<!-- /Highlight -->

						</div>
						
					</div>
					
					<div class="row">
						<div class="12u">

							<!-- Features -->
							
								
							<!-- /Features -->

						</div>
					</div>
	

		<!-- /Main -->

		<!-- Footer -->
			<footer id="footer" class="container">


				<!-- Copyright -->
					<div id="copyright">
						&copy; <? echo $company_name; ?> | Template: <a href="http://html5up.net/">HTML5 UP</a>
					</div>
				<!-- /Copyright -->

			</footer>
		<!-- /Footer -->

	</body>
</html>