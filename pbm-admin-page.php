<?php
/**
 * PBM_Admin_Page Class for PawnBat Module plugin
 *
 * @version       1.x
 * @package       PawnBat Module
 * @author        Denys Nosov (dgm.denys@gmail.com)
 * @copyright (C) 2018, PawnBat
 *
 **/

/*
 * Class PBM_Admin_Page
 *
 * @property PBM_Admin_Page Class for PawnBat Module plugin
 * @version: 1.0
 *
 */

class PBM_Admin_Page
{
	/**
	 * PBM_Admin_Page constructor.
	 */
	public function __construct()
	{
		add_action('admin_menu', array ( $this, 'pbm_admin_menu' ));
		add_action('admin_init', array ( $this, 'pbm_admin_init' ));
	}

	/**
	 * @since 1.0
	 */
	public function pbm_admin_menu()
	{
		add_options_page('PawnBat Module', 'PawnBat Module', 'manage_options', 'pawnbat-module', array (
			$this, 'add_options_page_callback'
		));
	}

	/**
	 * @since 1.0
	 */
	public function pbm_admin_init()
	{
		$this->pbm_set_defaults();

		register_setting(
			'pbm_general_settings', // Option group / tab page
			'pbm_general_settings', // Option name
			array ( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'pbm_general_section', // ID
			__('General Settings', 'pbm'), // Title //ML
			array ( $this, 'print_section_info' ), // Callback
			'pbm_general_settings' // Page / tab page
		);

		add_settings_field(
			'pawnbat_stores_id', // ID
			'Pawn Shop ID', // Title
			array ( $this, 'posttype_callback' ), // Callback
			'pbm_general_settings', // Page / tab page
			'pbm_general_section' // Section
		);

		add_settings_field( //HIDDEN
			'pawnbat_page_id', // ID
			'pawnbat_page_id', // Title
			array ( $this, 'posttype_callback' ), // Callback
			'pbm_general_settings', // Page / tab page
			'pbm_general_section' // Section
		);

		add_settings_field( //HIDDEN
			'pawnbat_page_url', // ID
			'pawnbat_page_url', // Title
			array ( $this, 'posttype_callback' ), // Callback
			'pbm_general_settings', // Page / tab page
			'pbm_general_section' // Section
		);
	}

	/**
	 * @since 1.0
	 */
	public function pbm_set_defaults()
	{
		$options = get_option('pbm_general_settings');
		$options = wp_parse_args($options, array (
			'pawnbat_stores_id' => ''
		));

		update_option('pbm_general_settings', $options);
	}

	/**
	 * @since 1.0
	 */
	public function add_options_page_callback()
	{
		wp_enqueue_style('pbm-admin', plugins_url('pbm-admin.css', __FILE__));

		?>
		<div class="wrap">

			<h2>PawnBat Module by PawnBat.com</h2>

			<form method="post" action="options.php">

				<?php
				settings_fields('pbm_general_settings');
				$options = get_option('pbm_general_settings'); //option_name
				?>
				<h3>General Settings</h3>

				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							Pawn Shop ID:
						</th>
						<td>
							<?php
							printf(
								'<input type="text" id="pawnbat_stores_id" name="pbm_general_settings[pawnbat_stores_id]" value="%s" size="50" />',
								esc_attr($options[ 'pawnbat_stores_id' ])
							);
							echo '<div class="description">You will get a BawnBat store ID like this: 123 (single Pawn Shop) or 123,222,333 (separete by comma for Pawn Shop Chain)</div>';
							?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							Module Store Page Target URL:
						</th>
						<td>
							<?php
							printf(
								'<input type="hidden" id="pawnbat_page_id" name="pbm_general_settings[pawnbat_page_id]" value="%s" />',
								esc_attr($options[ 'pawnbat_page_id' ])
							);
							printf(
								'<input type="text" id="pawnbat_page_url" name="pbm_general_settings[pawnbat_page_url]" value="%s" size="50" disabled />',
								esc_attr(get_page_link($options[ 'pawnbat_page_id' ]))
							);
							echo '<div><span class="description">The plugin automatically generated a page for displaying Pawnbat Module. You can see here the URL of this page. Please do not delete this page and do not change the permalink of it!</div>';
							?>
						</td>
					</tr>
				</table>
				<?php
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * @param $input
	 *
	 * @return mixed
	 *
	 * @since 1.0
	 */
	public function sanitize($input)
	{
		if( !is_numeric($input[ 'id_number' ]) )
		{
			$input[ 'id_number' ] = '';
		}

		if( !empty($input[ 'title' ]) )
		{
			$input[ 'title' ] = sanitize_text_field($input[ 'title' ]);
		}

		return $input;
	}
}