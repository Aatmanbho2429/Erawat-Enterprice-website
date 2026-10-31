<?php
/**
 * Enquiries — every contact-form submission is saved here (wp-admin → Enquiries), so none is lost
 * if the notification email fails. Private: no front-end URL, not in search, sitemaps or the REST API.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register the private "Enquiries" post type. Only the contact form creates enquiries (there is no
 * "Add New"); Editors and Administrators can read and delete them.
 */
function erawat_register_enquiries() {
    register_post_type( 'erawat_enquiry', [
        'labels'              => [
            'name'               => __( 'Enquiries', 'erawat-enterprise' ),
            'singular_name'      => __( 'Enquiry', 'erawat-enterprise' ),
            'menu_name'          => __( 'Enquiries', 'erawat-enterprise' ),
            'all_items'          => __( 'All Enquiries', 'erawat-enterprise' ),
            'edit_item'          => __( 'Enquiry', 'erawat-enterprise' ),
            'search_items'       => __( 'Search Enquiries', 'erawat-enterprise' ),
            'not_found'          => __( 'No enquiries yet.', 'erawat-enterprise' ),
            'not_found_in_trash' => __( 'No enquiries in the Bin.', 'erawat-enterprise' ),
        ],
        'public'              => false,
        'publicly_queryable'  => false,
        'exclude_from_search' => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => false,
        'show_in_rest'        => false,
        'has_archive'         => false,
        'rewrite'             => false,
        'query_var'           => false,
        'menu_position'       => 3,
        'menu_icon'           => 'dashicons-email-alt',
        'supports'            => [ 'title' ],
        'capability_type'     => 'post',
        'capabilities'        => [
            'edit_posts'   => 'edit_others_posts', // list screen: Editors and Administrators only
            'create_posts' => 'do_not_allow',      // created by the contact form, never by hand
        ],
        'map_meta_cap'        => true,
    ] );
}
add_action( 'init', 'erawat_register_enquiries' );

/**
 * Field keys stored as post meta (`_erawat_{key}`), with their admin labels.
 */
function erawat_enquiry_fields() {
    return [
        'name'     => __( 'Name', 'erawat-enterprise' ),
        'phone'    => __( 'Phone', 'erawat-enterprise' ),
        'email'    => __( 'Email', 'erawat-enterprise' ),
        'company'  => __( 'Company', 'erawat-enterprise' ),
        'product'  => __( 'Product', 'erawat-enterprise' ),
        'quantity' => __( 'Quantity', 'erawat-enterprise' ),
        'location' => __( 'Delivery location', 'erawat-enterprise' ),
    ];
}

/**
 * Save one enquiry. $fields uses the keys of erawat_enquiry_fields() (already sanitised);
 * $message is the requirements text. Returns the new post ID, or 0 if it could not be saved.
 */
function erawat_save_enquiry( $fields, $message ) {
    $id = wp_insert_post( [
        'post_type'    => 'erawat_enquiry',
        'post_status'  => 'publish',
        'post_title'   => $fields['name'] . ' — ' . $fields['product'],
        'post_content' => $message,
    ], true );

    if ( is_wp_error( $id ) || ! $id ) {
        return 0;
    }

    foreach ( array_keys( erawat_enquiry_fields() ) as $key ) {
        update_post_meta( $id, '_erawat_' . $key, $fields[ $key ] ?? '' );
    }
    return (int) $id;
}

/**
 * Record whether the notification email went out ('sent' | 'failed').
 */
function erawat_set_enquiry_mail_status( $id, $sent ) {
    if ( $id ) {
        update_post_meta( $id, '_erawat_mail', $sent ? 'sent' : 'failed' );
    }
}

/**
 * The value of one field for display: a tel: / mailto: link where it helps, '—' when empty.
 */
function erawat_enquiry_value_html( $post_id, $key ) {
    $value = (string) get_post_meta( $post_id, '_erawat_' . $key, true );
    if ( '' === $value ) {
        return '—';
    }
    if ( 'phone' === $key ) {
        return '<a href="' . esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $value ) ) . '">' . esc_html( $value ) . '</a>';
    }
    if ( 'email' === $key ) {
        return '<a href="' . esc_url( 'mailto:' . $value ) . '">' . esc_html( $value ) . '</a>';
    }
    return esc_html( $value );
}

/**
 * "Email sent" badge.
 */
function erawat_enquiry_mail_html( $post_id ) {
    $status = get_post_meta( $post_id, '_erawat_mail', true );
    if ( 'sent' === $status ) {
        return '<span style="color:#1A5C38;font-weight:600">' . esc_html__( '✓ Sent', 'erawat-enterprise' ) . '</span>';
    }
    if ( 'failed' === $status ) {
        return '<span style="color:#B3261E;font-weight:600">' . esc_html__( '✗ Not sent — reply from here', 'erawat-enterprise' ) . '</span>';
    }
    return '—';
}

/* ─── Admin list columns ─── */
function erawat_enquiry_columns( $columns ) {
    return [
        'cb'       => $columns['cb'],
        'title'    => __( 'Name — Product', 'erawat-enterprise' ),
        'phone'    => __( 'Phone', 'erawat-enterprise' ),
        'email'    => __( 'Email', 'erawat-enterprise' ),
        'location' => __( 'Delivery location', 'erawat-enterprise' ),
        'mail'     => __( 'Email sent', 'erawat-enterprise' ),
        'date'     => __( 'Received', 'erawat-enterprise' ),
    ];
}
add_filter( 'manage_erawat_enquiry_posts_columns', 'erawat_enquiry_columns' );

function erawat_enquiry_column( $column, $post_id ) {
    if ( 'mail' === $column ) {
        echo erawat_enquiry_mail_html( $post_id ); // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts
    } elseif ( in_array( $column, [ 'phone', 'email', 'location' ], true ) ) {
        echo erawat_enquiry_value_html( $post_id, $column ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper
    }
}
add_action( 'manage_erawat_enquiry_posts_custom_column', 'erawat_enquiry_column', 10, 2 );

/* ─── Single enquiry screen: a read-only details box ─── */
function erawat_enquiry_meta_box() {
    add_meta_box( 'erawat-enquiry-details', __( 'Enquiry details', 'erawat-enterprise' ), 'erawat_enquiry_details_box', 'erawat_enquiry', 'normal', 'high' );
}
add_action( 'add_meta_boxes_erawat_enquiry', 'erawat_enquiry_meta_box' );

function erawat_enquiry_details_box( $post ) {
    echo '<table class="form-table" role="presentation"><tbody>';
    echo '<tr><th scope="row">' . esc_html__( 'Received', 'erawat-enterprise' ) . '</th><td>'
       . esc_html( get_the_date( 'j M Y, g:i a', $post ) ) . '</td></tr>';
    foreach ( erawat_enquiry_fields() as $key => $label ) {
        echo '<tr><th scope="row">' . esc_html( $label ) . '</th><td>'
           . erawat_enquiry_value_html( $post->ID, $key ) . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper
    }
    echo '<tr><th scope="row">' . esc_html__( 'Requirements', 'erawat-enterprise' ) . '</th><td>'
       . nl2br( esc_html( $post->post_content ) ) . '</td></tr>';
    echo '<tr><th scope="row">' . esc_html__( 'Email notification', 'erawat-enterprise' ) . '</th><td>'
       . erawat_enquiry_mail_html( $post->ID ) . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts
    echo '</tbody></table>';
}
