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
    var origin1 = 'Autokoulu H.J. Hämäläinen Oy, Peltolankatu 5, 80220 Joensuu';
    var origin2 = 'Kauppakeskus Iso Myy';
    var origin3 = 'Linnunlahden päiväkoti';
    var destination = 'Joensuu Science Park Ltd., Länsikatu 15, 80110 Joensuu';

    var destinationIcon = '/meeting-destination.png';
    //var originIcon = '/current-location.png';
    var currentUserIcon = '/current-location.png';
    var otherUserIcon = '/other-user.png';

    var map = new google.maps.Map(document.getElementById('map-area-tracking'), {
        center: {lat: 62.6010, lng: 29.7636}
    });
    var geocoder = new google.maps.Geocoder;
    var service = new google.maps.DistanceMatrixService;
    service.getDistanceMatrix({
        origins: [origin1, origin2, origin3],
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
            var showGeocodedAddressOnMap = function(icon) {
                return function(results, status) {
                    if (status === 'OK') {
                        map.fitBounds(bounds.extend(results[0].geometry.location));
                        markersArray.push(new google.maps.Marker({
                            map: map,
                            position: results[0].geometry.location,
                            icon: icon
                        }));
                    } else {
                        alert('Geocode was not successful due to: ' + status);
                    }
                };
            };

            for (var i = 0; i < originList.length; i++) {
                icon = otherUserIcon;
                if(i === 0) {
                    icon = currentUserIcon;
                }
                var results = response.rows[i].elements;
                geocoder.geocode({'address': originList[i]},
                    showGeocodedAddressOnMap(icon));
                for (var j = 0; j < results.length; j++) {
                    geocoder.geocode({'address': destinationList[j]},
                        showGeocodedAddressOnMap(destinationIcon));
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