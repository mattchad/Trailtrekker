<?php include $_SERVER['DOCUMENT_ROOT'] . "/_inc/config.php"; ?>
<?php
$current_location = mysql_fetch_array(mysql_query("SELECT * FROM locations ORDER BY route_location_id DESC LIMIT 1"));

if(sizeof($_POST))
{
	//Update location
	$route = mysql_query("SELECT * FROM route WHERE routeid >= '" . $current_location['route_location_id'] . "' ORDER BY routeid ASC");
	$closest_point = $current_location['route_location_id'];
	$min_distance = 9999;
	while($row = mysql_fetch_array($route))
	{
		$this_distance = haversine($_POST['latitude'],$_POST['longitude'],$row['latitude'],$row['longitude']);
		if($this_distance < $min_distance)
		{
			$min_distance = $this_distance;
			$closest_point = $row['routeid'];
		}
	}
	
	mysql_query("INSERT INTO locations SET route_location_id='" . $closest_point . "', latitude='" . $_POST['latitude']  . "', longitude='" .  $_POST['longitude'] . "', altitude='" . $_POST['altitude']  . "', location_time='" . time() . "'") or die(mysql_error());

	
	//Set text
	$result_text = "Location updated at ";
}

$current_location = mysql_fetch_array(mysql_query("SELECT * FROM locations ORDER BY route_location_id DESC LIMIT 1"));

$random_location = mysql_fetch_array(mysql_query("SELECT * FROM route WHERE routeid = '" . ($current_location['route_location_id']+5) . " ' LIMIT 1"));
?>

<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script type="text/javascript">
			window.onload = function()
			{
				function getLocation()
				{
					// Get location no more than 10 minutes old. 600000 ms = 10 minutes.
					navigator.geolocation.getCurrentPosition(showLocation, showError, {enableHighAccuracy:true,maximumAge:10});
					//navigator.geolocation.watchPosition(showLocation, showError, {enableHighAccuracy:true,maximumAge:10,frequency: 1000 });
				}
				
				function showError(error)
				{
					switch(error.code)
					{
						case error.PERMISSION_DENIED:
						alert("User denied the request for Geolocation.");
						break;
						case error.POSITION_UNAVAILABLE:
						alert("Location information is unavailable.");
						break;
						case error.TIMEOUT:
						alert("The request to get user location timed out.");
						break;
						case error.UNKNOWN_ERROR:
						alert("An unknown error occurred.");
						break;
					}
				}
				
				function showLocation(position)
				{
					document.getElementById("latitude").value = position.coords.latitude;
					document.getElementById("longitude").value = position.coords.longitude;
					document.getElementById("altitude").value = position.coords.altitude;
					document.getElementById("accuracy").value = position.coords.accuracy;
				}
								
				getLocation();
			};
		</script>
		<style type="text/css">
			body
			{
				background: #000000;
				text-align: center;
				font-family: Arial, sans-serif;
			}
			*
			{
				margin: 0px;
				border: 0px;
				padding: 0px;
			}
			
			form
			{
				padding: 1em;
			}
			
			input,
			.button
			{
				width: 100%;
				border: 0px;
				margin-bottom: 0.5em;
				background: blue;
				color: #FFFFFF;
				-webkit-appearance: none;
				padding: 0.5em 0px;
				font-size: 1.5em;
				font-weight: bold;
				text-align: center;
				display: block;
				border-radius: 0.3em;
				text-decoration: none;
				font-family: Arial, sans-serif;
				cursor: pointer;
			}
			
			.result
			{
				color: #FFFFFF;
				text-align: center;
				border-bottom: 2px solid #FFFFFF;
				font-size: 1em;
				font-weight: bold;
				padding: 0.5em 0px;
			}
		</style>
	</head>
	<body>
		<div class="result">
			<?php echo $result_text; ?> <?php echo date("H:i:s")?>
		</div>
		<form action="" method="post">
			<p><a href="/insert.php" class="button" style="background: purple;">Refresh</a></p>
			<p><input type="text" id="latitude" name="latitude" value="<?php echo $random_location['latitude'];?>" /></p>
			<p><input type="text" id="longitude" name="longitude" value="<?php echo $random_location['longitude'];?>" /></p>
			<p><input type="text" id="accuracy" name="accuracy" /></p>
			<p><input type="text" id="altitude" name="altitude" value="<?php echo $random_location['altitude'];?>" /></p>
			<?php if(!sizeof($_POST)){ ?>			
			<p><input type="submit" value="Start" style="background: green;" /></p>
			<p><input type="submit" value="Location update" /></p>
			<p><input type="submit" value="Stile" /></p>
			<p><input type="submit" value="Gate" /></p>
			<p><input type="submit" value="End" style="background: red;" /></p>
			<?php } ?>
		</form>
	</body>
</html>