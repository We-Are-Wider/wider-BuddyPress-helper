<?php
/*
Plugin Name: Tanc BuddyPress Helper
Plugin URI: http://www.tancdesign.com
Description: WORK IN PROGRESS! Adds useful BuddyPress global variables to the admin bar for debug purposes.
Version: 0.2
Author: Jonny Allbut/Tanc Design
Author URI: http://jonnya.net
License: GPLv2 or later
*/

/*
TODO: This is a good start, but needs to be extended to include all BuddyPress views!
*/

// Only do stuff if BuddyPress is active thanks!
// Do everything, but not in the admin area thanks buddy
function tanc_bphelper_do() {
	if ( is_super_admin() && !is_admin() ) {
		add_action( 'admin_bar_menu', 'tanc_bphelper_add' );
		add_action( 'admin_bar_menu', 'tanc_bphelper_info' );
	}	
}
add_action('bp_init', 'tanc_bphelper_do', 1);


function tanc_bphelper_add( $admin_bar ) {
	$admin_bar->add_menu( array(
	    'id'    => 'tanc-bp-helper', 
	    'parent' => 'top-secondary',
	    'title' => 'BP Info',
	    'href'  => '#',
	    'meta'  => array(
	    	'title' => __('BuddyPress Debug information')
	    ),
	) );	
}


function tanc_bphelper_info($admin_bar) {
	$bp_val = array();

	if ( bp_current_component() && bp_is_user() ) {
		$bp_val['component'] = bp_current_component();
	}

	if ( bp_current_action() ) {
		$bp_val['action'] = bp_current_action();
	}

	if ( bp_action_variables() ) {
		$bp_val['action_variables'] = bp_action_variables();
	}

	if ( bp_is_group() ) {
		$group = groups_get_current_group();
		$bp_val['slug']   = $group->slug;
		$bp_val['status'] = $group->status;
	}	
	
	if ( !empty($bp_val) )
		foreach ($bp_val as $key => $value) {
			
			if (is_array($value)) {

				foreach ($value as $k => $v) {
					$admin_bar->add_menu( array(
					    'id'    => 'tanc-bp-helper-'.$k, 
					    'parent' => 'tanc-bp-helper',
					    'title' => $key . '[' . $k . '] = ' . $v,
					    'href'  => '#',
					    'meta'  => array(
					    	'title' => $k
					    ),
					) );	
				}	

			} else {
				
				$admin_bar->add_menu( array(
				    'id'    => 'tanc-bp-helper-'.$key, 
				    'parent' => 'tanc-bp-helper',
				    'title' => ucfirst($key) . ' = ' . $value,
				    'href'  => '#',
				    'meta'  => array(
				    	'title' => $key
				    ),
				) );

			}

		}
	
	else
		$admin_bar->add_menu( array(
	    'id'    => 'tanc-bp-helper-2', 
	    'parent' => 'tanc-bp-helper',
	    'title' => __('No informaion available'),
	    'href'  => '#',
	    'meta'  => array(
	    	'title' => __('No informaion available')
	    ),
		) );
			
}
?>