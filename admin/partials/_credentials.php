<?php

$subdomain       = ($options != '') ? esc_attr( $options['subdomain'] ) : '';
$domain_api_key  = ($options != '') ? esc_attr( $options['domain_api_key'] ) : '';
$company_api_key = ($options != '') ? esc_attr( $options['company_api_key'] ) : '';

// $endpoint        = ($options != '') ? esc_attr( $options['endpoint'] ) : '';
// $account_api_key = ($options != '') ? esc_attr( $options['account_api_key'] ) : '';

?>

<table class="form-table">
    <tr>
    	<th><?php _e( 'Subdomain', $this->plugin_name ); ?></th>
    	<td>
    		<input 
                type="text" 
                name="<?php echo $settings_section; ?>[subdomain]" 
                value="<?php echo $subdomain; ?>" 
                maxlength="50"
                size="40" /> .timtravel.app
    	</td>
    </tr>

    <tr>
        <th><?php _e( 'Domain Api Key', $this->plugin_name ); ?></th>
        <td>
            <input 
                type="text" 
                name="<?php echo $settings_section; ?>[domain_api_key]" 
                value="<?php echo $domain_api_key; ?>" 
                maxlength="100" 
                size="40" />
        </td>
    </tr>

    <tr>
        <th><?php _e( 'Company Api Key', $this->plugin_name ); ?></th>
        <td>
            <input 
                type="text" 
                name="<?php echo $settings_section; ?>[company_api_key]" 
                value="<?php echo $company_api_key; ?>" 
                maxlength="100" 
                size="40" />
        </td>
    </tr>

    <!-- <tr>
    	<th><?php _e( 'Account Api Key', $this->plugin_name ); ?></th>
        <td>
        	<input 
                type="text" 
                name="<?php echo $settings_section; ?>[account_api_key]" 
                value="<?php echo $account_api_key; ?>" 
                maxlength="100" 
                size="40" />
        </td>
    </tr>

     -->
</table>