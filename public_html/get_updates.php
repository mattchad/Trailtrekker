<?php 
	include $_SERVER['DOCUMENT_ROOT'] . "/_inc/config.php";
	include $_SERVER['DOCUMENT_ROOT'] . "/_inc/twitteroauth/twitteroauth.php";
	//Twitter API 1.1 - Constants live in config.php
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
	$connection->host = "https://api.twitter.com/1.1/";

	$res = mysql_query("SELECT * FROM twitter_accounts");
	while($row = mysql_fetch_array($res))
	{
		if($row['last_checked'] < (time() - (60*15)))
		{
			$api_data = $connection->get('statuses/user_timeline', array("screen_name" => $row['username'], "exclude_replies" => "true", "count" => "200", "include_rts" => false, "since_id" => $row['last_id']));
			if(sizeof($api_data))
			{
				mysql_query("UPDATE twitter_accounts SET last_checked='" . time() . "', last_id='" . $api_data[0]->id . "' WHERE username = '" . $row['username'] . "'") or die(mysql_error());
				foreach($api_data as $tweet)
				{
					$closest_point = "";
					$closest_distance = 999999;
					$temp_distance = 0;
					if(isset($tweet->geo->coordinates))
					{
						$route = mysql_query("SELECT * FROM route ORDER BY routeid ASC");
						while($point = mysql_fetch_array($route))
						{
							$temp_distance = haversine($tweet->geo->coordinates[0], $tweet->geo->coordinates[1], $point['latitude'], $point['longitude']);
							
							//If the point isn't within 10 miles we assume it has nothing to do with the day.
							if($temp_distance < $closest_distance && $temp_distance < 10)
							{
								$closest_distance = $temp_distance;
								$closest_point = $point['routeid'];
							}
						}
					}
					if(preg_match("@trailtrekker@msi", $tweet->text))
					{
						mysql_query("INSERT INTO updates SET type='twitter', content='" . htmlentities($tweet->text, ENT_QUOTES) . "', update_time='" . strtotime($tweet->created_at) . "', source='" . $row['username'] . "', route_location_id='" . $closest_point . "'") or die(mysql_error());
					}
				}
			}
			else
			{
				mysql_query("UPDATE twitter_accounts SET last_checked='" . time() . "' WHERE username = '" . $row['username'] . "'") or die(mysql_error());
			}
		}
	}
	if(isset($_GET['last_update']))
	{
		$last_update = $_GET['last_update'];
		$i = 0;
		$res = mysql_query("SELECT * FROM updates WHERE updateid > " . $_GET['last_update'] . " ORDER BY update_time DESC") or die(mysql_error());
		if(mysql_num_rows($res))
		{
			while($row = mysql_fetch_array($res))
			{
				if($i == 0)
				{
					$last_update = $row['updateid'];
				}
				//#########################################################
				//THIS ALSO NEEDS TO BE UPDATED IN THE INDEX.PHP FILE
				//#########################################################
				?>
				<div class="update" id="update_<?php echo $row['updateid']; ?>">
					<div class="side">
						<p class="image">
							<?php 
								switch($row['type'])
								{
									case "twitter":
									{
										switch($row['source'])
										{
											case "mattchad":
											{
												echo '<img src="https://si0.twimg.com/profile_images/3191939089/16b0f6665e99fdd96dac93ed10acf5e9_bigger.jpeg" width="50" height="50" alt="Matt Chadwick" />';
												break;
											}
											case "chrischarlton":
											{
												echo '<img src="https://si0.twimg.com/profile_images/3563521366/72c188d465c80284f0956c622cab1e42_bigger.jpeg" width="50" height="50" alt="Christopher Charlton" />';
												break;
											}
											case "harrybailey":
											{
												echo '<img src="https://si0.twimg.com/profile_images/1629325845/image_bigger.jpg" width="50" height="50" alt="Harry Bailey" />';
												break;
											}
											case "mynamesnotdave":
											{
												echo '<img src="https://si0.twimg.com/profile_images/3558929795/3180e03e4ceeaddcc8fed595505bc4b7_bigger.jpeg" width="50" height="50" alt="James Galley" />';
												break;
											}
											case "James_Galley":
											{
												echo '<img src="https://si0.twimg.com/profile_images/2165865136/me-300x300_bigger.png" width="50" height="50" alt="James Galley" />';
												break;
											}
											default:
											{
												echo '<img src="http://placehold.it/50x50" />';
												break;
											}
										}
										break;
									}
									case "stile":
									{
										echo '<img src="/_images/icon-stile.png" width="50" height="50" alt="Stile" />';
										break;
									}
									case "gate":
									{
										echo '<img src="/_images/icon-gate.png" width="50" height="50" alt="Gate" />';
										break;
									}
									default:
									{
										echo '<img src="http://placehold.it/50x50" />';
										break;
									}
								}
							?>
						</p>
						<p class="time"><?php echo date("H:i", $row['update_time']); ?></p>
					</div>
					<div class="main">
						<p class="text">
						<?php 
							switch($row['type'])
							{
								case "twitter":
								{
									$row['content'] = preg_replace_callback("/((https|http):\/\/[^\s]+)/", "my_urlencode", $row['content']);
									$row['content'] = preg_replace("/#([[:alpha:]]+[[:alnum:]]+)/", "<a href=\"http://www.twitter.com/search?q=$1\">#$1</a>", $row['content']); 
									$row['content'] = trim(preg_replace("/(^|[\n ])@([a-zA-Z0-9_]+)/", " <a href=\"http://www.twitter.com/$2\">@$2</a>", $row['content']));
									echo html_entity_decode($row['content']); 
									break;
								}
								case "stile":
								{
									$stiles = mysql_query("SELECT * FROM updates WHERE type='stile' AND update_time <= " . $row['update_time']);
									echo "We've just crossed a stile, that's " . mysql_num_rows($stiles) .  " in total!";
									break;
								}
								case "gate":
								{
									$gates = mysql_query("SELECT * FROM updates WHERE type='gate' AND update_time <= " . $row['update_time']);
									echo "We've just gone through a gate, that's " . mysql_num_rows($gates) .  " in total!";
									break;
								}
								default:
								{
									break;
								}
							}
						?>
						</p>
						<p class="date"><?php echo date("jS F", $row['update_time']); ?></p>
					</div>
				</div>
				<?php
				//#########################################################
				//THIS ALSO NEEDS TO BE UPDATED IN THE INDEX.PHP FILE
				//#########################################################
				$i++;
			}
		}
		echo "~~DATA~~" . $last_update;
	}
?>