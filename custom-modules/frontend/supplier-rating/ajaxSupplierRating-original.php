<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * --------------------------------------------
 * AJAX:: Open Rating Pop-up
 * --------------------------------------------
 */

add_action('wp_ajax_open_supplier_popup', 'ajaxSupplierRatingPopUp');

if (!function_exists('ajaxSupplierRatingPopUp')) {

    function ajaxSupplierRatingPopUp() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => '', 'supplierTableHTML'];
        $GeneralThemeObject = new GeneralTheme();
        $FinalizeObject = new classFinalize();
        $msg = NULL;
        $deal_id = base64_decode($_POST['deal_id']);
        $userDetails = $GeneralThemeObject->user_details();
        $getDealSupplierDetails = $FinalizeObject->selectDistinctSupplierIDs($deal_id, $userDetails->data['user_id']);

        if (is_array($getDealSupplierDetails) && count($getDealSupplierDetails) > 0) {
            $supplierTableHTML = '<div class="table-responsive dataTable">';
            $supplierTableHTML .= '<table class="table cart-table">';
            $supplierTableHTML .= '<thead>';
            $supplierTableHTML .= '<th>Fornecedor</th>';
            $supplierTableHTML .= '<th>Preço</th>';
            $supplierTableHTML .= '<th>Qualidade do atendimento</th>';
            $supplierTableHTML .= '<th>Tempo de entrega</th>';
            $supplierTableHTML .= '</thead>';
            $supplierTableHTML .= '<tbody>';
            foreach ($getDealSupplierDetails as $eachDealSupplier) {
                $supplierDetails = $GeneralThemeObject->user_details($eachDealSupplier->supplier_id);

                if ($supplierDetails->data['pro_pic_exists'] == TRUE) {
                    $user_pro_pic = wp_get_attachment_image_src($supplierDetails->data['pro_pic'], 'full');
                    $supplierThumb = $user_pro_pic[0];
                } else {
                    $supplierThumb = 'http://via.placeholder.com/100x100';
                }

                $supplierTableHTML .= '<tr>';
                $supplierTableHTML .= '<td>';
                $supplierTableHTML .= '<div class="profile-pic ssss"><div class="user-pic"><img src="' . $supplierThumb . '" alt="" width="100" height="100"/></div></div>';
                $supplierTableHTML .= '<div class="rate-supplier-name">' . $supplierDetails->data['fname'] . '</div>';
                $supplierTableHTML .= '<div class="rate-supplier-name">' . $supplierDetails->data['lname'] . '</div>';
                $supplierTableHTML .= '</td>';
                $supplierTableHTML .= '<td>';
                $supplierTableHTML .= '<input name="price_rate[' . $eachDealSupplier->supplier_id . ']" type="number" class="rating new-rating" value="" data-show-clear="false" data-size="xs" data-show-caption="false" data-readonly="false" />';
                $supplierTableHTML .= '</td>';
                
                $supplierTableHTML .= '<td>';
                $supplierTableHTML .= '<input name="attendence_rate[' . $eachDealSupplier->supplier_id . ']" type="number" class="rating new-rating" value="" data-show-clear="false" data-size="xs" data-show-caption="false" data-readonly="false" />';
                $supplierTableHTML .= '</td>';
                $supplierTableHTML .= '<td>';
                $supplierTableHTML .= '<input name="delivery_rate[' . $eachDealSupplier->supplier_id . ']" type="number" class="rating new-rating" value="" data-show-clear="false" data-size="xs" data-show-caption="false" data-readonly="false" />';
                $supplierTableHTML .= '</td>';
                 
                $supplierTableHTML .= '</tr>';
            }
            $supplierTableHTML .= '</tbody>';
            $supplierTableHTML .= '</table>';
            $supplierTableHTML .= '</div>';
            $supplierTableHTML .= '</div>';
            $resp_arr['flag'] = TRUE;
        }

        $resp_arr['msg'] = $msg;
        $resp_arr['supplierTableHTML'] = $supplierTableHTML;
        echo json_encode($resp_arr);
        exit;
    }

}

/*
 * --------------------------------------------
 * AJAX:: Provide Supplier Rating
 * --------------------------------------------
 */

add_action('wp_ajax_supplier_rating', 'ajaxSupplierRating');

if (!function_exists('ajaxSupplierRating')) {

    function ajaxSupplierRating() {
        $resp_arr = ['flag' => FALSE, 'msg' => '', 'url' => ''];
        $GeneralThemeObject = new GeneralTheme();
        $RatingObject = new classReviewRating();
        $msg = NULL;
        $selected_deal = base64_decode($_POST['selected_deal']);
        $price_rate = $_POST['price_rate'];
        $attendence_rate = $_POST['attendence_rate'];
        $delivery_rate = $_POST['delivery_rate'];
        $userDetails = $GeneralThemeObject->user_details();

        /* Price rate data */
        if (is_array($price_rate) && count($price_rate) > 0) {
            foreach ($price_rate as $key => $val) {
                $priceData = [
                    'user_id' => $userDetails->data['user_id'],
                    'deal_id' => $selected_deal,
                    'supplier_id' => $key,
                    'price_rate' => ($price_rate[$key] == 0) ? 0 : $price_rate[$key],
                    'attendence_rate' => ($attendence_rate[$key] == 0) ? 0 : $attendence_rate[$key],
                    'delivery_rate' => ($delivery_rate[$key] == 0) ? 0 : $delivery_rate[$key],
                    'date' => strtotime(date('Y-m-d')),
                ];
                $RatingObject->insertIntoReviewRating($priceData);
            }
            $resp_arr['flag'] = TRUE;
            $msg = 'Obrigado por avaliar o fornecedor!';
        }

        $resp_arr['msg'] = $msg;
        echo json_encode($resp_arr);
        exit;
    }

}