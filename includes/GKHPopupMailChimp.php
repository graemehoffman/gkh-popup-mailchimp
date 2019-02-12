<?php

namespace GKH;

/**
 * Build simple mailchimp popup plugin
 *
 * @package GKH
 */
class GKHPopupMailChimp {

	public function hooks() {
		add_action( 'wp_footer', [ $this, 'add_mc_embed' ], 100 );
		add_action( 'admin_menu', [ $this, 'options_page' ] );
		add_action( 'admin_init', [ $this, 'settings_init' ] );

	}

	public function add_mc_embed() {
		$this->options = get_option( 'gkh_mailchimp_popup_option_name' );

		if (
				isset($this->options['uuid']) &&
				isset($this->options['lid']) &&
				isset($this->options['domain']) &&
				!empty($this->options['uuid']) &&
				!empty($this->options['lid'])&&
				!empty($this->options['domain'])
		) {

			$domain = $this->options['domain']; // mc.us16.list-manage.com mc.us5.list-manage.com
			$uuid = $this->options['uuid']; // e5608fd1f1b1139cc0d6dbd97
			$lid = $this->options['lid']; // de6d673052

			?>
			<script type="text/javascript" src="//downloads.mailchimp.com/js/signup-forms/popup/unique-methods/embed.js" data-dojo-config="usePlainJson: true, isDebug: false"></script>
			<script type="text/javascript">window.dojoRequire(["mojo/signup-forms/Loader"], function(L) { L.start({"baseUrl":"<?php echo $domain; ?>","uuid":"<?php echo $uuid; ?>","lid":"<?php echo $lid; ?>","uniqueMethods":true}) })</script>
			<?php
		}
	}

	/**
	 * top level menu
	 */
	function options_page() {
		add_options_page(
			'Mailchimp Popup',
			'Mailchimp Popup Options',
			'manage_options',
			'gkh-mailchimp-popup',
			[ $this, 'create_admin_page' ]
		);

	}

	function settings_init() {

		register_setting(
			'gkh_mailchimp_popup_option_group', // Option group
			'gkh_mailchimp_popup_option_name', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'gkh_mailchimp_popup_setting_section_id', // ID
			'Settings', // Title
			array( $this, 'print_section_info' ), // Callback
			'gkh-mailchimp-popup' // Page
		);

		add_settings_field(
			'domain',
			'Mailchimp domain (ex: mc.us5.list-manage.com)',
			array( $this, 'domain_callback' ),
			'gkh-mailchimp-popup',
			'gkh_mailchimp_popup_setting_section_id'
		);

		add_settings_field(
			'uuid',
			'UUID (ex: e5608fd1f1b1139cc0d6dbd97)',
			array( $this, 'uuid_callback' ),
			'gkh-mailchimp-popup',
			'gkh_mailchimp_popup_setting_section_id'
		);

		add_settings_field(
			'lid',
			'LID (ex: de6d673052)',
			array( $this, 'lid_callback' ),
			'gkh-mailchimp-popup',
			'gkh_mailchimp_popup_setting_section_id'
		);

	}

	/**
	 * Options page callback
	 */
	public function create_admin_page()
	{
		// Set class property
		$this->options = get_option( 'gkh_mailchimp_popup_option_name' );
		?>
		<div class="wrap">
			<h1>Mailchimp Popup Options</h1>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'gkh_mailchimp_popup_option_group' );
				do_settings_sections( 'gkh-mailchimp-popup' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input )
	{
		$new_input = array();

		if( isset( $input['uuid'] ) )
			$new_input['uuid'] = sanitize_text_field( $input['uuid'] );

		if( isset( $input['lid'] ) )
			$new_input['lid'] = sanitize_text_field( $input['lid'] );

		if( isset( $input['domain'] ) )
			$new_input['domain'] = sanitize_text_field( $input['domain'] );

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info()
	{
		?>
		Enter your mailchimp popup settings here. UUID and LID values are coming from the Mailchimp embed code which can be found in the Mailchimp admin after building the popup form.

		<pre style="padding: 20px; background: #dddddd; border: 1px solid #cccccc; color: #777777; font-family: monospace; white-space: pre-wrap; word-wrap: break-word; margin: 1em 0;">
<?php echo htmlspecialchars('<script type="text/javascript" src="//downloads.mailchimp.com/js/signup-forms/popup/unique-methods/embed.js" data-dojo-config="usePlainJson: true, isDebug: false"></script>'); ?>
<?php echo htmlspecialchars( '<script type="text/javascript">window.dojoRequire(["mojo/signup-forms/Loader"], function(L) { L.start({"baseUrl":"mc.us5.list-manage.com","uuid":"e5608fd1f1b1139cc0d6dbd97","lid":"de6d673052","uniqueMethods":true}) })</script>'); ?>
		</pre>

		<?php
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function uuid_callback()
	{
		printf(
			'<input type="text" id="uuid" name="gkh_mailchimp_popup_option_name[uuid]" value="%s" />',
			isset( $this->options['uuid'] ) ? esc_attr( $this->options['uuid']) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function lid_callback()
	{
		printf(
			'<input type="text" id="lid" name="gkh_mailchimp_popup_option_name[lid]" value="%s" />',
			isset( $this->options['lid'] ) ? esc_attr( $this->options['lid']) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function domain_callback()
	{
		printf(
			'<input type="text" id="domain" name="gkh_mailchimp_popup_option_name[domain]" value="%s" />',
			isset( $this->options['domain'] ) ? esc_attr( $this->options['domain']) : ''
		);
	}

}
