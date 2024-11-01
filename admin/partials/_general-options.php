<?php

$google_map_api_key  = $options != '' ? esc_attr( $options['google_map_api_key'] ) : '';
$hideGoogleMapApyKey = $options['google_map_enabled'] ? '' : ' style="display: none;"';

$hideTranslationSettings = $options['translation_plugin_id'] ? '' : ' style="display: none;"';

$TRANSLATION_PLUGIN_OPTIONS = array( 
    array('option' => 0,              'name' => 'None'), 
    array('option' => 'qtranslate-x', 'name' => 'qTranslate-X'), 
    array('option' => 'wpml',         'name' => 'WordPress Multilingual (WPML)')
);

$TRANSLATION_PLUGIN_LABEL_OPTIONS = array( 
    array('option' => 0,      'name' => 'Default'), 
    array('option' => 'name', 'name' => 'Name only'), 
    array('option' => 'flag', 'name' => 'Flag only')
);

// Default translation plugin
$options['translation_plugin_id'] = isset( $options['translation_plugin_id'] ) ? $options['translation_plugin_id'] : 0;
$options['translation_plugin_label_setting'] = isset( $options['translation_plugin_label_setting'] ) ? $options['translation_plugin_label_setting'] : 0;

?>

<div class="tim_clr">
    <div class="tim_col_2">
        <h2><?php _e( 'General options', $this->plugin_name ); ?></h2>
    </div>
    <div class="tim_col_10">
        <table class="form-table tim-table">
            <!-- Cart widget -->
            <tr>
                <th><?php _e( 'Cart widget', $this->plugin_name ); ?></th>
                <td>
                    <label>
                        <input 
                            type="checkbox" 
                            name="<?php echo $settings_section; ?>[cart_widget_enabled]" 
                            value="1" <?php checked( $options['cart_widget_enabled'], 1 ); ?> /> <?php _e( 'Enable', $this->plugin_name ); ?>
                    </label>
                </td>
            </tr>

            <!-- Google map -->
            <tr>
                <th><?php _e( 'Google map', $this->plugin_name ); ?></th>
                <td>
                    <label>
                        <input 
                            type="checkbox" 
                            name="<?php echo $settings_section; ?>[google_map_enabled]" 
                            value="1" 
                            onclick="timDisplayContent(this, 'timGoogleMapApyKey')"
                            <?php checked( $options['google_map_enabled'], 1 ); ?> /> <?php _e( 'Enable', $this->plugin_name ); ?> 
                    </label>
                    <span id="timGoogleMapApyKey"<?php echo $hideGoogleMapApyKey; ?>>
                        <span style="margin-left: 20px;">
                            <b>y<?php _e( 'APY key', $this->plugin_name ); ?>:</b>
                            <input 
                                type="text" 
                                name="<?php echo $settings_section ?>[google_map_api_key]" 
                                value="<?php echo $google_map_api_key; ?>" 
                                maxlength="100" 
                                size="40" /> <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"><?php _e( 'More info', $this->plugin_name ); ?></a>
                        </span>
                    </span>
                </td>
            </tr>

            <!-- Translation plugin -->
            <tr>
                <th><?php _e( 'Translation plugin', $this->plugin_name ); ?></th>
                <td>
                    <?php
                    $count = count($TRANSLATION_PLUGIN_OPTIONS);
                    for ( $i = 0; $i < $count; $i++ ) {
                        $item = $TRANSLATION_PLUGIN_OPTIONS[$i];

                        $option = $item['option'];
                        $name = $item['name'];
                        ?>
                        <input 
                            type="radio" 
                            id="translation_plugin_<?php echo $option; ?>" 
                            name="<?php echo $settings_section; ?>[translation_plugin_id]" 
                            value="<?php echo $option; ?>"<?php echo checked( $option, $options['translation_plugin_id'], false ) ?> 
                            onclick="timDisplayContentForRadios(this, 'timTranslationSettings')" /> 
                        <label for="translation_plugin_<?php echo $option; ?>"><?php echo $name ?></label> &nbsp; | &nbsp; 
                        <?php
                    }
                    ?> <input type="hidden" name="translation_error"></div>
                </td>
            </tr>

            <!-- Translation language label -->
            <tr id="timTranslationSettings"<?php echo $hideTranslationSettings; ?>>
                <th><?php _e( 'Translation settings', $this->plugin_name ); ?></th>
                <td>
                    <?php
                    $count = count($TRANSLATION_PLUGIN_LABEL_OPTIONS);
                    for ( $i = 0; $i < $count; $i++ ) {
                        $item = $TRANSLATION_PLUGIN_LABEL_OPTIONS[$i];

                        $option = $item['option'];
                        $name = $item['name'];
                        ?>
                        <input 
                            type="radio" 
                            id="translation_plugin_label_setting_<?php echo $option; ?>" 
                            name="<?php echo $settings_section; ?>[translation_plugin_label_setting]" 
                            value="<?php echo $option; ?>"<?php echo checked( $option, $options['translation_plugin_label_setting'], false ) ?> /> 
                        <label for="translation_plugin_label_setting_<?php echo $option; ?>"><?php echo $name ?></label> &nbsp; | &nbsp; 
                        <?php
                    }
                    ?> <input type="hidden" name="translation_error"></div>
                </td>
            </tr>
        </table>
    </div>
</div>




<?php
//$options['translation_plugin_id'] = ( count($TRANSLATION_PLUGIN_OPTIONS['translation_plugin_id']) ) ? $TRANSLATION_PLUGIN_OPTIONS['translation_plugin_id'] : 'qtranslate-x';
// $options['translation_plugin_id'] = ( count($options['translation_plugin_id']) ) ? $options['translation_plugin_id'] : 'qtranslate-x';
?>