<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classFinalize
 *
 * @author Sayanta Dey
 */
if (!class_exists('classFinalize')) {

    class classFinalize extends GeneralTheme {

        //put your code here

        public function __construct() {
            global $wpdb;
            $CartObject = new classCart();
            $this->cartObj = &$CartObject;
            $this->db = &$wpdb;
            $this->global_deal_acceptance = get_option('_global_deal_acceptance');
            $this->global_deal_acceptance_counter = get_option('_global_deal_acceptance_counter');
        }

        /*
         * @param type array
         * @param $data
         * @return type integer
         * @description It returns inserted deal finalize id
         */

        public function insertIntoTBLFinalize(array $data) {
            $inserted_finalize_id = $this->db->insert(TBL_DEAL_FINALIZE, $data);
            return $inserted_finalize_id;
        }

        /*
         * @param type array
         * @param $data
         * @return type array
         * @description It returns inserted deal finalize products id
         */

        public function insertIntoTBLFinalizeProducts(array $data) {
            $inserted_finalize_id = $this->db->insert(TBL_DEAL_FINALIZE_PRODUCTS, $data);
            return $inserted_finalize_id;
        }

        /*
         * @param type array
         * @param $data
         * @return type array
         * @description It returns inserted deal finalize product's suppliers id
         */

        public function insertIntoTBLFinalizeSuppliers(array $data) {
            $inserted_finalize_id = $this->db->insert(TBL_DEAL_FINALIZE_SUPPLIERS, $data);
            return $inserted_finalize_id;
        }

        /*
         * @param type null
         * @return type array
         * @description It returns overall deal finalize data
         */

        public function prepareFinalizeData($deal_name = NULL, $deal_description = NULL) {
            $dealID = $this->generateRandomString(5);
            $userDetails = $this->user_details();
            $cartTotal = $this->cartObj->getCartTotal($userDetails->data['user_id']);

            if ($deal_name) {
                $dealName = $deal_name . ' ' . $dealID;
            } else {
                $dealName = 'My deal ' . $dealID;
            }

            if ($deal_description) {
                $dealDesc = $deal_description;
            } else {
                $dealDesc = 'No description provided.';
            }

            $finalizeData = [
                'deal_id' => $dealID,
                'user_id' => $userDetails->data['user_id'],
                'deal_name' => $dealName,
                'deal_description' => $dealDesc,
                'transaction_id' => '',
                'total_price' => $cartTotal,
                'deal_status' => 2,
                'deal_locking_status' => 2,
                'deal_initialized_on' => strtotime(date('Y-m-d')),
                'deal_completed_on' => '',
            ];
            return $finalizeData;
        }

        /*
         * @param type array
         * @param $data, $getFinalizeData
         * @return type array
         * @description It returns overall deal finalize product data
         */

        public function prepareFinalizeProductData(array $data, array $getFinalizeData) {
            $finalizeProductData = [];
            if (is_array($data) && count($data) > 0) {
                foreach ($data as $eachData) {
                    $prepareBundledProducts = $this->prepareBundleProducts($eachData->product_id);
                    $getProductMaterialCategory = get_post_meta($eachData->product_id, '_material_category', TRUE);
                    $finalizeProductData[] = [
                        'deal_id' => $getFinalizeData['deal_id'],
                        'user_id' => $getFinalizeData['user_id'],
                        'product_id' => $eachData->product_id,
                        'product_material_category' => $getProductMaterialCategory,
                        'state' => $eachData->state,
                        'city' => $eachData->city,
                        'no_of_items' => $eachData->no_of_items,
                        'price' => $eachData->total_price,
                        'product_bundles' => serialize($prepareBundledProducts)
                    ];
                }
            }
            return $finalizeProductData;
        }

        /*
         * @param type array
         * @param $data, $getFinalizeData
         * @return type array
         * @description It returns overall deal finalize product suppliers data
         */

        public function prepareFinalizeSupplierData(array $data, array $getFinalizeData) {
            $finalizeSupplierData = [];
            $CartObject = new classCart();
            $getCartTotal = $CartObject->getCartTotal($getFinalizeData['user_id']);
            $currentUserDetails = $this->user_details($getFinalizeData['user_id']);
            if (is_array($data) && count($data) > 0) {
                foreach ($data as $eachData) {
                    $bundleProductDetails = [];
                    $product_details = $this->product_details($eachData->product_id);
                    //$prepareSuppliers = $this->prepareSuppliers($eachData->product_id, $eachData->no_of_items);
                    $prepareSuppliers = $this->getSuppliersByCityCategory($eachData->product_id, $eachData->city, $eachData->no_of_items);
                    if (is_array($prepareSuppliers) && count($prepareSuppliers) > 0) {
                        foreach ($prepareSuppliers as $eachSupplierKey => $eachSupplierVal) {
                            $supplierDetails = $this->user_details($eachSupplierKey);
                            $supplierMinimumDealAmount = $supplierDetails->data['minimum_deal_amount'];
                            if ($supplierMinimumDealAmount <= $getCartTotal) {

                                if ($supplierDetails->data['check_user_address'] == 1 && $currentUserDetails->data['address'] != '') {
                                    $insertedSupplier = $eachSupplierKey;
                                } else if ($supplierDetails->data['check_user_contact_no'] == 1 && ($currentUserDetails->data['phone'] != '' || $currentUserDetails->data['lphone'] != '')) {
                                    $insertedSupplier = $eachSupplierKey;
                                } else if ($supplierDetails->data['check_user_cpf_cnpj'] == 1 && ($currentUserDetails->data['cpf'] != '' || $currentUserDetails->data['cnpj'] != '')) {
                                    $insertedSupplier = $eachSupplierKey;
                                } else {
                                    $insertedSupplier = $eachSupplierKey;
                                }

                                $finalizeSupplierData[] = [
                                    'deal_id' => $getFinalizeData['deal_id'],
                                    'user_id' => $getFinalizeData['user_id'],
                                    'product_id' => $eachData->product_id,
                                    'supplier_id' => $eachSupplierKey,
                                    'state' => $eachData->state,
                                    'city' => $eachData->city,
                                    'bundled_product_details' => serialize($eachSupplierVal)
                                ];
                            } else {
                                $finalizeSupplierData[] = [
                                    'deal_id' => $getFinalizeData['deal_id'],
                                    'user_id' => $getFinalizeData['user_id'],
                                    'product_id' => $eachData->product_id,
                                    'supplier_id' => $eachSupplierKey,
                                    'state' => $eachData->state,
                                    'city' => $eachData->city,
                                    'bundled_product_details' => serialize($eachSupplierVal)
                                ];
                            }
                        }
                    } else {
                        //$bundleProductDetails[] = get_the_title($product_details->data['ID']) . '-' . $eachData->no_of_items . '-' . $product_details->data['unit'];
                        $bundleProductDetails[] = $product_details->data['ID'] . '-' . $eachData->no_of_items . '-' . $product_details->data['unit'];
                        $finalizeSupplierData[] = [
                            'deal_id' => $getFinalizeData['deal_id'],
                            'user_id' => $getFinalizeData['user_id'],
                            'product_id' => $eachData->product_id,
                            'supplier_id' => $eachSupplierKey,
                            'state' => $eachData->state,
                            'city' => $eachData->city,
                            'bundled_product_details' => serialize($bundleProductDetails)
                        ];
                    }
                }
            }
            return $finalizeSupplierData;
        }

        /*
         * @param type integer
         * @param $productID
         * @return type array
         * @description It returns bundle products
         */

        public function prepareBundleProducts($productID) {
            $prepareBundledData = [];
            $i = 0;
            $product_details = $this->product_details($productID);
            $getBundledProducts = $product_details->data['product_cats'];
            $getBundleProductQuantity = $product_details->data['product_cat_quantity'];
            if (is_array($getBundledProducts) && count($getBundledProducts) > 0) {
                foreach ($getBundledProducts as $eachProductKey => $eachProductVal) {
                    $bundled_product_details = $this->product_details($eachProductVal);
                    $prepareBundledData[$i]['product_id'] = $eachProductVal;
                    $prepareBundledData[$i]['product_type'] = $bundled_product_details->data['type'];
                    $prepareBundledData[$i]['product_quantity'] = $getBundleProductQuantity[$eachProductKey];
                    $prepareBundledData[$i]['product_unit'] = $bundled_product_details->data['unit'];
                    $prepareBundledData[$i]['product_price'] = $bundled_product_details->data['default_price'];
                    $i++;
                }
            }
            return $prepareBundledData;
        }

        /*
         * @param type integer
         * @param $productID
         * @return type array
         * @description It returns suppliers for a deal
         */

        public function prepareSuppliers($productID, $noOfItems = NULL) {
            $suppArr = [];
            $product_details = $this->product_details($productID);
            if ($product_details->data['is_simple'] == TRUE) {
                $productSuppliers = $product_details->data['suppliers'];
                if (is_array($productSuppliers) && count($productSuppliers) > 0) {
                    foreach ($productSuppliers as $val) {
                        $suppArr[$val][] = get_the_title($product_details->data['ID']) . ' - ' . $noOfItems . ' ' . $product_details->data['unit'];
                    }
                }
            } else {
                $prepareBundledProducts = $this->prepareBundleProducts($productID);
                $getBundleProductQuantity = $product_details->data['product_cat_quantity'];
                if (is_array($prepareBundledProducts) && count($prepareBundledProducts) > 0) {
                    foreach ($prepareBundledProducts as $eachBundleProductKey => $eachBundleProductVal) {
                        $getProductDetails = $this->product_details($eachBundleProductVal['product_id']);
                        $productSuppliers = $getProductDetails->data['suppliers'];
                        if (is_array($productSuppliers) && count($productSuppliers) > 0) {
                            foreach ($productSuppliers as $val) {
                                $suppArr[$val][] = get_the_title($eachBundleProductVal['product_id']) . ' - ' . $getBundleProductQuantity[$eachBundleProductKey] . ' ' . $getProductDetails->data['unit'];
                            }
                        }
                    }
                }
            }

            return $suppArr;
        }

        /*
         * @param type array
         * @param $data
         * @return type null
         * @description It shoots mails to all requested deal suppliers
         */

        public function sendMailAllRequestedSuppliers(array $data) {
            if (is_array($data) && count($data) > 0) {
                foreach ($data as $eachData) {
                    $suppliersQuery = "SELECT * FROM " . TBL_DEAL_FINALIZE_SUPPLIERS . " WHERE `ID`=" . $eachData . "";
                    $suppliersQueryRes = $this->db->get_results($suppliersQuery);
                    if (is_array($suppliersQueryRes) && count($suppliersQueryRes) > 0) {
                        foreach ($suppliersQueryRes as $eachSupplier) {
                            $getSupplierDetails = $this->user_details($eachSupplier->supplier_id);

                            /* send mail to suppliers */
                        }
                    }
                }
            }
        }

        /*
         * @param type integer
         * @param $userID
         * @return type null
         * @description It returns all the deals for an user (if user supplied)
         */

        public function getDeals($userID = NULL, $queryString = NULL, $isAdmin = FALSE) {
            $getDealsQuery = "SELECT * FROM " . TBL_DEAL_FINALIZE . " WHERE `ID` !=''";
            if ($userID) {
                $getDealsQuery .= " AND `user_id`=" . $userID . "";
            }
            if ($queryString) {
                $getDealsQuery .= $queryString;
            }
            if($isAdmin == FALSE){
                $getDealsQuery .= ' AND `deal_status` != 5';
            }
            $getDealsQuery .= " ORDER BY `ID` DESC";
            $getDealsQueryRes = $this->db->get_results($getDealsQuery);
            return $getDealsQueryRes;
        }

        /*
         * @param type string
         * @param $dealID
         * @return type object
         * @description It returns details of a deal
         */

        public function getDealDetails($dealID = NULL) {
            $dealObject = new stdClass();
            $dealObject->data = [];
            $getDealsQuery = "SELECT * FROM " . TBL_DEAL_FINALIZE . " WHERE `deal_id`='" . $dealID . "'";
            $getDealsQueryRes = $this->db->get_results($getDealsQuery);
            if (is_array($getDealsQueryRes) && count($getDealsQueryRes) > 0) {
                foreach ($getDealsQueryRes as $val) {
                    if ($val->deal_status == 1) {
                        $dealStatus = __('Completed', THEME_TEXTDOMAIN);
                    } else if ($val->deal_status == 2) {
                        $dealStatus = __('Initiated', THEME_TEXTDOMAIN);
                    } else if ($val->deal_status == 3) {
                        $dealStatus = __('Processing', THEME_TEXTDOMAIN);
                    } else if ($val->deal_status == 4) {
                        $dealStatus = __('Rejected', THEME_TEXTDOMAIN);
                    }
                    $userDetails = $this->user_details($val->user_id);
                    $dealObject->data['ID'] = $val->ID;
                    $dealObject->data['deal_id'] = $val->deal_id;
                    $dealObject->data['user_id'] = $val->user_id;
                    $dealObject->data['deal_name'] = $val->deal_name;
                    $dealObject->data['deal_description'] = $val->deal_description;
                    $dealObject->data['user_name'] = $userDetails->data['fname'] . ' ' . $userDetails->data['lname'];
                    $dealObject->data['transaction_id'] = $val->transaction_id;
                    $dealObject->data['total_price'] = 'R$ ' . number_format($val->total_price, 2);
                    $dealObject->data['status'] = $dealStatus;
                    $dealObject->data['locking_status'] = $val->deal_locking_status;
                    $dealObject->data['original_status'] = $val->deal_status;
                    $dealObject->data['initiated'] = $this->getProtugeeseFormattedDate($val->deal_initialized_on);
                    $dealObject->data['completed'] = ($val->deal_completed_on) ? $this->getProtugeeseFormattedDate($val->deal_completed_on) : __('Not set', THEME_TEXTDOMAIN);;
                }
            }
            return $dealObject;
        }

        /*
         * @param type string, int
         * @param $dealID, $userID
         * @return type array
         * @description It returns details of a deal products
         */

        public function getDealProductDetails($dealID = NULL, $userID = NULL, $productID = NULL, $category = NULL) {
            $dealProductDetails = [];
            $getDealProductDetailsQuery = "SELECT * FROM " . TBL_DEAL_FINALIZE_PRODUCTS . " WHERE `ID` !=''";
            if ($dealID) {
                $getDealProductDetailsQuery .= " AND `deal_id`='" . $dealID . "'";
            }
            if ($userID) {
                $getDealProductDetailsQuery .= " AND `user_id`=" . $userID . "";
            }
            if ($productID) {
                $getDealProductDetailsQuery .= " AND `product_id`=" . $productID . "";
            }
            if ($category) {
                $getDealProductDetailsQuery .= " AND `product_material_category`='" . $category . "'";
            }
            $getDealProductDetailsQueryRes = $this->db->get_results($getDealProductDetailsQuery);
            if (is_array($getDealProductDetailsQueryRes) && count($getDealProductDetailsQueryRes) > 0) {
                $i = 0;
                foreach ($getDealProductDetailsQueryRes as $eachDealProductDetails) {
                    $getStateDetails = get_term_by('id', $eachDealProductDetails->state, themeFramework::$theme_prefix . 'product_city');
                    $getCityDetails = get_term_by('id', $eachDealProductDetails->city, themeFramework::$theme_prefix . 'product_city');
                    $dealProductDetails[$i]['product_id'] = $eachDealProductDetails->product_id;
                    $dealProductDetails[$i]['product_material_category'] = $eachDealProductDetails->product_material_category;
                    $dealProductDetails[$i]['state'] = $getStateDetails->name;
                    $dealProductDetails[$i]['city'] = $getCityDetails->name;
                    $dealProductDetails[$i]['no_of_items'] = $eachDealProductDetails->no_of_items;
                    $dealProductDetails[$i]['price'] = 'R$ ' . number_format($eachDealProductDetails->price, 2);
                    $dealProductDetails[$i]['bundle_products'] = $eachDealProductDetails->product_bundles;
                    $i++;
                }
            }
            return $dealProductDetails;
        }

        /*
         * @param type string, int
         * @param $dealID, $userID, $productID
         * @return type array
         * @description It returns details of a deal products suppliers
         */

        public function getDealSupplierDetails($dealID = NULL, $userID = NULL, $productID = NULL) {
            $dealSupplierDetails = [];
            $getDealSupplierDetailsQuery = "SELECT * FROM " . TBL_DEAL_FINALIZE_SUPPLIERS . "  WHERE `ID` !=''";

            if ($dealID) {
                $getDealSupplierDetailsQuery .= " AND `deal_id`='" . $dealID . "'";
            }
            if ($userID) {
                $getDealSupplierDetailsQuery .= " AND `user_id`='" . $userID . "'";
            }
            if ($productID) {
                $getDealSupplierDetailsQuery .= " AND `product_id`='" . $productID . "'";
            }
            $getDealSupplierDetailsQueryRes = $this->db->get_results($getDealSupplierDetailsQuery);
            if (is_array($getDealSupplierDetailsQueryRes) && count($getDealSupplierDetailsQueryRes) > 0) {
                $i = 0;
                foreach ($getDealSupplierDetailsQueryRes as $eachSupplierDetails) {
                    $getStateDetails = get_term_by('id', $eachSupplierDetails->state, themeFramework::$theme_prefix . 'product_city');
                    $getCityDetails = get_term_by('id', $eachSupplierDetails->city, themeFramework::$theme_prefix . 'product_city');
                    $supplierDetails = $this->user_details($eachSupplierDetails->supplier_id);
                    /* $bundleProductDetails = unserialize($eachSupplierDetails->bundled_product_details);
                      if (is_array($bundleProductDetails) && count($bundleProductDetails) > 0) {
                      $joinedBundledProducts = join(', ', array_unique($bundleProductDetails));
                      } else {
                      $joinedBundledProducts = $bundleProductDetails;
                      } */
                    $dealSupplierDetails[$i]['product_id'] = $eachSupplierDetails->product_id;
                    $dealSupplierDetails[$i]['supplier_id'] = $eachSupplierDetails->supplier_id;
                    $dealSupplierDetails[$i]['state'] = $getStateDetails->name;
                    $dealSupplierDetails[$i]['city'] = $getCityDetails->name;
                    $dealSupplierDetails[$i]['supplier_name'] = ($eachSupplierDetails->supplier_id) ? $supplierDetails->data['fname'] . ' ' . $supplierDetails->data['lname'] : 'All suppliers';
                    $dealSupplierDetails[$i]['bundled_product_details'] = $eachSupplierDetails->bundled_product_details;
                    $i++;
                }
            }
            return $dealSupplierDetails;
        }

        /*
         * @param type array
         * @param $data
         * @return type int
         * @description It returns no of rows deleted
         */

        public function deleteFinazlizeData(array $data) {
            $delete_finalize_data = $this->db->delete(TBL_DEAL_FINALIZE, $data);
            return $delete_finalize_data;
        }

        /*
         * @param type array
         * @param $data
         * @return type int
         * @description It returns no of rows deleted
         */

        public function deleteFinazlizeProductData(array $data) {
            $delete_finalize_data = $this->db->delete(TBL_DEAL_FINALIZE_PRODUCTS, $data);
            return $delete_finalize_data;
        }

        /*
         * @param type array
         * @param $data
         * @return type int
         * @description It returns no of rows deleted
         */

        public function deleteFinazlizeSupplierData(array $data) {
            $delete_finalize_data = $this->db->delete(TBL_DEAL_FINALIZE_SUPPLIERS, $data);
            return $delete_finalize_data;
        }

        /*
         * @param type string, int
         * @param $dealID, $userID
         * @return type array of objects
         * @description It returns distinct product id from deal finalize suppliers
         */

        public function selectDistinctProductIDs($dealID, $userID) {
            $selectDistinctProductIDsQuery = "SELECT DISTINCT(`product_id`) FROM " . TBL_DEAL_FINALIZE_SUPPLIERS . " WHERE `deal_id`='" . $dealID . "' AND `user_id`=" . $userID . "";
            $selectDistinctProductIDsQueryRes = $this->db->get_results($selectDistinctProductIDsQuery);
            return $selectDistinctProductIDsQueryRes;
        }

        /*
         * @param type string, int
         * @param $dealID, $userID
         * @return type array of objects
         * @description It returns distinct product id from deal finalize suppliers
         */

        public function selectDistinctSupplierIDs($dealID, $userID) {
            $selectDistinctProductIDsQuery = "SELECT DISTINCT(`supplier_id`) FROM " . TBL_DEAL_FINALIZE_SUPPLIERS . " WHERE `deal_id`='" . $dealID . "' AND `user_id`=" . $userID . "";
            $selectDistinctProductIDsQueryRes = $this->db->get_results($selectDistinctProductIDsQuery);
            return $selectDistinctProductIDsQueryRes;
        }

        /*
         * @param type array
         * @param $updateData, $whereData
         * @return type int
         * @description It returns no of updated rows
         */

        public function updateDealFinalizeData(array $updateData, array $whereData) {
            $update_deal_data = $this->db->update(TBL_DEAL_FINALIZE, $updateData, $whereData);
            return $update_deal_data;
        }

        /*
         * @param type string, int
         * @param $dealID, $userID
         * @return type array
         * @description It returns no of suppliers need to email for deal finalization
         */

        public function sortingSuppliersToEmailAboutDealFinalization($dealID, $userID) {
            $returnedArr = [];
            $newArr = $this->sortingAllSuppliersSupplyAllProducts($dealID);
            $getDistinctProduct = $this->selectDistinctProductIDs($dealID, $userID);
            if (is_array($getDistinctProduct) && count($getDistinctProduct) > 0) {
                foreach ($getDistinctProduct as $eachDistinctProduct) {
                    $dealSupplierDetails = $this->getDealSupplierDetails($dealID, $userID, $eachDistinctProduct->product_id);
                    if (is_array($dealSupplierDetails) && count($dealSupplierDetails) > 0) {
                        foreach ($dealSupplierDetails as $eachSupplier) {
                            if (!empty($eachSupplier['supplier_id'])) {
                                //$returnedArr[$eachSupplier['supplier_id']][] = 'For ' . get_the_title($eachDistinctProduct->product_id) . ' - ' . $eachSupplier['bundled_product_details'];
                                $returnedArr[$eachSupplier['supplier_id']][] = $eachDistinctProduct->product_id . '%' . $eachSupplier['bundled_product_details'];
                            }
                        }
                    }
                }
            }

            if (is_array($newArr) && count($newArr) > 0) {
                foreach ($newArr as $eachSupplierKey => $eachSupplierVal) {
                    if (array_key_exists($eachSupplierKey, $returnedArr)) {
                        $returnedArr[$eachSupplierKey] = join(',', $returnedArr[$eachSupplierKey]) . ',' . $eachSupplierVal;
                    } else {
                        $returnedArr[$eachSupplierKey] = $eachSupplierVal;
                    }
                }
            } else {
                if (is_array($returnedArr) && count($returnedArr) > 0) {
                    foreach ($returnedArr as $key => $val) {
                        $returnedArr[$key] = join(', ', $val);
                    }
                }
            }
            return $returnedArr;
        }

        public function sortingAllSuppliersSupplyAllProducts($dealID) {
            $mainQuery = "SELECT * FROM " . TBL_DEAL_FINALIZE_SUPPLIERS . " WHERE `deal_id`='" . $dealID . "' AND `supplier_id`=''";
            $mainQueryRes = $this->db->get_results($mainQuery);
            $getSuppliers = $this->getSuppliers();
            if (is_array($mainQueryRes) && count($mainQueryRes) > 0) {
                $productToSupply = unserialize($mainQueryRes[0]->bundled_product_details);
                if (is_array($getSuppliers) && count($getSuppliers) > 0) {
                    foreach ($getSuppliers as $eachSupplier) {
                        //$newArr[$eachSupplier->ID] = 'For ' . get_the_title($mainQueryRes[0]->product_id) . ' - ' . $productToSupply[0];
                        $newArr[$eachSupplier->ID] = $mainQueryRes[0]->product_id . '%' . $productToSupply[0];
                    }
                }
            }
            return $newArr;
        }

        public function getSuppliersByCityCategory($product_id, $city, $noOfItems) {
            $suppArr = [];
            $product_details = $this->product_details($product_id);
            $productCats = $product_details->data['product_categories_arr'];
            $getSuppliersFromCities = ['role' => 'supplier', 'meta_query' => [
                    [
                        'key' => '_city',
                        'value' => $city,
                        'compare' => '='
                    ]
            ]];
            $getSuppliers = get_users($getSuppliersFromCities);
            if (is_array($getSuppliers) && count($getSuppliers) > 0) {
                foreach ($getSuppliers as $eachSupplier) {
                    $get_user_details = $this->user_details($eachSupplier->ID);
                    $prioritySuppliersEligibility = $this->prioritySuppliersEligibility($eachSupplier->ID);
                    $getBuisnessCategories = $get_user_details->data['buisness_categories'];
                    $getSameCats = array_intersect($productCats, $getBuisnessCategories);
                    if (is_array($getSameCats) && count($getSameCats) > 0) {
                        if ($product_details->data['is_simple'] == TRUE) {
                            if ($prioritySuppliersEligibility == TRUE) {
                                //$suppArr[$eachSupplier->ID] = get_the_title($product_details->data['ID']) . '-' . $noOfItems . '-' . $product_details->data['unit'];
                                $suppArr[$eachSupplier->ID] = $product_details->data['ID'] . '-' . $noOfItems . '-' . $product_details->data['unit'];
                            } else {
                                $suppArr[1] = $product_details->data['ID'] . '-' . $noOfItems . '-' . $product_details->data['unit'];
                            }
                        } else {
                            $simpleProductsArr = [];
                            $prepareBundledProducts = $this->prepareBundleProducts($product_id);
                            $getBundleProductQuantity = $product_details->data['product_cat_quantity'];
                            if (is_array($prepareBundledProducts) && count($prepareBundledProducts) > 0) {
                                foreach ($prepareBundledProducts as $eachBundleProductKey => $eachBundleProductVal) {
                                    $getProductDetails = $this->product_details($eachBundleProductVal['product_id']);
                                    //$simpleProductsArr[] = get_the_title($eachBundleProductVal['product_id']) . '-' . $getBundleProductQuantity[$eachBundleProductKey] . '-' . $getProductDetails->data['unit'];
                                    $simpleProductsArr[] = $eachBundleProductVal['product_id'] . '-' . $getBundleProductQuantity[$eachBundleProductKey] . '-' . $getProductDetails->data['unit'];
                                }
                            }
                            if ($prioritySuppliersEligibility == TRUE) {
                                $suppArr[$eachSupplier->ID] = $simpleProductsArr;
                            } else {
                                $suppArr[1] = $simpleProductsArr;
                            }
                        }
                    }
                }
            }
            return $suppArr;
        }

        public function prioritySuppliersEligibility($supplier_id) {
            $returnFlag = FALSE;
            $supplierDetails = $this->user_details($supplier_id);
            $GC = $this->global_deal_acceptance;
            $RC = $this->global_deal_acceptance_counter;
            $supplierPlanDetails = $this->getMembershipPlanDetails($supplierDetails->data['selected_plan']);
            $getGoldDetails = $this->getPlanMaxDeals('gold');
            $getSilverDetails = $this->getPlanMaxDeals('silver');
            $getBronzeDetails = $this->getPlanMaxDeals('bronze');
            $getBronzePlusOne = $getBronzeDetails + 1;
            $getSilverPlusOne = $getSilverDetails + 1;
//            if (($RC <= 2) && ($supplierPlanDetails->data['name'] == 'gold' || $supplierPlanDetails->data['name'] == 'silver' || $supplierPlanDetails->data['name'] == 'bronze')) {
            if (($RC < $getBronzeDetails) && ($supplierPlanDetails->data['name'] == 'gold' || $supplierPlanDetails->data['name'] == 'silver' || $supplierPlanDetails->data['name'] == 'bronze')) {
                $returnFlag = TRUE;
                $RC++;
                update_option('_global_deal_acceptance_counter', $RC);
//            } else if (($RC >= 3 && $RC <= 6) && ($supplierPlanDetails->data['name'] == 'gold' || $supplierPlanDetails->data['name'] == 'silver')) {
            } else if (($RC >= $getBronzeDetails && $RC < $getSilverDetails) && ($supplierPlanDetails->data['name'] == 'gold' || $supplierPlanDetails->data['name'] == 'silver')) {
                $returnFlag = TRUE;
                $RC++;
                update_option('_global_deal_acceptance_counter', $RC);
//            } else if (($RC >= 7 && $RC < $GC) && ($supplierPlanDetails->data['name'] == 'gold')) {
            } else if (($RC >= $getSilverDetails && $RC < $GC) && ($supplierPlanDetails->data['name'] == 'gold')) {
                $returnFlag = TRUE;
                $RC++;
                update_option('_global_deal_acceptance_counter', $RC);
            } else if (($RC == $GC) && ($supplierPlanDetails->data['name'] == 'gold')) {
                $returnFlag = TRUE;
                $RC = 0;
                update_option('_global_deal_acceptance_counter', $RC);
            } else {
                $returnFlag = FALSE;
                if ($RC < $GC) {
                    $RC++;
                } else {
                    $RC = 0;
                }
                update_option('_global_deal_acceptance_counter', $RC);
            }
            return $returnFlag;
        }

        public function generateSupplierEmailProductData($dealID, $productSuppliedData, $deal_name = NULL, $deal_description = NULL) {
            $mailMsg = NULL;
            $onlySimpleProducts = [];
            $onlySimpleProductUnit = [];
            if ($deal_name) {
                $dealName = $deal_name . ' ' . $dealID;
            } else {
                $dealName = 'My deal ' . $dealID;
            }

            if ($deal_description) {
                $dealDesc = $deal_description;
            } else {
                $dealDesc = 'No description provided.';
            }

            $dealProductDetails = $this->getDealProductDetails($dealID);
            $explodedSuppliedData = explode(',', $productSuppliedData);
            if (is_array($explodedSuppliedData) && count($explodedSuppliedData) > 0) {
                foreach ($explodedSuppliedData as $val) {
                    $explodedVal = explode('%', $val);
                    $unserializeContent = unserialize($explodedVal[1]);
                    if (is_array($unserializeContent) && count($unserializeContent) > 0) {
                        foreach ($unserializeContent as $eachUnserializeCont) {
                            $explodedSupplierProducts = explode('-', $eachUnserializeCont);
                            if (array_key_exists($explodedSupplierProducts[0], $onlySimpleProducts) == TRUE) {
                                $totalQuantity = $onlySimpleProducts[$explodedSupplierProducts[0]] + $explodedSupplierProducts[1];
                                $onlySimpleProducts[$explodedSupplierProducts[0]] = $totalQuantity;
                                $onlySimpleProductUnit[$explodedSupplierProducts[0]] = $explodedSupplierProducts[2];
                            } else {
                                $onlySimpleProducts[$explodedSupplierProducts[0]] = $explodedSupplierProducts[1];
                                $onlySimpleProductUnit[$explodedSupplierProducts[0]] = $explodedSupplierProducts[2];
                            }
                        }
                    } else {
                        $explodedSupplierProducts = explode('-', $unserializeContent);
                        if (array_key_exists($explodedSupplierProducts[0], $onlySimpleProducts) == TRUE) {
                            $totalQuantity = $onlySimpleProducts[$explodedSupplierProducts[0]] + $explodedSupplierProducts[1];
                            $onlySimpleProducts[$explodedSupplierProducts[0]] = $totalQuantity;
                            $onlySimpleProductUnit[$explodedSupplierProducts[0]] = $explodedSupplierProducts[2];
                        } else {
                            $onlySimpleProducts[$explodedSupplierProducts[0]] = $explodedSupplierProducts[1];
                            $onlySimpleProductUnit[$explodedSupplierProducts[0]] = $explodedSupplierProducts[2];
                        }
                    }
                }
            }
            if (is_array($onlySimpleProducts) && count($onlySimpleProducts) > 0) {
                foreach ($onlySimpleProducts as $eachKey => $eachVal) {
                    $productSuppliedImg = wp_get_attachment_image_src(get_post_thumbnail_id($eachKey), 'full');
                    if ($productSuppliedImg[0]) {
                        $mainSuppliedImg = $productSuppliedImg[0];
                    } else {
                        $mainSuppliedImg = 'https://via.placeholder.com/100x75';
                    }
                    $mailMsg .= '<tr style="background: #e6e6e6;">';
                    $mailMsg .= '<td style="background-color:transparent; width: 100px; color: #fff; border-top: none; font-weight: normal;">';
                    $mailMsg .= '<img style="margin: 10px; border-radius:100%;" src="' . $mainSuppliedImg . '" width="60" height="60"/>';
                    $mailMsg .= '</td>';
                    $mailMsg .= '<td style="background-color:transparent; font-family: verdana; padding: 15px; color: #000; border-top: none; font-weight: normal;">';
                    $mailMsg .= get_the_title($eachKey);
                    $mailMsg .= '</td>';
                    $mailMsg .= '<td style="background-color:transparent; font-family: verdana; padding: 15px; color: #000; border-top: none; font-weight: normal;">';
                    $mailMsg .= '' . $eachVal . ' ' . $onlySimpleProductUnit[$eachKey];
                    $mailMsg .= '</td>';
                    $mailMsg .= '</tr>';
                    $mailMsg .= '<br>';
                }
            }
            return $mailMsg;
        }

        public function getUserDealProductCategories($deal_id) {
            $cartCategoryArr = [];
            $getDealItems = $this->getDealProductDetails($deal_id);
            if (is_array($getDealItems) && count($getDealItems) > 0) {
                foreach ($getDealItems as $eachCartItem) {
                    $getProductDetails = $this->product_details($eachCartItem['product_id']);
                    /* $getProductCategories = $getProductDetails->data['product_categories_arr'];
                      if (is_array($getProductCategories) && count($getProductCategories) > 0) {
                      foreach ($getProductCategories as $eachCategory) {
                      $cartCategoryArr[] = $eachCategory;
                      }
                      } */
                    $cartCategoryArr[] = $eachCartItem['product_material_category'];
                }
                if (is_array($cartCategoryArr) && count($cartCategoryArr) > 0) {
                    $uniqueCartCategoryArr = array_unique($cartCategoryArr);
                } else {
                    $uniqueCartCategoryArr = [];
                }
            }
            return $uniqueCartCategoryArr;
        }

        public function getUserDealCategoryProducts($deal_id, $category = NULL) {
            $returnProductsArr = [];

            if ($category) {
                $getDealItems = $this->getDealProductDetails($deal_id, '', '', $category);
                if (is_array($getDealItems) && count($getDealItems) > 0) {
                    foreach ($getDealItems as $eachCartItem) {
                        $newProductArr[] = $eachCartItem['product_id'];
                    }
                }
                $returnProductsArr[$category] = $newProductArr;
            } else {
                $getUserCartProductCategories = $this->getUserDealProductCategories($deal_id);
                if (is_array($getUserCartProductCategories) && count($getUserCartProductCategories) > 0) {
                    foreach ($getUserCartProductCategories as $eachUserCartCategory) {
                        $getCartItems = $this->getDealProductDetails($deal_id, '', '', $eachUserCartCategory);
                        if (is_array($getCartItems) && count($getCartItems) > 0) {
                            $newProductArr = [];
                            foreach ($getCartItems as $eachCartItem) {
                                $newProductArr[] = $eachCartItem['product_id'];
                            }
                        }
                        $returnProductsArr[$eachUserCartCategory] = $newProductArr;
                    }
                }
            }

            return $returnProductsArr;

            /* if ($category) {
              $getProductsByCategory = get_posts(['post_type' => themeFramework::$theme_prefix . 'product', 'posts_per_page' => -1, 'include' => $returnProductsArr, 'tax_query' => [
              [
              'taxonomy' => themeFramework::$theme_prefix . 'product_category',
              'field' => 'slug',
              'terms' => $category
              ]
              ]]);
              if (is_array($getProductsByCategory) && count($getProductsByCategory) > 0) {
              foreach ($getProductsByCategory as $eachProducts) {
              $newProductArr[] = $eachProducts->ID;
              }
              }
              return $newProductArr;
              } else {
              return $returnProductsArr;
              } */
        }

        public function getDealSuppliersStatus($deal_id) {
            $supplierQuery = "SELECT * FROM " . TBL_DEAL_FINALIZE_SUPPLIERS . " WHERE `deal_id`='" . $deal_id . "'";
            $supplierQueryRes = $this->db->get_results($supplierQuery);
            if (is_array($supplierQueryRes) && count($supplierQueryRes) > 0) {
                foreach ($supplierQueryRes as $eachSupplierQueryRes) {
                    if ($eachSupplierQueryRes->supplier_id == 9999999999) {
                        $returnFlag = 2;
                    } else {
                        $returnFlag = 1;
                        break;
                    }
                }
            }
            return $returnFlag;
        }

        public function getUserCompletedDeals($user_id) {
            $dealQuery = "SELECT * FROM " . TBL_DEAL_FINALIZE . " WHERE `user_id`=" . $user_id . " AND `deal_status`=1";
            $dealQueryRes = $this->db->get_results($dealQuery);
            return $dealQueryRes;
        }

    }

}

