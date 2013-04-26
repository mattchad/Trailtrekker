<?php 
	define("DATABASE_LOCATION", "localhost");
	if(preg_match("@dalestreettrailtrekkers.com@", $_SERVER['HTTP_HOST']))
	{
		define("DATABASE_USER", "trailtre_ttuser");
		define("DATABASE_PASSWORD", "sLMk0GE3l2SE");
		define("DATABASE_NAME", "trailtre_trailtrekker");
	}
	else
	{
		define("DATABASE_USER", "root");
		define("DATABASE_PASSWORD", "");
		define("DATABASE_NAME", "trailtre_trailtrekker");
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
?>