<?


?>

<html>
<head>
<style>
	/* css for timepicker */
	.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
	.ui-timepicker-div dl { text-align: left; }
	.ui-timepicker-div dl dt { float: left; clear:left; padding: 0 0 0 5px; }
	.ui-timepicker-div dl dd { margin: 0 10px 10px 45%; }
	.ui-timepicker-div td { font-size: 90%; }
	.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }

	.ui-timepicker-rtl{ direction: rtl; }
	.ui-timepicker-rtl dl { text-align: right; padding: 0 5px 0 0; }
	.ui-timepicker-rtl dl dt{ float: right; clear: right; }
	.ui-timepicker-rtl dl dd { margin: 0 45% 10px 10px; }
</style>
<link href="css/jquery-ui.css" rel="stylesheet">
<link href="css/jquery-ui-timepicker-addon.css" rel="stylesheet">
<script src="js/jquery-2.1.1.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>
 <script>
$('#slider_example_2').datetimepicker({
	timeFormat: 'HH:mm:ss',
	stepHour: 2,
	stepMinute: 10,
	stepSecond: 10
});

</script>
</head>
<body>

<form>
<input id="slider_example_2" name="slider_example_2" type="text"/>

</form>

</body>
</html>