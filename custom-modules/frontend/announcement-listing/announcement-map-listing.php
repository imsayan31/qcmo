<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$GeneralThemeObject = new GeneralTheme();
$AnnouncementObject = new classAnnouncement();
$getSupplierForMapsArgs = ['post_type' => themeFramework::$theme_prefix . 'announcement', 'posts_per_page' => -1];
$getSupplierForMaps = $AnnouncementObject->getAnnouncementForMap($getSupplierForMapsArgs);
$infoWindowContent = NULL;
$prevAuthor = NULL;
$saveAuthor = NULL;
$infoWindowContentArr = [];

if (is_array($getSupplierForMaps) && count($getSupplierForMaps) > 0) {
    $infoContent = 0;
    foreach ($getSupplierForMaps as $eachSupplierMap) {
        $saveAuthor = $eachSupplierMap['author'];
        if ($prevAuthor && $prevAuthor != $saveAuthor) {
            $infoWindowContent = NULL;
        }
        $infoWindowContent .= '<div class="media">';
        $infoWindowContent .= '<div class="media-left"><a href="' . get_permalink($eachSupplierMap['announcement_id']) . '"><img src="' . $eachSupplierMap['thumbnail'] . '" style="width:100px;height:100px;"></a></div>';
        $infoWindowContent .= '<div class="media-body">';
        $infoWindowContent .= '<div class="supp-title"><a href="' . get_permalink($eachSupplierMap['announcement_id']) . '">' . $eachSupplierMap['name'] . '</a></div>';
        $infoWindowContent .= '<div class="supp-addr">' . $eachSupplierMap['address'] . '</div>';
        $infoWindowContent .= '</div></div>';
        $infoWindowContentArr[$infoContent]['content'] = $infoWindowContent;
        $prevAuthor = $eachSupplierMap['author'];
        $infoContent++;
    }
}

?>

<script type="text/javascript">
    function initSupplierMap() {
        var markers = <?php echo (is_array($getSupplierForMaps) && count($getSupplierForMaps) > 0) ? json_encode($getSupplierForMaps) : json_encode($getNoSupplierForMaps); ?>;
        var infoWindowContent = <?php echo json_encode($infoWindowContentArr); ?>;
        //initialize(markers, infoWindowContent, 3);
        initMapOnSupplierListing(markers, infoWindowContent, 3);
    }

    function initMapOnSupplierListing(markers, infoWindowContent, zoomVal) {
        /*var locations = [
         ['Bondi Beach', -33.890542, 151.274856, 4],
         ['Coogee Beach', -33.923036, 151.259052, 5],
         ['Cronulla Beach', -34.028249, 151.157507, 3],
         ['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
         ['Maroubra Beach', -33.950198, 151.259302, 1]
         ];*/

        //var centerLat = parseInt(markers[0]['lat']);
        //var centerLat = -22.4563555;
        //var centerLng = -45.016007400000035;
        var centerLat = -12;
        var centerLng = -53;
        //var centerLng = parseInt(markers[0]['lng']);

        var map = new google.maps.Map(document.getElementById('announcement_map_canvas'), {
            zoom: zoomVal,
            center: new google.maps.LatLng(centerLat, centerLng),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();
        var marker, i;

        for (i = 0; i < markers.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(markers[i]['lat'], markers[i]['lng']),
                map: map,
                icon: markers[i]['marker'],
                title: markers[i]['name']
            });

            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    infowindow.setContent(infoWindowContent[i]['content']);
                    infowindow.open(map, marker);
                };
            })(marker, i));
        }

        var input = document.getElementById('supplier_location');
        var autocomplete = new google.maps.places.Autocomplete(input);
        //autocomplete.bindTo('bounds', map);
        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            var place_id = place.place_id;
            var lat = place.geometry.viewport.f.f;
            var lng = place.geometry.viewport.b.b;
            jQuery('#supplier_location_id').val(place_id);
            jQuery('#supplier_location_loc').val(lat + ',' + lng);
        });
    }



    //google.maps.event.addDomListener(window, 'load', initialize);
</script>
<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDnO0V9m09BUXB-JqwuuFno7efIs4FD5nM&libraries=places&sensor=false&callback=initSupplierMap" async defer></script>-->
<style type="text/css">
    #announcement_map_wrapper {
        height: 400px;
    }

    #announcement_map_canvas {
        width: 100%;
        height: 100%;
    }
</style>
<div class="section-heading"><?php _e('ANNOUNCES MAP', THEME_TEXTDOMAIN); ?></div>
<div id="announcement_map_wrapper">
    <div id="announcement_map_canvas" class="mapping"></div>
</div>
<?php
