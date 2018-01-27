<?php
$ip=@$_SERVER['REMOTE_ADDR'];
//$ip='59.93.102.188';

echo ip2long($ip);
echo "<br>";
// output of this will be 995976892

$var=ip2long($ip);

echo long2ip($var);

if ($_GET['srv']=="y"){
	echo "<br><br>This is a server";
	//log server into mysql table
	
	$File = "testlog.txt";
	$Handle = fopen($File, 'a');
	$Data = $ip . "-". date ("h-m-s") . "\n";
	fwrite($Handle, $Data);
	fclose($Handle);
	
	echo "logged";
}


// if not server direct user to server

?>