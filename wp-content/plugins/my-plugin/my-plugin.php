<?php
/*
* Plugin Name: FAQ Plugin
* Description: Display content in the form of FAQ
* Author: Gazal
* Version: 1.0
*/

if(!defined('ABSPATH')){
    header("Location: /wp");
    die();
}

function my_plugin_activation(){
     // Add code to create the post when the plugin is activated
     $post_id = wp_insert_post(array(
        'post_title' => 'FAQ',
        'post_content' => '[faq-accordion]',
        'post_status' => 'publish',
    ));
    // Save the post ID for future reference
    update_option('my_custom_post_id', $post_id);
}

register_activation_hook(__FILE__, 'my_plugin_activation');

function my_plugin_deactivation(){
    // Retrieve the post ID from the options and delete the post when the plugin is deactivated
    $post_id_to_delete = get_option('my_custom_post_id');
    if ($post_id_to_delete) {
        wp_delete_post($post_id_to_delete, true);
    }
}

register_deactivation_hook(__FILE__, 'my_plugin_deactivation');

// function connect_to_custom_database() {
//     $db_host = 'localhost';
//     $db_user = 'root';
//     $db_pass = '';
//     $db_name = 'wp';

//     $db = new wpdb($db_user, $db_pass, $db_name, $db_host);

//     return $db;
// }

function faq_accordion_shortcode() {
    ob_start();
    include('functions.php');
    return ob_get_clean();
}

add_shortcode('faq-accordion', 'faq_accordion_shortcode');



// add_action('init', 'faq_accordion_init');


