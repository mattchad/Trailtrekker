<?php 
	define("DATABASE_LOCATION", "localhost");
	if(preg_match("@dalestreettrailtrekkers.mc@", $_SERVER['HTTP_HOST']))
	{
		define("DATABASE_USER", "root");
		define("DATABASE_PASSWORD", "");
		define("DATABASE_NAME", "trailtre_trailtrekker");
		
		define("LIVE_SITE", false);
	}
	else
	{
		define("DATABASE_USER", "trailtre_ttuser");
		define("DATABASE_PASSWORD", "sLMk0GE3l2SE");
		define("DATABASE_NAME", "trailtre_trailtrekker");
		
		define("LIVE_SITE", true);
	}
	
	if(time() > 1370041200)
	{
		define("AFTER_EVENT_START_DATE", true);
	}
	else
	{
		define("AFTER_EVENT_START_DATE", false);
	}
	
	define("CONSUMER_KEY", "NqWlcWnagu6V7TLo8yavw");
	define("CONSUMER_SECRET", "h7yKu8npj0xWSDz5XYOYU3Um4CQbyBpBtqbJpLysRBw");
	define("ACCESS_TOKEN", "21230228-wzzwJXSQlXOrEuYavqy2TxITE2HEzKwC81pUf6em8");
	define("ACCESS_TOKEN_SECRET", "WE0Ru7njkUDNCLCZMGZ3qs5XrTEzIfStHVFDFLVRc");
	
	mysql_connect(DATABASE_LOCATION, DATABASE_USER, DATABASE_PASSWORD) or die(mysql_error());
	mysql_select_db("trailtre_trailtrekker") or die(mysql_error());
	
	
	function my_urlencode($a)
	{
		$string = "<a href=\"" . preg_replace("/#/", "%23", $a[0]) . "\">" . $a[0] . "</a>";
		return $string;	
	}
	
	function haversine($lat_1,$long_1,$lat_2, $long_2)
	{
		$R = 6378.137;
		$dLat = deg2rad($lat_1 - $lat_2);
		$dLon = deg2rad($long_1 - $long_2);;
		$lat1 = deg2rad($lat_1);
		$lat2 = deg2rad($lat_2);
		
		$a = sin($dLat/2) * sin($dLat/2) + sin($dLon/2) * sin($dLon/2) * cos($lat2) * cos($lat2); 
		$c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
		return ($R * $c);
	}
?>