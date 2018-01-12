<?php
require('_config.php');

$columns_info = array ("uid"=>"uid"
,"start_date"=>"Start Date"
,"ft_pt"=>"Full Time/Part Time"
,"hourly_salary"=>"Hourly/Salary"
,"compensation"=>"Compensation"
,"position"=>"Position"
,"division"=>"Division"
,"pay_increase_date"=>"Pay Increase Date"
,"sin"=>"SIN"
,"dob"=>"Date of Birth"
,"td1"=>"td1"
,"td1ab"=>"td1ab"
,"home_phone"=>"Home Phone"
,"home_cell"=>"Home Cell"
,"home_email"=>"Home Email"
,"street"=>"Street"
,"city"=>"City"
,"province"=>"Province"
,"postal_code"=>"Postal Code"
,"work_email"=>"Work Email"
,"work_phone"=>"Work Phone"
,"work_cell"=>"Work Cell"
,"drivers_license"=>"Drivers License"
,"expiry"=>"DL Expiry"
,"emergency_contact"=>"Emerg Contact"
,"emerg_number"=>"Emerg Phone"
,"notes"=>"Notes"
,"status"=>"Status"
,"supervisor"=>"Supervisor"
);
$tablename = "employee_info";
$last_field = "supervisor";

	//get and set initial variables
		$mysql_link = new MySQLi($dbhost, $dbusername, $dbpass, $dbname);
		if ($mysql_link->connect_errno) {die($mysql_link->connect_error);}
		
		$where = " WHERE phpauthent_users.username = 'jdeboer'";
		$sql="SELECT employee_info.*,phpauthent_users.* From employee_info JOIN `phpauthent_users` ON employee_info.uid = phpauthent_users.id".$where;
		echo $sql;
		$retval = $mysql_link->query($sql);
		
		$output = "<form>\r\n";
		while($row=$retval->fetch_assoc()){
		echo "";
		}
		
		foreach ($columns_info as $key=>$value){
			$output .="<label>".$value."</label>\r\n";
			$output .="<input name=\"$key\" type=\"text\" value='".$row[$key]    ."' /><br>\r\n";
		}
		foreach ($columns_info as $key=>$value){
			/*$output .="<tr>\r\n".'<td bgcolor="#E9E9E9"><span class="style3">'.$value."</span></td>\r\n";
			
			$output .='<td>' . "<input id=\"$key\" name=\"$key\" type=\"text\" value=\"<?php echo \$row['".$key    ."'];?>\" /></td>\r\n</tr>\r\n";*/

		}
		
		$output .="</form><br>\r\n";

		
		
		echo $output;
		
		echo "HTML Form Code Follows:<br><textarea cols=80 rows=25>";
		echo $output;
		echo "</textarea>";

		
///php code for form processing
		
 $phpcode = "/*//////////////////////////////////////////////////////////////////////////\r\n";
 $phpcode .= "Process Form\r\n";
 $phpcode .= "/////////////////////////////////////////////////////////////////////////*/\r\n";
 $phpcode .= "if (isset(\$_POST['submit'])){\r\n";
 $phpcode .= "//write code for submitting\r\n";
 $phpcode .= "//\$_Post variables\r\n";

  foreach ($columns_info as $key=>$value){	 
	$phpcode .= "\$$key = \$_POST['$key'];\r\n";
  }

$phpcode .= "\r\n\r\n//Data Validation\r\n";
$phpcode .= "//Write validation code below\r\n"; 
$phpcode .= "if(\$field1 ==\"\" || \$field2==\"\" {\r\n";
$phpcode .= "echo '<head><meta name=\"viewport\" content=\"width=device-width, user-scalable=no\" /><meta name=\"HandheldFriendly\" content=\"true\"><meta name=\"MobileOptimized\" content=\"320\"></head>';\r\n";
$phpcode .= "echo \"<br><br><b><big>Hey BOZO! You forgot some information!</b></big><br>Fill in all of the fields marked with an *<br><br>\";\r\n";
$phpcode .= "echo 	'<input type=\"Button\" value=\"Back\" onclick=\"history.go(-1)\">';\r\n";
$phpcode .= "die();\r\n";
$phpcode .= "}\r\n";
$phpcode .="\r\n\r\n\r\n//Enter data into database\r\n";
	 
	$phpcode .= "if (1){//enter constraint\r\n";
	$phpcode .= "\$sql = \"INSERT INTO $tablename (";
	
  foreach ($columns_info as $key=>$value){	 
   $phpcode .= "`$key`";
   if ($last_field != $key){$phpcode .=",\r\n";}
  }	
		 
		
		$phpcode .=")\r\n";
		$phpcode .="VALUES (\r\n";
  foreach ($columns_info as $key=>$value){	 
   $phpcode .= "'\$$key'";
   if ($last_field != $key){$phpcode .=",\r\n";}
  }	
		$phpcode .=")\";\r\n";
	$phpcode .="}\r\n";
	$phpcode .="else{\r\n";
		$phpcode .="\$sql = \"UPDATE $tablename SET\r\n";  
		 
		  foreach ($columns_info as $key=>$value){	 
		   $phpcode .= "`$key`  =  '\$$key'";
		   if ($last_field != $key){$phpcode .=",\r\n";}
		  }	
		 
		$phpcode .=" WHERE 1\r\n";	 
	$phpcode .="\";}//end else\r\n\r\n";

	$phpcode .="\$retval = mysql_query(\$sql) or die(mysql_error());\r\n\r\n";
	
			// //write log
				// $details="jn:$jobnumber,$date_opened,$customer,$description";
				// if ($id==""){ $event="Job Entered/Changed";} else { $event="Time Entered";}
				// write_log_file ($user,$event,$employee,$details);
				
$phpcode .="echo '<head><meta name=\"viewport\" content=\"width=device-width, user-scalable=no\" /><meta name=\"HandheldFriendly\" content=\"true\"><meta name=\"MobileOptimized\" content=\"320\"></head>';\r\n";
$phpcode .="echo \"<br><br><b><big>Successfully Submitted</b></big><br><br>\";\r\n";
$phpcode .="echo 	'<input type=\"Button\" value=\"Back\" onclick=\"location.href=\'employee_job_view.php\'\">';\r\n";
$phpcode .="die();\r\n";
$phpcode .="}//end if submit\r\n";

// ///////////////////////////////////////////////////////////////

		// $employee_list = getEmployeeNames();//get employee names

	// $edit_record = $_REQUEST['edit_record'];

// if ($edit_record !=""){
	// //read form date 
	// $sql = "SELECT * FROM $db_table_jobfile WHERE jobnumber = $edit_record";
	// $retval = mysql_query($sql) or die(mysql_error());

	// //return single value
	// $row = mysql_fetch_array($retval);

	// $jobnumber 			= $row['jobnumber'];
	// $description 		= $row['description'];
	// $location 			= $row['location'];
	// $customer 			= $row['customer'];
	// $bill_to 			= $row['bill_to'];
	// $supervisor 		= $row['supervisor'];
	// $status 			= $row['status'];
	// $start_date 		= $row['start_date'];
	// $end_date 			= $row['end_date'];
	// $quote_number 		= $row['quote_number'];
	// $po_number 			= $row['po_number'];
	// $notes 				= $row['notes'];
	// $invoice_number 	= $row['invoice_number'];
	// $contact_name		= $row['contact_name'];
	// $contact_number 	= $row['contact_number'];
	// $opened_by 			= $row['opened_by'];
	// $date_opened 		= $row['date_opened'];
	// $date_invoiced 		= $row['date_invoiced'];
	// $date_closed 		= $row['date_closed'];
	// $require_div		= $row['require_div'];
	// $require_subdiv		= $row['require_subdiv'];
// }
	// else{
	// //default form data
	// $jobnumber 		= "";
	// $description 	= "";
	// $location 		= "";
	// $customer 		= "";
	// $bill_to 		= "";
	// $supervisor 	= $user;
	// $status 		= "In Progress";
	// $start_date 	= date("Y-m-d");
	// $end_date 		= "";
	// $quote_number 	= "";
	// $po_number 		= "";
	// $notes 			= "";
	// $invoice_number = "";
	// $contact_name 	= "";
	// $contact_number = "";
	// $opened_by 		= $user;
	// $date_opened 	= date("Y-m-d");
	// $date_invoiced 	= "";
	// $date_closed 	= "";
	// $require_div	= "";
	// $require_subdiv	= "";
// }//END else		
		
		echo "<br><br><br>PHP Form Code Follows:<br><textarea cols=80 rows=25>";
		echo $phpcode;
		echo "</textarea>";

		die();
	
?>