<?php		
//ini_set('display_errors', 1);
//ini_set('log_errors', 1);
//ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
//error_reporting(E_ALL);

  //header("Cache-Control: private, must-revalidate, max-age=0");
  //header("Pragma: no-cache");
  //header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // A date in the past

if (isset($_POST['file_download'])){
header('Location:http://www.jdsservices.ca/summary.csv');
}

	//includes
	include_once ("auth.php");
	include_once ("authconfig.php");
	include_once ("check.php");
	include_once ("cfg_variables.php");
	include_once ("fn_lib.php");
	
	  if (($check['team']=='Contractor') )
    {
        echo 'You are not allowed to access this page.';
		exit();
    }
	
	//get and set initial variables
	$user = $_COOKIE['USERNAME'];
	$delete_record=$_GET['delete_record'];
	
	$employee = $_GET['employee'];
	if ($employee == ""){
		$employee = $user;
	}
	//set initial variables
		$jobnumber = "All";
		$date = strtotime (date ("Y-m-d"));
		$date_end= cutoff_date($date); // in fn_lib.php
		
		$display_cutoff = cutoff_date(strtotime ($date_end));
		$previous_cutoff = date ("Y-m-d" , strtotime('-14 days',strtotime($display_cutoff)));

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
		if (isset($_POST['submit'])) {
		//echo "submit pressed";
			//$jobnumber = $_POST['jobnumber'];
			$date_start = $_POST['date1'];
			$date_end = $_POST['date2'];
			//$employee = $_POST['employee'];	
		}
		
		if (isset($_POST['download'])){
			$date_current = $_POST['date_current'];	
			$date_start = $_POST['date1'];
			$date_end = $_POST['date2'];
		}

	//connect to database
	//open database
			$connection = mysql_connect($dbhost, $dbusername, $dbpass);
			$SelectedDB = mysql_select_db($dbname) or die('Could not connect: ' . mysql_error());	

	//get data
		$where = "";
		$where = " WHERE (`date` BETWEEN '$date_start' AND '$date_end')";
		//$order = " ORDER BY `jobnumber`";
		$order="";
		$sql = "SELECT `jobnumber`,`employee`,`hours` FROM $db_timelog" . $where . $order;
		
		$retval = mysql_query($sql) or die(mysql_error());
		

		$hours_job_total="";
		$hours_employee_total[]="";

		while($row=mysql_fetch_array($retval))
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
		
		//var_dump($employee_list);
		
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
//$employee_total['Job Number']="Total";
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
		
 //download tag set
if (isset($_POST['download'])){
//echo "Here:$summary_data_file";
$handle = fopen($summary_data_file, "w");
fputcsv($handle, $headings, ",");
foreach ($new_array as $x){
	fputcsv($handle, $x, ",");
}

// fputcsv($handle, array (""), ",");
// fputcsv($handle, array (""), ",");
// fputcsv($handle, array ("Job Totals:"), ",");
// fputcsv($handle, array("Job","Hours","Percentage"), ",");
// foreach ($hours_job_total as $key=>$value){
		// $percentage = ($value/$total_hours);
		// $data_row=array($key,$value,$percentage);
		// fputcsv($handle, $data_row, ",");
// }

fclose($handle);
header('Location:http://'.$company_web.'/summary.csv');
}
 
// ///////////////////////////print out table
$table="";
$table .= "<table style=\"text-align: left; \"border=\"1\" cellpadding=\"1\" cellspacing=\"0\"><tr>";

//Show Headings
//$table.="<th></th>";
foreach ($headings as $x){

	$table.= "<th>$x</th>";
  }
  //$table.= '<th>Total</th>';//header for delete row
$table.= "</tr>";

foreach ($new_array as $row){

if (++$j % 2 == 0){ $table .= '<tr style="background-color: #EDDA74;">'; } else { $table.= '<tr style="background-color: #FFF380;">';}//color odd colored lines
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
$company_separations=array("NULL","JDS Construction","Omega","Formcrete","Aztec","Group7");
for ($i = 1; $i <=5; $i++) {
	$company_total="";
    foreach ($hours_job_total as $key=>$value){//key is job #, $value is hours
		if (substr($key,0,1)==$i) {
			$company_total += $value;
		}
	}//end foreach
	$table_job .= '<b>'. $company_separations[$i] .'</b><br>';
	$table_job .= "<table style=\"text-align: left; \"border=\"1\" cellpadding=\"1\" cellspacing=\"0\"><tr>";				
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
			
			if ($company_separations[$i]=="JDS Construction"){  
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
$table_job .= "<b>Company Exchange Percentages:</b><br>";
$table_job .= "<table style=\"text-align: left; \"border=\"1\" cellpadding=\"1\" cellspacing=\"0\"><tr>";				
$table_job .= '<tr><th>Company</th><th>Hours</th><th>PCT</th></tr>';
for ($i = 1; $i <=4; $i++) {
$table_job .= "<tr><td>" .$company_separations[$i] . "</td><td>" .$overall_totals[$i]."</td><td>" . sprintf ("%.02f\n",($overall_totals[$i]/$total_hours*100)) . "</td></tr>";
}
$table_job .= "</table>";

		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<head><title>Timelog</title>
<meta name="keywords" content="JDS Construction Mobile iPhone">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META Http-Equiv="Cache-Control" Content="no-cache">
<META Http-Equiv="Pragma" Content="no-cache">
<META Http-Equiv="Expires" Content="0"> 
<link href="style.css" rel="stylesheet" type="text/css">
<script src="./js/jquery.js"></script>
<script src="./js/anytime.js"></script>
<link rel="stylesheet" type="text/css" href="./css/anytime.css" />
<style type="text/css">
  #date1, #date2{
    background-image:url("images/calendar.png");
    background-position:right center;
    background-repeat:no-repeat; }
</style>
</head>

<body>
<?php include ("./header.inc"); ?>
<?php include("./menu.inc"); ?>


<div class="content">
<!-- Place Content Below -->
<span class="hangingHead">Timelog View</span> - Current Period:<?php echo $date_start . " to " . $date_end ?><br><hr>
<div id="menu">
<ul>
<li><a href="employee_time_view.php" ><span class="view">View Time</span></a></li>
</ul>
</div>

<form style="background-color: rgb(204, 204, 204);" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" name="viewoptions">
<input type="submit" name="previous" value="Previous Cutoff">
<input type="submit" name="next" value="Next Cutoff">
<input type="submit" name="download" value="Create File for Download"/>
<input type="submit" name="file_download" value="Download Created File"/>
<br>
<input name="employee" type="hidden" value=<?php echo $employee;?>>
<input name="date_current" type="hidden" value=<?php echo $date_end;?>>
<hr>
<label>Start Date:</label><input id="date1" name="date1" type="textbox" readonly="readonly" value="<?php echo $date_start; ?>">
<label>End Date:</label><input id="date2" name="date2" type="textbox" readonly="readonly" value="<?php echo $date_end; ?>">
<br><hr>

<script>
	var time_format = "%Y-%m-%d";
	var time1Conv = new AnyTime.Converter({format:time_format});
	var date_1 = time1Conv.parse($("#date1").val());//chg this to read time1
	
	AnyTime.picker( "date1",{ format:time_format} );
	$("#date2").AnyTime_picker({earliest: date_1, format:time_format});
	
	$("#date1").change( function(e) { try {
      		var time1Conv = new AnyTime.Converter({format:time_format});
			var date_1 = time1Conv.parse($("#date1").val());//chg this to read time1
			var date_2 = time1Conv.parse($("#date2").val());//get date_2
      
	  //if time in changes then change earliest value allowed for date_2
	 if (date_1 > date_2)
  {
  $("#date2").
	     AnyTime_noPicker().
          removeAttr("disabled").
          val(time1Conv.format(date_1)).
          AnyTime_picker(
              { earliest: date_1,
                format: time_format
              } );
  }
else
  {
	  $("#date2").
	     AnyTime_noPicker().
          removeAttr("disabled").
          val(time1Conv.format(date_2)).
          AnyTime_picker(
              { earliest: date_1,
                format: time_format
              } );
  }
	  

      } catch(e){ $("#date2").val(""); } } );
	  
	  	$("#date2").change( function(e) { try {
      		var time1Conv = new AnyTime.Converter({format:time_format});
			var date_1 = time1Conv.parse($("#date1").val());//chg this to read time1
			var date_2 = time1Conv.parse($("#date2").val());//get date_2
      
	  //if time in changes then change earliest value allowed for date_2
	 if (date_1 < date_2)
  {
  $("#date1").
	     AnyTime_noPicker().
          removeAttr("disabled").
          val(time1Conv.format(date_1)).
          AnyTime_picker(
              { 
                format: time_format
              } );
  }
else
  {
	  $("#date1").
	     AnyTime_noPicker().
          removeAttr("disabled").
          val(time1Conv.format(date_2)).
          AnyTime_picker(
              { 
			  format: time_format
              } );
  }
	  

      } catch(e){ $("#date1").val(""); } } );

	</script>


<input type="submit" name="submit" value="Update View";/>
<br><hr>
</form>
<b>Timelog</b>
<?php echo $table;?>
<br><hr>
<?php echo $table_job;?>
<br>
	<b>Total Hours:</b><?php echo "$total_hours";?>

<!-- End of user content -->
</div>

<?php include("./footer.inc"); ?>

</body>
</html>