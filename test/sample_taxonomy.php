<?php

add_action('init', 'wp_tt_register_taxonomy_region');

function wp_tt_register_taxonomy_region() {
    $labels = array(
        'name' => _x( 'Regions', 'taxonomy general name', 'wp_tt' ),
        'singular_name' => _x('Regions', 'taxonomy singular name', 'wp_tt'),
        'search_items' => __('Search Region', 'wp_tt'),
        'popular_items' => __('Common Regions', 'wp_tt'),
        'all_items' => __('All Regions', 'wp_tt'),
        'edit_item' => __('Edit Region', 'wp_tt'),
        'update_item' => __('Update Region', 'wp_tt'),
        'add_new_item' => __('Add new Region', 'wp_tt'),
        'new_item_name' => __('New Region:', 'wp_tt'),
        'add_or_remove_items' => __('Remove Region', 'wp_tt'),
        'choose_from_most_used' => __('Choose from common Region', 'wp_tt'),
        'not_found' => __('No Region found.', 'wp_tt'),
        'menu_name' => __('Regions', 'wp_tt'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
    );

    register_taxonomy('wp_tt_region', array('post'), $args);
}