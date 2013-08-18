<?php

class ProductLaunchCountdown_Widgets_Widget extends WP_Widget
{
	public function __construct() {
		parent::__construct(
	 		'productlaunchcountdown_widgets_widget', // Base ID
			'Product Launch Countdown Widget', // Name
			array( 'description' => 'Lets you select one of your "Product Launch Countdown Widgets" and show it in a widget area.' ) // Args
		);
	}

 	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );		
		?>
		<script type="text/javascript">
			/* <![CDATA[ */
			var PLCAjax = {
				"plc_image_url":"<?php echo(plugins_url('../images', __FILE__)); ?>"
			};
			/* ]]> */
		</script>
		<?php
	  wp_register_script("product-launch-countdown", plugins_url("../js/application.js", __FILE__));
	  wp_enqueue_script("product-launch-countdown");

	  // Widget output

	  $counter_visible = true;
	  $counter_style = "block";
	  $launch_content_style = "none";
	  // Check to see if the launch date has passed.

	  if (strtotime("now") > strtotime($instance['launch_month']." ".$instance['launch_day'].", ".$instance['launch_year']." ".$instance['launch_time'])) {
	  	// Launch date has passed.
	  	$counter_visible = false;
	  	$counter_style = "none";
	  	$launch_content_style = "block";
	  } 
	  ?>
		<div class="counter wrapper">
			<input type="hidden" name="launch_date" class="launch-date" value="<?php echo($instance['launch_month']." ".$instance['launch_day'].", ".$instance['launch_year']." ".$instance['launch_time']); ?>" />
			<div style="display: <?php echo($counter_style); ?>;" class="counter"></div>
			<div style="display: <?php echo($counter_style); ?>;" class="desc">
		    <div>Days</div>
		    <div>Hours</div>
		    <div>Minutes</div>
		    <div>Seconds</div>
		  </div>
		  <div style="display: <?php echo($launch_content_style); ?>;" class="launch-content">
				<?php 
		  	// Launch date has not passed yet.
				$id = $instance['plc-widget-id'];

				$title = apply_filters( 'widget_title', $instance['title'] );
				$post = get_post($id);

				echo $before_widget;
				if(!empty($title)) { echo $before_title . $title . $after_title; }
				if($post && !empty($id)) {
					$content = $post->post_content;
					$content = do_shortcode($content);
					$content = wpautop($content);
					echo $content;		
				} else {
					if(current_user_can('manage_options')) { ?>
						<p style="color:red;">
							<strong>ADMINS ONLY NOTICE:</strong>
							<?php if(empty($id)) { ?>
								Please select a Product Launch Countdown Widget post to show in this area.
							<?php } else { ?>
								No post found with ID <?php echo $id; ?>, please select an existing Product Launch Countdown Widget post.
							<?php } ?>
						</p>
						<?php }
				}
				echo $after_widget;
				?>
			</div>
		</div>
		<?php
		product_launch_countdown_admin_footer();
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['plc-widget-id'] = $new_instance['plc-widget-id'];
 
    $instance['launch_year'] = strip_tags( $new_instance['launch_year'] );
    $instance['launch_month'] = strip_tags( $new_instance['launch_month'] );
    $instance['launch_day'] = strip_tags( $new_instance['launch_day'] );
    $instance['launch_time'] = strip_tags( $new_instance['launch_time'] );
    $instance['launch_content'] = $new_instance['launch_content'];

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		product_launch_countdown_admin_init();

		$posts = get_posts(array(
			'post_type' => 'plc-widget',
			'numberposts' => -1
		));

		$title = isset($instance['title']) ? $instance['title'] : 'Just another Product Launch Countdown Widget';
		$selected_widget_id = (isset($instance['plc-widget-id'])) ? $instance['plc-widget-id'] : 0;

		if(empty($posts)) { ?>

			<p>You should first create at least 1 Product Launch Countdown Widget <a href="<?php echo admin_url('edit.php?post_type=plc-widget'); ?>">here</a>.</p>

		<?php
		} else { ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'plc-widget-id' ); ?>"><?php _e( 'Widget Content:' ); ?></label> 
			<select id="<?php echo $this->get_field_id('plc-widget-id'); ?>" name="<?php echo $this->get_field_name( 'plc-widget-id' ); ?>">
				<option value="0">Select a Product Launch Countdown Widget..</option>
				<?php foreach($posts as $p) { ?>
					<option value="<?php echo $p->ID; ?>" <?php if($p->ID == $selected_widget_id) echo 'selected="selected"'; ?>><?php echo $p->post_title; ?></option>
				<?php } ?>
			</select>
		</p>
		<p class="drop-datepicker">
        <label style="display:block;" for="<?php echo $this->get_field_id( 'launch_year' ); ?>"><?php _e('Launch Date:', 'product-launch-year'); ?></label> 
        <select class="year" id="<?php echo $this->get_field_id( 'launch_year' ); ?>" name="<?php echo $this->get_field_name( 'launch_year' ); ?>">
            <?php for($i=date("Y");$i<(date("Y")+10);$i++) { ?>
                <option
                    <?php 
                        if ($instance['launch_year'] == $i) {
                            echo " selected ";
                        }
                    ?>
                 value="<?php echo($i); ?>"><?php echo($i); ?></option>
            <?php } ?>
        </select>
        <select class="month" id="<?php echo $this->get_field_id( 'launch_month' ); ?>" name="<?php echo $this->get_field_name( 'launch_month' ); ?>">
            <?php 
            $months = product_launch_months();
            foreach($months as $month) {
            ?>
                <option 
                    <?php 
                        if ($instance['launch_month'] == $month) {
                            echo " selected ";
                        }
                    ?>
                value="<?php echo($month); ?>"><?php echo($month); ?></option>
                <?php
            }
        ?>
        </select>
        <select class="day" id="<?php echo $this->get_field_id( 'launch_day' ); ?>" name="<?php echo $this->get_field_name( 'launch_day' ); ?>">
            <?php 

            $number_of_days = 31;
            if (in_array($instance['launch_month'], array("April", "June", "September", "November"))) {
                $number_of_days = 30;
            }
            if (($instance['launch_month'] == "February") && (($instance['launch_year'] % 4) == 0)) {
                $number_of_days = 30;
            } else if ($instance['launch_month'] == "February") {
                $number_of_days = 29;
            }

            for($i=1;$i<($number_of_days+1);$i++) { ?>
                <option 
                    <?php 
                        if ($instance['launch_day'] == $i) {
                            echo " selected ";
                        }
                    ?> 
                value="<?php echo($i); ?>"><?php echo($i); ?></option>
            <?php } ?>
        </select> 
    	</p>

    	<p>
        <label for="<?php echo $this->get_field_id( 'launch_time' ); ?>"><?php _e('Launch Time:', 'product-launch-countdown'); ?></label>  
        <input class="timepicker" id="<?php echo $this->get_field_id( 'launch_time' ); ?>" name="<?php echo $this->get_field_name( 'launch_time' ); ?>" value="<?php echo $instance['launch_time']; ?>" style="width:100%;" />  
    	</p>

		<?php 
		}

	}

}