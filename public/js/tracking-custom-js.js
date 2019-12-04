function getLocation() {
    if (navigator.geolocation) {
        return navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    userLat = position.coords.latitude;
    userLng = position.coords.longitude;
}

function error(position) {
    userLat = position.coords.latitude;
    userLng = position.coords.longitude;
}

/**
 * @param lat
 * @param lng
 * @param meeting_name
 */
function loadMap(lat, lng, meeting_name, time, user_name) {
    var latlng = new google.maps.LatLng(lat, lng);
    navigator.geolocation.getCurrentPosition(function (position) {
        showPosition(position);
        var userLatLng = new google.maps.LatLng(62.5883,29.7708);
        map = new google.maps.Map(document.getElementById('map-area-tracking'), {
            center: userLatLng,
            zoom: 16,
            mapTypeId: google.maps.MapTypeId.ROADMAPS
        });

        var directionsService = new google.maps.DirectionsService();
        var directionsRequest = {
            origin: userLatLng,
            destination: latlng,
            travelMode: google.maps.DirectionsTravelMode.WALKING,
            unitSystem: google.maps.UnitSystem.METRIC
        };
        directionsService.route(
            directionsRequest,
            function (response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    new google.maps.DirectionsRenderer({
                        map: map,
                        directions: response,
                        suppressMarkers: true
                    });
                    var leg = response.routes[0].legs[0];
                    userImage = '<span class="glyphicon glyphicon-user"></span>';
                    targetImage = '<img id="meeting-image" src="/meeting-picture.png">';
                    makeMarker(leg.start_location, icons.start, user_name, map, userImage);
                    makeMarker(leg.end_location, icons.end, meeting_name + '<br>' + time, map, targetImage);
                }
            }
        );
    }, error);


}

function makeMarker(position, icon, title, map, image) {
    marker = new google.maps.Marker({
        position: position,
        map: map,
        icon: icon
    });
    var infowindow = new google.maps.InfoWindow();
    infowindow.setContent(image + '<br><b>' + title);
    infowindow.open(map, marker);
}

var icons = {
    start: new google.maps.MarkerImage(
        // URL
        '/current-location.png',
        // (width,height)
        new google.maps.Size(44, 32),
        // The origin point (x,y)
        new google.maps.Point(0, 0),
        // The anchor point (x,y)
        new google.maps.Point(15, 20)
    ),
    end: new google.maps.MarkerImage(
        // URL
        '/meeting-destination.png',
        // (width,height)
        new google.maps.Size(50, 50),
        // The origin point (x,y)
        new google.maps.Point(0, 0),
        // The anchor point (x,y)
        new google.maps.Point(10, 50))
};