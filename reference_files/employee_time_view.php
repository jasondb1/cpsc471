<?php
//ini_set('display_errors',0); 
//error_reporting(0);
header("Cache-Control: private, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // A date in the past

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
	$groupsArray = array("admin","employee","payroll","supervisor");
	pageProtect($usersArray,$groupsArray);

/*////////////////////////////////////////////////////////////////////////////////
Variables
///////////////////////////////////////////////////////////////////////////////*/	
	//get and set initial variables
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		
		if ($mysql_link->connect_errno) {
			die($mysql_link->connect_error);
		}
		
		$user 			= getUsername();
		$user_email 	= getEmail(getUserId(),"phpauthent_users");
		$delete_record	=$_GET['delete_record'];
		$edit_allowed_days = 30;
		
		$employee = $_GET['employee'];
		if ($employee == ""){
			$employee = $user;
		}
		//Sets the columns visible in the table format (column name=>display name) special ("edit,"amount","price","del","delete","filename","hours")
			$columns=array("edit"=>"","jobnumber"=>"Job No","date"=>"Date","time_in"=>"In","time_out"=>"Out", "hours"=>"Hours", "division"=>"Div", "sub_division"=>"Sub-Div", "comment"=>"Comment","del"=>"");
		
		if (isEnabled(array("administrator"),array("admin","payroll"))){
			$columns=array("edit"=>"","jobnumber"=>"Job No","employee"=>"Employee","date"=>"Date","time_in"=>"In","time_out"=>"Out", "hours"=>"Hours", "division"=>"Div", "sub_division"=>"Sub-Div", "comment"=>"Comment","del"=>"");
		}
		
		$db_table="timelog";
		$page_title="Timelog";	

	//Edit or upload page
		$edit_page="employee_time_entry.php";	
	
	//set initial variables
		$jobnumber = "All";
		$date = strtotime (date ("Y-m-d"));
		$date_end= cutoff_date($date);
		$display_cutoff = cutoff_date(strtotime ($date_end));
		$previous_cutoff = date ("Y-m-d" , strtotime('-14 days',strtotime($display_cutoff)));

	//if date is passed with url
		if (isset($_GET['date_current'])){
			$date_current 	= $_GET['date_current'];
			$date_end 		= $_GET['date_end'];
			$employee 		= $_GET['employee'];	
		}
	
	//if date is passed with url
		if (isset($_POST['previous'])){
			$date_current = $_POST['date_current'];
			$jobnumber = $_POST['jobnumber'];
			$employee = $_POST['employee'];	
			$display_cutoff = cutoff_date(strtotime ($date_current));
			$date_end = date ("Y-m-d" , strtotime('-14 days',strtotime($display_cutoff)));
		}
		
		if (isset($_POST['next'])){
			$date_current = $_POST['date_current'];
			$jobnumber = $_POST['jobnumber'];
			$employee = $_POST['employee'];	
			$display_cutoff = cutoff_date(strtotime ($date_current));
			$date_end = date ("Y-m-d" , strtotime('+14 days',strtotime($display_cutoff)));
		}

	//calculate payperiods and cutoffs
		$date_start = date ("Y-m-d" , strtotime('-13 days',strtotime($date_end)));
		$cutoff_code = "";
		
	//view options
		if (isset($_POST['submit']) || isset($_POST['final'])) {

			$jobnumber = $_POST['jobnumber'];
			$date_start = $_POST['date1'];
			$date_end = $_POST['date2'];
			$employee = $_POST['employee'];	
		}
	
/*/////////////////////////////////////////////////
Delete Record
/////////////////////////////////////////////////*/
			if ($delete_record!=""){
	
				//mysql_query("DELETE FROM $db_table WHERE uid='$delete_record'") or die(mysql_error());  
				 //write log
				 $sql = "DELETE FROM $db_table WHERE uid='$delete_record'";
				 $mysql_link->query($sql);
				 
					$details="jn:$jobnumber,$date,$start_time,$end_time,$delete_record";
					write_log_file ($user,'Time Delete',$employee,$details);
					
				echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
				echo "<br><br><b><big>Successfully Deleted</b></big><br><br>";
				echo 	'<input type="Button" value="Back" onclick="location.href=\''.$_SERVER['PHP_SELF']  .'?j='.$masked_jobnumber  .'&date_current='. $date_current .'&date_end='. $date_end .'&employee='.$employee   .'\'">';
				die();		
			}//end if delete

///////////////////////////////////////////////////////////////

	$employee_list = getEmployeeNames();//get employee names
	
	//get data
		$where = "";
		$where = " WHERE (`date` BETWEEN '$date_start' AND '$date_end')";
		if ($employee != "All"){$where .= " AND employee = '$employee'";}
		if ($jobnumber != "All" AND $jobnumber!=""){$where .= " AND timelog.jobnumber = '$jobnumber'";}
		$order = " ORDER BY `date`,`time_in`";
		//$sql = "SELECT * FROM $db_timelog" . $where . $order;
		$sql = "SELECT * FROM $db_timelog JOIN jobdata ON timelog.jobnumber = jobdata.jobnumber" . $where . $order;
		//echo $sql;
		//$retval = mysql_query($sql) or die(mysql_error());
		$retval = $mysql_link->query($sql);
		$sql = "SELECT SUM(hours) AS totalhours FROM $db_timelog".$where;
		//$sum = mysql_query("SELECT SUM(hours) AS totalhours FROM $db_timelog".$where);
		$sum = $mysql_link->query($sql);
		while($row = $sum->fetch_assoc())
		{
			$total_hours = $row['totalhours'];
		}

/////////////////////////////////////Table 2 - Calculate Summary by Day
	//date start to date end
	$hour_basis=44;
	$table2 = '<div style="color:#000; text-align:left;"><br><br><b>Daily Total</b><br>';
	$table_daily = '<table><tr><th>Date</th><th>Regular Hours</th><th>OT Hours</th><th>Total</th></tr>';
	$t1=strtotime($date_start);
	$t2=strtotime($date_end);	
	$oneday = (24*60*60);
	$x=$t1;
	while ($x<=$t2){
		$d1= date ("Y-m-d",$x);
		$where1 = " WHERE (`date` = '$d1')";
		if ($employee != "All"){$where1 .= " AND employee = '$employee'";}
		if ($jobnumber != "All"){$where1 .= " AND jobnumber = '$jobnumber'";}
		$sql = "SELECT SUM(hours) AS totalhours FROM $db_timelog".$where1;
		//$sum = mysql_query("SELECT SUM(hours) AS totalhours FROM $db_timelog".$where1);
		$sum = $mysql_link->query($sql);
			while($row=$sum->fetch_assoc())
				{
					$key=date ("ymd",$x);
					$week = date ("W",$x);
					$hours_this_day = $row['totalhours'];
					if($hours_this_day>=8){
						$regular_hours[$key]= 8;
						$ot_hours[$key]= $hours_this_day - 8;
						$daily_ot_total += $hours_this_day - 8;
						$regular_total += 8;
					} else{
						$regular_hours[$key] = $hours_this_day;
						$regular_total += $hours_this_day;
					}
					
					$hrs = $row['totalhours'];
					$weekly_hours[$week] += $hrs;
					
				}
		if ($hrs>0){
		$table2 .= date("D-M-d",$x) . ": <b>" . round($hrs, 2) . "</b><br>";
		$table_daily .='<tr><td>'.date("D-M-d",$x).'</td>';
		$table_daily .='<td>'.round($regular_hours[$key],2).'</td>';
		$table_daily .='<td>'.round($ot_hours[$key],2).'</td>';
		$table_daily .='<td>'.round($hrs, 2).'</td></tr>';
		}
			$x+=$oneday;
	}
	$table_daily .='</table>';
	$table_daily .= "<br><h4>Daily Totals:</h4>";
	$table_daily .= "<h5>Daily OT Total:</h5>$daily_ot_total<br>";
	$table_daily .= "<h5>Regular Hours Total:</h5>$regular_total<br>";
	$table_daily .= "<br><h4>Weekly Totals:</h4>";
	foreach ($weekly_hours as $key=>$value){
		$table_daily .= "<h5>Week:". $key ."</h5>Total Hours - " . round($value,2) . " Hrs<br>";
		if ($value >= $hour_basis){
			$table_daily .= "Regular Hours: $hour_basis</br>";
			$table_daily .= "OT Hours:". (round($value,2) - $hour_basis) . "<br>";
			$weekly_ot += (round($value,2) - $hour_basis);
		}else{
			$table_daily .= "Regular Hours:". round($value,2) . "<br>";
			$table_daily .= "OT Hours:0<br>";
		}
	}
	
		$table_daily .= "<br><h4>Hours To Be Banked:</h4>";
		$table_daily .= "Greater of Daily or Weekly OT Totals: ";
		if ($daily_ot_total > $weekly_ot){
			$table_daily .= $daily_ot_total."<br>";
		}else{
			$table_daily .= $weekly_ot."<br>";
		}
	
	$table2 .="</div>";

/////////////////////////////////////Table 2 - Calculate Summary	
	//get data
		$sql1 = "SELECT SUM(hours) AS totalhours FROM $db_timelog WHERE (`date` BETWEEN '$date_start' AND '$date_end') AND employee='$employee' AND jobnumber = '1019';";
		//$return = mysql_query($sql1) or die(mysql_error());
		$return = $mysql_link->query($sql1);
		
		$table3 = '<div style="color:#000; text-align:left;"><br><br><b>Other Totals</b><br>Total Hours:'.round($total_hours,2);
		$row= $return->fetch_assoc();//mysql_fetch_array($return);
		$bank_time=$row['totalhours'];
		$table3 .="<br>Bank Time:".round($bank_time,2)."<br>";
		
		$sql1 = "SELECT SUM(hours) AS totalhours FROM $db_timelog WHERE (`date` BETWEEN '$date_start' AND '$date_end') AND employee='$employee' AND jobnumber = '1015';";
		//$return = mysql_query($sql1) or die(mysql_error());
		$return = $mysql_link->query($sql1);
		$row= $return->fetch_assoc();//mysql_fetch_array($return);
		//$row=mysql_fetch_array($return);
		$vacation_time=$row['totalhours'];
		$table3 .="Vacation Time:".  round($vacation_time,2) ."<br>";
		$table3 .='Hours Worked:'. round(($total_hours - $vacation_time - $bank_time),2).'</div>';

///////////////////////////////////////Table 1 - Main Calculate Summary by Day	
//table code
		$table = '<table id="dbtable" style="width: 100%;"><tr>';
			//Show Headings
				foreach ($columns as $x=>$y){
					$heading=$y;
					// if ($direction =="ASC") {
						// $table .= "<th><b><a href=". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=DESC><b>$heading</b></a></b></th>";
					// }
					// else {
						// $table .= "<th><b><a href='". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=ASC'><b>$heading</b></a></b></th>";
					// }
					$table .= "<th><b>$heading</b></th>";
				  }
				$table .= '</tr>';
			//Output the data		
				$j=1;
				while($row = $retval->fetch_assoc())
				{
					// Color odd lines
					if ($j % 2 == 0){ 
						$table .= '<tr style="background-color: #f0f0f0;">'; 
					} 
					else { 
						$table .= '<tr style="background-color: #e0e0e0;">';
					}
					foreach ($columns as $i=>$value){
						$color = "000000";//basic color
							// foreach ($status_color as $status_key=>$color_value){
								// if ($row['status']==$status_key){
								// $color=$color_value;
								// }
							// }
						$table .=  '<td style="color:#' . $color . ';">';  
				//check if special cell and format set formatting conditions				
						if ($i == "edit" && $row['jobnumber'] != 1005 ) { 
							if (strtotime($row['date'])>mktime(0,0,0,date("m"),date("d")-$edit_allowed_days,date("y"))){
								$id_number = $row['uid'];
								
								$table .= '<a href="'.$edit_page .'?id='. $id_number . '">
								<span style="color:#092;">
								<i class="fa fa-edit fa-lg fa-fw"></i>
								</span></a></td>';
							}
						}
						elseif ($i == "filename"){
							$filename=$row[$i];
							$table .= '<a href="'.	$path . $filename.'"><span style="color:#00f;"><i class="fa fa-file-o fa-lg fa-fw"></i></span></a>';
						}
						elseif ($i=="jobnumber"){
							$string = $row['customer']." : ".$row ['location']." : ".$row ['description'];
							$table .= '&nbsp<a href="#" title="'.$string .'" onclick="alert(\''.$string.'\')"><span style="color:#20d;"><i class="fa fa-info-circle fa-fw"></i></span></a>'.$row[$i];
						}
						elseif ($i=="amount" || $i=="price"){ 
							$table .= money_format("%n",$row[$i]);
						}
						elseif ($i=="hours"){ 
							$table .= sprintf("%01.2f", $row[$i]).'</td>';
						}
						elseif ($i == "time_in" or $i == "time_out") {
						$table .= date ("H:i",strtotime($row[$i]));  
						}
						elseif ($i=="delete" || $i=="del"){ 
						if (strtotime($row['date'])>mktime(0,0,0,date("m"),date("d")-$edit_allowed_days,date("y"))){
							$table .=  '<a href="'.$_SERVER['PHP_SELF'].'?delete_record=' . $row['uid'] . '&j='. $masked_jobnumber . '&date_current='. $date_current.'&date_end='.$date_end.'&employee='.$employee.'" onclick="return confirm(\'Confirm Delete?\')" ><span style="color:#f00;"><i class="fa fa-minus-circle fa-lg fa-fw"></i></span></a>';
							//$table .=  '<a href="" onClick="checkDelete(\''.$row['uid'].'\')" ><span style="color:#f00;"><i class="fa fa-minus-circle fa-lg fa-fw"></i></span></a></td>';
							
							}
						}
				//Regular Cell	
						else{ 
							$table .= $row[$i] . "</td>"; 
						}	
					}//end foreach for column

				// add extra columns (if required)	
					$table .= '</tr>';
					$j++;
				}//end while
			
		$table .='</table>';
	$table .= '<div style="color:#000; text-align:left;"><b>Total Hours:</b>'. round($total_hours,2) . "</div>";	
	
///////////////////////////////email submitted timelog	

if (isset($_REQUEST['final'])) { // if finalize timesheet is selected
		$to = $user_email;
	//$to = "jasond@jdsservices.ca";
		$from = "noreply.timesheet@amcgroup.ca";
		$subject = "Timesheet - ".$employee.' - ' . $date_end;
	//begin of HTML message
		$message = '<head>
		<style>
		table {border-collapse: collapse; empty-cells: show ; border:1px solid black;} 
		td,th {border:1px solid black;} 
		</style> 
		</head>';
		$message = '<html><body bgcolor="#FFFFFF">';
		$message .= '<b>Timesheet</b><br><br>';
		$message .= 'Employee:'.$employee;
		$message .= '<br>Pay Period:'. $date_start . " to " . $date_end;
		$message .= '<br><hr>';
		$table1 = str_replace ("><",">\n<",$table);
		$message .= $table1;
		$message .= $table2;
		$message .= $table3;

		$message .= '</body></html>';
	//end of message
			$headers  = "From: $from\r\n";
			//$headers .= "Cc: $user_email\r\n";
			$headers .= "Content-type: text/html\r\n";   
	// Send the email.
		//print_r ($message);
		mail($to, $subject, $message, $headers);

//////////////////////////////mail to user
		$to = $email_payroll;
		$message .= "<html><body>".$table_daily."</body></html>";
		mail($to, $subject, $message, $headers);
		
		
		
		
		
		echo $table2;
		echo $table3;
		
		echo "Timesheet has been sent to:$to and cc: $user_email....!";
						// $data=$lname . ":$employee Timelog Submitted";
						// write_log ($logfile,$data  );	
		echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
		echo "<br><br><b><big>Successfully Submitted</b></big><br><br>";
		echo '<input type=button value="Back" onClick="location.href=\'employee_time_view.php\'">';
die ();
}//end final	
	
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
										<h3>Timelog View - Current Period:<?php echo $date_start . " to " . $date_end ?></h3>
									</header>
						
											<div id="menu">
												<ul>
												<?php 
													if (isEnabled(array("administrator"),array("admin","payroll"))){
													echo '<li><a href="employee_time_summary.php" ><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; View Overall Summary</span></a></li>';}
												?>
												<?php 
												if (isEnabled(array("administrator"),array("admin","payroll","supervisor"))){
													echo '<li><a href="employee_time_approve.php" ><span class="button-menu"><i class="fa fa-check-square-o fa-fw"></i>&nbsp; Approve Time</span></a></li>';}
												?>
												<li><a href="employee_time_select_job.php" ><span class="button-menu"><i class="fa fa-plus fa-fw"></i>&nbsp; Add Time</span></a></li>
												</ul>
											</div>
								</div>
							</div>
					<div class="row flush" style="padding:0em;">					
						<div class="12u">
												<hr>
												<form style="" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" name="viewoptions">
													<input class="submit" type="submit" name="previous" value="Previous Cutoff">
													<input class="submit" type="submit" name="next" value="Next Cutoff">
													<br>
													<input name="employee" type="hidden" value=<?php echo $employee;?>>
													<input name="date_current" type="hidden" value=<?php echo $date_end;?>>
													<input type="hidden" name="jobnumber" value="<? echo $jobnumber;?>">
												</form>
												<hr>
					
						</div>
					</div>
					<div class="row flush" style="padding:0em;">
											<div class="6u" style="border-right:1px;">
												<form style="" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" name="viewoptions">
													<label>Start Date:</label><input id="datepicker1" class="text" name="date1" type="textbox" readonly="readonly" value="<?php echo $date_start; ?>">
													<label>End Date:</label><input class="text" id="datepicker2" name="date2" type="textbox" readonly="readonly" value="<?php echo $date_end; ?>">
													
											<?php 
													if (isEnabled(array("administrator"),array("admin","payroll"))){
														echo '<hr><label>Job Number:</label>';
														// echo '<select name="jobnumber">';
														// echo '<option>All</option>';
														// foreach ($job_number_list as $x){
														// echo "<option";
														// if ($x == $jobnumber) {echo " selected";}
														// echo ">$x</option>";}
														// echo '</select>';
														echo '<input class="text" type="text" name="jobnumber" value="'. $jobnumber . '">';
														echo '<label>Employee:</label>';
														echo '<select name="employee">';
														echo '<option>All</option>';
														foreach ($employee_list as $x){
															echo "<option";
															if ($x == $employee) {echo " selected";}
															echo ">$x</option>";
														}
														echo '</select>';
														//echo '<input class="submit" type="submit" name="submit" value="Update View";/>';
													}
													else{
														echo '<input type="hidden" name="employee" value="'. $employee . '">';
														echo '<input type="hidden" name="jobnumber" value="All">';
													}
													?>
													<input class="submit" type="submit" name="submit" value="Update View";/>
												</form>
											</div>	
											<div class="-2u 4u">
												<br><hr>
												<form style="" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" name="viewoptions">
													<input name="employee" type="hidden" value=<?php echo $employee;?>>
													<input type="hidden" name="jobnumber" value="All">
													<input name="date_current" type="hidden" value=<?php echo $date_end;?>>
													<input id="datepicker1" name="date1" type="hidden" value=<?php echo $date_start;?>>
													<input id ="datepicker2" name="date2" type="hidden" value=<?php echo $date_end;?>>
													<input name="final" type="checkbox" value="1">Confirm timesheet submission for dates shown<br>
													<input class="submit" name="submit_time" value="Submit Timelog" type="submit">
												</form>
											</div>
											
					</div>
								<hr>
								<div class="row flush" style="padding:0em;">
											<div class="12u">	
												<b>Timelog</b>
												<?php echo $table;?>
											</div>
								</div>
								
								<div class="row flush" style="padding:0em;">
									<hr>
									<div class="4u">		
												<?php 		
												if (isEnabled(array("administrator"),array("admin","payroll"))){
													echo $table_daily;
												}else{
													echo $table2;
												}
												?>
									</div>
									<div class="4u">			
												<?php echo $table3;?>
									</div>
									<div class="4u">
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