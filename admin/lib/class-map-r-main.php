<?php

if( ! defined('ABSPATH') ) exit;

class Map_R_Main {

    private $memberpress_api;
    private $plugin_name;
    private $version;

    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( Map_R_Memberpress_Api $memberpress_api, $plugin_name, $version ) {

        $this->memberpress_api = $memberpress_api;
		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

    public function add_menu_pages(){
        add_menu_page(  
            'Map_R', 
            'Map-R', 
            'manage_options', 
            'map_r',  
            array( $this, 'render_settings_page' ),
            'dashicons-location-alt',
            '2'
        );

        add_submenu_page( 'map_r', 'Map_R', 'Map_R', 'manage_options', 'map_r');

    }   

    public function render_settings_page(){

        $map_r_options = get_option( 'map_r_option', [] );

        $local_memberships = $this->memberpress_api->get_memberships();

        $map_r_memberpress_url = get_option('map_r_memberpress_url');
        $map_r_memberpress_api = get_option('map_r_memberpress_api');

        if( $map_r_memberpress_url ) 
        {
            $remote_memberships = json_decode(
                wp_remote_get( 
                    $map_r_memberpress_url . '/memberships?per_page=1000',
                    array(
                        'headers' => array(
                            'MEMBERPRESS-API-KEY' => $map_r_memberpress_api
                        )
                    )
                )['body']
            );
        }
        else
        {
            $remote_memberships = [];
        }

        require_once ADMIN_PATH . '/partials/map-r-admin-main.php';
    }

    public function save_new_map_r(){
        
        check_admin_referer( 'add-new-map_r');

        $post_data = sanitize_post( $_POST );    

        $local_membership = explode( "|", $post_data['map_r']['local_membership']) ;
        $post_data['map_r']['local_membership'] = [];
        $post_data['map_r']['local_membership']['membership_id'] = $local_membership[0];
        $post_data['map_r']['local_membership']['membership_title'] = $local_membership[1];

        $remote_membership = explode( "|", $post_data['map_r']['remote_membership']) ;
        $post_data['map_r']['remote_membership'] = [];
        $post_data['map_r']['remote_membership']['membership_id'] = $remote_membership[0];
        $post_data['map_r']['remote_membership']['membership_title'] = $remote_membership[1];

        $map_r_option = get_option( 'map_r_option', [] );

        $map_r_option[] = $post_data['map_r'];

        update_option( 'map_r_option', $map_r_option );

        wp_redirect(admin_url('admin.php?page=map_r&created=1'));

        die();
    }

    public function delete_map_r(){
        check_admin_referer( 'delete-map_r');

        $post_data = sanitize_post( $_POST );

        $map_r_key = $post_data['map_r_key'];

        $map_r_option = get_option( 'map_r_option', [] );

        unset( $map_r_option[$map_r_key]);

        update_option( 'map_r_option', $map_r_option );

        wp_redirect(admin_url('admin.php?page=map_r&deleted=1'));

        die();
       

    }

    
}