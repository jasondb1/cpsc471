<?php
ini_set('display_errors',0); 
error_reporting(0);
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
			$columns=array("edit"=>"","jobnumber"=>"Job No","date"=>"Date","time_in"=>"In","time_out"=>"Out", "hours"=>"Hours", "division"=>"Div", "sub_division"=>"Sub-Div", "comment"=>"Comment","del"=>"");
		
		// if (isEnabled(array("administrator"),array("admin","payroll"))){
			// $columns=array("edit"=>"","jobnumber"=>"Job Number","employee"=>"Employee","date"=>"Date","time_in"=>"Time In","time_out"=>"Time Out", "hours"=>"Hours", "division"=>"Division", "sub_division"=>"Sub-Division", "comment"=>"Comment""del"=>"");
		// }
		
		$db_table="timelog";
		$page_title="Timelog Summary";	

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
	
/*/////////////////////////////////////////////////
Delete Record
/////////////////////////////////////////////////*/
			if ($delete_record!=""){
	
				$mysql_link->query("DELETE FROM $db_table WHERE uid='$delete_record'");  
				 //write log
					$details="jn:$jobnumber,$date,$start_time,$end_time,$delete_record";
					write_log_file ($user,'Time Delete',$employee,$details);
				echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
				echo "<br><br><b><big>Successfully Deleted</b></big><br><br>";
				echo 	'<input type="Button" value="Back" onclick="location.href=\''.$_SERVER['PHP_SELF']  .'?j='.$masked_jobnumber  .'&date_current='. $date_current .'&date_end='. $date_end .'&employee='.$employee   .'\'">';
				die();		
			}//end if delete

///////////////////////////////////////////////////////////////
	
	//get data
		$where = "";
		$where = " WHERE (`date` BETWEEN '$date_start' AND '$date_end')";
		//$order = " ORDER BY `jobnumber`";
		$order="";
		$sql = "SELECT `jobnumber`,`employee`,`hours` FROM $db_timelog" . $where . $order;
		
		$retval = $mysql_link->query($sql);
		
		$hours_job_total="";
		$hours_employee_total[]="";

		while($row=$retval->fetch_assoc())
		{
			$total_hours += $row['hours'];
			$jobnumber_list[] = $row['jobnumber'];
			$employee_list[]  = $row['employee'];
			$hours_job_total[$row['jobnumber']] += $row['hours'];
			//$hours_employee_total[$row['employee']] += $row['hours'];
			//$hours[$row['employee']][] = array($row['jobnumber']=>$row['hours']);
			$hour_data[] = array("employee"=>$row['employee'],"jobnumber"=>$row['jobnumber'],"hours"=>$row['hours']);
		}
		
		$hours_job[]="";
		$hours_employee[]="";
		$jobnumber_list = array_unique($jobnumber_list);
		$employee_list = array_unique($employee_list);
		sort($employee_list);
		rsort($jobnumber_list);

////////////////////////Make new array for summary table
	$index=0;
	 $new_array="";
	 $row=0;
	 
	 $headings="";
	 $headings[]="Employee";
	 foreach ($jobnumber_list as $jobnumber){
				$headings[]=$jobnumber;
				}
	$headings[]="total";
	$employee_total="";
	$employee_total['Job Number']="Total";
	$grand_total=0;
	 
	foreach ($employee_list as $x){
		$employee_sum=0;	
		foreach ($jobnumber_list as $y){			
				$sum=0;
				foreach ($hour_data as $z){	
					$emp=$z['employee'];
					$jn=$z['jobnumber'];
					if ($emp==$x && $jn==$y){$n=$z['hours'];$sum += $n;}	
				}//end foreach hours
				//add sum total to newmake new array
				$temp_array['Employee']=$x;
				$temp_array[$y] = $sum;
				$employee_sum +=$sum;
				$sum=0;
				//echo $sum;
		
		}//end for each job
	$temp_array['total']=$employee_sum;
	$new_array[$x] = $temp_array;
	$employee_sum=0;

	}//end foreach employee
	
	/////////////////////////////print out table
$table="";
$table .= "<table style=\"color:#000; text-align: left;\" border=\"1\" cellpadding=\"1\" cellspacing=\"0\"><tr>";

//Show Headings
//$table.="<th></th>";
foreach ($headings as $x){

	$table.= "<th>$x</th>";
  }
  //$table.= '<th>Total</th>';//header for delete row
$table.= "</tr>";

foreach ($new_array as $row){

if (++$j % 2 == 0){ $table .= '<tr style="background-color: #f0f0f0;">'; } else { $table.= '<tr style="background-color: #e0e0e0;">';}//color odd colored lines
	//$table.= '<tr>';
		
		foreach($headings as $x){	
			$table.= '<td>';
			if ($x =="Employee") {$table .= $row['Employee'];}
			elseif ($row[$x] !=0){$table.= round ($row[$x],2);}
			$table.= '</td>';
		}//end for each column
	
	// echo "<td>$pct</td>";	//insert percentages
	$table.= '</tr>';
}//end foreach $array
$table.= '<tr>';
foreach ($employee_total as $x){
	$table.= '<td>';
		$table.= $x;
		$table.= '</td>';
}	
$table.= '</tr>';

$table.= "</table>";

krsort ($hours_job_total);
$company_separations=array("NULL","AMC Group");
for ($i = 1; $i <=2; $i++) {
	$company_total="";
    foreach ($hours_job_total as $key=>$value){//key is job #, $value is hours
		if (substr($key,0,1)==$i) {
			$company_total += $value;
		}
	}//end foreach
	$table_job .= '<b>'. $company_separations[$i] .'</b><br>';
	$table_job .= "<table style=\"text-align: left;\" border=\"1\" cellpadding=\"1\" cellspacing=\"0\"><tr>";				
	$table_job .= '<tr><th>Job No.</th><th>Hours</th><th>PCT</th></tr>';
	foreach ($hours_job_total as $key=>$value){	
		if (substr($key,0,1)==$i) {
			$table_job .= '<tr>';
			$table_job .= '<td>';
			$table_job .= $key;
			$table_job .= '</td>';
			$table_job .= '<td>';
			$table_job .= sprintf ("%.02f\n", $value);
			$table_job .= '</td>';
			$table_job .= '<td>';
			
			if ($company_separations[$i]=="AMC Group"){  
				$table_job .= sprintf ("%.02f\n",($value/$total_hours*100));
			}
			else{
				$table_job .= sprintf ("%.02f\n",($value/$company_total*100));
			}
			$table_job .= '</td></tr>';
		}
	}
	$table_job .= '</table>';
	
	
	$table_job .= "<br>Total:".$company_total. "<br><br><hr>";
	
	$overall_totals[$i]=$company_total;
	
}//end for loop
//job percentages.
// $table_job .= "<b>Company Exchange Percentages:</b><br>";
// $table_job .= "<table style=\"text-align: left; \"border=\"1\" cellpadding=\"1\" cellspacing=\"0\"><tr>";				
// $table_job .= '<tr><th>Company</th><th>Hours</th><th>PCT</th></tr>';
// for ($i = 1; $i <=4; $i++) {
// $table_job .= "<tr><td>" .$company_separations[$i] . "</td><td>" .$overall_totals[$i]."</td><td>" . sprintf ("%.02f\n",($overall_totals[$i]/$total_hours*100)) . "</td></tr>";
// }
// $table_job .= "</table>";


////////////////////////////////////////////////////////////////////
//Output employee totals	
////////////////////////////////////////////////////////////////////	
foreach ($employee_list as $value_employee){
echo "";
	

///////////////////////////////////////////////////////////////

	//get data
		$where = "";
		$where = " WHERE (`date` BETWEEN '$date_start' AND '$date_end')";
		if ($employee != "All"){$where .= " AND employee = '$value_employee'";}
		if ($jobnumber != "All" AND $jobnumber!=""){$where .= " AND jobnumber = '$jobnumber'";}
		$order = " ORDER BY `date`,`time_in`";
		$sql = "SELECT * FROM $db_timelog" . $where . $order;
		//echo $sql;
		$retval = $mysql_link->query($sql);
		
		$sum = $mysql_link->query("SELECT SUM(hours) AS totalhours FROM $db_timelog".$where);
		while($row=$sum->fetch_assoc())
		{
			$total_hours = $row['totalhours'];
		}

///////////////////////////////////////Table 2 - Calculate Summary by Day
	//date start to date end
	$table2[$value_employee] = '<div style="line-height:1;color:#000; text-align:left;"><br><br><b>Daily Total</b><br>';
	$t1=strtotime($date_start);
	$t2=strtotime($date_end);	
	$oneday = (24*60*60);
	$oneweek = (24*60*60*7);
	$x=$t1;
	while ($x<=$t2){
		$d1= date ("Y-m-d",$x);
		$where1 = " WHERE (`date` = '$d1')";
		if ($employee != "All"){$where1 .= " AND employee = '$value_employee'";}
		if ($jobnumber != "All"){$where1 .= " AND jobnumber = '$jobnumber'";}
		$sql = "SELECT * FROM $db_timelog" . $where1;
		$sum = $mysql_link->query("SELECT SUM(hours) AS totalhours FROM $db_timelog".$where1);	
			while($row=$sum->fetch_assoc())
				{
					$hrs = $row['totalhours'];
				}
		if ($hrs>0){$table2[$value_employee] .= date("D-M-d",$x) . ": <b>" . round($hrs, 2) . "</b><br>";}
			$x+=$oneday;
	}
	$table2[$value_employee] .="</div>";

///////////////////////////////////////Table 2 - Calculate Summary	
	//get data
		$sql1 = "SELECT SUM(hours) AS totalhours FROM $db_timelog WHERE (`date` BETWEEN '$date_start' AND '$date_end') AND employee='$value_employee' AND jobnumber = '1019';";
		$return = $mysql_link->query($sql1);
		
		$table3[$value_employee] = '<div style="line-height:1; color:#000; text-align:left;"><br><br><b>Other Totals</b><br>Total Hours:'.round($total_hours,2);
		$row=$return->fetch_array();
		$bank_time=$row['totalhours'];
		$table3[$value_employee] .="<br>Bank Time:".round($bank_time,2)."<br>";
		
		$sql1 = "SELECT SUM(hours) AS totalhours FROM $db_timelog WHERE (`date` BETWEEN '$date_start' AND '$date_end') AND employee='$value_employee' AND jobnumber = '1015';";
		$return = $mysql_link->query($sql1);
		$row=$return->fetch_assoc();
		$vacation_time=$row['totalhours'];
		$table3[$value_employee] .="Vacation Time:".  round($vacation_time,2) ."<br>";
		$table3[$value_employee] .='Hours Worked:'. round(($total_hours - $vacation_time - $bank_time),2).'</div>';

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
										<h3>Timelog View Summary - Current Period:<?php echo $date_start . " to " . $date_end ?></h3>
									</header>
						
											<div id="menu">
												<ul>
													<li><a href="employee_time_view.php" ><span class="button-menu"><i class="fa fa-book fa-fw"></i>&nbsp; Time View</span></a></li>
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
													<input class="submit" type="submit" name="submit" value="Update View";/>
												</form>
											</div>	
											<div class="-2u 4u">
												
											</div>
											
					</div>
								<hr>
								<div class="row flush" style="padding:0em;">
											<div class="12u">	
												<b>Timelog Summary </b>
												<?php echo $table;?>
											</div>
								</div>
								
								<div class="row flush" style="padding:0em;">
									<hr>
									<div class="4u">		
												<?php echo $table_job;?>
									</div>
									<div class="4u">			
												<?php //echo $table3;?>
												<b>Total Hours:</b><?php echo "$total_hours";?>
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

						<?php
							foreach ($table2 as $key=>$value){
								echo $value;
							}
						?>
						
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