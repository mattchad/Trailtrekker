<?php include $_SERVER['DOCUMENT_ROOT'] . "/_inc/config.php"; ?>
<?php 
	$total_distance = 0;
	$total_elevation = 0;
	$res = mysql_query("SELECT * FROM route ORDER BY routeid ASC");
	$previous_lat = 0;
	$previous_long = 0;
	$previous_alt = 0;
	while($row = mysql_fetch_array($res))
	{
		if($previous_lat != 0 && $previous_long != 0)
		{
			$total_distance += haversine($previous_lat,$previous_long,$row['latitude'],$row['longitude']);;
		}
		$altitude = $row['altitude'];
		if($row['altitude'] <= 0)
		{
			$elevationapi = json_decode(file_get_contents("http://maps.googleapis.com/maps/api/elevation/json?locations=" . $row['latitude'] . "," . $row['longitude'] . "&sensor=false"));
			if($elevationapi->status == "OK")
			{
				$altitude = $elevationapi->results[0]->elevation;
			}
			else
			{
				echo "<pre>";
				print_r($elevationapi);
				echo "</pre>";
			}
		}
		else
		{
			if(($altitude - $previous_alt) > 0 && $previous_alt != 0)
			{
				$total_elevation += ($altitude - $previous_alt);
			}
		}
		mysql_query("UPDATE route SET cumulative_distance = '" . $total_distance . "', cumulative_elevation='" . $total_elevation . "', altitude='" . $altitude . "' WHERE routeid='" . $row['routeid'] . "'") or die(mysql_error());
		$previous_long = $row['longitude'];
		$previous_lat = $row['latitude'];
		$previous_alt = $altitude;
	}
	echo "DONE";
?>