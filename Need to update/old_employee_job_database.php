<?php
//ini_set('display_errors', 1);
//ini_set('log_errors', 1);
//ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
//error_reporting(E_ALL);

////////////////////////process view options
$headings = array("jobnumber"=>"Job No.","description"=>"Description","location"=>"Location","customer"=>"Customer","bill_to"=>"Bill To","supervisor"=>"Supervisor","status"=>"Status","start_date"=>"Start Date","end_date"=>"End Date","quote_number"=>"Quote","po_number"=>"PO No","notes"=>"Notes","invoice_number"=>"Invoice","contact_name"=>"Contact","contact_number"=>"Number","opened_by"=>"Opened By","date_opened"=>"Date Opened","date_invoiced"=>"Date Invoiced","date_closed"=>"Date Closed");

 if (isset($_GET['update'])) {
		if (isset($_GET['showinprogress'])) {$options[0]=1;} else {$options[0]=0;}
		if (isset($_GET['showpending'])) {$options[1]=1;} else {$options[1]=0;}
		if (isset($_GET['showonhold'])) {$options[2]=1;} else {$options[2]=0;}
		if (isset($_GET['showcomplete'])) {$options[3]=1;} else {$options[3]=0;}
		if (isset($_GET['showrecurring'])) {$options[4]=1;} else {$options[4]=0;}
		if (isset($_GET['showinvoiced'])) {$options[5]=1;} else {$options[5]=0;}
		$page_rows = $_GET['page_rows'];
		$j=0;
		foreach ($headings as $x){
			$field="col_" . $j;
			if ($_GET[$field]==1) {$cols[$j]=1;} else {$cols[$j]=0;}
			++$j;
		}//end foreach

		$value = implode (":",$options);
		setcookie ('view_options',$value,mktime (0, 0, 0, 12, 31, 2015));
		$value = implode (":",$cols);
		setcookie ('view_cols',$value,mktime (0, 0, 0, 12, 31, 2015));
		setcookie ('page_rows',$page_rows,mktime (0, 0, 0, 12, 31, 2015));
		header("Location: " . $_SERVER['PHP_SELF'] . "?search=$search&page_rows=$page_rows"); 
}
  header("Cache-Control: private, must-revalidate, max-age=0");
  header("Pragma: no-cache");
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // A date in the past
  
	/*
		Place code to connect to your DB here.
	*/
	include_once ("auth.php");
	include_once ("authconfig.php");
	include_once ("check.php");
	include_once ("cfg_variables.php");
	
	  if (($check['team']=='Contractor') )
    {
        echo 'You are not allowed to access this page.';
		exit();
    }
	
	//Cookies relating to view
	$view_options = explode (":",$_COOKIE['view_options']);
	$view_cols = explode (":",$_COOKIE['view_cols']);
	
if ($check['team'] == "Admin"){
	$j=0;
	if ($view_cols[0]!=""){
		foreach ($headings as $key=>$value){
				if ($view_cols[$j]==1) {$columns[$key] = $value;}
				++$j;
		}
	}
	else{
	$view_options=array(1,0,0,1,1,0);
	$view_cols=array(1,1,1,1,1,1,0,0,0,1,0,1,1,0,0,0,0,0);
			foreach ($headings as $key=>$value){
				if ($view_cols[$j]==1) {$columns[$key] = $value;}
				++$j;
			}
	}
	
}
else{
//Sets the columns visible in the table
	$columns=array("jobnumber"=>"Job No","description"=>"Description","location"=>"Location","customer"=>"Customer", "supervisor"=>"Supervisor", "status"=>"Status", "notes"=>"Notes");
}

    // if (!($check['team']=='Employee') && !($check['team']=='Group 3'))
    // {
        // echo 'You are not allowed to access this page.';
		// exit();
    // }

//////////////////////////////////////////////get passed variables	
 if (!(isset($pagenum)))
 {
 $pagenum = 1;
 }

 
$search = $_GET['search']; 
if ($search!=""){
     $where = " WHERE jobnumber LIKE '%$search%'
				OR notes LIKE '%$search%'
				OR description LIKE '%$search%'
				OR location LIKE '%$search%'
				OR supervisor LIKE '%$search%'
				OR customer LIKE '%$search%'
				OR bill_to LIKE '%$search%'
				OR invoice_number LIKE '%$search%'
	 ";
}
else{
		//$where =" WHERE (status <> 'Completed' OR status = 'Recurring') AND status <> 'Permanent'";
		if( !isset( $_COOKIE['view_options'] ) ){
			$where =" WHERE ((status = 'Completed' AND invoice_number = '')  OR status = 'Recurring') AND status <> 'Permanent' OR status='In Progress'";
		}
		else{
			$n=0;
			$where = " WHERE";
			if ($view_options[0]==1){ if ($n==1){ $where .= " OR ";}$where .= " status = 'In Progress'"; $n=1;}
			//if ($view_options[1]==1){ if ($n==1){ $where .= " OR ";}$where .= " status = 'Recurring'";$n=1'}
			//if ($view_options[2]==1){ if ($n==1){ $where .= " OR ";}$where .= " status = 'Recurring'";$n=1;}
			if ($view_options[3]==1 ){ if ($n==1){ $where .= " OR ";}$where .= " (status = 'Completed' AND invoice_number = '')"; $n=1;}
			if ($view_options[4]==1){ if ($n==1){ $where .= " OR ";}$where .= " status = 'Recurring'";$n=1;}
			if ($view_options[5]==1){ if ($n==1){ $where .= " OR ";}$where .= " invoice_number <> ''";$n=1;}
			if ($n==0){
				$where =" WHERE ((status = 'Completed' AND invoice_number = '')  OR status = 'Recurring') AND status <> 'Permanent' OR status='In Progress'";
			}
		}
}

$order_by = $_GET['sort_by'];
$direction = $_GET['direction'];
$page_rows = $_COOKIE['page_rows'];

if ($order_by != ""){
     $order = " ORDER BY $order_by $direction";
}
else{
		$order =" ORDER BY jobnumber DESC";
}
	
	//connect to database
	//open database
			$connection = mysql_connect($dbhost, $dbusername, $dbpass);
			$SelectedDB = mysql_select_db($dbname) or die('Could not connect: ' . mysql_error());	
				
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	
	if ($check['team']=="Admin"){
			$n=0;
			$sql="";
			foreach ($company_databases as $key=>$value){
				if ($n!=0){ $sql .= " UNION ";}
				$sql .= "SELECT * FROM $key". $where;
				$n++;
			}
			//$sql = "SELECT * FROM $db_table_jobfile" .$where;
	}
	else{
	
	$sql = "SELECT * FROM $db_table_jobfile" .$where;
	}
	//echo $sql;
		$result = mysql_query($sql) or die(mysql_error());
		 $rows = mysql_num_rows($result);	
	
	 //This is the number of results displayed per page
	//$page_rows=$_GET['page_rows'];
	if(!$page_rows){
		 $page_rows = 50; 		//how many items to show per page **change this with a user entered value default = 25
	}

//This tells us the page number of our last page
 $last = ceil($rows/$page_rows);
 
 //this makes sure the page number isn't below one, or more than our maximum pages
 if ($pagenum < 1)
 {
	$pagenum = 1;
 }
 elseif ($pagenum > $last)
 {
	$pagenum = $last;
 }

 //This sets the range to display in our query 
 $max = ' LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
 if ($rows==0){$max = "";}

	/* Get data. */
	//$sql = "SELECT * FROM $db_table_jobfile". $where . $order . $max;
		if ($check['team']=="Admin"){
			$n=0;
			$sql="";
			foreach ($company_databases as $key=>$value){
				if ($n!=0){ $sql .= " UNION ";}
				$sql .= "SELECT * FROM $key". $where;
				$n++;
			}
			$sql .= $order . $max;
			//$sql = "SELECT * FROM $db_table_jobfile" .$where;
	}
	else{
	
	$sql = "SELECT * FROM $db_table_jobfile". $where . $order . $max;
	}
	
	$result = mysql_query($sql) or die('Could not retrieve data: ' . mysql_error());
		
////////////////////////Pagination
$pagination = "";
$pagination .= "<div class=\"pagination\">";
$pagination = "<p>";
$pagination .=  " --Page $pagenum of $last-- <p>";

 // First we check if we are on page one. If we are then we don't need a link to the previous page or the first page so we do nothing. If we aren't then we generate links to the first page, and to the previous page.
 if ($pagenum == 1)
 {
 }
 else
 {
$pagination .=" <a href='{$_SERVER['PHP_SELF']}?pagenum=1&sort_by=$order_by&direction=$direction'>First</a> ";
$pagination .= " ";
$previous = $pagenum-1;
$pagination .= " <a href='{$_SERVER['PHP_SELF']}?pagenum=$previous&sort_by=$order_by&direction=$direction'> Prev</a> ";
 }

 //just a spacer
$pagination .= " -- ";

 //This does the same as above, only checking if we are on the last page, and then generating the Next and Last links
 if ($pagenum == $last)
 {
 }
 else {
	$next = $pagenum+1;
	$pagination .= " <a href='{$_SERVER['PHP_SELF']}?pagenum=$next&sort_by=$order_by&direction=$direction'>Next</a> ";
	$pagination .= " ";
	$pagination .= " <a href='{$_SERVER['PHP_SELF']}?pagenum=$last&sort_by=$order_by&direction=$direction'> Last</a> ";
 }
	$pagination .= "<div>";
	



		
	
	
	?>
<html>
<head>
	<title>Jobfile Database</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="DESCRIPTION" content="Jobfile Database">
	<meta name="KEYWORDS" content="condominium, maintenance, repairs, general, renovations, flooding, construction, landscaping, snow removal, garbage cleanup, project, management, line painting, sweeping, excavating, rental">
	<META Http-Equiv="Cache-Control" Content="no-cache">
<META Http-Equiv="Pragma" Content="no-cache">
<META Http-Equiv="Expires" Content="0"> 
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<script type="text/javascript">
<!--
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
//-->
</script>
<?php include ("./header.inc"); ?>
<?php include("./menu.inc"); ?>

<div class="content">
<!-- Place Content Below -->

	<div id="menu">
	<ul>
	<li><a href="employee_job_entry.php" ><span class="add">Add Job</span></a></li>
	</ul>
	</div>
	
<input id="Button1" type="button" value="Show/Hide Opts" onclick="toggle_visibility('opts');" />
<div id="opts" style="display:none; font-size: 70%;">
<form style="background-color: rgb(244, 244, 244);" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="viewoptions">
	<input type="submit" name="update" value="Update View"/>

	<?php
		echo '<input type="checkbox" name="showinprogress" '; 
		if($view_options[0]==1) {echo " checked=checked"; } 
		echo '><span style="color:#000000"> In Progress</span>  &nbsp;';
		echo '<input type="checkbox" name="showrecurring"'; 
		if ($view_options[4]==1) {echo " checked=checked"; } 
		echo '><span style="color:#800080">Recurring</span>  &nbsp;';
		echo '<input type="checkbox" name="showcomplete"'; 
		if($view_options[3]==1) {echo " checked=checked"; } 
		echo '><span style="color:#ff0000">Completed</span>  &nbsp;';
		//echo '<input type="checkbox" name="showpending"'; if($view_options[1]==1) {echo " checked=checked"; } echo '><span style="color:#707070">Pending</span>  &nbsp;';
		//echo '<input type="checkbox" name="showonhold"'; if($view_options[2]==1) {echo " checked=checked"; } echo '><span style="color:#707070">On Hold</span>  &nbsp;';

		if ($check['team'] == "Admin") {echo '<input type="checkbox" name="showinvoiced"'; 
		if($view_options[5]==1) {echo " checked=checked"; } 
		echo '><span style="color:#0000ff">Invoiced</span> &nbsp;';}

		echo 'Items per Page';
		//add selected for when page numbers are 
		echo '<select name="page_rows">
		<option>25</option>
		<option selected>50</option>
		<option>100</option>
		<option>500</option>
		</select>';
		echo '<br><hr>';
		
		//print out view options
		$j=0;
		foreach ($headings as $x){
			if ($check['team']=="Admin"){
				echo '<input type="checkbox" value="1" name="col_' . $j . '"'; 
				if($view_cols[$j]==1) {echo "checked=checked"; } ; 
					echo '>' . $x . ' ';
			}//end if view cols
			 else{
				 echo '<input type="hidden" name="col_' . $j. '" value="'.$view_cols[$j].'">';
			 }
			$j++;
		}//end foreach
	?>
</form>
</div>
	
<form style="background-color: rgb(255, 255, 255);" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="viewoptions">
	<?php  
		echo '<br><input style="background-color:#FFFFCC; border-style:solid; border-width:medium;" type="text" name="search" placeholder="Search" value="'.$search. '">';
		echo '<input type="submit" name="submit" value="Search"/>';
	?>
</form>

<table class="dbtable" style="clear:both; width:100%;"><tr>
<?php
//Show Headings
foreach ($columns as $x=>$y){
	$heading=$y;
	if ($direction =="ASC") {echo "<th><b><a href=". $_SERVER['PHP_SELF']."?sort_by=$x&direction=DESC&pagenum=1><b>$heading</b></a></b></th>";}
	else {echo "<th><b><a href=". $_SERVER['PHP_SELF']."?sort_by=$x&direction=ASC&pagenum=1><b>$heading</b></a></b></th>";}
  }
?>
<th>WO</th>
</tr>
	<?php
	$j=1;
		while($row = mysql_fetch_array($result))
		{
			// Your while loop here
			if ($j % 2 == 0){ echo '<tr style="background-color: #f0f0f0;">'; } else { echo '<tr style="background-color: #e0e0e0;">';}//color odd colored lines
			
					foreach ($columns as $i=>$nowrap){
					
						//color code rows
						if ($row ['status'] == "Completed" && $row ['invoice_number'] !="" ){ $color = "0000ff";} 
						elseif ($row ['status'] == "Completed" ){ $color = "ff0000";} 
						elseif ($row ['status'] == "Recurring" ){ $color = "800080";} 
						elseif ($row ['status'] == "Pending" ){ $color = "707070";} 
						elseif ($row ['status'] == "On Hold" ){ $color = "707070";} 
						elseif ($row ['status'] == "In Progress" ){ $color = "000000";} 
						else  { $color = "000000";}				
						//if ($nowrap ==1) {echo  '<td nowrap="nowrap" style="color:#' . $color . ';">';} else {echo  '<td style="color:#' . $color . ';">';  }		
						echo  '<td style="color:#' . $color . ';">';  
						if ($i == "jobnumber" && $row['jobnumber']<22000) { 
							echo "<a href=\"employee_job_entry.php?record_edit=$row[$i]\">$row[$i]</a></td>";
						} 
						else{ 
							echo $row[$i] . "</td>"; 
						}	
					}//end foreach for column				
					//add extra columns
					echo  "<td>"; 
					echo  '<a href="employee_workorder_entry.php?edit_record=' . $row['jobnumber'] . '"><img src="images/wo.png" /></a></td>';
			echo '</tr>';
			$j++;
		}//end while
	?>
	</table>
	
<?php echo $pagination; ?>
<!-- End of user content -->
</div>

<?php include("./footer.inc"); ?>
</body>
</html>