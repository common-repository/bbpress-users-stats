<?php
/*
Plugin Name: bbPress Users Stats
Plugin URI: 
Description:  Displays per user BBPress stats
Version: 0.1.0
Author: bastho
Author URI: http://urbancube.fr
License: GPLv3
*/

add_action( 'show_user_profile', array( 'bbPressUserStat', 'profile') );
add_action( 'edit_user_profile', array( 'bbPressUserStat', 'profile') );


add_filter('manage_users_columns', array( 'bbPressUserStat', 'columns_head'),2);  
add_action('manage_users_custom_column', array( 'bbPressUserStat', 'columns_content'), 10, 4); 

//add_action( 'manage_users_sortable_columns',  array( 'bbPressUserStat', 'sortable')  );
//add_action( 'manage_users-network_sortable_columns',  array( 'bbPressUserStat', 'sortable')  );

class bbPressUserStat{
  function stat($user_id,$type='topic'){
  	$query = new WP_Query( array('post_type'=>$type,'author'=>$user_id) );				
	// The Loop
	if ( $query->have_posts() ) {
		return '<a href="edit.php?post_type=reply&author='.$user_id.'">'.$query->post_count.'</a>';
	}
	else{
		return __('None');
	}
  }
  function columns_head($defaults) {  
    $defaults['topics'] = __('Topics','bbpress'); 
    $defaults['replies'] = __('Replies','bbpress'); 
    return $defaults;  
  }  
  function sortable( $columns ) {
		$columns[ 'topics' ] = 'topics';
		$columns[ 'replies' ] = 'replies';

		return $columns;
  }  
  function columns_content($value='', $column_name='', $id=0) {
  	if ($column_name == 'topics') {  
      return self::stat($id,'topic');
    }
    if ($column_name == 'replies') {  
      return self::stat($id,'reply');
    }  
	return $value;
  }
function profile( $user ) { ?>
	<h3><?php _e('Forums','bbpress'); ?></h3>
	<table class="form-table">
		<tr>
			<th><?php _e('Topics','bbpress'); ?></th>
			<td>
				<?php echo self::stat($user->ID,'topic');?>
			</td>
		</tr>
		<tr>
			<th><?php _e('Replies','bbpress'); ?></th>
			<td>
				<?php echo self::stat($user->ID,'reply');?>
			</td>
		</tr>
	</table>
<?php } 

}
