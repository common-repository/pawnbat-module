<?php
/*
Plugin Name: PawnBat Module
Plugin URI:
Description: This plugin gives a very simple way to add PawnBat Module to your WordPress site.
Version: 1.0.3
Author: evolvens
Author URI:
License: GPLv2 or later
Text Domain: pbm
Requires at least: 3.7
Tested up to: 4.9.8
*/

if( !class_exists('PawnBat_Module') )
{
	/**
	 * PawnBat_Module Class for PawnBat Module plugin
	 *
	 * @version       1.x
	 * @package       PawnBat Module
	 * @author        Denys Nosov (dgm.denys@gmail.com)
	 * @copyright (C) 2018, PawnBat
	 *
	 **/
	/*
	 * Class PawnBat_Module
	 *
	 * @property PawnBat_Module Class for PawnBat Module plugin
	 * @version: 1.0
	 *
	 */

	class PawnBat_Module
	{
		public $plugin_path;

		public $plugin_url;

		/**
		 * PawnBat_Module constructor.
		 */
		public function __construct()
		{
			include_once 'pbm-admin-page.php';

			add_action('init', array ( $this, 'init' ), 0);
			register_activation_hook(__FILE__, array ( $this, 'pbm_activation' ));

			$options = get_option('pbm_general_settings');

			include_once 'pbm-widget.php';
			do_action('pbm_init');
		}

		/**
		 * @since 1.0
		 */
		public function init()
		{
			load_plugin_textdomain('pbm', false, dirname(plugin_basename(__FILE__)) . '/languages/');

			global $pbm_admin_page;

			$pbm_admin_page = new PBM_Admin_Page;

			add_shortcode('pawnbat_module', array ( $this, 'pawnbat_module_shortcode' ));
			do_action('pbm_init', $this);
		}

		/**
		 * @since 1.0
		 */
		public function pbm_activation()
		{
			$options = get_option('pbm_general_settings');

			if( $options[ 'pawnbat_page_id' ] == null || get_post($options[ 'pawnbat_page_id' ]) == null )
			{
				$pawnbat_page = array (
					'post_content' => '[pawnbat_module]',
					'post_name'    => 'pawnbat',
					'post_title'   => 'PawnBat Module',
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_author'  => get_current_user_id(),
					'post_excerpt' => 'PawnBat Module',
					'post_date'    => date('Y-m-d H:i:s'),
				);

				$pawnbat_page_id               = wp_insert_post($pawnbat_page);
				$options[ 'pawnbat_page_id' ]  = $pawnbat_page_id;
				$options[ 'pawnbat_page_url' ] = get_page_link($pawnbat_page_id);

				update_option('pbm_general_settings', $options);
			}
		}

		/**
		 * @return string
		 *
		 * @since 1.0
		 */
		public function plugin_path()
		{
			if( $this->plugin_path )
			{
				return $this->plugin_path;
			}

			return $this->plugin_path = untrailingslashit(plugin_dir_path(__FILE__));
		}

		/**
		 * @return string
		 *
		 * @since 1.0
		 */
		public function plugin_url()
		{
			if( $this->plugin_url )
			{
				return $this->plugin_url;
			}

			return $this->plugin_url = untrailingslashit(plugins_url('/', __FILE__));
		}

		/**
		 * @param $atts
		 *
		 * @return string
		 *
		 * @since 1.0
		 */
		public function pawnbat_module_shortcode($atts)
		{
			$options = get_option('pbm_general_settings');

			$store_id = 0;
			if( $options[ 'pawnbat_stores_id' ] )
			{
				$store_id = $options[ 'pawnbat_stores_id' ];
			}

			$content = '';
			$content .= '<div class="pbm_wrapper" id="pbm_wrapper_id">';
			$content .= '<!-- PawnBat Module Start --><div id="widget_pawnbat_body"><iframe id="widget_pawnbat" src="https://pawnbat.com/embed_' . $store_id . '/" width="100%" height="600" scrolling="no" frameborder="0" name="target"></iframe><div id="wpb_site" class="wpb_site">Powered by <a href="https://pawnbat.com">pawnbat.com</a></div></div><script src="https://pawnbat.com/assets/widget/widget.pawnbat.js" async defer></script><!-- PawnBat Module End -->';

			$content = apply_filters('pbm_shortcode_content', $content);

			$content .= '</div>';

			return $content;
		}
	}

	$GLOBALS[ 'pawnbat_module' ] = new PawnBat_Module();

	function pawnbat_module_actions($links)
	{
		return array_merge(array (
			'settings' => '<a href="options-general.php?page=pawnbat-module.php">Setting</a>'
		), $links);
	}

	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'pawnbat_module_actions');
}