<?php

class ProductLaunchCountdown_Widgets
{

	public function init()
	{
		add_action('init', array($this, 'on_init_action'));
		add_action( 'widgets_init', array($this, 'register_widget'));
	}

	public function on_init_action()
	{
		$labels = array(
		    'name' => 'Product Launch Countdown Widget',
		    'singular_name' => 'Product Launch Countdown Widget',
		    'add_new' => 'Add New',
		    'add_new_item' => 'Add New ProductLaunchCountdown Widget',
		    'edit_item' => 'Edit Widget',
		    'new_item' => 'New Widget',
		    'all_items' => 'All Widgets',
		    'view_item' => 'View  Widget',
		    'search_items' => 'Search Widgets',
		    'not_found' =>  'No widgets found',
		    'not_found_in_trash' => 'No widgets found in Trash', 
		    'parent_item_colon' => '',
		    'menu_name' => 'Product Launch Countdown'
		  );
		$args = array(
			'public' => true,
			'publicly_queryable' => false,
			'show_in_nav_menus' => false,
			'exclude_from_search' => true,
			'labels' => $labels,
			'has_archive' => false,
			'supports' => array('title', 'editor')
		);
   		register_post_type( 'plc-widget', $args );

	}

	public function register_widget()
	{
		register_widget('ProductLaunchCountdown_Widgets_Widget');  
	}

}