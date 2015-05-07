/**
 * Author Adriel Walter
 * Editor Isaac de Cuba, Serhildan Akdeniz
 *
 * Temporary disabled
 */
var map;

console.log('this col: ');
function initialize() {
    var mapOptions = {
        zoom: 12,
        center: new google.maps.LatLng(52.3731, 4.8922)// Rotterdam Lat, Lon
    }
    map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);

    //var image = 'images/house.png';
    var myLatLng = new google.maps.LatLng(52.3731, 4.8922);
    var houseMarker = new google.maps.Marker({
        position: myLatLng,
        map: map
       // icon: image
    });
}

google.maps.event.addDomListener(window, 'load', initialize);