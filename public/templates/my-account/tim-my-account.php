<?php
/*
    Tim My Account Template
*/
    
?>
<div class="tim_clr">
    <div class="tim_col_6">
        <b><?php _e( 'Welcome', $plugin_name ); ?> <?php echo $_SESSION['tim_client_session']['name']; ?></b>
    </div>
    <div class="tim_col_6">
        <div class="tim_pull_right">
            <div class="tim_menu_options">
                <ul>
                    <li>
                        <a href="javascript:void(0);" class="tablink<?php if ( $action == 'profile' ){ echo ' active'; } ?>" onclick="timTabContent(event, 'timClientProfile'); timLoadData('load_client_profile');">
                            <?php _e( 'View profile', $plugin_name ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="tablink<?php if ( $action == 'orders' ){ echo ' active'; } ?>" onclick="timTabContent(event, 'timViewOrders'); timLoadData('list_orders_api');">
                            <?php _e( 'View orders', $plugin_name ); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<hr />

<div id="timClientProfile" class="tim_tab_content"></div>
<div id="timViewOrders" class="tim_tab_content"></div>

<div id="timCustomerFormSuccessMsg"></div>
<div id="timLoadDataResult"></div>

<script type="text/javascript">
    <?php
    if ( $action == '' ){
        ?>timLoadData('load_client_profile', '<?php echo $content_language; ?>');<?php
    }
    elseif ( $action == 'orders' ){
        ?>timLoadData('list_orders_api', '<?php echo $content_language; ?>');<?php
    }
    ?>
</script>