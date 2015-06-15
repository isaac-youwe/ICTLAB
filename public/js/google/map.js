/**
 * Author Adriel Walter
 * Editor Isaac de Cuba, Serhildan Akdeniz
 *
 */

var map;
var infoBuurt = new google.maps.InfoWindow();
var infoWindow = new google.maps.InfoWindow();
var infoPopUp = new google.maps.InfoWindow();

// this variable will collect the html which will eventually be placed in the side_bar
var side_bar_html = "";

// gmarkers is storing all the google.maps.Marker houseMarkers
var gmarkers = [];

var icon = new google.maps.MarkerImage('images/house-marker.png');
var icon2 = new google.maps.MarkerImage('images/MousOver-house-marker.png');

// This function picks up the click and opens the corresponding info window
function myclick(i) {
    google.maps.event.trigger(gmarkers[i], "click");
}

function initialize() {
    mapOptions = {
        minZoom: 8, //minimum zoom level van de kaart
        zoom: 14,
        center: new google.maps.LatLng(lat, lng),
        disableDefaultUI: true,
        mapTypeControl: false,
        zoomControl: true
    };

    infowindow = new google.maps.InfoWindow();

    map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);

    for (var i = 0; i < collection.length; i++) {
        housePhoto = '<img src="' + collection[i]['Foto'] + '">';
        houseSquare = 'Oppervlakte: ' + collection[i]['Woonoppervlakte'] + ' m' + '<sup>2</sup>';
        housePrice = 'Prijs: &#8364;' + collection[i]['Koopprijs'];
        houseMakelaar = 'Makelaar: ' + collection[i]['MakelaarNaam'];
        houseAdress = collection[i]['Adres'];
        houseRooms = 'Aantal kamers: ' + collection[i]['AantalKamers'];
        myLatLng = new google.maps.LatLng(collection[i]['WGS84_Y'], collection[i]['WGS84_X']);
        houseMarker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            icon: icon
        });

        houseAdressDetails =
            houseAdress + '<br>';

        adressHouseDetails =
            houseRooms + '<br>' +
            housePrice + '<hr />';

        gmarkers.push(houseMarker);

        marker_id = gmarkers.length - 1;

        side_bar_html += '<a class ="listmarker" href="javascript:myclick(' + marker_id + ')' +
        '" onmouseover="gmarkers[' + marker_id + '].setIcon(icon2)' +
        '" onmouseout="gmarkers[' + marker_id + '].setIcon(icon)">' + '<h4> ' + houseAdressDetails + '</h4></a>';

        side_bar_html += '<p>' + adressHouseDetails + '</p>';

        details =
            '<p class="text-center">' +
            '<h5>' + houseAdress + '</h5>' +
            housePrice + '<br />' +
            houseRooms + '<br />' +
            houseSquare + '<br />' +
            houseMakelaar + '<br />' +
            '<div class="img-responsive">' + housePhoto + '</div>' +
            '</p>';

        detailsPopUp = '<p class="text-center">' + houseAdress + ", " + housePrice + '</p>';

        addInfoWindow(houseMarker, details);
        addPopupWindow(houseMarker, detailsPopUp);

    }

    var regExpression = /\(([^()]+)\)/g;
    var searchedBuurtPolygonCoordinates = [];
    var aangrenzendeBuurtenPolygonCoordinates = [];

    filterPolygons(searchedBuurtPolygonCoordinates, polygonBuurt);
    filterPolygons(aangrenzendeBuurtenPolygonCoordinates, aangrezendePolygon);

    function filterPolygons(coordinatesArrayName, polygon) {
        while (regExpressionChunks = regExpression.exec(polygon)) {
            coordinatesArrayName.push(regExpressionChunks[1]);
        }
    }

    var searchedPolygon = [];
    var aangrenzendePolygons = [];

    var addListenersOnPolygon = function (polygon) {
        google.maps.event.addListener(polygon, 'mouseover', function (event) {

            // get name using polygon.indexID
            infoBuurt.setContent(aangrenzendeNamen[polygon.indexID] + "</br>" + aangrenzendeObjecten[polygon.indexID] + " huizen beschikbaar");
            infoBuurt.setPosition(event.latLng);
            infoBuurt.open(map);
            this.setOptions({fillColor: '#6599FF'});
        });

        google.maps.event.addListener(polygon, 'mouseout', function () {
            this.setOptions({fillColor: '#F89406'});
            infoBuurt.close();
        });

        google.maps.event.addListener(polygon, 'click', function () {
            console.log("Stad: " + buurtSteden[polygon.indexID] + " & buurt: " + aangrenzendeNamen[polygon.indexID]);
        });
    };

    // Add coordinates points into searchedPolygon Array
    for (i = 0; i < searchedBuurtPolygonCoordinates.length; i++) {
        AddPoints(searchedBuurtPolygonCoordinates[i]);
    }

    // Create aangrenzendePolygons and push them into the aangrenzendePolygons Array
    for (i = 0; i < aangrenzendeBuurtenPolygonCoordinates.length; i++) {
        pointsData = aangrenzendeBuurtenPolygonCoordinates[i].split(",");
        aangrenzendePolygonPoints = [];

        for (j = 0; j < pointsData.length; j++) {
            coordinates = pointsData[j].split(" ");
            point = new google.maps.LatLng(parseFloat(coordinates[0]), parseFloat(coordinates[1]));
            aangrenzendePolygonPoints.push(point);
        }

        polygon = new google.maps.Polygon({
            paths: aangrenzendePolygonPoints,
            strokeColor: '#F89406',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#F89406',
            fillOpacity: 0.5,
            indexID: i
        });

        aangrenzendePolygons.push(polygon);
    }

    for (i = 0; i < aangrenzendePolygons.length; i++) {
        aangrenzendePolygons[i].setMap(map);
        addListenersOnPolygon(aangrenzendePolygons[i]);
    }

    poly = new google.maps.Polygon({
        paths: searchedPolygon,
        clickable: false,
        fillColor: '#FFF',
        fillOpacity: 0,
        strokeColor: '#ffa500',
        strokeOpacity: 1,
        strokeWeight: 3,
        zIndex: 99
    });

    poly.setMap(map);

    /**
     * Create google maps points and push them into searchedPolygon Array
     *
     * @param data
     * @constructor
     */
    function AddPoints(data) {
        pointsData = data.split(",");

        for (i = 0; i < pointsData.length; i++) {
            coordinates = pointsData[i].split(" ");
            buurtPoly = new google.maps.LatLng(parseFloat(coordinates[0]), parseFloat(coordinates[1]));

            searchedPolygon.push(buurtPoly);
        }
    }

    document.getElementById("side_bar").innerHTML = side_bar_html;

    var allowedBounds = new google.maps.LatLngBounds(
        new google.maps.LatLng(50.690492, 3.167453),
        new google.maps.LatLng(53.587940, 6.993237)
    );

    google.maps.event.addListener(map, 'center_changed', function () {
        if (allowedBounds.contains(map.getCenter())) {
            lastValidCenter = map.getCenter();
            return;
        }

        map.panTo(lastValidCenter);
    });
}

google.maps.event.addDomListener(window, 'load', initialize);

function addInfoWindow(houseMarker, details) {
    google.maps.event.addListener(houseMarker, 'click', function () {
        if (!houseMarker.open) {
            infoWindow.setContent(details);
            infoWindow.open(map, houseMarker);
            houseMarker.open = true;
        }
        else {
            infoWindow.close(map, houseMarker);
            houseMarker.open = false;
        }
    });

}
function addPopupWindow(houseMarker, detailsPopUp) {
    google.maps.event.addListener(houseMarker, 'mouseover', function () {
        infoPopUp.setContent(detailsPopUp);
        infoPopUp.open(map, houseMarker);
        houseMarker.setIcon(icon2);
    });

    google.maps.event.addListener(houseMarker, 'mouseout', function () {
        infoPopUp.close();
        houseMarker.setIcon(icon);

    });
}