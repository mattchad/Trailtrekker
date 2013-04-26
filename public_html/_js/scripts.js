function adjust_block_heights()
{
	$(".block_row").each(function(i,e)
	{
		var max_height = 0;
		$(e).find(".block").each(function(j,f)
		{
			if($(f).height() > max_height)
			{
				max_height = $(f).height();
			}
		});
		$(e).find(".block").height(max_height);
	});
	
	$("#donate_button").one('load', function()
	{
		$(this).css("marginTop", (($(this).closest(".block").outerHeight() - $(this).height()) / 2)-20)
	}).each(function()
	{
		if(this.complete) $(this).load();
	});
}

google.maps.Polyline.prototype.inKm = function(n)
{ 
	var a = this.getPath(n), len = a.getLength(), dist = 0; 
	for (var i=0; i < (a.getLength()-1); i++)
	{ 
		dist += google.maps.geometry.spherical.computeDistanceBetween(a.getAt(i), a.getAt(i+1))
	}
	return (dist / 1000); //Return distance in kilometers
}

function initialize()
{
	//SET UP MAP
	var mapOptions =
	{
		mapTypeId: google.maps.MapTypeId.TERRAIN,
		scrollwheel: false,
		scaleControl: true
	}

	var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	
	//ADD ROUTE LINE TO MAP
	var routeLine = new google.maps.Polyline(
	{
		path: route,
		//strokeOpacity: 0.5,
		strokeOpacity: 1,
		strokeWeight: 8,
		strokeColor: '#666199'
	});
	
	routeLine.setMap(map);
	
	//ADD COMPLETED LOCATIONS LINE TO MAP
	var locationsLine = new google.maps.Polyline(
	{
		path: locations,
		strokeOpacity: 1,
		strokeWeight: 3,
		strokeColor: "#FF0000"
	});
	
	//locationsLine.setMap(map);
	
	//DETERMINE THE BOUNDS OF THE ROUTE AND SCALE THE MAP ACCORDINGLY
	var bounds = new google.maps.LatLngBounds();
	
	for (var i = 0; i < route.length; i++)
	{
		bounds.extend(route[i]);
	}

	map.fitBounds(bounds);
	
	//ADD TOTAL DISTANCE IN KM
	
	$("#distance").html(routeLine.inKm());
	
	//ADD MARKERS TO MAP
	var marker = new google.maps.Marker(
	{
		map: map
	});
	
	var marker2 = new google.maps.Marker(
	{
		map: map,
	});
	
	var sf = new google.maps.Marker(
	{
		map: map,
		position: new google.maps.LatLng(53.962407,-2.036387	),
		icon: 'http://trailtrekker.modliadev.com/_images/map-sf.png'
	});
	
	var cp1 = new google.maps.Marker(
	{
		map: map,
		position: new google.maps.LatLng(54.057265,-2.153943),
		icon: 'http://trailtrekker.modliadev.com/_images/map-cp1.png'
	});
	
	var cp2 = new google.maps.Marker(
	{
		map: map,
		position: new google.maps.LatLng(54.149485,-2.298439),
		icon: 'http://trailtrekker.modliadev.com/_images/map-cp2.png'
	});
	
	var cp3 = new google.maps.Marker(
	{
		map: map,
		position: new google.maps.LatLng(54.188843,-2.089022),
		icon: 'http://trailtrekker.modliadev.com/_images/map-cp3.png'
	});
	
	var cp4 = new google.maps.Marker(
	{
		map: map,
		position: new google.maps.LatLng(54.103137,-2.034767),
		icon: 'http://trailtrekker.modliadev.com/_images/map-cp4.png'
	});
	
	
	//FINDS THE CLOSEST POINT ON THE ROUTE TO GIVEN LATLNG
	function calculateClosest(point)
	{
		var nearestPoint = 0;
			var nearestDistance = 9999999;
			for (var i = 0; i < route.length; i++)
			{
				if(google.maps.geometry.spherical.computeDistanceBetween(route[i], point) < nearestDistance)
				{
					nearestPoint = i;
					nearestDistance = google.maps.geometry.spherical.computeDistanceBetween(route[i], point);

				}
			}
			marker.setPosition(route[nearestPoint]);
	}
	
	//WE SET A MARKER TO THE LOCATION WE CLICKED AND FIND THE CLOSEST POINT ON THE ROUTE
	google.maps.event.addListener(map, 'click', function(event)
	{
		marker2.setPosition(event.latLng);
		calculateClosest(event.latLng);
	});
}

$(document).ready(function()
{
	initialize();
	
	adjust_block_heights();
	
	function get_updates()
	{
		$.ajax(
		{
			url: "get_updates.php?last_update=" + last_update,
			cache: false,
			success: function(data)
			{
				data = data.split("~~DATA~~");
				last_update = data[1];
				$("#update_list").prepend(data[0]);
			},
			error: function(data)
			{
				
			}
		});
	}
	
	get_updates();
	
	setInterval(function()
	{
		get_updates();
	}, 60000);
	
	$("#temp_fundraising_content").load('/get_fundraising_progress.php #thermometer', function()
	{
		$("#temp_fundraising_content").find(".base > strong .cent-sign").remove();
		var total = $("#temp_fundraising_content").find(".base > strong em").html();
		$("#temp_fundraising_content").remove();
		$("#donation_progress").animate(
		{
			width: "+=" + total + "%"
		}, 2000, function()
		{
			//$("#donation_progress").delay(1000).html(total + "%");
		});
		
		var i = 0;
		var interval = setInterval(function()
		{
			if(i >= total)
			{
				clearInterval(interval);
			}
			$("#donation_progress").html(i + "%");
			i++;
		}, (1800/total));
	});
});