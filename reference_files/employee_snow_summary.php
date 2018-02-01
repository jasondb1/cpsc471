<?php
//ini_set('display_errors',0); 
//error_reporting(0);
//error_reporting(E_ALL ^E_NOTICE ^E_WARNING);
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
	require_once("_functions_common.php");
	
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
		
		$user 			= getUsername();
		$delete_record	=$_GET['delete_record'];
		
		//get and set initial variables		
		$employee = $_GET['employee'];
		if ($employee == ""){
			$employee = $user;
		}

		//Sets the columns visible in the table format (column name=>display name) special ("edit,"amount","price","del","delete","filename","hours")
			$columns=array("edit"=>"","jobnumber"=>"Job No","date"=>"Date","time_in"=>"In","time_out"=>"Out", "hours"=>"Hours", "division"=>"Div", "sub_division"=>"Sub-Div", "comment"=>"Comment");
		
		// if (isEnabled(array("administrator"),array("admin","payroll"))){
			// $columns=array("edit"=>"","jobnumber"=>"Job Number","employee"=>"Employee","date"=>"Date","time_in"=>"Time In","time_out"=>"Time Out", "hours"=>"Hours", "division"=>"Division", "sub_division"=>"Sub-Division", "comment"=>"Comment""del"=>"");
		// }
		
		$db_table="timelog";
		$page_title="Snow Log Summary";	

	//Edit or upload page
		$edit_page="employee_snow_entry.php";	
	
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
			$employee = $_GET['employee'];	
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
	
///////////////////////////////////////////////////////////////
	
	//get data
		$where = "";
		$where = " WHERE (`date` BETWEEN '$date_start' AND '$date_end')";
		$order = " ORDER BY `date`";
		//$order="";
		$sql = "SELECT * FROM $db_snowlog" . $where . $order;
//echo $sql;		
		$retval = $mysql_link->query($sql);

		while($row=$retval->fetch_assoc())
		{
			$my_array[]=array('location'=>$row['location'],'employee'=>$row['employee'],'date'=>$row['date'],'hours'=>$row['hours'],'activity'=>$row['activity'],'salt_bags'=>$row['salt_bags'],'loads_hauled'=>$row['loads_hauled']);
			$location_list[]=$row['location'];
		}
		
		$location_list = array_unique($location_list);
		sort($location_list);

////////////////////////Make new array for summary table
$table="";

foreach ($location_list as $key=>$value){
	$loc=$value;
	$total_hours="";
	$salt_bags="";
	$loads="";
	$billable_time="";
	$travel_time = "";
	$hours_activity = array();
	$hours_activity_travel = array();
	$table .= "<h5>$value</h5>";
	$table .= "<table id=\"table_print\" style=\"text-align: left; color:#000;\" border=\"1\" cellpadding=\"1\" cellspacing=\"0\">";
	$table .= "<tr>
		<th>Employee</th>
		<th>Date</th>
		<th>Activity</th>
		<th>Hours</th>
		<th>Saltbags</th>
		<th>Loads Hauled</th>
		<th>Billable Time</th>
		<th>Travel Time</tr>
	</tr>";
	foreach ($my_array as $row){
		//echo $key_1."=".$value_1."<br>";
		if ($row['location']==$loc){
			$hours = $row['hours'];
			$billable_hours = (ceil ($hours * 60 / 15)) * 15 / 60;
			$table .="<tr>
			<td>".$row['employee']."</td>
			<td>".$row['date']."</td>
			<td>".$row['activity']."</td>
			<td>".$row['hours']."</td>
			<td>".$row['salt_bags']."</td>
			<td>".$row['loads_hauled']."</td>
			<td>".$billable_hours."</td>
			<td>0.25</td>
			</tr>";
			$act=$row['activity'];
			$total_hours += $row['hours'];
			$billable_time += $billable_hours;
			$salt_bags += $row['salt_bags'];
			$loads += $row['loads_hauled'];
			$travel_time += 0.25;
			$hours_activity[$act] += $billable_hours;
			$hours_activity_travel[$act] += 0.25;
		}//end if
	
	}//end foreach my array
	$table.= "</table>";
	$grand_total = $travel_time + $billable_time;
	$table .= "<b>Total Billable Hours + Travel:</b>$grand_total<br>
	<b>Billable Hours:</b>$billable_time<br>
	<b>Travel Time:</b>$travel_time<br>
	<b>Total Hours Worked:</b>$total_hours<br>
	<b>Salt Bags:</b>$salt_bags<br>
	<b>Loads Hauled:</b>$loads<br>";
	
	//insert breakdown of shovelling etc
	$hours_activity = array_filter($hours_activity);
	$hours_activity_travel = array_filter($hours_activity_travel);
	//print_r($hours_activity);
	$keys = array_keys($hours_activity);
	//print_r($keys);
	$keys = array_unique($keys);
	
	foreach ($keys as $value){
		$table .= "<br><b>$value:</b>". $hours_activity[$value]." + ".$hours_activity_travel[$value] . " (Travel)";
		$hours_total_travel = $hours_activity[$value] + $hours_activity_travel[$value];
		$table .= " Total:" . $hours_total_travel ;
	}
	
	$table.= "<br><hr>";
	$table .='<DIV style="page-break-after:always"></DIV>';
}//end foreach location_list
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
			<link rel="stylesheet" type="text/css" media="print" href="css/print.css" />
		</noscript>
		
		<link rel="stylesheet" href="css/jquery-ui.min.css">
		<link rel="stylesheet" href="css/jquery-ui.theme.min.css">
		<link rel="stylesheet" href="css/jquery-ui.structure.min.css">
		<link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">
		<link rel="stylesheet" type="text/css" media="print" href="css/print.css" />

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
										<h3>Snow Log View Summary - Current Period:<?php echo $date_start . " to " . $date_end ?></h3>
									</header>
						
											<div id="menu">
												<ul>
													<li><a href="employee_snow_view.php" ><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Snow Log View</span></a></li>
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
												<br>
												<form style="" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" name="viewoptions">
													<label>Start Date:</label><input id="datepicker1" class="text" name="date1" type="textbox" readonly="readonly" value="<?php echo $date_start; ?>">
													<label>End Date:</label><input class="text" id="datepicker2" name="date2" type="textbox" readonly="readonly" value="<?php echo $date_end; ?>">
													<input class="submit" type="submit" name="submit" value="Update View";/>
												</form>
											</div>	
											<div class="-2u 4u">
												
											</div>
											
							</div>
								<hr>
								<div class="row flush" style="padding:0em;">
											<div class="12u">	
												<b>Snow Log Summary </b>
												<?php echo $table;?>
											</div>
								</div>
								
								
						</section>
							<!-- /Highlight -->

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