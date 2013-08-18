<?php
/*
Plugin Name: Product Launch Countdown Widgets
Plugin URI: http://wordpress.org/extend/plugins/product-launch-countdown/
Description: A wordpress widget that displays a product launch countdown.
Version: 1.0
Author: TheOnlineHero - Tom Skroza
License: GPL2
*/

/*  Copyright 2013  TheOnlineHero - Tom Skroza

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once 'includes/class-product-launch-countdown-widgets.php';
require_once 'includes/class-product-launch-countdown-widgets-widget.php';


function product_launch_countdown_admin_init() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker');
        wp_register_script("jquery-timepicker", plugins_url("/js/jquery.timepicker.min.js", __FILE__));
    wp_enqueue_script('jquery-timepicker');

    wp_register_script("datepicker", plugins_url("/js/datepicker.js", __FILE__));
    wp_enqueue_script('datepicker');

    wp_enqueue_style("jquery-ui-core");
      wp_enqueue_style("jquery-ui");
      wp_enqueue_style("jquery-ui-datepicker");
      wp_register_style("jquery-timepicker", plugins_url("/css/jquery.timepicker.css", __FILE__));
    wp_enqueue_style('jquery-timepicker');

    include_ckeditor_with_jquery_js();
}

add_action('admin_init', 'product_launch_countdown_admin_init');

add_action('wp_head', 'add_product_launch_countdown_js_and_css');
function add_product_launch_countdown_js_and_css() { 
  wp_enqueue_script('jquery');

  wp_register_style("product-launch-countdown", plugins_url("/css/style.css", __FILE__));
  wp_enqueue_style("product-launch-countdown");

  wp_register_script("jquery-countdown", plugins_url("/js/jquery.countdown.js", __FILE__));
  wp_enqueue_script("jquery-countdown");
} 

function product_launch_months() {
    return array("January","February","March","April","May","June","July","August","September","October","November","December");
}

$ProductLaunchCountdown_Widgets = new ProductLaunchCountdown_Widgets();
$ProductLaunchCountdown_Widgets->init();

function product_launch_countdown_admin_footer() {
    if (is_admin()) { ?>
        <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery(".launch-content").ckeditor();
            jQuery(".timepicker").timepicker({
                'minTime': '7:00am', 
                'maxTime': '11:00pm', 
                'timeFormat': 'H:i'
            });
            jQuery('body').on('focusin', '.timepicker', function(e) {
                  jQuery(this).timepicker({
                    'minTime': '7:00am', 
                    'maxTime': '11:00pm', 
                    'timeFormat': 'H:i'
                });
            });
        });
        </script>
    <?php
    }
}
add_action('admin_footer', 'product_launch_countdown_admin_footer');