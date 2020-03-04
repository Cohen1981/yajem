$(function() {
	//https://nominatim.openstreetmap.org/?format=json&addressdetails=1&q=bakery+in+berlin+wedding&format=json&limit=1
	var mymap = L.map('map').setView([53.4594, 10.1761], 9);
	var OpenStreetMap_DE = L.tileLayer('https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {
		maxZoom: 18,
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(mymap);
});