<?php
/**
 * Auto-creates pages with correct slugs on first theme activation.
 * Called from functions.php via after_switch_theme hook.
 */

function erawat_create_pages() {
    $pages = [
        [
            'title'   => 'Home',
            'slug'    => 'home',
            'content' => '',
        ],
        [
            'title'   => 'About Us',
            'slug'    => 'about-us',
            'content' => '',
        ],
        [
            'title'   => 'Product Range',
            'slug'    => 'product-range',
            'content' => '',
        ],
        [
            'title'   => 'Services',
            'slug'    => 'services',
            'content' => '',
        ],
        [
            'title'   => 'ISPM 15 Packing',
            'slug'    => 'ispm-15-packing',
            'content' => '',
        ],
        [
            'title'   => 'Contact Us',
            'slug'    => 'contact-us',
            'content' => '',
        ],
    ];

    foreach ( $pages as $page ) {
        $existing = get_page_by_path( $page['slug'] );
        if ( ! $existing ) {
            wp_insert_post( [
                'post_title'   => $page['title'],
                'post_name'    => $page['slug'],
                'post_content' => $page['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ] );
        }
    }

    // Set home page
    $home = get_page_by_path( 'home' );
    if ( $home ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $home->ID );
    }
}
add_action( 'after_switch_theme', 'erawat_create_pages' );
