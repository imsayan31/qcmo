<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classCart
 *
 * @author Sayanta Dey
 */
if (!class_exists('classCart')) {

    class classCart extends GeneralTheme {

        //put your code here
        public function __construct() {
            global $wpdb;
            $this->db = &$wpdb;
        }

        public function addToCart(array $data) {
            $cart_insert_id = $this->db->insert(TBL_CART, $data);
            return $this->db->insert_id;
        }

        public function removeFromCart($product_id, $user_id, $state, $city) {
            $cart_deleted_row = $this->db->delete(TBL_CART, ['user_id' => $user_id, 'product_id' => $product_id, 'state' => $state, 'city' => $city]);
            return $cart_deleted_row;
        }

        public function emptyCart($user_id) {
            $cart_deleted_row = $this->db->delete(TBL_CART, ['user_id' => $user_id]);
            return $cart_deleted_row;
        }

        public function isItemInCart($product_id, $user_id, $state, $city) {
            $isItemInCartQuery = "SELECT * FROM " . TBL_CART . " WHERE `user_id`=" . $user_id . " AND `product_id`=" . $product_id . "";
            if ($state) {
                $isItemInCartQuery .= " AND `state`=" . $state . "";
            }
            if ($city) {
                $isItemInCartQuery .= " AND `city`=" . $city . "";
            }
            $isItemInCartQueryRes = $this->db->get_results($isItemInCartQuery);
            if (is_array($isItemInCartQueryRes) && count($isItemInCartQueryRes) > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }

        public function getCartItems($user_id = NULL, $product_id = NULL, $state = NULL, $city = NULL, $category = NULL) {
            $getCartItemsQuery = "SELECT * FROM " . TBL_CART . " WHERE `ID` !=''";
            if ($user_id) {
                $getCartItemsQuery .= " AND `user_id`=" . $user_id . "";
            }
            if ($product_id) {
                $getCartItemsQuery .= " AND `product_id`=" . $product_id . "";
            }
            if ($state) {
                $getCartItemsQuery .= " AND `state`=" . $state . "";
            }
            if ($city) {
                $getCartItemsQuery .= " AND `city`=" . $city . "";
            }
            if ($category) {
                $getCartItemsQuery .= " AND `product_material_category`='" . $category . "'";
            }
            $getCartItemsQueryRes = $this->db->get_results($getCartItemsQuery);
            return $getCartItemsQueryRes;
        }

        public function updateCartItems(array $data, array $where) {
            $cart_updated_row = $this->db->update(TBL_CART, $data, $where);
            return $cart_updated_row;
        }

        public function countCartItems($user_id = NULL) {
            $getCart = $this->getCartItems($user_id);
            $totalNumber = count($getCart);
            return $totalNumber;
        }

        public function updatedNoOfItems($product_id, $user_id, $state, $city, $totalNo = NULL) {
            $getCartItems = $this->getCartItems($user_id, $product_id, $state, $city);
            if (is_array($getCartItems) && count($getCartItems) > 0) {
                $totalNo = $totalNo + $getCartItems[0]->no_of_items;
            } else {
                $totalNo = 1;
            }
            return $totalNo;
        }

        public function updatedCartPrice($product_id, $user_id, $state, $city, $noOfItems) {
            //$totalPrice = $this->getProductPrice($product_id, $city);
            $productDetails = $this->product_details($product_id);
            $totalPrice = $productDetails->data['main_price'];
            //$totalPrice = get_post_meta($product_id, '_product_price', TRUE);
            $getCartItems = $this->getCartItems($user_id, $product_id, $state, $city);
            if (is_array($getCartItems) && count($getCartItems) > 0) {
                $newPrice = ($noOfItems * $getCartItems[0]->product_price);
            } else {
                $newPrice = ($noOfItems * $totalPrice);
            }
            /* if (is_array($getCartItems) && count($getCartItems) > 0) {
              $totalPrice = $newPrice + $getCartItems[0]->total_price;
              } else {
              $totalPrice = $newPrice;
              } */
            return $newPrice;
        }

        public function checkCartQuantity($netCartItems) {
            if (is_array($netCartItems) && count($netCartItems) > 0) {
                foreach ($netCartItems as $eachKey => $eachVal) {
                    if ($eachVal[0] <= 0) {
                        $returnVal = FALSE;
                        break;
                    } else {
                        $returnVal = TRUE;
                    }
                }
            }
            return $returnVal;
        }

        public function getCartTotal($user_id = NULL) {
            $totalCartPrice = NULL;
            $getCartItems = $this->getCartItems($user_id);
            if (is_array($getCartItems) && count($getCartItems) > 0) {
                foreach ($getCartItems as $eachCartItem) {
                    $totalCartPrice = $totalCartPrice + $eachCartItem->total_price;
                }
            }
            return $totalCartPrice;
        }

        public function getUserCartProductCategories($user_id) {
            $cartCategoryArr = [];
            $getCartItems = $this->getCartItems($user_id);

            if (is_array($getCartItems) && count($getCartItems) > 0) {
                foreach ($getCartItems as $eachCartItem) {
                    $getProductDetails = $this->product_details($eachCartItem->product_id);
                    /* $getProductDetails = $this->product_details($eachCartItem->product_id);
                      $getProductCategories = $getProductDetails->data['product_categories_arr'];
                      if (is_array($getProductCategories) && count($getProductCategories) > 0) {
                      foreach ($getProductCategories as $eachCategory) {
                      $cartCategoryArr[] = $eachCategory;
                      }
                      } */
                    //$cartCategoryArr[] = $getProductDetails->data['material_category'];
                    $cartCategoryArr[] = $eachCartItem->product_category;
                }
                if (is_array($cartCategoryArr) && count($cartCategoryArr) > 0) {
                    $uniqueCartCategoryArr = array_unique($cartCategoryArr);
                } else {
                    $uniqueCartCategoryArr = [];
                }
            }
            return $uniqueCartCategoryArr;
        }

        public function getUserCartCategoryProducts($user_id, $category = NULL) {
            $returnProductsArr = [];
            if ($category) {
                $getCartItems = $this->getCartItems($user_id, '', '', '', $category);
                if (is_array($getCartItems) && count($getCartItems) > 0) {
                    foreach ($getCartItems as $eachCartItem) {
                        $newProductArr[] = $eachCartItem->product_id;
                    }
                }
                $returnProductsArr[$category] = $newProductArr;
            } else {
                $getUserCartProductCategories = $this->getUserCartProductCategories($user_id);
                if (is_array($getUserCartProductCategories) && count($getUserCartProductCategories) > 0) {
                    foreach ($getUserCartProductCategories as $eachUserCartCategory) {
                        $getCartItems = $this->getCartItems($user_id, '', '', '', $eachUserCartCategory);
                        if (is_array($getCartItems) && count($getCartItems) > 0) {
                            $newProductArr = [];
                            foreach ($getCartItems as $eachCartItem) {
                                $newProductArr[] = $eachCartItem->product_id;
                            }
                        }
                        $returnProductsArr[$eachUserCartCategory] = $newProductArr;
                    }
                }
            }


            return $returnProductsArr;

            /* if (is_array($getCartItems) && count($getCartItems) > 0) {
              foreach ($getCartItems as $eachCartItem) {
              $returnProductsArr[] = $eachCartItem->product_id;
              }
              }
              if ($category) {
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

    }

}

