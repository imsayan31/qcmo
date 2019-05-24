<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$GeneralThemeObject = new GeneralTheme();
$userCity = $GeneralThemeObject->getLandingCity();
if (isset($_GET['supplier_location']) && $_GET['supplier_location'] != ''):
    $supplierAddress = $_GET['supplier_location'];
endif;
if (isset($_GET['supplier_lists']) && $_GET['supplier_lists'] != ''):
    $supplierLists = $_GET['supplier_lists'];
endif;

$getSupplierMetaArgs[] = [
    'key' => '_allow_where_to_buy',
    'value' => 1,
    'compare' => '='
];
/*$getSupplierMetaArgs[] = [
    'key' => '_active_status',
    'value' => 1,
    'compare' => '='
];*/

if($supplierAddress){
    $getSupplierMetaArgs['relation'] = 'AND';
    $getSupplierMetaArgs[] = [
      'key' => '_city',
      'value' => $userCity,
      'compare' => '='
      ];
    $getSupplierMetaArgs[] = [
        'key' => '_user_address',
        'value' => $supplierAddress,
        'compare' => 'LIKE'
    ];
}

if($supplierLists){
    $getSupplierMetaArgs['relation'] = 'AND';
    $getSupplierMetaArgs[] = [
      'key' => '_city',
      'value' => $userCity,
      'compare' => '='
      ];
}

$getSupplierArgs = ['role' => 'supplier'];
if(is_array($getSupplierMetaArgs) && count($getSupplierMetaArgs) > 0){
    $getSupplierArgs['meta_query'] = $getSupplierMetaArgs;
}
/*echo "<pre>";
print_r($getSupplierArgs);
echo "</pre>";*/
$getSupplierForMaps = $GeneralThemeObject->getSupplierForMap($getSupplierArgs);
$infoWindowContent = NULL;
$infoWindowContentArr = [];

if (is_array($getSupplierForMaps) && count($getSupplierForMaps) > 0) {
    $infoContent = 0;
    foreach ($getSupplierForMaps as $eachSupplierMap) {
        $getUserDetails = $GeneralThemeObject->user_details($eachSupplierMap['user_id']);
        $supplierPlan = $GeneralThemeObject->getMembershipPlanDetails($getUserDetails->data['selected_plan']);
        if ($supplierPlan->data['name'] == 'gold'):
            $imgClass = 'gold-class';
        elseif ($supplierPlan->data['name'] == 'silver'):
            $imgClass = 'silver-class';
        else:
            $imgClass = 'bronze-class';
        endif;
        $infoWindowContent = NULL;
        $infoWindowContent .= '<div class="media">';
        //$infoWindowContent .= '<div class="media-left"><a href="' . $eachSupplierMap['where_to_buy'] . '" target="_blank"><img class="'. $imgClass .'" src="' . $eachSupplierMap['thumbnail'] . '" style="width: 100px; height: 100px;"></a></div>';
        $infoWindowContent .= '<div class="media-left"><a href="' . get_author_posts_url($eachSupplierMap['user_id']) . '" target="_blank"><img class="' . $imgClass . '" src="' . $eachSupplierMap['thumbnail'] . '" style="width: 100px; height: 100px;"></a></div>';
        $infoWindowContent .= '<div class="media-body">';
        //$infoWindowContent .= '<div class="supp-title"><a href="' . $eachSupplierMap['where_to_buy'] . '" target="_blank">' . $eachSupplierMap['cname'] . '</a></div>';
        $infoWindowContent .= '<div class="supp-title"><a href="' . get_author_posts_url($eachSupplierMap['user_id']) . '" target="_blank">' . $eachSupplierMap['cname'] . '</a></div>';
        //$infoWindowContent .= '<div class="supp-title"><a href="' . $eachSupplierMap['where_to_buy'] . '" target="_blank">' . $eachSupplierMap['lname'] . '</a></div>';
        $infoWindowContent .= '<div class="supp-rating">' . $eachSupplierMap['rating'] . '</div>';
        $infoWindowContent .= '<div class="supp-addr">' . $eachSupplierMap['phone'] . '</div>';
        $infoWindowContent .= '<div class="supp-addr">' . $eachSupplierMap['address'] . '</div>';
        $infoWindowContent .= '</div>';
        $infoWindowContent .= '</div></div>';
        $infoWindowContentArr[$infoContent]['content'] = $infoWindowContent;
        $infoContent++;
    }
} else {
    $getNoSupplierForMaps = [];
    $getUserEnteredLatLng = explode(',', $_GET['supplier_location_loc']);
    $getNoSupplierForMaps[0]['lat'] = $getUserEnteredLatLng[0];
    $getNoSupplierForMaps[0]['lng'] = $getUserEnteredLatLng[1];
}
?>

<script type="text/javascript">
    function initSupplierMap(){
        var markers = <?php echo (is_array($getSupplierForMaps) && count($getSupplierForMaps) > 0) ? json_encode($getSupplierForMaps) : json_encode($getNoSupplierForMaps); ?>;
        var infoWindowContent = <?php echo json_encode($infoWindowContentArr); ?>;
        //initialionne(markers, infoWindowContent, 3);
        initMapOnSupplierListing(markers, infoWindowContent, 3);
    }
    

    function initMapOnSupplierListing(markers, infoWindowContent, zoomVal) {
        
        //eu que desativei em 05-11-8 

        var halfLatLng = (markers.length/2);
        var roundedLatLng = Math.round(halfLatLng);

        /*var centerLat = parseInt(markers[10]['lat']);
        var centerLng = parseInt(markers[10]['lng']);*/
        var centerLat = markers[roundedLatLng]['lat'];
        var centerLng = markers[roundedLatLng]['lng']; 
        
        /*var centerLat = '-13';
        var centerLng = '-53';*/

        var map = new google.maps.Map(document.getElementById('map_canvas'), {
            zoom: <?php echo ($supplierLists || $supplierAddress) ? '12' : '3'; ?>,
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
        
        /*var input = document.getElementById('supplier_location');
        var autocomplete = new google.maps.places.Autocomplete(input);
        //autocomplete.bindTo('bounds', map);
        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            var place_id = place.place_id;
            var lat = place.geometry.viewport.f.f;
            var lng = place.geometry.viewport.b.b;
            jQuery('#supplier_location_id').val(place_id);
            jQuery('#supplier_location_loc').val(lat + ',' + lng);
        });*/
    }

  
</script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        /* Supplier Search */
        $('#supplierSearchFrm').on('submit', function () {
            var data = $(this).serialize();
            var l = Ladda.create(document.getElementById('supplierSearch'));
            l.start();
            $.post(SupplierSearch.ajaxurl, data, function (resp) {
                if (resp.flag == true) {
                    $('.got-error').hide();
                    $('.got-result').show();
                    $('.got-result').text(resp.countSupplier);
                    initMapOnSupplierListing(resp.marker, resp.infoWindowContent, 5);
                    //$.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                } else {
                    $('.got-result').hide();
                    $('.got-error').show();
                    $('.got-error').text(resp.msg);
                    initMapOnSupplierListing(resp.marker, resp.infoWindowContent, 5);
                    //$.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                }
            }, 'json').always(function () {
                l.stop();
            });
        });
    });
</script>
<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDnO0V9m09BUXB-JqwuuFno7efIs4FD5nM&libraries=places&callback=initSupplierMap" async defer></script>-->

<style type="text/css">
    #map_wrapper {
        height: 400px;
    }

    #map_canvas {
        width: 100%;
        height: 100%;
    }
</style>
<div class="alert alert-success got-result" style="<?php echo (is_array($getSupplierForMaps) && count($getSupplierForMaps) > 0) ? 'display:block;' : 'display:none;'; ?>"><?php 
if (count($getSupplierForMaps) == 1) {
    
    _e('Total de ' . count($getSupplierForMaps) . ' fornecedor encontrado.', THEME_TEXTDOMAIN); 

}
if (count($getSupplierForMaps) > 1) {
    
    _e('Total de ' . count($getSupplierForMaps) . ' fornecedores encontrados.', THEME_TEXTDOMAIN); 

}


?></div>
<div class="alert alert-danger got-error" style="<?php echo (is_array($getSupplierForMaps) && count($getSupplierForMaps) > 0) ? 'display:none;' : 'display:block;'; ?>"><?php _e('Sorry, no supplier found according to your search.', THEME_TEXTDOMAIN); ?></div>

<div id="map_wrapper">
    <div id="map_canvas" class="mapping"></div>
</div>



<?php
