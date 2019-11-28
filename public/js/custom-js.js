$(document).ready(function () {
    /*$(function () {
        $('.meeting-time').datetimepicker({
            format: 'DD.MM.YYYY HH:mm',
            minDate: new Date()
        });
    });*/


});
$(".meeting-table-row").click(function () {
    loadMap();
    $('#meeting-popup').modal('show');
});
function loadMap() {
    var lat = 62.5982406,
        lng = 29.74364939999998,
        latlng = new google.maps.LatLng(lat, lng);
    map = new google.maps.Map(document.getElementById('map-area'), {
        center: latlng,
        zoom: 12
    });
    marker = new google.maps.Marker({
        draggable: true,
        position: latlng,
        map: map,
        icon: '/icon.png'
})
    ;
    var input = document.getElementById('search-location');
    var autocomplete = new google.maps.places.Autocomplete(input);
    var infowindow = new google.maps.InfoWindow();
    google.maps.event.addListener(autocomplete, 'place_changed', function (event) {
        infowindow.close();
        var place = autocomplete.getPlace();
        map.setCenter(place.geometry.location);
        map.setZoom(16);
        marker.setPosition(place.geometry.location);
        infowindow.setContent(place.name);
        // document.getElementById("latitude-val").innerHTML = 'latitude: <b>' + place.geometry.location.lat() + '</b>';
        // document.getElementById("longitude-val").innerHTML = 'longitude: <b>' + place.geometry.location.lng()+ '</b>';
    });
    google.maps.event.addListener(marker, 'dragend', function (event) {
        // document.getElementById("latitude-val").innerHTML = 'latitude: <b>' + event.latLng.lat() + '</b>';
        // document.getElementById("longitude-val").innerHTML = 'longitude: <b>' + event.latLng.lng() + '</b>';
        infowindow.close();
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            "latLng": event.latLng
        }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var lat = results[0].geometry.location.lat(),
                    lng = results[0].geometry.location.lng(),
                    placeName = results[0].address_components[0].long_name,
                    latlng = new google.maps.LatLng(lat, lng);
                document.getElementById("search-location").value = results[0].formatted_address;
            }
        });
    });
};