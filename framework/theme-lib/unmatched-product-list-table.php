<?php

/*
 * ---------------------------------------
 * THEME LIST TABLE:: FOR USE IN ADMIN 
 * ---------------------------------------
 */

if (!class_exists('Unmatched_Product_List_Table'))
    return;

class Unmatched_Product_List_Table extends Theme_List_Table {

    function __construct() {
        global $status, $page;

        //Set parent defaults
        parent::__construct(array(
            'singular' => 'search_string_val', //singular name of the listed records
            'plural' => 'env_invests', //plural name of the listed records
            'ajax' => false        //does this table support ajax?
        ));
    }

    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'search_string':
                return $item[$column_name];
            case 'user_name':
                return $item[$column_name];
            case 'date':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="%1$s[]" value="%2$s" />',
                /* $1%s */ $this->_args['singular'], //Let's simply repurpose the table's singular label ("movie")
                /* $2%s */ $item['search_string_val']             //The value of the checkbox should be the record's id
        );
    }

    function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'user_name' => '<strong>'.__('User name', 'wordpress').'</strong>',
            'search_string' => '<strong>'.__('Searched String', 'wordpress').'</strong>',
            'date' => '<strong>'.__('Searched On', 'wordpress').'</strong>'
        );
        return $columns;
    }

    function prepare_items($data) {
        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 10;


        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        //$sortable = $this->get_sortable_columns();


        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);


        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();


        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example 
         * package slightly different than one you might build on your own. In 
         * this example, we'll be using array manipulation to sort and paginate 
         * our data. In a real-world implementation, you will probably want to 
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        /* function usort_reorder($a, $b) {
          $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'title'; //If no sort, default to title
          $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
          $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
          return ($order === 'asc') ? $result : -$result; //Send final sort direction to usort
          }

          usort($data, 'usort_reorder'); */

        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();

        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = count($data);

        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        if ($total_items > 0) {
            $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);
        }



        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;


        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args(array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page, //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items / $per_page)   //WE have to calculate the total number of pages
        ));
    }

    public function get_bulk_actions() {

        return array(
            'delete' => __('Delete', 'your-textdomain'),
                //'approve' => __('Approve', 'your-textdomain'),
                //'reject' => __('Reject', 'your-textdomain'),
        );
    }

    public function process_bulk_action() {
        global $wpdb;

        $getNotMatchedProducts = get_option('_not_matched_products');

        // security check!
        if (isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce'])) {

            $nonce = filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING);
            $action = 'bulk-' . $this->_args['plural'];

            if (!wp_verify_nonce($nonce, $action))
                wp_die('Nope! Security check failed!');
        }

        $action = $this->current_action();

        switch ($action) {

            case 'delete':
                $getToBeDeletedSearchedString = $_GET['search_string_val'];
                if (is_array($getNotMatchedProducts) && count($getNotMatchedProducts) > 0) {
                    foreach ($getNotMatchedProducts as $eachNotMatchedStringKey => $eachNotMatchedStringVal) {
                        if (in_array($eachNotMatchedStringVal, $getToBeDeletedSearchedString)) {
                            unset($getNotMatchedProducts[$eachNotMatchedStringKey]);
                        }
                    }
                    $updatedNotMatchedProducts = array_values($getNotMatchedProducts);
                    update_option('_not_matched_products', $updatedNotMatchedProducts);
                }
                wp_redirect(admin_url() . 'admin.php?page=unmatched-products');
                break;
            default:
                // do nothing or something else
                break;
        }

        return;
    }

}
