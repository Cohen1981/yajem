function initMap() {
	var input = document.getElementById('jform_latlng').value;
	if(input !=='') {
	var latlngStr = input.split(',', 2);
	var locLatLng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};

		var title = document.getElementById('jform_title').value;

		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 8,
			center: locLatLng
		});
		
		var marker = new google.maps.Marker({
			position: locLatLng,
			map: map,
			title: title
		});
	} else {
		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 8,
			center: {lat: 53.580, lng: 9.486}
		});
	}
	var geocoder = new google.maps.Geocoder();

	document.getElementById('submit').addEventListener('click', function() {
		geocodeAddress(geocoder, map);
	});
}

function geocodeAddress(geocoder, resultsMap) {
	var street = document.getElementById('jform_street').value;
	var zip = document.getElementById('jform_postalCode').value;
	var city = document.getElementById('jform_city').value;
	var address = street+', '+zip+city;

	geocoder.geocode({'address': address}, function(results, status) {
		if (status === 'OK') {
			resultsMap.setCenter(results[0].geometry.location);
			var marker = new google.maps.Marker({
				map: resultsMap,
				position: results[0].geometry.location
			});
			document.getElementById('jform_latlng').value = String(results[0].geometry.location).replace(/[\(\)]/g, "");
		} else {
			alert('Geocode was not successful for the following reason: ' + status);
		}
	});
}