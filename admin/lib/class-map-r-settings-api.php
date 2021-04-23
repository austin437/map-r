<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Map_R_Settings_Api {

    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

    public function register_options_page() {
        add_options_page('Map-R Settings', 'Map-R', 'manage_options', 'map_r-settings', array( $this, 'options_page' ) );
    }

    public function options_page_css() {
        echo "
            <style type='text/css'>
                .map_r-text-input {
                    width: 20rem;
                }
            </style>
        ";
    }

    public function options_page() {        
        require_once ADMIN_PATH . '/partials/map-r-admin-settings-page.php';
    }

    public function register_settings() {

        register_setting( 'map_r_options_group', 'map_r_memberpress_url', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'  ) );
        register_setting( 'map_r_options_group', 'map_r_memberpress_api', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'  ) );

    }

}