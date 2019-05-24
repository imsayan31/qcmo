<?php
/*
 * This is home search section
 * 
 */
$GeneralThemeObject = new GeneralTheme();
$getAllCities = $GeneralThemeObject->getBrazilCities();
$selectedState = NULL;
$selectedCity = NULL;
$getProductKeyword = NULL;
if (isset($_COOKIE['andre_anonymous_state']) && $_COOKIE['andre_anonymous_state'] != '') {
    $selectedState = $_COOKIE['andre_anonymous_state'];
}
if (isset($_COOKIE['andre_anonymous_city']) && $_COOKIE['andre_anonymous_city'] != '') {
    $selectedCity = $_COOKIE['andre_anonymous_city'];
}
if (isset($_GET['product_keyword']) && $_GET['product_keyword'] != '') {
    $getProductKeyword = $_GET['product_keyword'];
}
$getStates = $GeneralThemeObject->getCities();
$getCities = $GeneralThemeObject->getCities($selectedState);
?>

<div class="header-search">
    <div class="row">

        <!-- Global Settings State & City -->
        <div class="col-sm-3 col-xs-6 select-color" style="padding-right: 0;">
            <select name="home_search_state" class="state-city-selection home-state-search chosen">
                <option value=""><?php _e('Selecione seu estado', THEME_TEXTDOMAIN); ?></option>
                <?php
                if (is_array($getStates) && count($getStates) > 0) :
                    foreach ($getStates as $eachCity) :
                        ?>
                        <option value="<?php echo $eachCity->term_id; ?>" <?php echo ($selectedState == $eachCity->term_id) ? 'selected' : ''; ?>><?php echo $eachCity->name; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>
        <div class="col-sm-3 col-xs-6 select-color" style="padding-left: 0;">
            <select name="home_search_city" class="select-your-city home-city-search chosen">
                <option value=""><?php _e('Selecione sua cidade', THEME_TEXTDOMAIN); ?></option>
                <?php
                if (is_array($getCities) && count($getCities) > 0) :
                    foreach ($getCities as $eachCity) :
                        ?>
                        <option value="<?php echo $eachCity->term_id; ?>" <?php echo ($selectedCity == $eachCity->term_id) ? 'selected' : ''; ?>><?php echo $eachCity->name; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>
        <!-- End of Global Settings State & City -->

        <!-- Product Keyword Search -->
        <form name="home_page_search_frm" id="home_page_search_frm" action="<?php echo (is_tax()) ? get_term_link(get_queried_object()) : ALL_PRODUCTS_PAGE; ?>" method="get">
            <input type="hidden" name="sorting_param" id="sorting_param" value="">
            <input type="hidden" name="product_feature" id="product_feature" value="<?php echo (isset($_GET['product_feature']) && $_GET['product_feature'] != '') ? $_GET['product_feature'] : ''; ?>">
            <div class="col-sm-6 col-xs-12" style="padding-left: 0;">
                <div class="input-group search-cs">
                    <input type="text" class="form-control input-lg" name="product_keyword" autocomplete="off" placeholder="<?php _e('Buscar produtos por nome, descrição...', THEME_TEXTDOMAIN); ?>" value="<?php echo $getProductKeyword; ?>"/>
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </span>
                </div>
            </div>
        </form>
        <!-- End of Product Keyword Search -->
    </div>
</div>
<?php
