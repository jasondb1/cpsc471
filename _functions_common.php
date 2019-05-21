<?php
// enter this to use this library of functions
// require ('_functions_common.php');

/*////////////////////////////////////////////////////////////////////////////////
Back Button
///////////////////////////////////////////////////////////////////////////////*/
function backButton ($message,$url){
	echo '<head><meta name="viewport" content="width=device-width, user-scalable=no" /><meta name="HandheldFriendly" content="true"><meta name="MobileOptimized" content="320"></head';
	//echo 	'<input type="Button" value="Back" onclick="location.href=\'employee_snow_view.php?employee='. $employee.'\'">';
	echo 	'<input type="Button" value="Back" onclick="location.href='.$url .'">';
	die();
}

/*////////////////////////////////////////////////////////////////////////////////
Clean Input
///////////////////////////////////////////////////////////////////////////////*/
function cleanInput($input) {
 
  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  );
 
    $output = preg_replace($search, '', $input);
    return $output;
  }
/*////////////////////////////////////////////////////////////////////////////////
Sanitize mysql strings
Also use for getting POST/GET variables
  $_POST = sanitize($_POST);
  $_GET  = sanitize($_GET)
///////////////////////////////////////////////////////////////////////////////*/
function sanitize($input) {
	require "_config.php";		
	$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);

	if ($mysql_link->connect_errno) {
		die($mysql_link->connect_error);
	}
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input  = cleanInput($input);
		$output = $mysql_link->real_escape_string($input);
    }
    return $output;
}
/*////////////////////////////////////////////////////////////////////////////////
Write Log
///////////////////////////////////////////////////////////////////////////////*/
function write_log_file($user,$event,$affected,$details){
	require "_config.php";
		
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);

		if ($mysql_link->connect_errno) {
			die($mysql_link->connect_error);
		}
		// First check previous connections number
		$query ="INSERT INTO `log`
		(`id`, `user`, `event`, `details`, `timestamp`, `affected`)
		VALUES
		(NULL, '$user', '$event', '$details', CURRENT_TIMESTAMP, '$affected');";
		$r_query = $mysql_link->query($query);

		if ($mysql_link->affected_rows <> 1) {
			// Error while updating. Return false
			return false;
		} else {
			return true;
		}
}//end function


/*////////////////////////////////////////////////////////////////////////////////
Get Employee Names
///////////////////////////////////////////////////////////////////////////////*/
function getEmployeeNames(){
		require "_config.php";
		
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {
			die($mysql_link->connect_error);
		}
		$sql = "SELECT phpauthent_users.username, phpauthent_users.id FROM `employee_info` JOIN `phpauthent_users` ON `phpauthent_users`.id = `employee_info`.uid WHERE employee_info.status='Active'";
		$retval = $mysql_link->query($sql);
		$employee_list = array();
		
		while ($row = $retval->fetch_assoc()) {
			$username = $row['username'];
			$employee_list[ $row['username'] ]=$row['id'];
		}
		krsort ($employee_list);

		return $employee_list;
}

/*////////////////////////////////////////////////////////////////////////////////
Get Part List
///////////////////////////////////////////////////////////////////////////////*/
function getPartList(){
		require "_config.php";
		
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {
			die($mysql_link->connect_error);
		}
		$sql = "SELECT part_no, description FROM Inventory WHERE 1 ORDER BY part_no ASC;";
		$retval = $mysql_link->query($sql);
		$part_list = array();
		
		while ($row = $retval->fetch_assoc()) {
			$part_description = $row['part_no'] . " - " . $row['description'];
			$part_list[ $part_description ]=$row['part_no'];
		}
		//krsort ($part_list);

		return $part_list;
}

/*////////////////////////////////////////////////////////////////////////////////
Get Components
///////////////////////////////////////////////////////////////////////////////*/
function getComponentList(){
		require "_config.php";
		
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {
			die($mysql_link->connect_error);
		}
		$sql = "SELECT Components.part_no, Inventory.description FROM Components JOIN Inventory ON Components.part_no = Inventory.part_no WHERE 1 ORDER BY part_no ASC;";
		$retval = $mysql_link->query($sql);
		$part_list = array();
		
		while ($row = $retval->fetch_assoc()) {
			$part_description = $row['part_no'] . " - " . $row['description'];
			$part_list[ $part_description ]=$row['part_no'];
		}
		//krsort ($part_list);

		return $part_list;
}

/*////////////////////////////////////////////////////////////////////////////////
Get Part List
///////////////////////////////////////////////////////////////////////////////*/
function getActiveJobsList(){
		require "_config.php";
		
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {
			die($mysql_link->connect_error);
		}
		$sql = "SELECT ProjectNo, Description FROM Project WHERE Status <> 'Complete' ORDER BY ProjectNo DESC;";
		$retval = $mysql_link->query($sql);
		$job_list = array();
		
		while ($row = $retval->fetch_assoc()) {
			$job_description = $row['ProjectNo'] . " - " . $row['Description'];
			$job_list[ $job_description ]=$row['ProjectNo'];
		}
		//krsort ($part_list);

		return $job_list;
}


/*////////////////////////////////////////////////////////////////////////////////
Get Email
///////////////////////////////////////////////////////////////////////////////*/
function getEmail ($user,$table){
		require "_config.php";
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		
		if ($mysql_link->connect_errno) {
			die($mysql_link->connect_error);
		}	
		$sql = "SELECT phpauthent_users.email FROM `employee_info` JOIN `phpauthent_users` ON `phpauthent_users`.id = `employee_info`.uid WHERE employee_info.status='Active' AND phpauthent_users.id='".$user."'";
		$retval = $mysql_link->query($sql);
		$row = $retval->fetch_assoc();
	return $row['email'];
}

/*////////////////////////////////////////////////////////////////////////////////
Cutoff Date Calculations
///////////////////////////////////////////////////////////////////////////////*/
function cutoff_date ($test_date){
	if ((date('W',$test_date) % 2) == 1) { // week is even, payday is last friday //changed to odd week for 2016 due to every 2 weeks.
		$cutoff = date('Y-m-d', strtotime('last Sunday + 2 weeks',$test_date)); 
		//$payday = date('Y-m-d', strtotime('next Friday',$test_date)); 
		//if ((date("D",$test_date))=="Sat" || (date("D",$test_date))=="Sun" )
		if ((date("D",$test_date))=="Sun" ){
			//$payday = date('Y-m-d', strtotime('last Friday',$test_date));
			$cutoff = date('Y-m-d', strtotime('last Sunday + 1 week',$test_date));
		}
		if ((date("D",$test_date))=="Fri"){$payday = date('Y-m-d', strtotime('last Friday +1 week',$test_date));}
	} 
	else { // week is odd
		//$payday = date('Y-m-d', strtotime('next Friday +1 week',$test_date));
		$cutoff = date('Y-m-d', strtotime('last Sunday +1 week',$test_date)); 
		//if ((date("D",$test_date))=="Sat" || (date("D",$test_date))=="Sun" )
		if ((date("D",$test_date))=="Sun" ){
			//$payday = date('Y-m-d', strtotime('last Friday +1 week',$test_date));
			$cutoff = date('Y-m-d', strtotime('this Sunday',$test_date));
		}
	} 
	return $cutoff;
}//end function

/*////////////////////////////////////////////////////////////////////////////////
Get Job List
///////////////////////////////////////////////////////////////////////////////*/

function getJobList($default){
		require_once "_config.php";
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		
		if ($mysql_link->connect_errno) {
			die($mysql_link->connect_error);
		}
		
		$where = " WHERE (((invoice_number IS NULL OR invoice_number='') AND status = 'Completed') OR status <> 'Completed')";
		$order = " ORDER BY jobnumber DESC";	
		$table= "";
		$table .= "<select name=jobnumber>";
		$table .= "<option>$default</option>";
			$sql = "SELECT jobnumber FROM `jobdata`". $where .$order;
			$result = $mysql_link->query($sql);
			$table .= "<b>$value Jobs</b><br><hr>";
			//output table  
			while($row = $result->fetch_assoc()){
			$jobnumber=$row['jobnumber'];
			$table .= "<option>$jobnumber</option>";
			  }
			$table .= "</select>";
			  return $table;
}


/*////////////////////////////////////////////////////////////////////////////////
XOR function
///////////////////////////////////////////////////////////////////////////////*/
function xor_this($string) {
		$key = ('J');

		 // Our plaintext/ciphertext
		 $text =$string;

		 // Our output text
		 $outText = '';

		 // Iterate through each character
		 for($i=0;$i<strlen($text);)
		 {
			 for($j=0;($j<strlen($key) && $i<strlen($text));$j++,$i++)
			 {
				 $outText .= $text{$i} ^ $key{$j};
				 //echo 'i='.$i.', '.'j='.$j.', '.$outText{$i}.'<br />'; //for debugging
			 }
		 }  
		 return $outText;
}


?>
