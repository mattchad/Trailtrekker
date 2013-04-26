<?php include $_SERVER['DOCUMENT_ROOT'] . "/_inc/config.php"; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/_inc/twitteroauth/twitteroauth.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		
		<title>Dale Street Trailtrekkers - 100km in 30 hours | 1-2 June 2013</title>
		<!-- CSS -->
		<link type="text/css" rel="stylesheet" href="/_css/style.css" />
		
		<!-- TYPEKIT -->
		<script type="text/javascript" src="//use.typekit.net/mbh0ugf.js"></script>
		<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
		
		<!-- GOOGLE MAPS API -->
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=geometry"></script>
		
		<!-- JQUERY -->
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
		
		<!-- UPDATES -->
		<script type="text/javascript">
			<?php 
			$res = mysql_query("SELECT * FROM updates ORDER BY update_time DESC LIMIT 1");
			$row = mysql_fetch_array($res);
			echo "var last_update = " . (int)$row['update_time'] . ";";
			?>
		</script>
		
		<!-- ROUTE / LOCATIONS PATHS -->
		<script type="text/javascript">
			var route = 
			[
				<?php 
				$res = mysql_query("SELECT * FROM route ORDER BY routeid ASC") or die(mysql_error());
				$num = mysql_num_rows($res);
				$i = 1;
				while($row = mysql_fetch_array($res))
				{
				?>
					new google.maps.LatLng(<?php echo $row['latitude'] . "," . $row['longitude']; ?>)<?php echo ($i < $num) ? "," : ""; ?>
					
				<?php
					$i++;
				}
				?>
			];
				
			var locations = 
			[
				<?php 
				$res = mysql_query("SELECT * FROM route ORDER BY routeid ASC LIMIT 1000") or die(mysql_error());
				$num = mysql_num_rows($res);
				$i = 1;
				while($row = mysql_fetch_array($res))
				{
				?>
					new google.maps.LatLng(<?php echo $row['latitude'] . "," . $row['longitude']; ?>)<?php if($i < $num) { echo ","; } ?>
					
				<?php
					$i++;
				}
				?>
			];
		</script>
		
		<!-- JAVASCRIPT -->
		<script type="text/javascript" src="/_js/scripts.js"></script>
		
		<!-- WEBFONT LOADER -->
		<script type="text/javascript" src="/_js/webfont-loader.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="block block_collapsed">
				<div class="block_inner">
					<h1><span>Dale Street Trailtrekkers - 100km in 30 hours. 1-2 June 2013</span></h1>
				</div>
			</div>
			<div class="block_row">
				<div class="block block_three_quarters fundraising_block">
					<h2>Fundraising progress</h2>
					<div class="block_inner">
						<p class="donation_progress_outer"><span id="donation_progress">0%</span></p>
						<p>Of our &pound;1400 target</p>
					</div>
				</div>
				<div class="block block_collapsed block_quarter block_right">
					<div class="block_inner">
						<a href="https://www.justgiving.com/teams/dale-street-trailtrekkers"><img src="/_images/donate.png" alt="Donate with JustGiving" id="donate_button" /></a>
					</div>
				</div>
			</div>
			<div class="block_row">
				<div class="block block_half">
					<h2>About Trailtrekker</h2>
					<div class="block_inner">
						<?php 
							$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
							$connection->host = "https://api.twitter.com/1.1/";
							$api_data = $connection->get('application/rate_limit_status', array("resources" => "statuses"));
							//print_r($api_data);
						?>
						<p>On 1st June 2013 Chris Charlton, Matt Chadwick, James Galley and Tom Yates will set off from Skipton in the Yorkshire Dales to complete Trailtrekker 2013, supported by Harry Bailey and Martin Hicks</p>
						<p>Trailtrekker is a 100km trek for teams of four, over 30 hours (yes, that's day and night!) across the Yorkshire Dales National Park. Around 300 teams take part in the event each year, all raising money to help people in poverty.</p>
					</div>
				</div>
				<div class="block block_half block_right updates">
					<h2>Updates</h2>
					<div class="block_inner" id="update_list">
						<?php 
							$res = mysql_query("SELECT * FROM updates ORDER BY update_time DESC LIMIT 50");
							while($row = mysql_fetch_array($res))
							{
								//#########################################################
								//THIS ALSO NEEDS TO BE UPDATED IN THE GET_UPDATES.PHP FILE
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
																echo '<img src="https://si0.twimg.com/profile_images/3563521366/72c188d465c80284f0956c622cab1e42_bigger.jpeg" width="50" height="50" alt="Chris Charlton" />';
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
													$stiles = mysql_query("SELECT * FROM updates WHERE type='stile' AND updateid <= " . $row['updateid']);
													echo "We've just crossed a stile, that's " . mysql_num_rows($stiles) .  " in total!";
													break;
												}
												case "gate":
												{
													$gates = mysql_query("SELECT * FROM updates WHERE type='gate' AND updateid <= " . $row['updateid']);
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
								//THIS ALSO NEEDS TO BE UPDATED IN THE GET_UPDATES.PHP FILE
								//#########################################################
							}
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="block block_collapsed map_canvas_outer">
			<div id="map-canvas"></div>
			<h2>The Route</h2>
		</div>
		<div class="container">
			<div class="block_row">
				<div class="block block_quarter">
					<h2>Chris</h2>
					<div class="block_inner">
						<p class="profile_image"><img src="/_images/profile-chris.jpg" alt="Photo of Chris Charlton" /></p>
					</div>
				</div>
				<div class="block block_quarter">
					<h2>James</h2>
					<div class="block_inner">
						<p class="profile_image"><img src="/_images/profile-james.jpg" alt="Photo of James Galley" /></p>
					</div>
				</div>
				<div class="block block_quarter">
					<h2>Matt</h2>
					<div class="block_inner">
						<p class="profile_image"><img src="/_images/profile-matt.jpg" alt="Photo of Matt Chadwick" /></p>
					</div>
				</div>
				<div class="block block_quarter block_right">
					<h2>Tom</h2>
					<div class="block_inner">
						<p class="profile_image"><img src="/_images/profile-tom.jpg" alt="Photo of Tom Yates" /></p>
					</div>
				</div>
			</div>
			<div class="block_row">
				<div class="block block_half">
					<h2>Support Crew</h2>
					<div class="block_inner">
						<p>Harry and Martin will be our support crew for the day and are a vital part of our team - we simply could not complete the event without them.</p>
						<p> Their role is to keep the team moving by being present at check points along the route, providing additional food, topping up supplies of water and snacks, and most of all encouraging us. They will also carry our extra kit like wet weather / warm clothing.</p>
					</div>
				</div>
				<div class="block block_quarter">
					<h2>Harry</h2>
					<div class="block_inner">
						<p class="profile_image"><img src="/_images/profile-harry.jpg" alt="Photo of Harry Bailey" /></p>
					</div>
				</div>
				<div class="block block_quarter block_right">
					<h2>Martin</h2>
					<div class="block_inner">
						<p class="profile_image"><img src="/_images/profile-martin.jpg" alt="Photo of Martin Hicks" /></p>
					</div>
				</div>
			</div>
			<div class="block_row">
				<div class="block block_half block_collapsed">
					<div class="block_inner">
						<p class="logo_pageplay"><a href="http://pageplay.com"><span>PagePlay</span></a></p>
					</div>
				</div>
				<div class="block block_half block_right block_collapsed">
					<div class="block_inner">
						<p class="logo_tariff_street"><a href="http://tariffstreet.com"><span>Tariff Street</span></a></p>
					</div>
				</div>
			</div>
		</div>
		<div id="temp_fundraising_content" style="display: none;"></div>
	</body>
</html>