<?php
require('_config.php');

$columns_info = array (
				"uid"=>"uid",
				 'username'=>"Username",
				 'password'=>"Password",
				 'team'=>"team",
				 'realname'=>"Real Name",
				 'status'=>'status',
				 'company'=>"Company",
				 'email'=>"Email",
				 'phone'=>"Phone",
				 'cell'=>"cell",
				 'street'=>"Street",
				 'city'=>"City",
				 'postal_code'=>'postal_code',
				 'notes'=>"Notes",
				 'start_date'=>"Start Date"
				 
);
$tablename  = "contractor_data";
$last_field = "start_date";

	// //get and set initial variables
		// $connection = mysql_connect($dbhost, $dbusername, $dbpass);
		// $SelectedDB = mysql_select_db($dbname) or die('Could not connect: ' . mysql_error());
		
		// $where = " WHERE phpauthent_users.username = 'jdeboer'";
		// $sql="SELECT employee_info.*,phpauthent_users.* From employee_info JOIN `phpauthent_users` ON employee_info.uid = phpauthent_users.id".$where;
		// echo $sql;
		// $retval = mysql_query($sql) or die(mysql_error());
		
		$output = "<form>\r\n";
		// while($row=mysql_fetch_array($retval)){
		// echo "";
		// }
		
		foreach ($columns_info as $key=>$value){
			$output .="<div class=\"element\">\r\n";
			$output .="<label>".$value."</label>\r\n";
			$output .="<input name=\"$key\" type=\"text\" value='<?php echo \$$key; ?>' /><br>\r\n";
			$output .="</div>\r\n";
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

		
		echo "<br><br><br>PHP Form Code Follows:<br><textarea cols=80 rows=25>";
		echo $phpcode;
		echo "</textarea>";

/////////////////////////////////////////////////////////////
$phpcode="";		
 $phpcode = "/*//////////////////////////////////////////////////////////////////////////\r\n";
 $phpcode .= "Edit Form\r\n";
 $phpcode .= "/////////////////////////////////////////////////////////////////////////*/\r\n";
			 $phpcode .= "if (\$uid !=\"\"){\r\n";
			 $phpcode .="//read form date \r\n";
			 $phpcode .= '$sql = "SELECT * FROM '. $tablename .' WHERE uid = \'$uid\'";'."\r\n";
			 $phpcode .= '$retval = mysql_query($sql) or die(mysql_error());'."\r\n";

			$phpcode .= '//return single value'."\r\n";
			$phpcode .= '$row = mysql_fetch_array($retval);'."\r\n";
			 
			 $phpcode .= '//get variables'."\r\n";
			 
			 foreach ($columns_info as $key=>$value){	 
			   // $phpcode .= "$$key = $row['$key'];"."\r\n";
			   $phpcode .= "$$key = \$row['$key'];"."\r\n";
			 }	
			 			
			$phpcode .= '}//end if id!=""'."\r\n";
			$phpcode .= 'else {//set default values'."\r\n";
			//$status = "Received";
			$phpcode .= '}'."\r\n";
		
		echo "<br><br><br>PHP Form Code Follows:<br><textarea cols=80 rows=25>";
		echo $phpcode;
		echo "</textarea>";
		
		
		/////////////////////////////////////////////////////////////
$phpcode="";		
 $phpcode = "/*//////////////////////////////////////////////////////////////////////////\r\n";
 $phpcode .= "Edit Form\r\n";
 $phpcode .= "/////////////////////////////////////////////////////////////////////////*/\r\n";
		
		$phpcode .= "\$columns=array(";
			 foreach ($columns_info as $key=>$value){	 
			   // $phpcode .= "$$key = $row['$key'];"."\r\n";
			   $phpcode .= '"'.$key .'"=>"'.$value.'"';
			 }	
		$phpcode .= ");";
			
		
		echo "<br><br><br>PHP Form Viewing Code Follows:<br><textarea cols=80 rows=25>";
		echo $phpcode;
		echo "</textarea>";		
		
		
		die();
		


?>