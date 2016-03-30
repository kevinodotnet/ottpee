<!DOCTYPE html>
<html lang="en">
  <title>Public Washrooms in the City of Ottawa</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" ></script>
  <link rel="stylesheet" href="https://js.arcgis.com/3.15/esri/css/esri.css">
  <script src="https://js.arcgis.com/3.15/"></script>
  <style>
  html, body, #map { padding: 0; margin: 0; height: 100%; }
  </style>
<body>

<div id="map"></div>

<script>
	var currLocation;
  var watchId;
require(
  [
    "esri/map",
    "esri/layers/FeatureLayer",
    "esri/symbols/TextSymbol",
    "esri/layers/LabelClass",
    "esri/InfoTemplate",
		"esri/geometry/Point",
    "dojo/domReady!",

  ], 
  function(Map,FeatureLayer,TextSymbol,LabelClass,InfoTemplate,Point) {


  var map = new Map( "map", { 
    center: [-75.721356969316, 45.378977023497], 
    zoom: 11, 
    basemap: "streets" ,
    showLabels: true
  }); 
	map.on("load", initFunc);

	function initFunc(map) {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(zoomToLocation, locationError); 
		} else {
			alert("Browser doesn't support Geolocation. Visit http://caniuse.com to see browser support for the Geolocation API."); 
		}
	}
	function locationError(error) {
		console.log(error);
		if (navigator.geolocation ) {
			navigator.geolocation.clearWatch(watchId);
		}
	}
	function zoomToLocation(location) {
		console.log('zooming...');
		console.log(location);
		var pt = new Point(location.coords.longitude, location.coords.latitude);
		// addGraphic(pt);
		map.centerAndZoom(pt, 16);
	}


  var template = new InfoTemplate();
  template.setTitle("<b>${NAME}</b>");
  template.setContent("...loading...");
  template.setContent(getTextContent);

  var featureLayer = new FeatureLayer(
    "http://maps.ottawa.ca/arcgis/rest/services/PublicWashrooms/MapServer/0",
    {
      id: "OBJECTID",
      infoTemplate: template,
      outFields: ["*"]
    }
  );

  map.addLayer(featureLayer);

  function getTextContent(graphic) {
    console.log(graphic);
    a = graphic.attributes;

		access = 'Unknown';
		if (a.ACCESSIBILITY == '0') { access = '(0) No Accessibility/Aucune accessibilit&eacute;'; }
		if (a.ACCESSIBILITY == '1') { access = '(1) Minimal Accessibility/Accessibilit&eacute; minimale'; }
		if (a.ACCESSIBILITY == '2') { access = '(2) Moderate Accessibility/Accessibilit&eacute; moyenne'; }
		if (a.ACCESSIBILITY == '3') { access = '(3) Maximum Accessibility/Accessibilit&eacute; maximale'; }
    t = 
    '<b>Address:</b> ' + a.ADDRESS + '<br/>' + 
		'<a target="_blank" href="http://data.ottawa.ca/dataset/publicwashrooms">' + access + '</a>' + 
    '<pre>' + 
    'MON: ' + a.HOURS_MONDAY_OPEN + '-' + a.HOURS_MONDAY_CLOSED + '<br/>' + 
    'TUE: ' + a.HOURS_TUESDAY_OPEN + '-' + a.HOURS_TUESDAY_CLOSED + '<br/>' + 
    'WED: ' + a.HOURS_WEDNESDAY_OPEN + '-' + a.HOURS_WEDNESDAY_CLOSED + '<br/>' + 
    'THU: ' + a.HOURS_THURSDAY_OPEN + '-' + a.HOURS_THURSDAY_CLOSED + '<br/>' + 
    'FRI: ' + a.HOURS_FRIDAY_OPEN + '-' + a.HOURS_FRIDAY_CLOSED + '<br/>' + 
    'SAT: ' + a.HOURS_SATURDAY_OPEN + '-' + a.HOURS_SATURDAY_CLOSED + '<br/>' + 
    'SUN: ' + a.HOURS_SUNDAY_OPEN + '-' + a.HOURS_SUNDAY_CLOSED + 
		'</pre>';
    if (a.SEASONAL == 1) {
      t = t + '<b>Seasonal:</b> ' + a.SEASON_START + '-' + a.SEASON_END + "<br/>";
    }
    return t;
  }
});

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-6324294-31', 'auto');
  ga('send', 'pageview');


</script>

</body>
</html>
