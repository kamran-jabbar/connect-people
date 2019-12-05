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
    currentUserDecodeData = JSON.parse(currentUserData);
    //set first user in array as current user to display different icon
    origins.push(currentUserDecodeData[0]['location']);
    names.push(currentUserDecodeData[0]['name']);
    otherUserDecodeData = JSON.parse(otherUserData);
    for (var i = 0; i < otherUserDecodeData.length ; i++){
        origins.push(otherUserDecodeData[i]['location']);
        names.push(otherUserDecodeData[i]['name']);
    }
    var destination = {lat, lng};

    var destinationIcon = '/meeting-destination.png';
    var currentUserIcon = '/current-location.png';
    var otherUserIcon = '/other-user.png';

    var map = new google.maps.Map(document.getElementById('map-area-tracking'), {
        center: {lat: lat, lng: lng}
    });
    var geocoder = new google.maps.Geocoder;
    var service = new google.maps.DistanceMatrixService;
    service.getDistanceMatrix({
        origins: origins,
        destinations: [destination],
        travelMode: 'WALKING',
        unitSystem: google.maps.UnitSystem.METRIC,
        avoidHighways: false,
        avoidTolls: false
    }, function(response, status) {
        if (status !== 'OK') {
            alert('Error was: ' + status);
        } else {
            var originList = response.originAddresses;
            var destinationList = response.destinationAddresses;
            deleteMarkers(markersArray);
            var showGeocodedAddressOnMap = function(icon, name) {
                return function(results, status) {
                    if (status === 'OK') {
                        var infowindow = new google.maps.InfoWindow();
                        map.fitBounds(bounds.extend(results[0].geometry.location));
                        customMarker = new google.maps.Marker({
                            map: map,
                            position: results[0].geometry.location,
                            icon: icon
                        });
                        markersArray.push(customMarker);
                        infowindow.setContent(name);
                        infowindow.open(map, customMarker);
                    } else {
                        alert('Geocode was not successful due to: ' + status);
                    }
                };
            };

            for (var i = 0; i < originList.length; i++) {
                icon = otherUserIcon;
                name = names[i];
                //icon for current user
                if(i === 0) {
                    icon = currentUserIcon;
                    name = 'My Location';
                }
                var results = response.rows[i].elements;
                geocoder.geocode({'address': originList[i]},
                    showGeocodedAddressOnMap(icon, name));
                for (var j = 0; j < results.length; j++) {
                    geocoder.geocode({'address': destinationList[j]},
                        showGeocodedAddressOnMap(destinationIcon, meeting_name));
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