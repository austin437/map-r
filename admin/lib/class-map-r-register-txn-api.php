<?php

if( ! defined('ABSPATH') ) exit;

class Map_R_Register_Txn_Api {

    private $txn;
    private $user;
    private $map_r;
   
    function __construct()
    {
        $this->remote_membership_object = new stdClass();
    }

    public function mepr_txn_status_complete($txn){

        $this->txn = $txn;
        $product_id = $txn->rec->product_id;
        $this->load_user( $txn->rec->user_id );      
        $this->load_mapr( $product_id );

        if( count( $this->map_r ) === 0 ) return;


        $remote_membership_object = $this->load_remote_membership_object();
        $remote_membership_response = $this->send_remote_txn( $remote_membership_object );
        if ( is_wp_error( $remote_membership_response ) ) throw new \Exception( $remote_membership_response->get_error_message() );        

        // error_log( print_r( $txn, true ), 3, LOG_PATH );
        // error_log( print_r( $this->user, true ), 3, LOG_PATH );
        // error_log( print_r( $this->map_r, true ), 3, LOG_PATH );
        // error_log( print_r( $remote_membership_object, true ), 3, LOG_PATH );
        // error_log( print_r( $remote_membership_response, true ), 3, LOG_PATH );

        
    }

    private function load_remote_membership_object(){
        return array(
            'memberpress_url' => get_option('map_r_memberpress_url'),
            'memberpress_api' => get_option('map_r_memberpress_api'),
            'membership_id' => $this->map_r['remote_membership']['membership_id'],
            'membership_amount' => $this->txn->rec->amount,
            'send_welcome_email' => true,
            'send_password_email' => true
        );
    }

    private function load_user($user_id)
    {
        $user = get_user_by( 'ID', $user_id );
        $user->first_name = get_user_meta( $user->ID, 'first_name', true );
        $user->last_name = get_user_meta( $user->ID, 'last_name', true );
        $this->user = $user;
    }

    private function load_mapr( $product_id )
    {
        $map_r_option = get_option( 'map_r_option', [] );

        $this->map_r = [];

        foreach( $map_r_option as $map_r ){
            if( (int) $product_id === (int) $map_r['local_membership']['membership_id']) $this->map_r = $map_r;
        }
    }

    private function send_remote_txn( $data )
    {
        $body = array(
            'method'      => 'POST',
            'timeout'     => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true,
            'headers'     => array(
                'MEMBERPRESS-API-KEY' => $data['memberpress_api']
            ),
            'body' => array(
                'email' => $this->user->user_email,
                'username' => $this->user->user_login,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'send_welcome_email' => $data['send_welcome_email'],
                'send_password_email' => $data['send_password_email'],
                'transaction' => array(
                    'membership' => $data['membership_id'],
                    'status' => 'complete',
                    'amount' => $data['membership_amount'],
                    'expires_at' => $this->txn->rec->expires_at
                )
            ),
            'cookies'  => array()
        );
        
        //error_log( print_r( $body, true ), 3, LOG_PATH );

        return wp_remote_post( $data['memberpress_url'] . '/members', $body );

    }

}