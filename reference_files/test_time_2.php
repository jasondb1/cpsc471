<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>jQuery UI Datepicker - Default functionality</title>
<link rel="stylesheet" href="css/jquery-ui.min.css">
<link rel="stylesheet" href="css/jquery-ui.theme.min.css">
<link rel="stylesheet" href="css/jquery-ui.structure.min.css">
<link rel="stylesheet" href="css/jquery-ui-timepicker-addon.css">
<script src="js/jquery-2.1.1.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>

<script>
$(function() {
$( "#datepicker" ).datepicker(
{
dateFormat: "yy-mm-dd",
showButtonPanel: true,
 changeMonth: true,
changeYear: true,
buttonImage: "images/calendar.png",
buttonImageOnly: false,
 showWeek: true,
firstDay: 1
	}
);




$('#timepicker1').timepicker({
timeFormat: 'hh:mm tt',
stepHour: 1,
	stepMinute: 15,
	hourGrid: 6,
	minuteGrid: 15
});
$('#slider_example_1').timepicker();

});
</script>
</head>
<body>
<p>Date: <input type="text" id="datepicker"></p>
<p>Time1: <input type="text" id="timepicker1"></p>
<p>Time2: <input type="text" id="slider_example_1"></p>
</body>
</html>