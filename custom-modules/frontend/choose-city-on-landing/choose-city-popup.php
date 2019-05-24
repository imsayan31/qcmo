<?php
/*
 * Choose City Popup
 * 
 */
$GeneralThemeObject = new GeneralTheme();
$getAllCities = $GeneralThemeObject->getBrazilCities();
$getStates = $GeneralThemeObject->getCities();
$custom_logo_id = get_theme_mod('custom_logo');
$image = wp_get_attachment_image_src($custom_logo_id, 'full');
?>

<div id="choose_city_modal" role="dialog" class="modal fade modal-cs" aria-hidden="true">
    <div class="modal-dialog">
        <form name="select_city_frm" id="select_city_frm" action="javascript:void(0);" method="post">
            <input type="hidden" name="action" value="landing_city_selection">
            <div class="modal-content">
                <div class="modal-header">
                   
                    <h2><?php _e('Obtenha os preços da sua cidade', THEME_TEXTDOMAIN); ?></h2>
                </div>
                <div class="modal-body">
                    <div class="modal-logo">
                        <img src="<?php echo $image[0]; ?>" >
                    </div>
                    <div class="form-group">
                        <select name="select_your_state" class="state-city-selection chosen" style="font-size: 16px;">
                            <option value=""><?php _e('Selecione o estado', THEME_TEXTDOMAIN); ?></option>
                            <?php
                            if (is_array($getStates) && count($getStates) > 0) :
                                foreach ($getStates as $eachCountry) :
                                    ?>
                                    <option value="<?php echo $eachCountry->term_id; ?>"><?php echo $eachCountry->name; ?></option>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="select_your_city" class="select-your-city chosen" style="font-size: 16px;">
                            <option value=""><?php _e('Selecione a cidade', THEME_TEXTDOMAIN); ?></option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" data-style="expand-right" class="btn btn-lg btn-cs ladda-button sub-btn" name="select_city_button" id="select_city_button"><?php _e('Buscar produtos', THEME_TEXTDOMAIN); ?></button>
                </div>
            </div>
        </form>
    </div>    
</div>
<?php
