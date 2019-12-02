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
    var latlng = new google.maps.LatLng(lat, lng);
    navigator.geolocation.getCurrentPosition(function (position) {
        showPosition(position);
        var userLatLng = new google.maps.LatLng(62.5883,29.7708);
        map = new google.maps.Map(document.getElementById('map-area-tracking'), {
            center: userLatLng,
            zoom: 16,
            mapTypeId: google.maps.MapTypeId.ROADMAPS,
            label: {
                text: meeting_name,
                color: 'black',
                fontWeight: 'bold',
                fontSize: '20px'
            }
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
                        directions: response
                    });
                }
            }
        );

        /* marker = new google.maps.Marker({
         draggable: false,
         label: {
         text: meeting_name,
         color: 'black',
         fontWeight: 'bold',
         fontSize: '20px'
         },
         position: latlng,
         map: map,
         icon: {
         labelOrigin: new google.maps.Point(20, -10),
         url: '/icon.png'
         }
         });*/
        // do some more stuff with lat and long
    }, error);


}