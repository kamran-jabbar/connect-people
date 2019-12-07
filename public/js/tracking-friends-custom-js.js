/**
 *
 * @param lat
 * @param lng
 * @param meeting_type
 * @param meeting_time
 * @param id
 * @param location
 */
function openMeetingDetailPopup(lat, lng, meeting_type, meeting_time, id, location) {
    loadMap(lat, lng, location + '-' + meeting_time);
    $('.modal-title').text(meeting_type);
    $('.meeting-time').text(meeting_time);
    $('.delete-meeting').attr('href', '/delete-meeting/' + id);
    $('.edit-meeting').attr('href', '/edit-meeting/' + id);
    $('#meeting-popup').modal('show');

}

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
function loadMap(lat, lng, meeting_name) {
    var bounds = new google.maps.LatLngBounds;
    var markersArray = [];
    var origins = [];
    var names = [];
    var userLatLang = [];
    currentUserDecodeData = JSON.parse(currentUserData);
    //set first user in array as current user to display different icon
    origins.push(currentUserDecodeData[0]['location']);
    names.push(currentUserDecodeData[0]['name']);
    userLatLang.push([currentUserDecodeData[0]['latitude'], currentUserDecodeData[0]['longitude']]);
    otherUserDecodeData = JSON.parse(otherUserData);
    for (var i = 0; i < otherUserDecodeData.length; i++) {
        origins.push(otherUserDecodeData[i]['location']);
        names.push(otherUserDecodeData[i]['name']);
        userLatLang.push([otherUserDecodeData[i]['latitude'], otherUserDecodeData[i]['longitude']]);
    }
    var destination = {lat, lng};

    var destinationIcon = '/meeting-destination.png';
    var currentUserIcon = '/current-location.png';
    var otherUserIcon = '/other-user.png';

    var map = new google.maps.Map(document.getElementById('map-area-tracking'), {
        center: {lat: lat, lng: lng},
        zoom: 1
    });

    var trafficLayer = new google.maps.TrafficLayer();
    trafficLayer.setMap(map);
    var travelMode;
    travelMode = google.maps.DirectionsTravelMode[travelModeCustom];

    var geocoder = new google.maps.Geocoder;
    var service = new google.maps.DistanceMatrixService;
    service.getDistanceMatrix({
        origins: origins,
        destinations: [destination],
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
            deleteMarkers(markersArray);
            var showGeocodedAddressOnMap = function (icon, name, origin, routeColor, userLatLang, isDestination = 1) {
                return function (results, status) {
                    if (status === 'OK') {
                        var directionsService = new google.maps.DirectionsService();
                        var directionsRequest = {
                            origin: origin,
                            destination: destination,
                            travelMode: travelMode,
                            unitSystem: google.maps.UnitSystem.METRIC,
                            provideRouteAlternatives: true
                        };
                        directionsService.route(
                            directionsRequest,
                            function (response, status) {
                                if (status === google.maps.DirectionsStatus.OK) {
                                    new google.maps.DirectionsRenderer({
                                        map: map,
                                        directions: response,
                                        suppressMarkers: true,
                                        provideRouteAlternatives: true
                                    });
                                    var infowindow = new google.maps.InfoWindow();
                                    map.fitBounds(bounds.extend(results[0].geometry.location));

                                    distanceKm = '';
                                    if (isDestination) {
                                        var service = new google.maps.DistanceMatrixService;
                                        service.getDistanceMatrix({
                                            origins: [origin],
                                            destinations: [destination],
                                            travelMode: travelModeCustom,
                                            unitSystem: google.maps.UnitSystem.METRIC,
                                            avoidHighways: false,
                                            avoidTolls: false
                                        }, function (response, status) {
                                            if (status !== 'OK') {
                                                alert('Error was: ' + status);
                                            } else {
                                                var resultsDistance = response.rows[0].elements;
                                                var timeAndDistance = resultsDistance[0].distance.text + ' in ' + resultsDistance[0].duration.text;
                                                customMarker = new google.maps.Marker({
                                                    map: map,
                                                    position: results[0].geometry.location,
                                                    icon: icon
                                                });
                                                var originAddress = origin.split(" ");
                                                infowindow.setContent('<b>' + name.substring(0, 15) + '<br>' + originAddress[0] + '<br>' +
                                                    timeAndDistance + '</b>');
                                                infowindow.open(map, customMarker);
                                            }
                                        });
                                    } else {
                                        var destinationAddress = meeting_name.split(" ");
                                        customMarker = new google.maps.Marker({
                                            map: map,
                                            position: results[0].geometry.location,
                                            icon: icon
                                        });
                                        infowindow.setContent('<b>' + destinationAddress[0] + '</b>');
                                        infowindow.open(map, customMarker);
                                    }

                                }
                            }
                        );
                    } else {
                        alert('Geocode was not successful due to: ' + status);
                    }
                };
            };

            for (var i = 0; i < originList.length; i++) {
                icon = otherUserIcon;
                name = names[i];
                routeColor = 'default';
                //icon for current user
                if (i === 0) {
                    icon = currentUserIcon;
                    name = 'My Location';
                }
                var results = response.rows[i].elements;
                geocoder.geocode({'address': originList[i]},
                    showGeocodedAddressOnMap(icon, name, originList[i], routeColor, userLatLang[i]));
                for (var j = 0; j < results.length; j++) {
                    geocoder.geocode({'address': destinationList[j]},
                        showGeocodedAddressOnMap(destinationIcon, meeting_name, originList[j], routeColor, userLatLang[j], 0));
                }
            }
        }
    });
}

function deleteMarkers(markersArray) {
    for (var i = 0; i < markersArray.length; i++) {
        markersArray[i].setMap(null);
    }
    markersArray = [];
}