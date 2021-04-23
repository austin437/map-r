<?php

if( ! defined( 'ABSPATH' ) ) exit;

class Map_R_Memberpress_Api {

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

    public function get_memberships(){
        return \MeprProduct::get_all();
    }

}