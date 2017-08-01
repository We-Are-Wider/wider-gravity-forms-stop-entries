<?php
/*
Plugin Name: Wider Gravity Forms Stop Entries
Plugin URI: https://www.wider.co.uk
Description: Help your websites comply with the GDPR and general privacy - selectively stops individual Gravity Forms entries being stored so potentially sensitive data is not stored on your web server. NOTE: requires Gravity Forms v1.8 or newer. Options available under Settings > Gravity Forms Stop Entries.
Version: 1.0
Author: Jonny Allbut
Author URI: https://wider.co.uk
License: GPLv2 or later
*/

/* Translators note text domain: wider-gf-stop-entries */

class wdr_gf_stop_entries {

	var $plugin_name;
	var $gf_forms_data;
	var $gf_forms_active;
	var $saved_data;

	function __construct() {

		$this->plugin_name = 'wider-gf-stop-entries';
		$this->gf_forms_active = ( class_exists( 'GFAPI' ) ) ? true : false;
		$this->gf_forms_data = ( is_admin() ) ? $this->get_gf_data() : '';
		$this->saved_data = get_option( $this->plugin_name );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		add_action( 'admin_menu', array( $this, 'options_page_init' ) );
		add_action( 'admin_init', array( $this, 'settings_init' ) );
		add_action( 'admin_init', array( $this, 'settings_fields' ) );
		add_action( 'gform_after_submission', array( $this, 'delete_entry_actions' ) );

	}

	/**
	 *
	 * Adds options page.
	 *
	 */
	function options_page_init() {

		add_options_page(
			esc_html__( 'Gravity Forms Stop Entries', 'wider-gf-stop-entries' ),
			esc_html__( 'Gravity Forms Stop Entries', 'wider-gf-stop-entries' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'options_page' )
		);

	}

	/**
	 *
	 * Register plugin settings.
	 *
	 */
	function settings_init() {

		register_setting(
			$this->plugin_name . '_entries',
			$this->plugin_name,
			array( $this, 'sanitization' )
		);

	}

	/**
	 *
	 * Setup sections and fields.
	 *
	 */
	function settings_fields() {

		if ( $this->gf_forms_active === true ) {

			add_settings_section(
				$this->plugin_name . '_entries',
				null,
				null,
				$this->plugin_name
			);

			foreach ( $this->gf_forms_data as $key => $value ) {

				$saved_op = ( is_array($this->saved_data) && array_key_exists( $key, $this->saved_data ) ) ? $this->saved_data[$key] : '';
				$chval = checked( 1, $saved_op, false );
				$entry_def = $this->plugin_name;

				add_settings_field(
					'form-checkbox-' . $key,
					$value,
					function() use ( $chval, $key, $entry_def ) { echo '<input type="checkbox" name="' . $entry_def . '[' . $key . ']" value="1"' . $chval . ' />'; },
					$this->plugin_name,
					$this->plugin_name . '_entries'
				);

			}

		}

	}

	/**
	 *
	 * Returns a nice array of Gravity Forms data for us to use.
	 * TODO: Surely a cuter way to do this instead of getting all form data in mega array?
	 *
	 */
	function get_gf_data() {

		$output = '';

		if ( $this->gf_forms_active === true ) {

			$output = array();
			$forms = GFAPI::get_forms();

			foreach ( $forms as $form ) {

				$output[ $form['id'] ] = $form['title'];

			}

		}

		return $output;

	}

	/**
	 *
	 * Checks for valid option values when saving, no funny business thanks!
	 *
	 */
	function sanitization( $input ) {

		$input_clean = array();

		if ( !empty( $input ) || is_array( $input ) ) {

			foreach ( $input as $k => $v ) {

				if ( is_numeric( $k ) && intval( $v ) === 1 ) { $input_clean[$k] = $v; }

			}

		} else {

			$input_clean = '';

		}

		return $input_clean;

	}

	/**
	 *
	 * Options page output.
	 *
	 */
	function options_page() {

		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Gravity Forms Stop Entries', 'wider-gf-stop-entries' ) . '</h1>';

		// Gravity Forms not active - no bueno!
		if ( $this->gf_forms_active === false ) {

			echo '<h2>' . esc_html__( 'Ooops! Looks like you do not have Gravity Forms plugin active (or maybe not even installed!)', 'wider-gf-stop-entries' ) . '</h2>';

			echo '<p>'
			. esc_html__( 'This plugin only works with', 'wider-gf-stop-entries' )
			. ' <a href="http://www.gravityforms.com/" target="_blank" title="Visit the Gravity Forms website">'
			. esc_html__( 'the Gravity Forms plugin from Rocket Genius', 'wider-gf-stop-entries' )
			. '</a>.'
			. '</p>';

			echo '<p><strong>'
			. esc_html__( 'Please install and activate the plugin, setup at-least one form and come back to use these options!', 'wider-gf-stop-entries' )
			. '</strong></p>';

		// No active Gravity Forms - no bueno!
		} elseif ( empty( $this->gf_forms_data ) ) {

			echo '<h2>' . esc_html__( 'Ooops! Looks like you do not have not created any forms yet!', 'wider-gf-stop-entries' ) . '</h2>';

			echo '<p>' . esc_html__( 'You will need to go create at-least one form to control if entries are stored.', 'wider-gf-stop-entries' ) . '</p>';

			echo '<p><strong>'
			. esc_html__( 'Please go to Forms > New Form to create at-least one form thanks!', 'wider-gf-stop-entries' )
			. '</strong></p>';

		} else {

			echo '<p>' . esc_html__( 'Tick the box next to each form that you wish to stop entries being stored and click the Save settings button.', 'wider-gf-stop-entries' ) . '</p>';
			echo '<p>' . esc_html__( 'Note that this plugin does not remove existing entry data, it just stops further entries being stored.', 'wider-gf-stop-entries' ) . '</p>';

			echo '<form method="post" action="options.php">';

			submit_button( esc_html__( 'Save settings', 'wider-gf-stop-entries' ) );

			echo '<table class="form-table">';
			echo '<tr valign="top">';
			echo '<td>';

			settings_fields($this->plugin_name . '_entries');
			do_settings_sections($this->plugin_name);

			echo '</td>';
			echo '</tr>';
			echo '</table>';

			submit_button( esc_html__( 'Save settings', 'wider-gf-stop-entries' ) );

			echo '</form>';

			echo '<p>' . esc_html__( 'Bought to you by the team at', 'wider-gf-stop-entries' );
			echo ' <a href="https://wider.co.uk">Wider</a> ';
			echo esc_html__( '- WordPress bespoke theme and plugin development in the UK.', 'wider-gf-stop-entries' ) . '</p>';

		}

		echo '</div>';

	}

	/**
	 *
	 * Adds actions to relevant Gravity Forms.
	 *
	 */
	function delete_entry_actions( $entry ) {

		if ( !empty( $this->saved_data ) && $this->saved_data ) {

			foreach ( $this->saved_data as $k => $v ) {
				if ( intval( $v ) === 1 ) {
					add_action( 'gform_after_submission_' . $k, array( $this, 'delete_entry_do' ) );
				}
			}

		}

	}

	/**
	 *
	 * Delete entry using Gravity Forms API.
	 *
	 */
	function delete_entry_do( $entry ) {

		GFAPI::delete_entry( $entry['id'] );

	}

	/**
	 *
	 * Delete options when the user deactivates plugin, leave no trace ;)
	 *
	 */
	function deactivate() {

		delete_option( $this->plugin_name );

	}

}

/**
 *
 * Now, go be a good little plugin and do your stuff!
 *
 */
function wdr_gf_se_do() { new wdr_gf_stop_entries; }
add_action( 'init','wdr_gf_se_do' );
?>