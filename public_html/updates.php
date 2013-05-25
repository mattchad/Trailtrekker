<?php include $_SERVER['DOCUMENT_ROOT'] . "/_inc/config.php"; ?>
<?php
if(isset($_GET['id']))
{
	mysql_query("DELETE FROM updates WHERE updateid='" . $_GET['id'] . "'");
	header("Location: /updates.php");
	die();
}
?>

<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
			
			.locations
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
		<div class="locations">
			<?php 
			$updates = mysql_query("SELECT * FROM updates ORDER BY update_time DESC");
			while($update = mysql_fetch_array($updates))
			{
			?>
				<p><a href="/updates.php?id=<?php echo $update['updateid']?>" class="button"><?php echo $update['type'] . " - " . date("H:i:s", $update['update_time']) . " - " . $update['route_location_id'];?></a></p>
			<?php
			}
		?>
		</div>
	</body>
</html>