<?php
$cancellationPolicies = $plugin_api->get_cancellation_policies( $content_language );

?>
<div style="padding: 15px;">
    <h3 style="margin-top: 0;"><?php _e( 'Booking cancellation policies', $plugin_name ); ?></h3>

    <?php echo $cancellationPolicies->policies; ?>
</div>