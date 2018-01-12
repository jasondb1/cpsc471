<?php 
date_default_timezone_set('America/Edmonton');
setlocale(LC_MONETARY, 'en_US');
	
// DB SETTINGS
	$dbhost 	= "localhost";	// Change this to the proper DB Host name
	$dbusername = "cpsc471"; 	// Change this to the proper DB User
	$dbpass 	= "testing";	// Change this to the proper DB User password
	$dbname		= "cpsc471"; 	// Change this to the proper DB Name

//paths
//site instruction path
	$si_path="../files/site_instructions/";//change later as required
	$itb_path="../files/invitaion_to_bid/";//change later as required
	$vi_path="../files/vendor_invoices/";//change later as required
	$project_path="../files/projects/";

//set divisions	
$divisions_list = array (
"99"=>"99-Mgmt & Supervision",
"00"=>"00-Proc. and Contract",
"01"=>"01-General Reqmt's",
"02"=>"02-Existing Cond.",
"03"=>"03-Concrete",
"04"=>"04-Masonry",
"05"=>"05-Metals",
"06"=>"06-Wood & Plastics",
"07"=>"07-Thermal Protect.",
"08"=>"08-Openings",
"09"=>"09-Finishes",
"10"=>"10-Specialties",
"11"=>"11-Equipment",
"12"=>"12-Furnishings",
"13"=>"13-Special Const.",
"14"=>"14-Conveying Equip.",
"21"=>"21-Fire Suppression",
"22"=>"22-Plumbing",
"23"=>"23-HVAC",
"25"=>"25-Integrated Auto.",
"26"=>"26-Electrical",
"27"=>"27-Communications",
"28"=>"28-Elec. Safety & Secur.",
"31"=>"31-Earthwork",
"32"=>"32-Ext. Improvements",
"33"=>"33-Utilities",
"98"=>"98-Quality Control",
);

//xor mask
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
