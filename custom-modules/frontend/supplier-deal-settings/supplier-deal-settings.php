<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$GeneralThemeObject = new GeneralTheme();
$userDetails = $GeneralThemeObject->user_details();
?>
<div class="right">
    <form name="supplierDealSettings" id="supplierDealSettings" action="javascript:void(0);" method="post">
        <input type="hidden" name="action" value="supplier_deal_settings"/>
        <div class="row">
            <div class="col-sm-12 form-group">
                <label for="supplier_deal_min_val"><?php _e('Minimum Deal Amount To Receive(in R$): ', THEME_TEXTDOMAIN); ?></label>
                <div class="form-group">
                    <input type="text" class="form-control" id="supplier_deal_min_val_set" onkeyup="changeSliderRange(this)" name="supplier_deal_min_val_set" value="<?php echo ($userDetails->data['minimum_deal_amount_set']) ? $userDetails->data['minimum_deal_amount_set'] : ''; ?>" placeholder="<?php _e('Set minimum value*', THEME_TEXTDOMAIN); ?>">
                    <div class="input-error-msg"></div>
                </div>
                <input type="text" id="supplier_deal_min_val" name="supplier_deal_min_val" readonly style="border:0; color:#f6931f; font-weight:bold;">
                <div id="slider-range-min"></div>
                <span class="min-slide-val"><?php echo ($userDetails->data['minimum_deal_amount_set']) ? 'R$ ' . $userDetails->data['minimum_deal_amount_set'] : 'R$ 10'; ?></span>
                <span class="max-slide-val"><?php echo ($userDetails->data['minimum_deal_amount_set']) ? 'R$ ' . $userDetails->data['minimum_deal_amount_set'] * 10 : 'R$ 20'; ?></span>
            </div>
        </div>
        <label><?php _e('Minimum Requirement To Receive Deals: ', THEME_TEXTDOMAIN); ?></label>
        <div class="row">
            <div class="col-sm-4">
                <label class="control control--checkbox" for="user_cmplt_addrs">
                    <input class="form-control supplier-type" id="user_cmplt_addrs" type="checkbox" name="user_cmplt_addrs" <?php echo ($userDetails->data['check_user_address'] == 1) ? 'checked' : ''; ?> value="1"/>
                    <span></span>
                    <?php _e('Receive deals from users having complete address', THEME_TEXTDOMAIN); ?>
                    <div class="control__indicator"></div>
                </label>
            </div>
            <div class="col-sm-4">
                <label class="control control--checkbox" for="user_com_mob">
                    <input class="form-control supplier-type" id="user_com_mob" type="checkbox" name="user_com_mob" <?php echo ($userDetails->data['check_user_contact_no'] == 1) ? 'checked' : ''; ?> value="1"/>
                    <span></span>
                    <?php _e('Receive deals from users having commercial or mobile phone', THEME_TEXTDOMAIN); ?>
                    <div class="control__indicator"></div>
                </label>
            </div>
            <div class="col-sm-4">
                <label class="control control--checkbox" for="user_cpf_cnpj">
                    <input class="form-control supplier-type" id="user_cpf_cnpj" type="checkbox" name="user_cpf_cnpj" <?php echo ($userDetails->data['check_user_cpf_cnpj'] == 1) ? 'checked' : ''; ?> value="1"/>
                    <span></span>
                    <?php _e('Receive deals from users having CPF or CNPJ', THEME_TEXTDOMAIN); ?>
                    <div class="control__indicator"></div>
                </label>
            </div>
        </div>
        <div class="form-group text-center">
            <button type="submit" class="btn btn-cs ladda-button btn-lg" data-style="expand-right" name="supplierDealSettingsSbmt" id="supplierDealSettingsSbmt"><?php _e('Save', THEME_TEXTDOMAIN); ?></button>
        </div>
    </form>
</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {

        var min = <?php echo ($userDetails->data['minimum_deal_amount_set']) ? $userDetails->data['minimum_deal_amount_set'] : '10'; ?>;
        var max = <?php echo ($userDetails->data['minimum_deal_amount_set']) ? ($userDetails->data['minimum_deal_amount_set'] * 10) : '100'; ?>;
        initSlider(min, max);

        /* Slider range */
        /*$("#slider-range-min").slider({
         range: "min",
         value: <?php echo ($userDetails->data['minimum_deal_amount']) ? $userDetails->data['minimum_deal_amount'] : '100'; ?>,
         min: 1,
         max: 700,
         slide: function (event, ui) {
         $("#supplier_deal_min_val").val(ui.value);
         }
         });*/
    });

    initSlider = function (min, max) {
        jQuery("#slider-range-min").slider({
            animate: "fast",
            classes: {
                "ui-slider": "highlight"
            },
            max: max,
            min: min,
            range: false,
            value: <?php echo ($userDetails->data['minimum_deal_amount']) ? $userDetails->data['minimum_deal_amount'] : '100'; ?>,
            slide: function (event, ui) {
                jQuery("#supplier_deal_min_val").val(ui.value);
            }
        });
        jQuery("#supplier_deal_min_val").val(jQuery("#slider-range-min").slider("value"));
        /*console.log($("#slider").slider("value"));
         console.log("Min Value:" + min);
         console.log("Max Value:" + max);*/
        jQuery('.min-slide-val').text('R$ ' + min);
        jQuery('.max-slide-val').text('R$ ' + max);
    };

    changeSliderRange = function (event) {
        var minRange = parseInt(jQuery("#" + event.id).val());
        var maxRange = minRange * 10;
        initSlider(minRange, maxRange);
    };
</script>
    <?php
