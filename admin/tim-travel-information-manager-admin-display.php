<div class="wrap">
    <h2><?php echo TIM_TRAVEL_MANAGER_PLUGIN_TITLE; ?></h2>

    <?php
    settings_errors();

    $show_tim_save_btn = true;

    ?>    
    <div id="timMsgBox" class="updated settings-error notice is-dismissible" style="display:none;"> 
        <p style="font-weight:700;" id="timMsgContent"></p>
        <button class="notice-dismiss" type="button">
            <span class="screen-reader-text"><?php _e( 'Dismiss this notice', $this->plugin_name ); ?></span>
        </button>
    </div>
    <?php

    $active_tab = isset( $_GET['tab'] ) ? $_GET[ 'tab'] : '';

    if ( $active_tab == 'general_options' ) {
        $active_tab = 'general_options';
        $settings_section = TIM_TRAVEL_MANAGER_GENERAL_OPTIONS;
    } elseif ( $active_tab == 'synchronize_api' ) {
        $active_tab = 'synchronize_api';
        $settings_section = '';

        $show_tim_save_btn = false;
    } else {
        $active_tab = 'credentials';
        $settings_section = TIM_TRAVEL_MANAGER_CREDENTIALS;
    }

    ?>
    <h2 class="nav-tab-wrapper">
        <a href="?page=<?php echo $this->plugin_name; ?>&tab=credentials" class="nav-tab<?php echo $active_tab == 'credentials' ? ' nav-tab-active' : ''; ?>">
            <?php _e( 'Credentials', $this->plugin_name ); ?>
        </a>
        <a href="?page=<?php echo $this->plugin_name; ?>&tab=general_options" class="nav-tab<?php echo $active_tab == 'general_options' ? ' nav-tab-active' : ''; ?>">
            <?php _e( 'General options', $this->plugin_name ); ?>
        </a>
        <a href="?page=<?php echo $this->plugin_name; ?>&tab=synchronize_api" class="nav-tab<?php echo $active_tab == 'synchronize_api' ? ' nav-tab-active' : ''; ?>">
            <?php _e( 'Synchronize Api', $this->plugin_name ); ?>
        </a>
    </h2>

    <form id="tim_travel_admin_form" method="post" action="options.php" autocomplete="off">
        <?php
        settings_fields( $settings_section );

        $options = get_option( $settings_section );

        if ( $active_tab == 'credentials' ) {
            require_once 'partials/_credentials.php';
        } elseif ( $active_tab == 'general_options' ) {
            require_once 'partials/_general-options.php';
            echo '<hr />';
            require_once 'partials/_theme-layout-options.php';
            echo '<hr />';
            require_once 'partials/_product-display-options.php';
            echo '<hr />';
            require_once 'partials/_check-availability-options.php';
            echo '<hr />';
            require_once 'partials/_notification-options.php';
            echo '<hr />';
            require_once 'partials/_booking-options.php';
        } elseif ( $active_tab == 'synchronize_api' ) {
            require_once 'partials/_synchronize-api.php';
        } else {
            return '';
        }
        
        if ( $show_tim_save_btn ){
            ?>
            <hr />
            <input type="submit" value="<?php _e( 'Save changes', $this->plugin_name ); ?>" class="button button-primary" id="submit" name="tim_settings_form_submitted" />
            <span id="tim_travel_admin_form_spinner" class="tim_travel_form_spinner"></span>
            <?php
            }
        ?>
    </form>
</div>