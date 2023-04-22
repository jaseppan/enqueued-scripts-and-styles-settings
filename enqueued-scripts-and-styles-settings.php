<?php

/**
 * Plugin Name: Enqueued Scripts and Styles Settings
 * Plugin URI: https://janneseppanen.site
 * Description: Allows users to select how enqueued scripts and styles should be loaded.
 * Version: 1.0
 * Author: Janne Seppänen
 * Author URI: https://janneseppanen.site
 */

// Register the plugin settings page
add_action( 'admin_menu', 'enqueued_scripts_and_styles_menu' );
function enqueued_scripts_and_styles_menu() {
    add_options_page( 'Enqueued Scripts and Styles Settings', 'Enqueued Scripts and Styles', 'manage_options', 'enqueued_scripts_and_styles', 'enqueued_scripts_and_styles_settings_page' );
}

// Add the plugin settings fields
add_action( 'admin_init', 'enqueued_scripts_and_styles_settings' );
function enqueued_scripts_and_styles_settings() {
    register_setting( 'enqueued_scripts_and_styles_options', 'enqueued_scripts_and_styles_options', 'enqueued_scripts_and_styles_sanitize_options' );

    add_settings_section( 'enqueued_scripts_and_styles_scripts_section', 'Enqueued Scripts', 'enqueued_scripts_and_styles_scripts_section_callback', 'enqueued_scripts_and_styles' );
    add_settings_section( 'enqueued_scripts_and_styles_styles_section', 'Enqueued Styles', 'enqueued_scripts_and_styles_styles_section_callback', 'enqueued_scripts_and_styles' );

    $registered_scripts = wp_scripts()->registered;
    $registered_styles = wp_styles()->registered;

    foreach ( $registered_scripts as $handle => $script ) {
        add_settings_field(
            'enqueued_scripts_and_styles_scripts_field_' . $handle,
            $handle,
            'enqueued_scripts_and_styles_field_callback',
            'enqueued_scripts_and_styles',
            'enqueued_scripts_and_styles_scripts_section',
            array(
                'type' => 'script',
                'handle' => $handle,
                'default' => 'none',
            )
        );
    }

    foreach ( $registered_styles as $handle => $style ) {
        add_settings_field(
            'enqueued_scripts_and_styles_styles_field_' . $handle,
            $handle,
            'enqueued_scripts_and_styles_field_callback',
            'enqueued_scripts_and_styles',
            'enqueued_scripts_and_styles_styles_section',
            array(
                'type' => 'style',
                'handle' => $handle,
                'default' => 'none',
            )
        );
    }
}

// Sanitize the plugin options
function enqueued_scripts_and_styles_sanitize_options( $input ) {
    $output = array();

    $registered_scripts = wp_scripts()->registered;
    $registered_styles = wp_styles()->registered;

    foreach ( $registered_scripts as $handle => $script ) {
        if ( isset( $input[ $handle ] ) && in_array( $input[ $handle ], array( 'none', 'async', 'defer', 'disable' ) ) ) {
            $output[ $handle ] = $input[ $handle ];
        } else {
            $output[ $handle ] = 'none';
        }
    }

    foreach ( $registered_styles as $handle => $style ) {
        if ( isset( $input[ $handle ] ) && in_array( $input[ $handle ], array( 'none', 'async', 'defer', 'disable' ) ) ) {
            $output[ $handle ] = $input[ $handle ];
        } else {
            $output[ $handle ] = 'none';
        }
    }

    return $output;
}

// Display the plugin settings page
function enqueued_scripts_and_styles_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'enqueued_scripts_and_styles_options' ); ?>
            <?php do_settings_sections( 'enqueued_scripts_and_styles' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Display the script settings section header
function enqueued_scripts_and_styles_scripts_section_callback() {
    echo '<p>Select how enqueued scripts should be loaded.</p>';
}

// Display the style settings section header
function enqueued_scripts_and_styles_styles_section_callback() {
    echo '<p>Select how enqueued styles should be loaded.</p>';
}

// Display the plugin settings fields
function enqueued_scripts_and_styles_field_callback( $args ) {
    $options = get_option( 'enqueued_scripts_and_styles_options' );
    $type = $args['type'];
    $handle = $args['handle'];
    $default = $args['default'];
    $selected = isset( $options[ $handle ] ) ? $options[ $handle ] : $default;

    echo '<select name="enqueued_scripts_and_styles_options[' . $handle . ']">';
    echo '<option value="none" ' . selected( $selected, 'none', false ) . '>None</option>';
    echo '<option value="async" ' . selected( $selected, 'async', false ) . '>Async</option>';
    echo '<option value="defer" ' . selected( $selected, 'defer', false ) . '>Defer</option>';
    echo '<option value="disable" ' . selected( $selected, 'disable', false ) . '>Disable</option>';
    echo '</select>';
}

// Modify enqueued scripts and styles based on the plugin options
add_action( 'wp_enqueue_scripts', 'enqueued_scripts_and_styles_modify_enqueued_scripts_and_styles', 9999 );
function enqueued_scripts_and_styles_modify_enqueued_scripts_and_styles() {
    $options = get_option( 'enqueued_scripts_and_styles_options' );
    $registered_scripts = wp_scripts()->registered;
    $registered_styles = wp_styles()->registered;

    foreach ( $registered_scripts as $handle => $script ) {
        if ( isset( $options[ $handle ] ) ) {
            $option = $options[ $handle ];

            if ( $option === 'async' ) {
                wp_script_add_data( $handle, 'async', true );
            } elseif ( $option === 'defer' ) {
                wp_script_add_data( $handle, 'defer', true );
            } elseif ( $option === 'disable' ) {
                wp_deregister_script( $handle );
            }
        }
    }

    foreach ( $registered_styles as $handle => $style ) {
        if ( isset( $options[ $handle ] ) ) {
            $option = $options[ $handle ];

            if ( $option === 'disable' ) {
                wp_deregister_style( $handle );
            }
        }
    }
}

// Elementor - Remove Font Awesome 
add_action( 'elementor/frontend/after_register_styles',function() {
    foreach( [ 'solid', 'regular', 'brands' ] as $style ) {
      wp_deregister_style( 'elementor-icons-fa-' . $style );
    }
}, 20 );