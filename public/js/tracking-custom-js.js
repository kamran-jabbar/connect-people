/**
 * Source: http://jsfiddle.net/geocodezip/kzcm02d6/136/
 */
var map;
var directionsService;
var stepDisplay;
var position;
var marker = null;
var polyline = null;
var poly2 = null;
var infowindow = null;
var timerHandle = null;
currentUserDecodeData = JSON.parse(currentUserData);

/**
 * @param latlng
 * @param html
 * @param markerIcon
 * @returns {google.maps.Marker}
 */
function createMarker(latlng, html, markerIcon) {
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        icon: markerIcon,
        draggable: false
    });
    infowindow.setContent('<b>' + html + '<b>');
    infowindow.open(map, marker);
    google.maps.event.addListener(marker, "click", function() {
        infowindow.open(map, marker);
    });

    return marker;
}

/**
 * Initialize tracking
 */
function initialize() {
    infowindow = new google.maps.InfoWindow(
        {
            size: new google.maps.Size(150, 50)
        });
    // Instantiate a directions service.
    directionsService = new google.maps.DirectionsService();

    var myOptions = {
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map-area-tracking"), myOptions);
    var trafficLayer = new google.maps.TrafficLayer();
    trafficLayer.setMap(map);
    address = currentUserDecodeData[0]['location']
    geocoder = new google.maps.Geocoder();
    geocoder.geocode({'address': address}, function (results, status) {
        //map.setCenter(results[0].geometry.location);
    });

    // Create a renderer for directions and bind it to the map.
    var rendererOptions = {
        map: map,
        suppressMarkers: true
    };
    directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);

    // Instantiate an info window to hold step text.
    stepDisplay = new google.maps.InfoWindow();

    polyline = new google.maps.Polyline({
        path: [],
        strokeColor: 'blue',
        strokeWeight: 3
    });
    poly2 = new google.maps.Polyline({
        path: [],
        strokeColor: 'blue',
        strokeWeight: 3
    });
}


var steps = [];

/**
 * Start calculating the distance from destination
 */
function calcRoute() {

    if (timerHandle) {
        clearTimeout(timerHandle);
    }
    if (marker) {
        marker.setMap(null);
    }
    polyline.setMap(null);
    poly2.setMap(null);
    directionsDisplay.setMap(null);
    polyline = new google.maps.Polyline({
        path: [],
        strokeColor: 'blue',
        strokeWeight: 3
    });
    poly2 = new google.maps.Polyline({
        path: [],
        strokeColor: 'blue',
        strokeWeight: 3
    });
    // Create a renderer for directions and bind it to the map.
    var rendererOptions = {
        map: map,
        suppressMarkers: true
    };
    directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
    var end = meeting_name;
    var start = currentUserDecodeData[0]['location'];
    travelMode = google.maps.DirectionsTravelMode[travelModeCustom];

    var request = {
        origin: start,
        destination: end,
        travelMode: travelMode
    };

    // Route the directions and pass the response to a
    // function to create markers for each step.
    directionsService.route(request, function (response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
            var bounds = new google.maps.LatLngBounds();
            var route = response.routes[0];
            startLocation = new Object();
            endLocation = new Object();
            // For each route, display summary information.
            var path = response.routes[0].overview_path;
            var legs = response.routes[0].legs;
            for (i = 0; i < legs.length; i++) {
                if (i == 0) {
                    startLocation.latlng = legs[i].start_location;
                    startLocation.address = legs[i].start_address;
                    marker = createMarker(legs[i].start_location, legs[i].start_address, "/meeting-destination.png");
                }
                endLocation.latlng = legs[i].end_location;
                endLocation.address = legs[i].end_address;
                var steps = legs[i].steps;
                for (j = 0; j < steps.length; j++) {
                    var nextSegment = steps[j].path;
                    for (k = 0; k < nextSegment.length; k++) {
                        polyline.getPath().push(nextSegment[k]);
                        bounds.extend(nextSegment[k]);
                    }
                }
            }
            polyline.setMap(map);
            map.fitBounds(bounds);
            var service = new google.maps.DistanceMatrixService;
            service.getDistanceMatrix({
                origins: [start],
                destinations: [end],
                travelMode: travelModeCustom,
                unitSystem: google.maps.UnitSystem.METRIC,
                avoidHighways: false,
                avoidTolls: false
            }, function (response, status) {
                if (status !== 'OK') {
                    alert('Error was: ' + status);
                } else {
                    var originList = response.originAddresses;
                    var destinationList = response.destinationAddresses;
                    var results = response.rows[0].elements;
                    var timeAndDistance = results[0].distance.text + ' ' + results[0].duration.text;
                    var startAddress = startLocation.address.split(" ");
                    var endAddress = endLocation.address.split(" ");
                    createMarker(startLocation.latlng, startAddress[0] + '<br>' + endAddress[0] + '<br>' + timeAndDistance, "/current-location.png");
                }
            });
            createMarker(endLocation.latlng, endLocation.address, "/meeting-destination.png");

            map.setZoom(50);
            startAnimation();
        }
    });
}


var step = 25; // 5; // metres
var tick = 100; // milliseconds
var eol;
var k = 0;
var stepnum = 0;
var speed = "";
var lastVertex = 1;


/**
 * @param d
 */
function updatePoly(d) {
    if (poly2.getPath().getLength() > 20) {
        poly2 = new google.maps.Polyline([polyline.getPath().getAt(lastVertex - 1)]);
    }

    if (polyline.GetIndexAtDistance(d) < lastVertex + 2) {
        if (poly2.getPath().getLength() > 1) {
            poly2.getPath().removeAt(poly2.getPath().getLength() - 1)
        }
        poly2.getPath().insertAt(poly2.getPath().getLength(), polyline.GetPointAtDistance(d));
    } else {
        poly2.getPath().insertAt(poly2.getPath().getLength(), endLocation.latlng);
    }
}

/**
 * @param d
 */
function animate(d) {
    if (d > eol) {
        map.panTo(endLocation.latlng);
        marker.setPosition(endLocation.latlng);
        return;
    }
    var p = polyline.GetPointAtDistance(d);
    map.panTo(p);
    map.panTo(p);
    map.setZoom(15);
    marker.setPosition(p);
    updatePoly(d);
    timerHandle = setTimeout("animate(" + (d + step) + ")", tick);
}

/**
 * Start animating on found path
 */
function startAnimation() {
    eol = google.maps.geometry.spherical.computeLength(polyline.getPath());
    map.setCenter(polyline.getPath().getAt(0));
    poly2 = new google.maps.Polyline({path: [polyline.getPath().getAt(0)], strokeColor: "#0000FF", strokeWeight: 10});
    setTimeout("animate(50)", 2000);  // Allow time for the initial map display
}

google.maps.LatLng.prototype.latRadians = function () {
    return this.lat() * Math.PI / 180;
};

google.maps.LatLng.prototype.lngRadians = function () {
    return this.lng() * Math.PI / 180;
};

google.maps.Polyline.prototype.GetPointAtDistance = function (metres) {
    // some awkward special cases
    if (metres == 0) return this.getPath().getAt(0);
    if (metres < 0) return null;
    if (this.getPath().getLength() < 2) return null;
    var dist = 0;
    var olddist = 0;
    for (var i = 1; (i < this.getPath().getLength() && dist < metres); i++) {
        olddist = dist;
        dist += google.maps.geometry.spherical.computeDistanceBetween(this.getPath().getAt(i), this.getPath().getAt(i - 1));
    }
    if (dist < metres) {
        return null;
    }
    var p1 = this.getPath().getAt(i - 2);
    var p2 = this.getPath().getAt(i - 1);
    var m = (metres - olddist) / (dist - olddist);
    return new google.maps.LatLng(p1.lat() + (p2.lat() - p1.lat()) * m, p1.lng() + (p2.lng() - p1.lng()) * m);
}

// === A method which returns the Vertex number at a given distance along the path ===
// === Returns null if the path is shorter than the specified distance ===
google.maps.Polyline.prototype.GetIndexAtDistance = function (metres) {
    // some awkward special cases
    if (metres == 0) return this.getPath().getAt(0);
    if (metres < 0) return null;
    var dist = 0;
    var olddist = 0;
    for (var i = 1; (i < this.getPath().getLength() && dist < metres); i++) {
        olddist = dist;
        dist += google.maps.geometry.spherical.computeDistanceBetween(this.getPath().getAt(i), this.getPath().getAt(i - 1));
    }
    if (dist < metres) {
        return null;
    }
    return i;
}



