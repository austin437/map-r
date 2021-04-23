<div class="wrap">
    <h1>Map-R Settings</h1>
    <hr>
    <form method="post" action="options.php">
        <?php settings_fields( 'map_r_options_group' ); ?>
        <?php do_settings_sections( 'map_r_options_group' ); ?>
        <h3>Conquer Computing</h3>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Memberpress URL</th>
                <td><input class="map_r-text-input" type="text" id="map_r_memberpress_url" name="map_r_memberpress_url" value="<?php echo esc_attr(get_option('map_r_memberpress_url')); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Memberpress API</th>
                <td><input class="map_r-text-input" type="password" id="map_r_memberpress_api" name="map_r_memberpress_api" value="<?php echo esc_attr(get_option('map_r_memberpress_api')); ?>" /></td>
            </tr>
        </table>

        <hr>
        <?php  submit_button(); ?>
    </form>
</div>