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
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
		
		$user 			= getUsername();
		$user_email 	= getEmail(getUserId(),"phpauthent_users");
		$delete_record	=$_GET['delete_record'];
		$edit_allowed_days = 30;
		
		$employee = $_GET['employee'];
		if ($employee == ""){
			$employee = $user;
		}

		$db_table="snowlog";
		$page_title="Snow Log";	

	//Edit or upload page
		$edit_page="employee_snow_entry.php";	
	
//get and set initial variables
	$columns = array ("edit"=>"",
						"date"=>"Date", 
						"location"		=>"Location",   
						"time_in"		=>"In",
						"time_out"		=>"Out",
						"hours"			=>"Hrs",
						"activity"		=>"Act",
						"salt_bags"		=>"Salt",
						"loads_hauled"	=>"Loads",
						"del"			=>"Delete"
						);
						
if (isEnabled(array("administrator"),array("admin","payroll"))){
						$columns = array (
						"edit"=>"",
						"date"=>"Date",
						"employee"		=>"Employee",
						"location"		=>"Location",   
						"time_in"		=>"In",
						"time_out"		=>"Out",
						"hours"			=>"Hrs",
						"activity"		=>"Act",
						"salt_bags"		=>"Salt",
						"loads_hauled"	=>"Loads",
						"del"			=>"Delete"
						);
	}
	
	$employee = $_GET['employee'];
	if ($employee == ""){
		$employee = $user;
	}
	//set initial variables
		$location = "All";
		$date = strtotime (date ("Y-m-d"));
		$date_end= cutoff_date($date); // in fn_lib.php
		
		$display_cutoff = cutoff_date(strtotime ($date_end));
		$previous_cutoff = date ("Y-m-d" , strtotime('-14 days',strtotime($display_cutoff)));

		//if date is passed with url
		if (isset($_POST['previous'])){
			$date_current = $_POST['date_current'];
			$location = $_POST['location'];
			$employee = $_POST['employee'];	
			$display_cutoff = cutoff_date(strtotime ($date_current));
			$date_end = date ("Y-m-d" , strtotime('-14 days',strtotime($display_cutoff)));
		}
		
		if (isset($_POST['next'])){
			$date_current = $_POST['date_current'];
			$location = $_POST['location'];
			$employee = $_POST['employee'];	
			$display_cutoff = cutoff_date(strtotime ($date_current));
			$date_end = date ("Y-m-d" , strtotime('+14 days',strtotime($display_cutoff)));
		}

		//calculate payperiods and cutoffs
		$date_start = date ("Y-m-d" , strtotime('-13 days',strtotime($date_end)));
		$cutoff_code = "";
		
		//view options
		if (isset($_POST['submit'])) {
		//echo "submit pressed";
			$location = $_POST['location'];
			$date_start = $_POST['date1'];
			$date_end = $_POST['date2'];
			$employee = $_POST['employee'];	
		}
	
/*/////////////////////////////////////////////////
Delete Record
/////////////////////////////////////////////////*/
			if ($delete_record!=""){
			
				$mysql_link->query("DELETE FROM $db_table WHERE timelog_id='$delete_record'");
				$mysql_link->query("DELETE FROM $db_timelog WHERE id='$delete_record'"); 
				 //write log
					$details="jn:$jobnumber,$date,$start_time,$end_time,$delete_record";
					write_log_file ($user,'Snow Time Delete',$employee,$details);
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
		if ($location != "All"){$where .= " AND location = '$location'";}
		$order = " ORDER BY `date`";
		$sql = "SELECT * FROM $db_snowlog" . $where . $order;
		
		$retval = $mysql_link->query($sql);
		
		$sum = $mysql_link->query("SELECT SUM(hours) AS totalhours FROM $db_snowlog".$where);
		while($row=$sum->fetch_assoc())
		{
		$total_hours = $row['totalhours'];
		}
		//get location_list
			
		$sql = "SELECT DISTINCT location FROM $db_snowlog".$where;
		$retval_location = $mysql_link->query($sql);

		while ($row = $retval_location->fetch_assoc()) {
			$location_list[]=$row['location'];
		}

///////////////////////////////////////Table 2 - Calculate Summary by Day
//date start to date end
	$table2 = '<div style="color:#000; text-align:left;"><br><br><b>Daily Total</b><br>';
	$t1=strtotime($date_start);
	$t2=strtotime($date_end);	
	$oneday = (24*60*60);
	$x=$t1;
	while ($x<=$t2){
		$d1= date ("Y-m-d",$x);
		$where1 = " WHERE (`date` = '$d1')";
		if ($employee != "All"){$where1 .= " AND employee = '$employee'";}
		$sql = "SELECT * FROM $db_snowlog" . $where1;
		$sum = $mysql_link->query("SELECT SUM(hours) AS totalhours FROM $db_snowlog".$where1);	
			while($row=$sum->fetch_assoc())
				{
					$hrs = $row['totalhours'];
				}
		if ($hrs>0){$table2 .= date("D-M-d",$x) . ": <b>" . round($hrs, 2) . "</b><br>";}
			 $x+=$oneday;
	}
	$table2 .="</div>";

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
						if ($i == "edit") { 
							if (strtotime($row['date'])>mktime(0,0,0,date("m"),date("d")-$edit_allowed_days,date("y"))){
								$id_number = $row['timelog_id'];
								
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
							$table .=  '<a href="'.$_SERVER['PHP_SELF'].'?delete_record=' . $row['timelog_id'] . '&j='. $masked_jobnumber . '&date_current='. $date_current.'&date_end='.$date_end.'&employee='.$employee.'" onclick="return confirm(\'Confirm Delete?\')" ><span style="color:#f00;"><i class="fa fa-minus-circle fa-lg fa-fw"></i></span></a>';
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
										<h3>Snowlog View - Current Period:<?php echo $date_start . " to " . $date_end ?></h3>
									</header>
						
											<div id="menu">
												<ul>
												<?php 
													if (isEnabled(array("administrator"),array("admin","payroll"))){
													echo '<li><a href="employee_snow_summary.php" ><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; View Snow Summary</span></a></li>';}
												?>
												<li><a href="employee_snow_entry.php" ><span class="button-menu"><i class="fa fa-plus fa-fw"></i>&nbsp; Add Time</span></a></li>
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
													<input type="hidden" name="location" value="<? echo $location;?>">
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
														echo '<label>Location:</label>';
														echo '<select name="location">';
														echo '<option>All</option>';
														foreach ($location_list as $x){
															echo "<option";
															if ($x == $location) {echo " selected";}
														echo ">$x</option>";}
														echo '</select>';

														if (isEnabled(array("administrator"),array("admin","payroll","supervisor"))){
															echo '<label>Employee:</label>';
															echo '<select name="employee">';
															echo '<option>All</option>';
															foreach ($employee_list as $x){
																echo "<option";
																if ($x == $employee) {echo " selected";}
																echo ">$x</option>";
															}//end foreach
															echo '</select>';
														}//end if
														else{
															echo '<input type="hidden" name="employee" value="'.  $employee .'">';
															echo '<input type="hidden" name="location" value="All">';
														}//end else
														?>
													
													<input class="submit" type="submit" name="submit" value="Update View";/>
												</form>
											</div>	
											<div class="-2u 4u">
											&nbsp
											</div>											
					</div>
								<hr>
								<div class="row flush" style="padding:0em;">
											<div class="12u">	
												<b>Snow Log</b>
												<?php echo $table;?>
											</div>
								</div>
								
								<div class="row flush" style="padding:0em;">
									<hr>
									<div class="4u">		
												<?php echo $table2;?>
									</div>
									<div class="4u">			
												
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