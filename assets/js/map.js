 jQuery(document).ready(function () {

   var input = document.getElementById('_job_location');

    var searchBox = new google.maps.places.SearchBox(input);
    var _long = '';
    var _lat = '';
    searchBox.addListener('places_changed', function () {
        var places = searchBox.getPlaces();
        if (places.length == 0) {
            return;
        }
        places.forEach(function (place) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }
            if (place.geometry.viewport) {
                _long = place.geometry.viewport.b.b;
                _lat = place.geometry.viewport.f.b;
            } else {
                _long = place.geometry.location.b.b;
                _lat = place.geometry.location.f.b;
            }
        })
        jQuery('#_service_lati').val(_lat);
        jQuery('#_service_langi').val(_long);
    })
})


