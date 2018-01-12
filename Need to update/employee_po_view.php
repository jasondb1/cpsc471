<?php
//ini_set('display_errors', 1);
//ini_set('log_errors', 1);
//ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
//error_reporting(E_ALL);
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

 if (!(isset($pagenum)))
 {
 $pagenum = 1;
 }
 
 //print_r($_GET);
 
$search = $_GET['search']; 
if ($search!=""){
     $where = " WHERE jobnumber LIKE '%$search%'
				OR notes LIKE '%$search%'
				OR po_number LIKE '%$search%'
				OR vendor LIKE '%$search%'
				OR date_entered LIKE '%$search%'
	 ";
}
else{
		//$where =" WHERE status = 'Open'";
}

$order_by = $_GET['sort_by'];
$direction = $_GET['direction'];


if ($order_by != ""){
     $order = " ORDER BY $order_by $direction";
}
else{
		$order =" ORDER BY `date_entered` DESC";
}
	
	//connect to database
	//open database
			$connection = mysql_connect($dbhost, $dbusername, $dbpass);
			$SelectedDB = mysql_select_db($dbname) or die('Could not connect: ' . mysql_error());	
				
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	$sql = "SELECT * FROM $db_purchase_order" .$where;
		$result = mysql_query($sql) or die(mysql_error());
		 $rows = mysql_num_rows($result);	

 if ($rows == 0) {
 echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head>';
echo "<br><br><b><big>No records found</b></big><br><br>";
echo 	'<input type="Button" value="Enter PO" onclick="location.href=\'employee_po_select_job.php\'">';
die();
 }			 
		 
	 //This is the number of results displayed per page
	$page_rows=$_GET['page_rows'];
	if(!$page_rows){
		 $page_rows = 25; 		//how many items to show per page **change this with a user entered value default = 25
	}

//This tells us the page number of our last page
 $last = ceil($rows/$page_rows) or die ("No results from query");
 
 
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

	/* Get data. */

	$sql = "SELECT * FROM $db_purchase_order". $where . $order . $max;
	//$sql = "SELECT * FROM $db_table_jobfile". $where . $max;
	//echo "SQL:$sql";
	$result = mysql_query($sql) or die('Could not retrieve data: ' . mysql_error());
	
	//Sets the columns visible in the table
	$columns=array("po_number"=>"PO Number","date_entered"=>"Date Entered","vendor"=>"Vendor","notes"=>"Notes","status"=>"Status","entered_by"=>"Entered By");

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
$pagination .=" <a href='{$_SERVER['PHP_SELF']}?pagenum=1'>First</a> ";
$pagination .= " ";
$previous = $pagenum-1;
$pagination .= " <a href='{$_SERVER['PHP_SELF']}?pagenum=$previous'> Prev</a> ";
 }

 //just a spacer
$pagination .= " -- ";

 //This does the same as above, only checking if we are on the last page, and then generating the Next and Last links
 if ($pagenum == $last)
 {
 }
 else {
 $next = $pagenum+1;
$pagination .= " <a href='{$_SERVER['PHP_SELF']}?pagenum=$next'>Next</a> ";
$pagination .= " ";
$pagination .= " <a href='{$_SERVER['PHP_SELF']}?pagenum=$last'> Last</a> ";
 }
	$pagination .= "<div>";
	
	
	?>
<html>
<head>
	<title>Purchase Orders</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="DESCRIPTION" content="Jobfile Database">
	<meta name="KEYWORDS" content="condominium, maintenance, repairs, general, renovations, flooding, construction, landscaping, snow removal, garbage cleanup, project, management, line painting, sweeping, excavating, rental">
	<META Http-Equiv="Cache-Control" Content="no-cache">
<META Http-Equiv="Pragma" Content="no-cache">
<META Http-Equiv="Expires" Content="0"> 
	<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php include ("./header.inc"); ?>
<?php include("./menu.inc"); ?>

<div class="content">
<!-- Place Content Below -->

<div id="menu">
<ul>
<li><a href="employee_po_select_job.php" ><span class="add">Add Purchase Orders</span></a></li>
</ul>
</div>
<form style="background-color: rgb(255, 255, 255);" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="viewoptions">

<?php  
// echo '<div style="background-color:#FFFFCC; border-style:solid; border-width:medium; float:left; padding:10px 30px; text-align:center;">';
// echo '<input type="button" name="add_job" value="Add PO" onclick="location.href=\'employee_po_select_job.php\'"/>';
// echo '</div>';

//echo '<div style="background-color:#FFFFCC; border-style:solid; border-width:medium; float:left; padding:10px 30px; text-align:center;">';

// echo 'Items per Page';
// //add selected for when page numbers are 
// echo '<select name="page_rows">
// <option selected>25</option>
// <option>50</option>
// <option>100</option>
// <option>All</option>
// </select>';
// echo '</div>';

// echo '<div style="background-color:#FFFFCC; border-style:solid; border-width:medium; float:left; padding:10px 30px; text-align:center;">';
// echo 'Sort By';
// echo '<select name="sort_by">';
// foreach ($columns as $x=>$y)
// {
		// echo '<option value="'. $x . '"';
		// if ($x == $sort_by){echo ' selected>';}else {echo '>';}
		// echo $y;
		// echo '</option>';
// }
// echo '</select>';

// echo '<select name="direction"><option value = "ASC" selected>Ascending</option><option value="DESC">Descending</option></select>';
//echo '</div>';

// echo '<input type="submit" name="submit" value="Update View"/>';


		echo '<br><input style="background-color:#FFFFCC; border-style:solid; border-width:medium;" type="text" name="search" placeholder="Search" value="'.$search. '">';
		echo '<input type="submit" name="submit" value="Search"/>';

?>
</form>
<br>
<table class="dbtable" style="clear:both; width:100%;"><tr>

<?php
//Show Headings
foreach ($columns as $x=>$y){
	$heading=$y;
	if ($direction =="ASC") {echo "<th><b><a href=". $_SERVER['PHP_SELF']."?sort_by=$x&direction=DESC&pagenum=1><b>$heading</b></a></b></th>";}
	else {echo "<th><b><a href=". $_SERVER['PHP_SELF']."?sort_by=$x&direction=ASC&pagenum=1><b>$heading</b></a></b></th>";}
  }

echo '</tr>';

	$j=1;
	
		while($row = mysql_fetch_array($result))
		{
			// Your while loop here
			if ($j % 2 == 0){ echo '<tr style="background-color: #f0f0f0;">'; } else { echo '<tr style="background-color: #e0e0e0;">';}//color odd colored lines
			
					foreach ($columns as $i=>$nowrap){
				 $color = "000000";
					
						echo  '<td style="color:#' . $color . ';">';  
							
						if ($i == "po_number") { 
							$id_number = $row['id'];
							//echo "<a href=\"employee_po_entry.php?id=$row['id']\">$row[$i]</a></td>";
							echo '<a href="employee_po_entry.php?id='. $id_number . '">'. $row[$i] .'</a></td>';
						} 
						else{ 
							echo $row[$i] . "</td>"; 
						}
						
					}//end foreach for column
					
					// add extra columns

			
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