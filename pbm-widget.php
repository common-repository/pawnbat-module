<?php
/**
 * PBM_Widget Class for PawnBat Module plugin
 *
 * @version       1.x
 * @package       PawnBat Module
 * @author        Denys Nosov (dgm.denys@gmail.com)
 * @copyright (C) 2018, PawnBat
 *
 **/

/*
 * Class PBM_Widget
 *
 * @property PBM_Widget Class for PawnBat Module plugin
 * @version: 1.0
 *
 */

class PBM_Widget extends WP_Widget
{
	/**
	 * PBM_Widget constructor.
	 */
	public function __construct()
	{
		parent::__construct(false, $name = 'PawnBat Module');
	}

	/**
	 * @param array $args
	 * @param array $instance
	 *
	 * @since 1.0
	 */
	public function widget($args, $instance)
	{
		echo $args[ 'before_widget' ];

		if( $instance[ 'hide_title' ] != 1 )
		{
			$title = apply_filters('widget_title', $instance[ 'title' ]);

			echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
		}

		$content = '<a href="https://pawnbat.com" target="_blank"><img src="' . get_option('siteurl') . '/wp-content/plugins/pawnbat-module/assets/logo.svg" style="margin: 0 auto;" alt="PawnBat"></a>';

		$content = apply_filters('pbm_shortcode_content', $content);

		echo apply_filters('pbm_widget_content', $content);

		echo $args[ 'after_widget' ];
	}

	/**
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	public function update($new_instance, $old_instance)
	{
		$instance                 = array ();
		$instance[ 'hide_title' ] = !empty($new_instance[ 'hide_title' ]) ? strip_tags($new_instance[ 'hide_title' ]) : 0;
		$instance[ 'title' ]      = !empty($new_instance[ 'title' ]) ? strip_tags($new_instance[ 'title' ]) : '';
		$instance[ 'promote' ]    = !empty($new_instance[ 'promote' ]) ? strip_tags($new_instance[ 'promote' ]) : 0;

		return $instance;
	}

	/**
	 * @param array $instance
	 *
	 * @return string|void
	 *
	 * @since 1.0
	 */
	public function form($instance)
	{
		$instance = wp_parse_args($instance, array (
			'hide_title' => 0,
			'title'      => 'PawnBat Module',
			'promote'    => 0
		));
		?>
		<p>
			<label for="<?php echo $this->get_field_id('hide_title'); ?>">
				<input class="checkbox" id="<?php echo $this->get_field_id('hide_title'); ?>" name="<?php echo $this->get_field_name('hide_title'); ?>" type="checkbox" value="1" <?php echo checked(1, esc_attr($instance[ 'hide_title' ]), false); ?>>
				Hide title
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">
				Title:
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance[ 'title' ]); ?>">
			</label>
		</p>
		<?php
	}
}

/**
 * @since 1.0
 */
function pbm_widget_init()
{
	register_widget('PBM_Widget');
}

add_action('widgets_init', 'pbm_widget_init');