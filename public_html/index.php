<?php include $_SERVER['DOCUMENT_ROOT'] . "/_inc/config.php"; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/_inc/twitteroauth/twitteroauth.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		
		<link rel="shortcut icon" href="/favicon.ico" />
		
		<title>Dale Street Trailtrekkers - 100km in 30 hours | 1-2 June 2013</title>
		<!-- CSS -->
		<link type="text/css" rel="stylesheet" href="/_css/style.css" />
		
		<!-- TYPEKIT -->
		<script type="text/javascript" src="//use.typekit.net/mbh0ugf.js"></script>
		<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
		
		<!-- GOOGLE MAPS API -->
		<!--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false&amp;libraries=geometry"></script>-->
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
		
		<!-- JQUERY -->
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
		
		<!-- FANCYBOX -->
		<script type="text/javascript" src="/_js/fancybox/jquery.fancybox.pack.js"></script>
		<link type="text/css" rel="stylesheet" href="/_js/fancybox/jquery.fancybox.css" />
		
		<!-- UPDATES -->
		<script type="text/javascript">
			<?php 
			//$res = mysql_query("SELECT * FROM updates ORDER BY updateid DESC LIMIT 1");
			//$row = mysql_fetch_array($res);
			//echo "var last_update = " . (int)$row['updateid'] . ";";
			?>
			var last_update = 0;
		</script>
		
		<!-- ROUTE / LOCATIONS PATHS -->
		<script type="text/javascript">
			<?php $route_precision = 3;?>
			var route_precision = <?php echo $route_precision;?>; //The higher the number, the less precise
			
			var route = 
			[
				<?php 
				$res = mysql_query("SELECT * FROM route ORDER BY routeid ASC") or die(mysql_error());
				$num = mysql_num_rows($res);
				$i = 1;
				while($row = mysql_fetch_array($res))
				{
					if(($i%$route_precision) == "0"){
					?>new google.maps.LatLng(<?php echo $row['latitude'] . "," . $row['longitude']; ?>)<?php 
					if($i < $num) { echo ","; }
					}
					$i++;
				}
				?>
				
			];
			
			var tweets = 
			[
				<?php 
				$res = mysql_query("SELECT * FROM updates WHERE type='twitter' AND route_location_id != ''") or die(mysql_error());
				$num = mysql_num_rows($res);
				$i = 1;
				while($row = mysql_fetch_array($res))
				{
					$route_coods = mysql_fetch_array(mysql_query("SELECT * FROM route WHERE routeid='" . $row['route_location_id'] . "'"));
					?>[new google.maps.LatLng(<?php echo $route_coods['latitude'] . "," . $route_coods['longitude']; ?>), '<p class="tweet_window"><?php echo addslashes($row['content']); ?></p>']<?php 
					if($i < $num) { echo ","; }
					$i++;
				}
				?>
				
			];
			
			<?php $last_location = mysql_fetch_array(mysql_query("SELECT * FROM locations ORDER BY route_location_id DESC")); ?>
			var last_location = <?php echo (int)$last_location['route_location_id']; ?>/route_precision;
			//var last_location = 0/route_precision;
		</script>
		
		<!-- JAVASCRIPT -->
		<script type="text/javascript" src="/_js/scripts.js"></script>
		
		<!-- WEBFONT LOADER -->
		<script type="text/javascript" src="/_js/webfont-loader.js"></script>
		
		<?php if(LIVE_SITE) { ?>
		<!-- GOOGLE ANALYTICS -->
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			
			ga('create', 'UA-40479841-1', 'dalestreettrailtrekkers.com');
			ga('send', 'pageview');
		</script>
		<?php } ?>
		
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
				<div class="block_outer block_outer_half">
					<div class="block">
						<h2>About Trailtrekker</h2>
						<div class="block_inner">
							<p>On 1st June 2013 Christopher Charlton, Matt Chadwick, James Galley and Tom Yates will set off from Skipton in the Yorkshire Dales to complete Trailtrekker 2013, supported by Harry Bailey and Martin Hicks</p>
							<p>Trailtrekker is a 100km trek for teams of four, over 30 hours (yes, that's day and night!) across the Yorkshire Dales National Park. Around 300 teams take part in the event each year, all raising money to help people in poverty.</p>
							<p>It's all in support of Oxfam - whose work is as vital today as it's ever been.</p>
						</div>
					</div>
					<div class="block progress">
						<h2>Progress / Statistics</h2>
						<div class="block_inner">
							<?php 
								$end_location = mysql_fetch_array(mysql_query("SELECT * FROM route ORDER BY cumulative_distance DESC LIMIT 1"));
								$current_location = mysql_query("SELECT * FROM locations ORDER BY route_location_id DESC LIMIT 1");
								if(mysql_num_rows($current_location))
								{
									$current_location = mysql_fetch_array($current_location);
									$current_location = mysql_fetch_array(mysql_query("SELECT * FROM route WHERE routeid='" . $current_location['route_location_id'] . "'"));
									$distance_covered = $current_location['cumulative_distance'];
									$total_elevation = $current_location['cumulative_elevation'];
									$calories_burned = "~" . round(($current_location['cumulative_distance'] / $end_location['cumulative_distance']) * 7000);
								}
								else
								{
									$distance_covered = 0;
									$total_elevation = 0;
									$calories_burned = 0;
								}
								
								$total_stiles = mysql_num_rows(mysql_query("SELECT * FROM updates WHERE type='stile'"));
								$total_gates = mysql_num_rows(mysql_query("SELECT * FROM updates WHERE type='gate'"));
							?>
							<p class="progress_row"><span class="label">Distance covered:</span> <span class="value"><?php echo round($distance_covered, 2); ?> km</span></p>
							<p class="progress_row"><span class="label">Total Elevation:</span> <span class="value"><?php echo round($total_elevation); ?> m</span></p>
							<p class="progress_row"><span class="label">Calories burned:</span> <span class="value"><?php echo $calories_burned; ?> kcal</span></p>
							<p class="progress_row"><span class="label">Stile count:</span> <span class="value"><?php echo $total_stiles; ?></span></p>
							<p class="progress_row"><span class="label">Gate count:</span> <span class="value"><?php echo $total_gates; ?></span></p>
						</div>
					</div>
				</div>
				<div class="block_outer block_outer_half block_right">
					<div class="block updates" id="update_list_outer">
						<h2>Updates</h2>
						<div class="block_inner" id="update_list"></div>
						<!-- This bit is added in using Javascript. -->
					</div>
				</div>
			</div>
		</div>
		<div class="block block_collapsed map_canvas_outer">
			<div id="map-canvas"></div>
			<h2>The Route <span id="distance"></span></h2>
		</div>
		<div class="container">
			<div class="block_row">
				<div class="block block_quarter">
					<h2>Christopher</h2>
					<div class="block_inner">
						<p class="profile_image"><img src="/_images/profile-chris.jpg" alt="Photo of Christopher Charlton" /></p>
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