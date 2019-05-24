<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists('product_export_import_func')) {

    function product_export_import_func() {
        $GeneralThemeObject = new GeneralTheme();
        $getSingleProducts = get_posts(['post_type' => themeFramework::$theme_prefix . 'product', 'posts_per_page' => -1
            ,
            'meta_query' => [
                [
                    'key' => '_simple_product',
                    'value' => '',
                    'compare' => '!='
                ]
            ]
//            , 
//            'meta_query' => [
//                [
//                    'key' => '_simple_product',
//                    'value' => serialize(strval(1)),
//                    'compare' => 'LIKE'
//                ]
//        ]
        ]);
        
        /* Export CSV */
        if (isset($_POST['export_settings_sbmt'])) {

            $csv_list = array();
            $csv_list[] = array('Name', 'Slug' ,'Default Price', 'Cities', 'Prices');
            if (is_array($getSingleProducts) && count($getSingleProducts) > 0) {
                foreach ($getSingleProducts as $eachProduct) {
                    $productCity = [];
                    $productPrice = [];
                    $productDetails = $GeneralThemeObject->product_details($eachProduct->ID);
                    $getProductCities = get_post_meta($eachProduct->ID, '_product_cities', true);
                    $getProductCitiesPrices = get_post_meta($eachProduct->ID, '_product_cities_prices', true);
                    $productTitle = $productDetails->data['title'];
                    if (is_array($getProductCities) && count($getProductCities) > 0) {
                        foreach ($getProductCities as $eachProductCity) {
                            
                            $getCityBy = get_term_by('id', $eachProductCity, themeFramework::$theme_prefix . 'product_city');
                            
                            $productCity[] = $getCityBy->name; //mb_convert_encoding($getCityBy->name, 'UTF-16LE', 'UTF-8');
                        }
                        $joinedProductCity = join(', ', $productCity);
                    }
                    if (is_array($getProductCitiesPrices) && count($getProductCitiesPrices) > 0) {
                        foreach ($getProductCitiesPrices as $eachProductCityPrice) {
                            $productPrice[] = $eachProductCityPrice;
                        }
                        $joinedProductCityPrices = join(', ', $productPrice);
                    } else if (is_array($getProductCities) && count($getProductCities) > 0) {
                            foreach ($getProductCities as $eachProductCity) {
                                $productPrice[] = $productDetails->data['default_price'];
                            }
                            $joinedProductCityPrices = join(', ', $productPrice);
                    }
                    //$csv_list[] = [$productTitle, $productDetails->data['default_price'], $joinedProductCity, $joinedProductCityPrices];
                    $csv_list[] = [mb_convert_encoding($productTitle, 'UTF-16LE', 'UTF-8'), $eachProduct->post_name, $productDetails->data['default_price'], $joinedProductCity, $joinedProductCityPrices];
                    //$csv_list[] = [htmlspecialchars($productDetails->data['title'], ENT_QUOTES, 'UTF-8'), $productDetails->data['default_price'], $joinedProductCity, $joinedProductCityPrices];
                }
            }
            $GeneralThemeObject->outputCsv('QCMO Single Products.csv', $csv_list);
            exit;
        }

        /* Import CSV */
        if (isset($_POST['import_settings_sbmt'])) {
            $upload_csv_data = $_FILES['upload_csv_data']["tmp_name"];

            if ($_FILES["upload_csv_data"]["size"] > 0) {
                $file = fopen($upload_csv_data, "r");
                $i = 0;
                while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
                    if ($i > 0) {

                        /* Product Import in New Way */
                        $productName = $getData[0];
                        $productDefaultPrice = $getData[1];
                        $productCities = $getData[2];
                        $productCityPrices = $getData[3];
                        $productSanitizeName = sanitize_title_with_dashes($productName);

                        if (!empty($productCities)) {
                            $explodedCities = explode(', ', $productCities);
                        }
                        if (!empty($productCityPrices)) {
                            $explodedCityPrices = explode(', ', $productCityPrices);
                        }
                        if($productSlug == '') {
                            $productSlug = 'cannotbefoundsluginfutureorprad';
                        }

                        $getOnlyCities = $GeneralThemeObject->getOnlyCities();

                                echo "<pre>";
                                print_r(count($getOnlyCities));
                                echo "</pre>";


                        // $getProduct = new WP_Query(['post_type' => themeFramework::$theme_prefix . 'product', 'name' => $productSlug, 'posts_per_page' => -1]);
                        $getProduct = new WP_Query(['post_type' => themeFramework::$theme_prefix . 'product', 'name' => $productSanitizeName, 'posts_per_page' => -1]);
                      
                        if ($getProduct->have_posts()) {
                            
                            while ($getProduct->have_posts()) {
                                $getProduct->the_post();

                                /* Update existing product data */
                                $productUpdatedData = [
                                    'ID' => get_the_ID(),
                                    'post_title' => $productName
                                ];
                                wp_update_post($productUpdatedData);

                                /* Updating Meta Fields */
                                update_post_meta(get_the_ID(), '_product_price', $productDefaultPrice);
                                update_post_meta(get_the_ID(), '_simple_product', [1]);

                                $getOnlyCities = $GeneralThemeObject->getOnlyCities();

                                if(count($getOnlyCities) == count($explodedCities)){
                                    update_post_meta(get_the_ID(), '_product_all_cities', 1);
                                }

                                /* Assigning Cities & Prices */
                                if (is_array($explodedCities) && count($explodedCities) > 0) {
                                     
                                    $exlodedCityIDs = [];
                                    $j = 0;
                                    foreach ($explodedCities as $val) {
                                        $city_wise_product_price = (trim($explodedCityPrices[$j]) == '0') ? $productDefaultPrice : trim($explodedCityPrices[$j]);
                                        $val = trim($val);
                                                                                
                                    $getCityDetails = get_term_by('slug', $val, themeFramework::$theme_prefix . 'product_city', ARRAY_A);
                                            
                                       
                                        if (is_array($getCityDetails) && count($getCityDetails) > 0) {
                                            $exlodedCityIDs[] = $getCityDetails['term_id'];
                                            $cityId = $getCityDetails['term_id'];
                                        } else {
                                            
                                            $insertTermCity = wp_insert_term(ucwords($val), themeFramework::$theme_prefix . 'product_city');
                                            $exlodedCityIDs[] = $insertTermCity['term_id'];
                                            $cityId = $insertTermCity['term_id'];
                                        }
                                        
                                        update_post_meta(get_the_ID(), '_product_city_price_'.$cityId, $city_wise_product_price);
                                        $j++;
                                    }
                                }
                                update_post_meta(get_the_ID(), '_product_cities', $exlodedCityIDs);
                                update_post_meta(get_the_ID(), '_product_cities_prices', $explodedCityPrices);
                               
                            }
                        } else {

                            /* Insert new product data */
                            $productInsertedData = [
                                'post_type' => themeFramework::$theme_prefix . 'product',
                                'post_title' => $productName,
                                'post_status' => 'publish'
                            ];
                            $insertedProductID = wp_insert_post($productInsertedData);

                            /* Updating Meta Fields */
                            update_post_meta($insertedProductID, '_product_price', $productDefaultPrice);
                            update_post_meta($insertedProductID, '_simple_product', [1]);

                            $getOnlyCities = $GeneralThemeObject->getOnlyCities();

                            if(count($getOnlyCities) == count($explodedCities)){
                                update_post_meta($insertedProductID, '_product_all_cities', 1);
                            }

                            /* Assigning Cities & Prices */
                            if (is_array($explodedCities) && count($explodedCities) > 0) {
                                $exlodedCityIDs = [];
                                $j = 0;
                                foreach ($explodedCities as $val) {
                                    $city_wise_product_price = (trim($explodedCityPrices[$j]) == '0') ? $productDefaultPrice : trim($explodedCityPrices[$j]);
                                    $val = trim($val);
                                    
                                    $getCityDetails = get_term_by('slug', $val, themeFramework::$theme_prefix . 'product_city', ARRAY_A);
                                    
                                    if (is_array($getCityDetails) && count($getCityDetails) > 0) {
                                        $exlodedCityIDs[] = $getCityDetails['term_id'];
                                        $cityId = $getCityDetails['term_id'];
                                    } else {
                                        $insertTermCity = wp_insert_term(ucwords($val), themeFramework::$theme_prefix . 'product_city');
                                        $exlodedCityIDs[] = $insertTermCity['term_id'];
                                        $cityId = $insertTermCity['term_id'];
                                    }
                                    update_post_meta($insertedProductID, '_product_city_price_'.$cityId, $city_wise_product_price);
                                    $j++;
                                }
                            }
                            update_post_meta($insertedProductID, '_product_cities', $exlodedCityIDs);
                            update_post_meta($insertedProductID, '_product_cities_prices', $explodedCityPrices);
                        }
                    }
                    $i++;
                }
                fclose($file);
            }
            
            wp_redirect(admin_url().'admin.php?page=product-export-import');
            exit;
        }
        
       /*$getCityDetails = get_term_by('name', "Anastácio", themeFramework::$theme_prefix . 'product_city', ARRAY_A);
       echo "<pre>";
       print_r($getCityDetails);*/
        
        ?>
        <h2><?php _e('Product Export/Import Section', THEME_TEXTDOMAIN); ?></h2>
        <div class="wrap">
            <form name="export_import_settings" method="post" action="" enctype="multipart/form-data">
                <table class="widefat">
                    <thead>
                    <th><strong><?php _e('Export Data', THEME_TEXTDOMAIN); ?></strong></th>
                    <th><strong><?php _e('Import Data', THEME_TEXTDOMAIN); ?></strong></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong><?php _e('Click the button to export all the single products in .csv format. You will have the following structure for your csv file.', THEME_TEXTDOMAIN); ?></strong>
                            </td>
                            <td>
                                <strong><?php _e('Upload the same format of .csv file which you have exported from here. If you have not exported it then please export it, check and then upload.', THEME_TEXTDOMAIN); ?></strong><br>
                                <input type="file" name="upload_csv_data"/>
                            </td>
                        </tr>
                        <tr>
                            <td><button class="button button-primary" type="submit" name="export_settings_sbmt"><?php _e('Export', THEME_TEXTDOMAIN); ?></button></td>
                            <td><button class="button button-primary" type="submit" name="import_settings_sbmt"><?php _e('Import', THEME_TEXTDOMAIN); ?></button></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>

        <h2><?php _e('Preview of .csv file', THEME_TEXTDOMAIN); ?></h2>
        <?php
// echo "<pre>";
// print_r(sanitize_title_with_dashes('Test Product 1 When It Has Been Given To You.'));
// echo "</pre>";
         ?>
        <div class="wrap">
            <table class="widefat">
                <thead>
                <th><strong><?php _e('Name', THEME_TEXTDOMAIN); ?></strong></th>
                <!-- <th><strong><?php _e('Slug', THEME_TEXTDOMAIN); ?></strong></th> -->
                <th><strong><?php _e('Default Price', THEME_TEXTDOMAIN); ?></strong></th>
                <th><strong><?php _e('Cities', THEME_TEXTDOMAIN); ?></strong></th>
                <th><strong><?php _e('Prices', THEME_TEXTDOMAIN); ?></strong></th>
                </thead>
                <tbody>
                    <?php
                    if (is_array($getSingleProducts) && count($getSingleProducts) > 0):
                        $countProduct = 1;
                        foreach ($getSingleProducts as $eachProduct):
                            $productCity = [];
                            $productPrice = [];
                            $productDetails = $GeneralThemeObject->product_details($eachProduct->ID);
                            $getProductCities = get_post_meta($eachProduct->ID, '_product_cities', true);
                            $getProductCitiesPrices = get_post_meta($eachProduct->ID, '_product_cities_prices', true);
                            if (is_array($getProductCities) && count($getProductCities) > 0) {
                                foreach ($getProductCities as $eachProductCity) {
                                    $getCityBy = get_term_by('id', $eachProductCity, themeFramework::$theme_prefix . 'product_city');
                                    $productCity[] = $getCityBy->name;
                                }
                                $joinedProductCity = join(', ', $productCity);
                            }
                            if (is_array($getProductCitiesPrices) && count($getProductCitiesPrices) > 0) {
                                foreach ($getProductCitiesPrices as $eachProductCityPrice) {
                                    $productPrice[] = $eachProductCityPrice;
                                }
                                $joinedProductCityPrices = join(', ', $productPrice);
                            } else if (is_array($getProductCities) && count($getProductCities) > 0) {
                                foreach ($getProductCities as $eachProductCity) {
                                    $productPrice[] = $productDetails->data['default_price'];
                                }
                                $joinedProductCityPrices = join(', ', $productPrice);
                            }
                            ?>
                            <tr>
                                <!--<td><?php echo $countProduct . ' - ' . $productDetails->data['ID'] . ' - ' . $productDetails->data['title']; ?></td>-->
                                <td><?php echo $productDetails->data['title']; ?></td>
                                <!-- <td><?php echo $eachProduct->post_name; ?></td> -->
                                <td><?php echo $productDetails->data['default_price']; ?></td>
                                <td><?php echo $joinedProductCity; ?></td>
                                <td><?php echo $joinedProductCityPrices; ?></td>
                            </tr>
                            <?php
                            $countProduct++;
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    }

}
	