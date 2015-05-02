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

//google.maps.event.addListener(map, 'idle', function(ev){
//    // update the coordinates here
//});

function checkCoordinates(map) {
    var bounds = map.getBounds();
    var ne = bounds.getNorthEast(); // LatLng of the north-east corner
    var sw = bounds.getSouthWest(); // LatLng of the south-west corder
    var nw = new google.maps.LatLng(ne.lat(), sw.lng());
    var se = new google.maps.LatLng(sw.lat(), ne.lng());

    console.log('north east: ' + ne);
    console.log('south west: ' + sw);
    console.log('north west: ' + nw);
    console.log('south east: ' + se);
}


