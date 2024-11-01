<?php

// echo $package_email_notification = ($options != '') ? esc_attr( $options['package_email_notification'] ) : '';

$package_email_notification = isset( $options['package_email_notification'] ) ? $options['package_email_notification'] : '';
?>

<div class="tim_clr">
    <div class="tim_col_2">
        <h2><?php _e( 'Email notifications', $this->plugin_name ); ?></h2>
    </div>
    <div class="tim_col_10">
        <table class="form-table tim-table">
            <!-- Email notifications -->
            <tr>
                <th><?php _e( 'Package request', $this->plugin_name ); ?></th>
                <td>
                    <input 
                        type="text" 
                        name="<?php echo $settings_section; ?>[package_email_notification]" 
                        value="<?php echo $package_email_notification; ?>" 
                        maxlength="100" 
                        size="40" />
                </td>
            </tr>
        </table>
    </div>
</div>