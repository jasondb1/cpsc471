<?php
<?php include_once('_config.php')?>
// get required variables
if (isset ($_POST['submit'])){
$to      = $email_office;
$name	= $_POST['name'];
$email	= $_POST['email'];
$today = date("F j, Y");                                     

if ($name == "" || $message == "") {echo "Please fill in the required information";
echo '<br><br><p><a href="request_quote.php" title="Return to the previous page">&laquo; Go back</a></p>'; die ();}

$subject = "Contact Us: ";
$subject .= $today ;

//format the message
$message = $_POST['message'];
$message .= "\n\nFrom: $email";

$headers = "From: Web Contact <noreply@testcompany.ca>";

if(mail($to,$subject,$message,$headers)) {
echo "Your comment was sent to $to \nwith the subject: $subject";
} else {
echo "There was a problem sending the request. Check your code and make sure that the e-mail address $to is valid";
}
$referer = $_SERVER['HTTP_REFERER'];
   if (!$referer == '') {
      echo '<p><a href="' . $referer . '" title="Return to the previous page">&laquo; Go back</a></p>';
   } else {
      echo '<p><a href="javascript:history.go(-1)" title="Return to the previous page">&laquo; Go back</a></p>';
	
   }
     die();
}
?>
