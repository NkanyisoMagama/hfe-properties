<?php
/**
 * Register Custom Post Types
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Property Post Type
 */
function hfe_register_property_post_type() {
    $labels = array(
        'name'                  => _x('Properties', 'Post Type General Name', 'hfe-properties'),
        'singular_name'         => _x('Property', 'Post Type Singular Name', 'hfe-properties'),
        'menu_name'             => __('Properties', 'hfe-properties'),
        'name_admin_bar'        => __('Property', 'hfe-properties'),
        'archives'              => __('Property Archives', 'hfe-properties'),
        'attributes'            => __('Property Attributes', 'hfe-properties'),
        'parent_item_colon'     => __('Parent Property:', 'hfe-properties'),
        'all_items'             => __('All Properties', 'hfe-properties'),
        'add_new_item'          => __('Add New Property', 'hfe-properties'),
        'add_new'               => __('Add New', 'hfe-properties'),
        'new_item'              => __('New Property', 'hfe-properties'),
        'edit_item'             => __('Edit Property', 'hfe-properties'),
        'update_item'           => __('Update Property', 'hfe-properties'),
        'view_item'             => __('View Property', 'hfe-properties'),
        'view_items'            => __('View Properties', 'hfe-properties'),
        'search_items'          => __('Search Property', 'hfe-properties'),
        'not_found'             => __('Not found', 'hfe-properties'),
        'not_found_in_trash'    => __('Not found in Trash', 'hfe-properties'),
        'featured_image'        => __('Featured Image', 'hfe-properties'),
        'set_featured_image'    => __('Set featured image', 'hfe-properties'),
        'remove_featured_image' => __('Remove featured image', 'hfe-properties'),
        'use_featured_image'    => __('Use as featured image', 'hfe-properties'),
        'insert_into_item'      => __('Insert into property', 'hfe-properties'),
        'uploaded_to_this_item' => __('Uploaded to this property', 'hfe-properties'),
        'items_list'            => __('Properties list', 'hfe-properties'),
        'items_list_navigation' => __('Properties list navigation', 'hfe-properties'),
        'filter_items_list'     => __('Filter properties list', 'hfe-properties'),
    );

    $args = array(
        'label'                 => __('Property', 'hfe-properties'),
        'description'           => __('Properties for sale and rent', 'hfe-properties'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        'taxonomies'            => array('property_type', 'property_location'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-building',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array('slug' => 'property'),
    );

    register_post_type('hfe_property', $args);
}
add_action('init', 'hfe_register_property_post_type', 0);

/**
 * Register Property Type Taxonomy
 */
function hfe_register_property_type_taxonomy() {
    $labels = array(
        'name'                       => _x('Property Types', 'Taxonomy General Name', 'hfe-properties'),
        'singular_name'              => _x('Property Type', 'Taxonomy Singular Name', 'hfe-properties'),
        'menu_name'                  => __('Property Types', 'hfe-properties'),
        'all_items'                  => __('All Types', 'hfe-properties'),
        'parent_item'                => __('Parent Type', 'hfe-properties'),
        'parent_item_colon'          => __('Parent Type:', 'hfe-properties'),
        'new_item_name'              => __('New Type Name', 'hfe-properties'),
        'add_new_item'               => __('Add New Type', 'hfe-properties'),
        'edit_item'                  => __('Edit Type', 'hfe-properties'),
        'update_item'                => __('Update Type', 'hfe-properties'),
        'view_item'                  => __('View Type', 'hfe-properties'),
        'separate_items_with_commas' => __('Separate types with commas', 'hfe-properties'),
        'add_or_remove_items'        => __('Add or remove types', 'hfe-properties'),
        'choose_from_most_used'      => __('Choose from the most used', 'hfe-properties'),
        'popular_items'              => __('Popular Types', 'hfe-properties'),
        'search_items'               => __('Search Types', 'hfe-properties'),
        'not_found'                  => __('Not Found', 'hfe-properties'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'property-type'),
    );

    register_taxonomy('property_type', array('hfe_property'), $args);
}
add_action('init', 'hfe_register_property_type_taxonomy', 0);

/**
 * Register Property Location Taxonomy
 */
function hfe_register_property_location_taxonomy() {
    $labels = array(
        'name'                       => _x('Locations', 'Taxonomy General Name', 'hfe-properties'),
        'singular_name'              => _x('Location', 'Taxonomy Singular Name', 'hfe-properties'),
        'menu_name'                  => __('Locations', 'hfe-properties'),
        'all_items'                  => __('All Locations', 'hfe-properties'),
        'parent_item'                => __('Parent Location', 'hfe-properties'),
        'parent_item_colon'          => __('Parent Location:', 'hfe-properties'),
        'new_item_name'              => __('New Location Name', 'hfe-properties'),
        'add_new_item'               => __('Add New Location', 'hfe-properties'),
        'edit_item'                  => __('Edit Location', 'hfe-properties'),
        'update_item'                => __('Update Location', 'hfe-properties'),
        'view_item'                  => __('View Location', 'hfe-properties'),
        'separate_items_with_commas' => __('Separate locations with commas', 'hfe-properties'),
        'add_or_remove_items'        => __('Add or remove locations', 'hfe-properties'),
        'choose_from_most_used'      => __('Choose from the most used', 'hfe-properties'),
        'popular_items'              => __('Popular Locations', 'hfe-properties'),
        'search_items'               => __('Search Locations', 'hfe-properties'),
        'not_found'                  => __('Not Found', 'hfe-properties'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'location'),
    );

    register_taxonomy('property_location', array('hfe_property'), $args);
}
add_action('init', 'hfe_register_property_location_taxonomy', 0);
