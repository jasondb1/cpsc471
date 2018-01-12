<?php	
	
	//includes
	include_once ("auth.php");
	include_once ("authconfig.php");
	include_once ("check.php");
	include_once ("cfg_variables.php");

	$search=$_GET['search'];
	
	  if (($check['team']=='Contractor') )
    {
        echo 'You are not allowed to access this page.';
		exit();
    }
	
//connect to database
	//open database
			$connection = mysql_connect($dbhost, $dbusername, $dbpass);
			$SelectedDB = mysql_select_db($dbname) or die('Could not connect: ' . mysql_error());	
		
	//get data	
		if ($search ==""){
		$where = " WHERE (((invoice_number IS NULL OR invoice_number='') AND status = 'Completed') OR status <> 'Completed')";
	}
	else{
	$where = "  WHERE (((invoice_number IS NULL OR invoice_number='') AND status = 'Completed') OR status <> 'Completed')
				AND (jobnumber LIKE '%$search%'
				OR description LIKE '%$search%'
				OR location LIKE '%$search%'
				OR customer LIKE '%$search%'
				)
	 ";
	}
	$order = " ORDER BY jobnumber DESC";
	
	$sql = "SELECT jobnumber,customer,description,location FROM $db_table_jobfile". $where . $order ;
	$result = mysql_query($sql) or die(mysql_error());

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<head><title>Workorder Order</title>
<script language="javascript" src="calendar/calendar.js"></script>
<meta name="keywords" content="JDS Construction Mobile iPhone">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="style.css" rel="stylesheet" type="text/css">	

</head>

<body>

<?php include("./header.inc"); ?>
<?php include("./menu.inc"); ?>


<div class="content">
<!-- Place Content Below -->
<span class="hangingHead">Work Order</span><br>

<form style="background-color: #FFFFCC;" method="get" action="<?php $_SERVER['PHP_SELF'] ?>" name="options">

<?php 
	echo '<input style="background-color:#FFFFCC; border-style:solid; border-width:medium;" type="text" name="search" placeholder="Search" value="'.$search. '">';
	echo '<input type="submit" name="submit" value="Search"/>';
?>
  </form>
   <div style="background-color:#f4eebe; margin:10px; border:1px solid black;">
      <?php
//display jobs and linke to time entry sheet	  
	  while($row = mysql_fetch_array($result)){
	  $jobnumber = $row['jobnumber'];
	  $description = $row['description'];
	  $location = $row['location'];
		echo "<a href=\"employee_workorder_entry.php?edit_record=$jobnumber\" ><b>$jobnumber</b>: $location - $description</a><br><hr>";
		}	//end while
	  
	  ?>
</div>



<!-- End of user content -->
</div>

<?php include("./footer.inc"); ?>

</body>
</html>