<?php
/*
 * Admin Screen Extra Column
 */

/* -----------------FOR USER----------------- */

add_filter( 'bulk_actions-users', 'register_my_bulk_actions' );
add_filter( 'handle_bulk_actions-users', 'my_bulk_action_handler', 10, 3 );

/**
 * Adding form fields in edit screen
 */


function register_my_bulk_actions($bulk_actions) {
   
  $bulk_actions['activate_profile'] = __( 'Activate', 'activate_profile');
  $bulk_actions['deactivate_profile'] = __( 'De-Activate', 'deactivate_profile');
  return $bulk_actions;
}

function my_bulk_action_handler( $redirect_to, $doaction, $users_ids ) {
  if($doaction == 'activate_profile'){
      foreach ( $users_ids as $user_id ) {
        update_user_meta($user_id, '_admin_approval', 1);
      }
  }
  
  if($doaction == 'deactivate_profile'){
      foreach ( $users_ids as $user_id ) {
        update_user_meta($user_id, '_admin_approval', 2);
      }
  }
  
  $redirect_to = add_query_arg( 'bulk_user_account', count( $users_ids ), $redirect_to );
  return $redirect_to;
}



/*   add search filter by active deactive user*/

//add_action('restrict_manage_users', 'user_status_filter');
function user_status_filter($which) {
    global $pagenow;
    
    
    
    echo '<input type="submit" class="button" value="Filter">';
}

//add_filter( 'pre_get_users', 'filter_user_status' );
function filter_user_status($query) {
    global $pagenow;

        // if (is_admin() && 'users.php' == $pagenow && current_user_can('manage_options')) {
        // if (current_user_can('list_users') && 'users.php' == $pagenow) {
        //$meta_query = [];

        $get_user_status = $_GET['filter_status'][0];
        
        if (NULL !== $get_user_status && '' !== $get_user_status) {
            $meta_query = [
              'relation' => 'AND',
                [
                  'key' => '_admin_approval',
                  'value' => $get_user_status,
                  'compare' => '='
                ]
            ];
        }

        
          

        // if (count($meta_query) > 0) {
            $query->set('meta_query', $meta_query);
        //}
    //}

    return $query;
}