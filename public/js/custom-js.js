/**
 * @param lat
 * @param lng
 * @param meeting_type
 * @param meeting_time
 * @param id
 * @param location
 */
function openMeetingDetailPopup(lat, lng, meeting_type, meeting_time, id, location) {
    loadMap(lat, lng, location, meeting_time);
    $('.modal-title').text(meeting_type);
    $('.meeting-time').text(meeting_time);
    $('.delete-meeting').attr('href', '/delete-meeting/' + id);
    $('.edit-meeting').attr('href', '/edit-meeting/' + id);
    $(".track-meeting").attr("id", id);
    $('.track-friends').attr('id', id);
    $('#meeting-popup').modal('show');

}

$('.track-meeting').click(function (e) {
    e.preventDefault();
    var id = $(this).attr('id');
    $('.track-meeting-walk').attr('href', '/track-meeting/' + id + '?mode=walking');
    $('.track-meeting-bike').attr('href', '/track-meeting/' + id + '?mode=bicycling');
    $('.track-meeting-driving').attr('href', '/track-meeting/' + id + '?mode=driving');
    $('#travelModeChoice').modal('show');
});

$('.track-friends').click(function (e) {
    e.preventDefault();
    var id = $(this).attr('id');
    $('.track-meeting-walk').attr('href', '/track-friends/' + id + '?mode=walking');
    $('.track-meeting-bike').attr('href', '/track-friends/' + id + '?mode=bicycling');
    $('.track-meeting-driving').attr('href', '/track-friends/' + id + '?mode=driving');
    $('#travelModeChoice').modal('show');
});

/**
 * @param lat
 * @param lng
 * @param meeting_name
 * @param meeting_time
 */
function loadMap(lat, lng, meeting_name, meeting_time) {
    var latlng = new google.maps.LatLng(lat, lng);
    map = new google.maps.Map(document.getElementById('map-area-modal'), {
        center: latlng,
        zoom: 16
    });
    var infowindow = new google.maps.InfoWindow();
    marker = new google.maps.Marker({
        draggable: false,
        position: latlng,
        map: map,
        icon: {
            labelOrigin: new google.maps.Point(30, -10),
            url: '/meeting-destination.png'
        }
    });
    infowindow.setContent('<img id="meeting-image" src="/meeting-picture.png"><br><b>' + meeting_name + '</b>' + '<br><b>' + meeting_time + '</b>');
    infowindow.open(map, marker);
}