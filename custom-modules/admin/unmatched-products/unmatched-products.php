<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!function_exists('unmatched_products_func')) {

    function unmatched_products_func() {
        $GeneralThemeObject = new GeneralTheme();
        $getNotMatchedProducts = get_option('_not_matched_products');
        ?>
        <h2><?php _e('Unmatched Products', THEME_TEXTDOMAIN); ?></h2>

        <div class="wrap">
            <?php
            if (is_array($getNotMatchedProducts) && count($getNotMatchedProducts) > 0) :
                $i = 0;
                $data_arr = [];
                foreach ($getNotMatchedProducts as $eachUnmatchedProducts) :
                    $explodedString = explode('%', $eachUnmatchedProducts);
                    if (is_numeric($explodedString[0])):
                        $userDetails = $GeneralThemeObject->user_details($explodedString[0]);
                        $userName = $userDetails->data['fname'] . ' ' . $userDetails->data['lname'];
                    else:
                        $userName = 'NON REGISTERED USER SEARCH';
                    endif;
                    $userDetails = $GeneralThemeObject->user_details();

                    $data_arr[$i] = [
                        'search_string_val' => $eachUnmatchedProducts,
                        'user_name' => $userName,
                        'search_string' => $explodedString[1],
                        'date' => ($explodedString[2]) ? date('d M, Y', $explodedString[2]) : ' - '
                    ];
                    $i++;
                endforeach;
            endif;

            $DonationTblObject = new Unmatched_Product_List_Table();
            $DonationTblObject->prepare_items($data_arr);
            ?>
            <form>
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
                <?php $DonationTblObject->display(); ?>
            </form>            
        </div>
        <?php
    }

}