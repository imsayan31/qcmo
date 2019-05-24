<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$GeneralThemeObject = new GeneralTheme();
$GeneralThemeObject->authentic();
$userDetails = $GeneralThemeObject->user_details();
$FinalizeObject = new classFinalize();
$CartObject = new classCart();
$productArr = [];
$productCategoryArr = [];
if (isset($_GET['deal_id']) && $_GET['deal_id'] != ''):
    $dealID = base64_decode($_GET['deal_id']);
    $dealDetails = $FinalizeObject->getDealDetails($dealID);
    $dealProductDetails = $FinalizeObject->getDealProductDetails($dealID);

    if (isset($_GET['selected_cat']) && $_GET['selected_cat'] != ''):
        $select_category = $_GET['selected_cat'];
        $getCatBy = get_term_by('slug', $select_category, themeFramework::$theme_prefix . 'product_category');
        if ($getCatBy):
            $catName = $getCatBy->name;
            $getUserCartItems = $FinalizeObject->getUserDealCategoryProducts($dealID, $select_category);
        else:
            $catName = 'Todas categorias';
            $getUserCartItems = $FinalizeObject->getUserDealCategoryProducts($dealID);
        endif;
    else:
        $getUserCartItems = $FinalizeObject->getUserDealCategoryProducts($dealID);
    endif;
else:
    if (isset($_GET['selected_cat']) && $_GET['selected_cat'] != ''):
        $select_category = $_GET['selected_cat'];
        $getCatBy = get_term_by('slug', $select_category, themeFramework::$theme_prefix . 'product_category');
        if ($getCatBy):
            $catName = $getCatBy->name;
            $getUserCartItems = $CartObject->getUserCartCategoryProducts($userDetails->data['user_id'], $select_category);
        else:
            $catName = 'Todas categorias';
            $getUserCartItems = $CartObject->getUserCartCategoryProducts($userDetails->data['user_id']);
        endif;
    else:
        $getUserCartItems = $CartObject->getUserCartCategoryProducts($userDetails->data['user_id']);
    endif;
endif;

?>
<style type="text/css">
    @media print{
        thead .main-row{
            background: #f7c02e !important; 
            border-top: #fff 1px solid !important; 
        }
        thead .main-row th{ color: #000 !important;}
        tbody .main-row{
            background: #666 !important; 
            /*border-top: #fff 1px solid !important; */
        }
        tbody .main-row td{ color: #000 !important;}
        .main-column{
            background-color:transparent; 
            color: #fff; 
            border: #fff9fe solid 1px; 
            border-top: none; 
            font-weight: normal;
        }
        .print-button{
            display: none;
        }
        .btn{
            display: none;
        }
    }
</style>

<div class="right deal-dtls">
    <div class="material-list-filteration" style="font-size:16 px;">
        <?php
        if (isset($_GET['deal_id']) && $_GET['deal_id'] != ''):
            ?>
            <a class="finalize-cart-items open-material-list-popup btn animated-bg-btn" data-deal_id="<?php echo base64_encode($dealID); ?>" href="javascript:void(0);" title="<?php _e('Material List', THEME_TEXTDOMAIN); ?>"><i class="fa fa-align-left" aria-hidden="true"></i>&nbsp; <?php _e('Material List', THEME_TEXTDOMAIN); ?></a>
            <?php
        else:
            ?>
            <a href="#user_cart_category_popup" class="finalize-cart-items open-material-popup btn animated-bg-btn" data-toggle="modal"><i class="fa fa-align-left" aria-hidden="true"></i>&nbsp; <?php _e('Material List', THEME_TEXTDOMAIN); ?></a>
        <?php
        endif;
        ?>
    </div>

    <?php if (isset($_GET['deal_id']) && $_GET['deal_id'] != ''): ?>
        <div class="text-center">
            <?php if ($dealDetails->data['deal_name']): ?>
                <h2 style="padding-top: 20px; padding-bottom: 10px; padding-left: 5px; display: inline-block; vertical-align: top;"><?php echo '<strong> Orcamento: </strong>' . $dealDetails->data['deal_name']; ?></h2>
            <?php else: ?>
                <h2 style="padding-top: 20px; padding-bottom: 10px; padding-left: 5px; display: inline-block; vertical-align: top;"><?php echo '<strong> Orcamento: </strong>' . 'Sem nome'; ?></h2>
            <?php endif; ?>
             
            <div>
                <p style="padding-top: 20px; padding-bottom: 10px; padding-left: 5px; display: inline-block; vertical-align: top;"><?php echo ($dealDetails->data['deal_description']!='No details provided.') ? $dealDetails->data['deal_description'] : 'Nenhuma descrição fornecida' ?></p>
            </div>
        </div>
        
        <?php
        if (is_array($dealProductDetails) && count($dealProductDetails) > 0):

            foreach ($dealProductDetails as $eachdeals => $eachCartDataValArr):
                $getProductCategoryDetails = get_term_by('slug', $eachdeals, themeFramework::$theme_prefix . 'product_category');
                ?>
                  
                        <?php
                        if (is_array($eachCartDataValArr) && count($eachCartDataValArr) > 0):
                            foreach ($eachCartDataValArr as $eachCartData):
                                $productDetails = $GeneralThemeObject->product_details($eachCartData);
                                $productMainSuppliedImg = wp_get_attachment_image_src(get_post_thumbnail_id($eachCartData), 'full');
                                $getCartItems = $FinalizeObject->getDealProductDetails($dealID, $userDetails->data['user_id'], $eachCartData);
                                if ($productMainSuppliedImg[0]) {
                                    $mainProductSuppliedImg = $productMainSuppliedImg[0];
                                } else {
                                    $mainProductSuppliedImg = 'https://via.placeholder.com/100x75';
                                }
                                if ($productDetails->data['is_simple'] == false):
                                    
                                    ?>
                                     <?php
if ($productDetails->data['product_categories'] != ''): ?>
                                    <table style="margin-top:15px;" >
                                        
                    <thead>
                       
                        <tr class="main-row">
                            
                            <th colspan="2" class="main-column" style="background-color:#f7c02e; padding: 10px; color: #000; border: #fff9fe solid 1px; border-top: none; font-weight: normal;"><?php _e('Product Category - ', THEME_TEXTDOMAIN); ?> <?php echo $productDetails->data['product_categories']; ?> </th>
                           
                        </tr>
                        
                    </thead>
                      <?php
endif ?>

                    <tbody>
                        <?php
                            $simpleProducts = $productDetails->data['product_cats'];
                            $simpleProductsQuantity = $productDetails->data['product_cat_quantity'];
                            if (is_array($simpleProducts) && count($simpleProducts) > 0):
                                foreach ($simpleProducts as $eachProductKey => $eachProductVal):
                                    $simpleProductDetails = $GeneralThemeObject->product_details($eachProductVal);
                                    $productSuppliedImg = wp_get_attachment_image_src(get_post_thumbnail_id($eachProductVal), 'full');
                                    if ($productSuppliedImg[0]) {
                                        $mainSuppliedImg = $productSuppliedImg[0];
                                    } else {
                                        $mainSuppliedImg = 'https://via.placeholder.com/100x75';
                                    }
                                    ?>
                                   
                                   
                                   <tr class="main-row" style="background: #e6e6e6 !important; border-bottom:none; /*border-top: #000 1px solid !important;*/">
                                       
                                        <td width="10%" class="main-column" style="background-color:#e6e6e6; padding: 10px; color: #000; /*border: #fff9fe solid 1px;*/ border-top: none; font-weight: normal;"><img src="<?php echo $mainSuppliedImg; ?>" width="100" height="75"/></td>
                                        <td class="main-column" style="background-color:#e6e6e6; padding: 10px; color: #000; /*border: #fff9fe solid 1px;*/ border-top: none; font-weight: normal;"><?php echo $simpleProductDetails->data['title'] . ' - ' . $simpleProductsQuantity[$eachProductKey] . ' ' . $simpleProductDetails->data['unit']; ?></td>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                        else:
                            ?>
                          <table style="margin-top:15px;">
                    <thead>
                        <tr class="main-row">

                            <th colspan="2" class="main-column" style="background-color:#f7c02e; padding: 10px; color: #000; border: #fff9fe solid 1px; border-top: none; font-weight: normal;"><?php _e('Product Category - ', THEME_TEXTDOMAIN); ?> <?php echo $productDetails->data['product_categories']; ?> </th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr class="main-row" style="background: #e6e6e6 !important; border-bottom:none; /*border-top: #fff 1px solid !important;*/">
                                <!-- <?php //echo $productDetails->data['product_categories']; ?>-->
                                <td width="10%" class="main-column" style="background-color:#e6e6e6; padding: 10px; color: #000; /*border: #fff9fe solid 1px;*/ border-top: none; font-weight: normal;"><img src="<?php echo $mainProductSuppliedImg; ?>" width="100" height="75"/></td>
                                <td class="main-column" style="background-color:#e6e6e6; padding: 10px; color: #000; /*border: #fff9fe solid 1px;*/ border-top: none; font-weight: normal;"><?php echo $productDetails->data['title'] . ' - ' . $getCartItems[0]['no_of_items'] . ' ' . $productDetails->data['unit']; ?></td>
                            </tr>
                        <?php
                        endif;
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                    </tbody>
                </table>
                <?php
            endforeach;
        endif;
        ?>
       

    <?php else: ?>
    <!-------------------------------#########################################------------------------------>
        <?php
        if (is_array($getUserCartItems) && count($getUserCartItems) > 0):
            foreach ($getUserCartItems as $eachCartDataKey => $eachCartDataValArr):
                $getProductCategoryDetails = get_term_by('slug', $eachCartDataKey, themeFramework::$theme_prefix . 'product_category');
                ?>
               
                        <?php
                        if (is_array($eachCartDataValArr) && count($eachCartDataValArr) > 0):
                            foreach ($eachCartDataValArr as $eachCartData):
                                $productDetails = $GeneralThemeObject->product_details($eachCartData);
                                $productMainSuppliedImg = wp_get_attachment_image_src(get_post_thumbnail_id($eachCartData), 'full');
                                $getCartItems = $CartObject->getCartItems($userDetails->data['user_id'], $eachCartData);
                                if ($productMainSuppliedImg[0]) {
                                    $mainProductSuppliedImg = $productMainSuppliedImg[0];
                                } else {
                                    $mainProductSuppliedImg = 'https://via.placeholder.com/100x75';
                                }
                                if ($productDetails->data['is_simple'] == false):?>
                                
                                     <table style="margin-top:15px;">
                    <thead>
                        <tr class="main-row">
                            <th colspan="2" class="main-column" style="background-color:#f7c02e; padding: 10px; color: #000; border: #fff9fe solid 1px; border-top: none; font-weight: normal;"><?php _e('Product Category - ', THEME_TEXTDOMAIN); ?> <?php echo $productDetails->data['product_categories']; ?> </th>
                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                    $simpleProducts = $productDetails->data['product_cats'];
                                    $simpleProductsQuantity = $productDetails->data['product_cat_quantity'];
                                    if (is_array($simpleProducts) && count($simpleProducts) > 0):
                                        foreach ($simpleProducts as $eachProductKey => $eachProductVal):
                                            $simpleProductDetails = $GeneralThemeObject->product_details($eachProductVal);
                                            $productSuppliedImg = wp_get_attachment_image_src(get_post_thumbnail_id($eachProductVal), 'full');
                                            if ($productSuppliedImg[0]) {
                                                $mainSuppliedImg = $productSuppliedImg[0];
                                            } else {
                                                $mainSuppliedImg = 'https://via.placeholder.com/100x75';
                                            }
                                            ?>
                                            
                                            <?php
                                            /*
if ($simpleProductDetails->data['unit'] == hora): ?>
 <?php echo $simpleProductsQuantity[$eachProductKey];?>
<?php
//print_r($simpleProductsQuantity);
//$a=array_sum($simpleProductsQuantity);
//echo $a;

//$sum = 0;
//foreach($simpleProductsQuantity[$eachProductKey] as $key => $value) {
//   $sum += $value; 
//}
//echo $sum;
?>


                                         <?php echo $simpleProductDetails->data['title'];?>
                                         <?php echo $simpleProductDetails->data['unit'];?>
                                       
                                        <?php 
                                       // endif ?>*/
                                       ?>
                                        
                                            <tr class="main-row" style="background: #e6e6e6 !important; border-bottom:none; /*border-top: #fff 1px solid !important;*/">
                                               
                                                <td width="10%" class="main-column" style="background-color:#e6e6e6; padding: 10px; color: #000; /*border: #fff9fe solid 1px;*/ border-top: none; font-weight: normal;"><img src="<?php echo $mainSuppliedImg; ?>" width="100" height="75"/></td>
                                                <td class="main-column" style="background-color:#e6e6e6; padding: 10px; color: #000; /*border: #fff9fe solid 1px;*/ border-top: none; font-weight: normal;"><?php echo $simpleProductDetails->data['title'] . ' - ' . $simpleProductsQuantity[$eachProductKey] . ' ' . $simpleProductDetails->data['unit']; ?></td>
                                               
                                            </tr>
                                            <?php
                                        endforeach;
                                    endif;
                                else:
                                    ?>
                                    <table style="margin-top:15px;">
                    <thead>
                        <tr class="main-row">
                            <th colspan="2" class="main-column" style="background-color:#f7c02e; padding: 10px; color: #000; border: #fff9fe solid 1px; border-top: none; font-weight: normal;"><?php _e('Product Category - ', THEME_TEXTDOMAIN); ?> <?php echo $productDetails->data['product_categories']; ?></th>
                        
                        </tr>
                    </thead>
                    <tbody>
                                    <tr class="main-row" style="background: #e6e6e6 !important; border-bottom:none; /*border-top: #fff 1px solid !important;*/">
                                        
                                        <td width="10%" class="main-column" style="background-color:#e6e6e6; padding: 10px; color: #000; /*border: #fff9fe solid 1px;*/ border-top: none; font-weight: normal;"><img src="<?php echo $mainProductSuppliedImg; ?>" width="100" height="75"/></td>
                                        <td class="main-column" style="background-color:#e6e6e6; padding: 10px; color: #000; /*border: #fff9fe solid 1px;*/ border-top: none; font-weight: normal;"><?php echo $productDetails->data['title'] . ' - ' . $getCartItems[0]->no_of_items . ' ' . $productDetails->data['unit']; ?></td>
                                         <?php
if ($productDetails->data['unit'] == 'hora'): ?>
                                        <td class="main-column" style="background-color:#e6e6e6; padding: 10px; color: #000; /*border: #fff9fe solid 1px;*/ border-top: none; font-weight: normal;"><?php echo $productDetails->data['title'] . ' - ' . $getCartItems[0]->no_of_items . ' ' . $productDetails->data['unit']; ?></td>
                                        <?php 
                                        endif ?>
                                    </tr>
                                <?php
                                endif;
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                    </tbody>
                </table>
                <?php
            endforeach;
        endif;
        ?>
    <?php endif; ?>

    <div class="print-button"><a href="javascript:window.print()" class="btn btn-default"><?php _e('Print', THEME_TEXTDOMAIN); ?></a></div>

</div>

 
 
 <style>
 th.main-column a {
    color: black !important;
}
 </style>
                                    