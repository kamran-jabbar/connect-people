$(".meeting-table-row").click(function () {
    loadMap(62.5982406, 29.74364939999998);
    $('#meeting-popup').modal('show');
});

function loadMap(lat, lng) {
    document.getElementById("latitude").value = lat;
    document.getElementById("longitude").value = lng;
    latlng = new google.maps.LatLng(lat, lng);
    map = new google.maps.Map(document.getElementById('map-area'), {
        center: latlng,
        zoom: 12
    });
    marker = new google.maps.Marker({
        draggable: true,
        position: latlng,
        map: map,
        icon: '/meeting-destination.png'
    });

    var input = document.getElementById('search-location');
    var autocomplete = new google.maps.places.Autocomplete(input);
    var infowindow = new google.maps.InfoWindow();
    google.maps.event.addListener(autocomplete, 'place_changed', function (event) {
        var place = autocomplete.getPlace();
        map.setCenter(place.geometry.location);
        map.setZoom(16);
        marker.setPosition(place.geometry.location);
        infowindow.setContent('<b>' + place.name + '</b>');
        document.getElementById("latitude").value = place.geometry.location.lat();
        document.getElementById("longitude").value = place.geometry.location.lng();
        infowindow.open(map, marker);

    });
    google.maps.event.addListener(marker, 'dragend', function (event) {
        document.getElementById("latitude").value = event.latLng.lat();
        document.getElementById("longitude").value = event.latLng.lng();
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            "latLng": event.latLng
        }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var lat = results[0].geometry.location.lat(),
                    lng = results[0].geometry.location.lng(),
                    placeName = results[0].address_components[0].long_name,
                    latlng = new google.maps.LatLng(lat, lng);
                infowindow.setContent('<b>' + results[0].formatted_address + '</b>');
                document.getElementById("search-location").value = results[0].formatted_address;
                infowindow.open(map, marker);

            }
        });
    });
};