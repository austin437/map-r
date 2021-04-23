<?php

/**
 * @array memberpress_memberships
 * @array map_r_memberships
 * 
 */



 ?>

<?php if( isset($_GET['created']) && (int) $_GET['created'] === 1) { ?>

    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Mapping Added!', 'sample-text-domain' ); ?></p>
    </div>

<?php } ?>

<?php if( isset($_GET['deleted']) && (int) $_GET['deleted'] === 1) { ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php _e( 'Mapping Deleted!', 'sample-text-domain' ); ?></p>
    </div>
<?php } ?>

<h1>Map-R</h1>
<hr />

<table class="map_r-form-table">  
    <thead>
        <tr valign="top">
            <th class="id-col">ID</th>
            <th class="local-col">Local Membership</th>
            <th class="remote-col">Remote Membership</th>        
            <th class="action-col">Action</th>        
        </tr>   
    </thead>
    <tbody>

        <?php foreach( $map_r_options as $key => $map_r_option ) { ?>
            
            <tr>
                <td><?php echo $key + 1; ?></td>
                <td><?php echo $map_r_option['local_membership']['membership_title']; ?></td>
                <td><?php echo $map_r_option['remote_membership']['membership_title']; ?></td>
                <td>
                    <form onsubmit="return confirm('Do you really want to delete this map_r?');" method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
                        <?php echo wp_nonce_field( 'delete-map_r' ); ?>
                        <input type="hidden" name="action" value="map_r_delete_map_r">
                        <input type="hidden" id="map_r_key" name="map_r_key" value="<?php echo $key; ?>"  />
                        <?php  submit_button( __( 'Delete', 'textdomain' ), 'delete' );?>
                    </form>
                </td>       
            </tr>  
        

        <?php } ?>

        <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">  
         
            <?php echo wp_nonce_field( 'add-new-map_r' ); ?>
            <input type="hidden" name="action" value="map_r_add_new_map_r">
            <tr>
                <th>Add New</th>
                <td>
                    <select required class="map_r-form-input" id="map_r_membership_id" name="map_r[local_membership]" >
                        <option value="" ></option>
                        <?php foreach( $local_memberships as $local_membership ) { ?>
                            <option 
                                value="<?php echo $local_membership->ID . '|' .  $local_membership->post_title; ?>" 
                            >
                                <?php echo $local_membership->post_title; ?>
                            </option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <select required class="map_r-form-input" id="membership_id" name="map_r[remote_membership]">
                        <option value=""></option>
                        <?php
                            foreach( $remote_memberships as $remote_membership ){
                                ?>
                                    <option 
                                        value="<?php echo $remote_membership->id . "|" . $remote_membership->title; ?>"
                                    >
                                        <?php echo $remote_membership->title; ?>
                                    </option>
                                <?php
                            }
                        ?>
                    </select>
                </td>
                <td>
                    <?php  submit_button(); ?>
                </td>       
            </tr>  
        </form>
    </tbody>
</table>


