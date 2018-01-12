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
	require_once("_functions_common.php");
	
/*////////////////////////////////////////////////////////////////////////////////
Page Protection
///////////////////////////////////////////////////////////////////////////////*/
	$usersArray = array("administrator");
	$groupsArray = array("admin","employee","payroll","supervisor");
	pageProtect($usersArray,$groupsArray);

/*////////////////////////////////////////////////////////////////////////////////
Variables
///////////////////////////////////////////////////////////////////////////////*/	
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
		
		$user 			= getUsername();
		//$user_email 	= getEmail(getUserId(),"phpauthent_users");
		$delete_record	=$_GET['delete_record'];
		$edit_allowed_days = 30;
		
		$employee = $_GET['employee'];
		if ($employee == ""){
			$employee = $user;
		}
		//Sets the columns visible in the table format (column name=>display name) special ("edit,"amount","price","del","delete","filename","hours")
			$columns=array("jobnumber"=>"Job No","date"=>"Date","time_in"=>"In","time_out"=>"Out", "hours"=>"Hours", "division"=>"Div", "sub_division"=>"Sub-Div", "comment"=>"Comment","approved"=>"Approved","edit"=>"Edit","del"=>"Del");
		
		// if (isEnabled(array("administrator"),array("admin","payroll"))){
			// $columns=array("edit"=>"","jobnumber"=>"Job No","employee"=>"Employee","date"=>"Date","time_in"=>"In","time_out"=>"Out", "hours"=>"Hours", "division"=>"Div", "sub_division"=>"Sub-Div", "comment"=>"Comment","del"=>"");
		// }
		
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
		
		//get employees
		$sql="SELECT * FROM `employee_info` JOIN `phpauthent_users` ON `phpauthent_users`.id = `employee_info`.uid WHERE employee_info.supervisor='$user'";
		if (isEnabled(array("administrator"),array("admin","payroll"))){
		$sql="SELECT * FROM `employee_info` JOIN `phpauthent_users` ON `phpauthent_users`.id = `employee_info`.uid";
		}
		$retval = mysql_query($sql) or die(mysql_error());
		while ($row=mysql_fetch_array($retval)){
			$employee_list[]=$row['username'];
		}
		
/*/////////////////////////////////////////////////
Submit Form
/////////////////////////////////////////////////*/	

if (isset($_POST['submit'])) { // if finalize timesheet is selected
//put submit text
	$date_start = $_POST['date1'];
	$date_end = $_POST['date2'];
	$approved=$_POST['approved'];
	$employee_approved=$_POST['employee_approved'];
	
	foreach ($approved as $key=>$value) {
		$sql="UPDATE timelog SET `approved`='$value' WHERE `uid`='$key'";
		$retval = $mysql_link->query($sql);
	}
	$message = "you approved hours for $employee_approved";
	//die();
}//end submit	
	
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
	
	sort ($employee_list);
	foreach ($employee_list as $employee_name){
///////////////////////////////////////Table 2 - Calculate Summary by Day
	//date start to date end
	$table2[$employee_name] = '<div style="color:#000; text-align:left; line-height:1em;"><br><br><b>Daily Total</b><br>';
	$t1=strtotime($date_start);
	$t2=strtotime($date_end);	
	$oneday = (24*60*60);
	$x=$t1;
	while ($x<=$t2){
		$d1= date ("Y-m-d",$x);
		$where1 = " WHERE (`date` = '$d1')";
		if ($employee != "All"){$where1 .= " AND employee = '$employee_name'";}
		//if ($jobnumber != "All"){$where1 .= " AND jobnumber = '$jobnumber'";}
		//$sql = "SELECT * FROM $db_timelog" . $where1;
		$sum = $mysql_link->query("SELECT SUM(hours) AS totalhours FROM $db_timelog".$where1);	
			while($row=$sum->fetch_assoc())
				{
					$hrs = $row['totalhours'];
				}
		if ($hrs>0){$table2[$employee_name] .= date("D-M-d",$x) . ": <b>" . round($hrs, 2) . "</b><br>";}
			$x+=$oneday;
	}
	$table2[$employee_name] .="</div>";

///////////////////////////////////////Table 2 - Calculate Summary	


/*/////////////////////////////////////////////////
Main Table
/////////////////////////////////////////////////*/	
//table code
	//need date			
		$where = "";
		$where = " WHERE (`date` BETWEEN '$date_start' AND '$date_end')";
		$where .= " AND employee = '$employee_name'";
		//if ($jobnumber != "All" AND $jobnumber!=""){$where .= " AND jobnumber = '$jobnumber'";}
		$order = " ORDER BY `date`,`time_in`";
		//$sql = "SELECT * FROM $db_timelog" . $where . $order;
		$sql = "SELECT * FROM $db_timelog JOIN jobdata ON timelog.jobnumber = jobdata.jobnumber" . $where . $order;
		//echo $sql;
		$retval = $mysql_link->query($sql);
		
		$sum = $mysql_link->query("SELECT SUM(hours) AS totalhours FROM $db_timelog".$where);
		while($row=$sum->fetch_assoc())
		{
			$total_hours = $row['totalhours'];
		}	

		$table[$employee_name] = '<table id="dbtable" style="width: 100%;"><tr>';
			//Show Headings
				foreach ($columns as $x=>$y){
					$heading=$y;
					// if ($direction =="ASC") {
						// $table[$employee_name] .= "<th><b><a href=". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=DESC><b>$heading</b></a></b></th>";
					// }
					// else {
						// $table[$employee_name] .= "<th><b><a href='". $_SERVER['PHP_SELF']."?j=$masked_jobnumber&sort_by=$x&direction=ASC'><b>$heading</b></a></b></th>";
					// }
					$table[$employee_name] .= "<th><b>$heading</b></th>";
				  }
				$table[$employee_name] .= '</tr>';
			//Output the data		
				$j=1;
				while($row = $retval->fetch_assoc())
				{
					// Color odd lines
					if ($j % 2 == 0){ 
						$table[$employee_name] .= '<tr style="background-color: #f0f0f0;">'; 
					} 
					else { 
						$table[$employee_name] .= '<tr style="background-color: #e0e0e0;">';
					}
					foreach ($columns as $i=>$value){
						$color = "000000";//basic color
							// foreach ($status_color as $status_key=>$color_value){
								// if ($row['status']==$status_key){
								// $color=$color_value;
								// }
							// }
						$table[$employee_name] .=  '<td style="color:#' . $color . ';">';  
				//check if special cell and format set formatting conditions				
						if ($i == "edit") { 
							if (strtotime($row['date'])>mktime(0,0,0,date("m"),date("d")-$edit_allowed_days,date("y"))){
								$id_number = $row['uid'];
								
								$table[$employee_name] .= '<a href="'.$edit_page .'?id='. $id_number . '">
								<span style="color:#092;">
								<i class="fa fa-edit fa-lg fa-fw"></i>
								</span></a></td>';
							}
						}
						elseif ($i == "filename"){
							$filename=$row[$i];
							$table[$employee_name] .= '<a href="'.	$path . $filename.'"><span style="color:#00f;"><i class="fa fa-file-o fa-lg fa-fw"></i></span></a>';
						}
						elseif ($i=="jobnumber"){
							$string = $row['customer']." : ".$row ['location']." : ".$row ['description'];
							$table[$employee_name] .= '&nbsp<a href="#" title="'.$string .'" onclick="alert(\''.$string.'\')"><span style="color:#20d;"><i class="fa fa-info-circle fa-fw"></i></span></a>'.$row[$i];
						}
						elseif ($i=="amount" || $i=="price"){ 
							$table[$employee_name] .= money_format("%n",$row[$i]);
						}
						elseif ($i=="date"){ 
							$table[$employee_name] .= date("D Y-m-d", strtotime($row[$i]));
						}
						elseif ($i=="hours"){ 
							$table[$employee_name] .= sprintf("%01.2f", $row[$i]).'</td>';
						}
						elseif ($i == "time_in" or $i == "time_out") {
						$table[$employee_name] .= date ("H:i",strtotime($row[$i]));  
						}
						elseif ($i == "approved" || $i=="approve" ) {
						
						$id=$row['uid'];
						$value=$row[$i];
						$table[$employee_name] .= '<input type="hidden" name="approved['.$id.']" value="0" >';
						$table[$employee_name] .= '<input class="checkbox" type="checkbox" name="approved['.$id.']" value="1" ';
						if ($value==1 or $value!=0){ $table[$employee_name] .="checked ";}
						$table[$employee_name] .= ' />'; 
						if ($value==1){ $table[$employee_name] .= '<span style="color:#0D0;">Approved</span>';}
						else{$table[$employee_name] .= '<span style="color:#D00;">Not Approved</span>';}						
						}
						elseif ($i=="delete" || $i=="del"){ 
						if (strtotime($row['date'])>mktime(0,0,0,date("m"),date("d")-$edit_allowed_days,date("y"))){
							$table[$employee_name] .=  '<a href="'.$_SERVER['PHP_SELF'].'?delete_record=' . $row['uid'] . '&j='. $masked_jobnumber . '&date_current='. $date_current.'&date_end='.$date_end.'&employee='.$employee.'" onclick="return confirm(\'Confirm Delete?\')" ><span style="color:#f00;"><i class="fa fa-minus-circle fa-lg fa-fw"></i></span></a>';
							//$table[$employee_name] .=  '<a href="" onClick="checkDelete(\''.$row['uid'].'\')" ><span style="color:#f00;"><i class="fa fa-minus-circle fa-lg fa-fw"></i></span></a></td>';						
							}
						}
				//Regular Cell	
						else{ 
							$table[$employee_name] .= $row[$i] . "</td>"; 
						}	
					}//end foreach for column

				// add extra columns (if required)	
					$table[$employee_name] .= '</tr>';
					$j++;
				}//end while
			
		$table[$employee_name] .='</table>';
	$table[$employee_name] .= '<div style="color:#000; text-align:left;"><b>Total Hours:</b>'. round($total_hours,2) . "</div>";
	
		// //get data
		$sql1 = "SELECT SUM(hours) AS totalhours FROM $db_timelog WHERE (`date` BETWEEN '$date_start' AND '$date_end') AND employee='$employee_name' AND jobnumber = '1019';";
		$return = $mysql_link->query($sql1);
		$table3[$employee_name] = '<div style="color:#000; text-align:left; line-height:1em;"><br><br><b>Other Totals</b><br>Total Hours:'.round($total_hours,2);
		$row=$return->fetch_assoc();
		$bank_time=$row['totalhours'];
		$table3[$employee_name] .="<br>Bank Time:".round($bank_time,2)."<br>";
		$sql1 = "SELECT SUM(hours) AS totalhours FROM $db_timelog WHERE (`date` BETWEEN '$date_start' AND '$date_end') AND employee='$employee_name' AND jobnumber = '1015';";
		$return = $mysql_link->query($sql1);
		$row=$return->fetch_assoc();
		$vacation_time=$row['totalhours'];
		$table3[$employee_name] .="Vacation Time:".  round($vacation_time,2) ."<br>";
		$table3[$employee_name] .='Hours Worked:'. round(($total_hours - $vacation_time - $bank_time),2).'</div>';
	
	}//end foreach	
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
		$(document).ready(function(){
    //  When user clicks on tab, this code will be executed
    $("#tabs li").click(function() {
        //  First remove class "active" from currently active tab
        $("#tabs li").removeClass('active');
 
        //  Now add class "active" to the selected/clicked tab
        $(this).addClass("active");
 
        //  Hide all tab content
        $(".tab_content").hide();
 
        //  Here we get the href value of the selected tab
        var selected_tab = $(this).find("a").attr("href");
 
        //  Show the selected tab content
        $(selected_tab).fadeIn();
 
        //  At the end, we add return false so that the click on the link is not executed
        return false;
    });
});
</script>
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
		<script type="text/javascript">
			checked=false;
			function checkedAll (timeapprove) {
				var aa= document.getElementById('timeapprove');
				 if (checked == false)
					  {
					   checked = true
					  }
					else
					  {
					  checked = false
					  }
				for (var i =0; i < aa.elements.length; i++) 
				{
				 aa.elements[i].checked = checked;
				}
				  }
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
	<body class="homepage" onload="init()">

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
												<li><a href="_employee_menu.php" ><span class="button-menu"><i class="fa fa-arrow-circle-o-left fa-fw"></i>&nbsp; Employee Menu</span></a></li>
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
												</form>
												
											</div>	
											<div class="-2u 4u">
											</div>
											
					</div>
								
								<div class="row flush" style="padding:0em;">
											<div class="12u">
											<?php echo '<span style="color:#0D0;">'.$message.'</span>'; ?>
										<div id="tabs_container">
											<ul id="tabs">
											<?php 
											$tab_number=1;
											foreach ($employee_list as $value){
											echo '<li><a href="#tab'.$tab_number.'">'.$value.'</a></li>';
											++$tab_number;
											}
											
											?>
											</ul>
										</div>
											
											
											<?php
											$tab_number=1;
											echo '<div id="tabs_content_container">';
											foreach ($employee_list as $value){
												echo '<div id="tab'.$tab_number .'" class="tab_content';
												//if ($tab_number==1) {echo 'style="display: block;"';}
												echo '">';
												echo '<form id="timeapprove" style="" method="post" action="'.$_SERVER['PHP_SELF'].'" name="timeapprove">	';
												echo '<input type="hidden" name="employee_approved" value="'.$value.'">';
												echo '<input type="hidden" name="date1" value="'.$date_start.'">';
												echo '<input type="hidden" name="date2" value="'.$date_end.'">';
												echo '<h3>Approve Time'.'&nbsp<input class="submit" type="button" onClick="window.print()" value="Print"/></h3>';
												echo $table[$value];
												//echo "<input type='checkbox' name='checkall' onclick='checkedAll(timeapprove');'>";
												echo '	<input class="submit" name="submit" value="Approve Hours" type="submit">';
												echo $table2[$value];
												if (isEnabled(array("administrator"),array("admin","payroll"))){
													//echo '<div style="float:right;">'.$table3[$value].'</div>';
													echo $table3[$value];
												}
												echo '	</form>';
												echo '</div>';
												++$tab_number;
											}
											echo '</div>';
											?>									
											
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

		<!-- /Footer -->

	</body>
</html>