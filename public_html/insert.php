<?php
mysql_connect("localhost", "trailtre_ttuser", "sLMk0GE3l2SE") or die(mysql_error());
mysql_select_db("trailtre_trailtrekker") or die(mysql_error());

if(sizeof($_POST))
{
	mysql_query("INSERT INTO locations SET latitude='" . $_POST['latitude']  . "', longitude='" .  $_POST['longitude'] . "', altitude='" . $_POST['altitude']  . "'");
	header("Location: /insert.php");
	die();
}
?>

<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function()
			{
				function getLocation()
				{
					// Get location no more than 10 minutes old. 600000 ms = 10 minutes.
					navigator.geolocation.getCurrentPosition(showLocation, showError, {enableHighAccuracy:true,maximumAge:0});
				}
 
				function showError(error)
				{
					alert(error.code + ' ' + error.message);
				}
 
				function showLocation(position)
				{
					$("#latitude").val(position.coords.latitude);
					$("#longitude").val(position.coords.longitude);
					$("#altitude").val(position.coords.altitude);
				}
				
				getLocation();
			});
		</script>
	</head>
	<body>
		<form action="" method="post">
			<p><input type="text" value="" id="latitude" name="latitude" /></p>
			<p><input type="text" value="" id="longitude" name="longitude" /></p>
			<p><input type="text" value="" id="altitude" name="altitude" /></p>
			<p><input type="submit" value="Insert" /></p>
		</form>
	</body>
</html>