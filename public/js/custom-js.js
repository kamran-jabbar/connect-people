/**
 * @param lat
 * @param lng
 * @param meeting_type
 * @param meeting_time
 * @param id
 */
function openMeetingDetailPopup(lat, lng, meeting_type, meeting_time, id, location) {
    loadMap(lat, lng, location);
    $('.modal-title').text(meeting_type);
    $('.meeting-time').text(meeting_time);
    $('.delete-meeting').attr('href', '/delete-meeting/' + id);
    $('.edit-meeting').attr('href', '/edit-meeting/' + id);
    $('#meeting-popup').modal('show');

}

/**
 * @param lat
 * @param lng
 * @param meeting_name
 */
function loadMap(lat, lng, meeting_name) {
    var latlng = new google.maps.LatLng(lat, lng);
    map = new google.maps.Map(document.getElementById('map-area'), {
        center: latlng,
        zoom: 16
    });
    marker = new google.maps.Marker({
        draggable: false,
        label: {text: meeting_name},
        position: latlng,
        map: map,
        icon: '/icon.png'
    });
}