var map;
function initialize() {
    var mapOptions = {
        zoom: 8,
        center: new google.maps.LatLng(52.3731, 4.8922)// Rotterdam Lat, Lon
    };
    map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);
}
google.maps.event.addDomListener(window, 'load', initialize);