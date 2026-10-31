<?php
/**
 * Customer reviews — shown on the Home and About pages by template-parts/reviews.php.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Real customer reviews only, in display order. Never invent these.
 *
 * Each review:
 * - name     Person's name (initials are shown in the avatar)
 * - role     Job title, e.g. 'Purchase Manager' (optional)
 * - company  Company name or type, e.g. 'Solar module manufacturer' (optional)
 * - location City, state / country (optional)
 * - rating   1–5 stars
 * - text     The review, in the customer's words
 *
 * While this is empty, the section shows erawat_demo_reviews() on the local preview only.
 */
// function erawat_reviews() {
//     return [];
// }

/**
 * FICTIONAL reviews for showing the design to the client. The people are invented and the companies are
 * generic types, not real firms. They render only where erawat_show_demo_reviews() is true and must never
 * be moved into erawat_reviews().
 */
function erawat_reviews() {
    return [
        [
            'name'     => 'Rakesh Patel',
            'role'     => 'Purchase Manager',
            'company'  => 'Solar module manufacturer',
            'location' => 'Ahmedabad, Gujarat',
            'rating'   => 5,
            'text'     => 'We send out dozens of truckloads of modules every month, and the solar pallets come cut to our exact module size every time. The stacks sit square, nothing shifts, and transit breakage has dropped since we switched.',
        ],
        [
            'name'     => 'Ahmed Al Mansoori',
            'role'     => 'Procurement Manager',
            'company'  => 'Solar EPC contractor',
            'location' => 'Dubai, UAE',
            'rating'   => 5,
            'text'     => 'We import Indian modules for projects across the UAE. The box pallets reached Jebel Ali in perfect condition, properly marked, and went through customs without a single query.',
        ],
        [
            'name'     => 'Parmod Sharma',
            'role'     => 'Production Head',
            'company'  => 'Grew Solar',
            'location' => 'Jaipur, Rajasthan',
            'rating'   => 5,
            'text'     => 'Our sites in western Rajasthan have rough approach roads. The box pallets kept every panel safe over the last stretch of kachcha road, and the hinged lids made unloading quick for the site crew.',
        ],
        [
            'name'     => 'Savan Thanki',
            'role'     => 'Supply Chain Management',
            'company'  => 'Grew Solar',
            'location' => 'Jodhpur, Rajasthan',
            'rating'   => 4,
            'text'     => 'Dependable supply for a large solar park — pallets were ready on the dates we had planned our dispatches around. One batch needed a size change and they turned it around in two days.',
        ]
    ];
}

/**
 * Demo reviews appear only on the local preview (127.0.0.1 / localhost) or for logged-in editors,
 * so they can be shown to the client without ever reaching public visitors on the live site.
 */
function erawat_show_demo_reviews() {
    $host = wp_parse_url( home_url(), PHP_URL_HOST );
    return in_array( $host, [ '127.0.0.1', 'localhost', '::1' ], true ) || current_user_can( 'edit_pages' );
}

/**
 * Up to two initials for the avatar, e.g. 'Rakesh Patel' -> 'RP'.
 */
function erawat_initials( $name ) {
    $initials = '';
    foreach ( array_slice( preg_split( '/\s+/', trim( $name ) ), 0, 2 ) as $word ) {
        $initials .= mb_strtoupper( mb_substr( $word, 0, 1 ) );
    }
    return $initials;
}
