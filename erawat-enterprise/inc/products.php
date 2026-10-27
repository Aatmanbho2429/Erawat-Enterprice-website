<?php
/**
 * Product data — the four products Erawat Enterprise sells.
 * Used by the Home cards, Product page, footer list, contact form select and SEO schema.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The four products, in display order, keyed by slug.
 *
 * - caption      One-liner shown on the Home cards and in the footer/SEO.
 * - page_caption One-liner shown on the image on the Product page.
 * - image        Preferred file in assets/images/.
 * - fallback     File used while the preferred one does not exist yet (or null).
 * - fallback_pos Optional CSS object-position for the fallback photo.
 */
function erawat_products() {
    return [
        'solar-pallet' => [
            'slug'         => 'solar-pallet',
            'name'         => 'Solar Pallet',
            'caption'      => 'Long, flat wooden pallet sized to carry a full stack of solar modules.',
            'page_caption' => 'Long-format wooden pallet for stacked solar modules.',
            'image'        => 'product-solar-pallet.jpg',
            'fallback'     => null,
            'alt'          => 'Wooden solar pallet for stacking solar panels — Erawat Enterprise, Gujarat',
            'icon'         => 'fa-layer-group',
        ],
        'box-pallet' => [
            'slug'         => 'box-pallet',
            'name'         => 'Box Pallet',
            'caption'      => 'A closed wooden box on a pallet base, with foam dividers and a hinged lid.',
            'page_caption' => 'Closed wooden box on a pallet base for solar panels.',
            'image'        => 'product-box-pallet.jpg',
            'fallback'     => null,
            'alt'          => 'Wooden box pallet with a braced pine frame and panelled walls on a forklift pallet base — Erawat Enterprise, Gujarat',
            'icon'         => 'fa-box-open',
        ],
        'custom-solar-pallet' => [
            'slug'         => 'custom-solar-pallet',
            'name'         => 'Customised Solar Pallet',
            'caption'      => 'Your module size, stack height and fork entry — built to your drawing.',
            'page_caption' => 'Built to your module size, stack and handling.',
            'image'        => 'product-custom-solar-pallet.jpg',
            'fallback'     => null,
            'alt'          => 'Solar modules wrapped and strapped on customised wooden solar pallets at a solar project site',
            'icon'         => 'fa-ruler-combined',
        ],
        'custom-box-pallet' => [
            'slug'         => 'custom-box-pallet',
            'name'         => 'Customised Box Pallet',
            'caption'      => 'Your box size, panel count, partitions and lid style.',
            'page_caption' => 'Your box size, partitions and lid style.',
            'image'        => 'product-custom-box-pallet.jpg',
            'fallback'     => null,
            'alt'          => 'Customised wooden box pallet with foam partitions holding solar panels upright',
            'icon'         => 'fa-cubes',
        ],
    ];
}

/**
 * Public URL of a file in assets/images/.
 */
function erawat_image_url( $file ) {
    return ERAWAT_URI . '/assets/images/' . ltrim( $file, '/' );
}

/**
 * Resolve a product photo: the preferred file if it exists, otherwise the fallback.
 * Returns [ 'url' => string, 'width' => int, 'height' => int, 'style' => string ].
 */
function erawat_product_image( $product ) {
    $file  = $product['image'];
    $style = '';
    if ( ! file_exists( ERAWAT_DIR . '/assets/images/' . $file ) && ! empty( $product['fallback'] ) ) {
        $file = $product['fallback'];
        if ( ! empty( $product['fallback_pos'] ) ) {
            $style = 'object-position: ' . $product['fallback_pos'] . ';';
        }
    }

    $width  = 1200;
    $height = 800;
    $size   = @getimagesize( ERAWAT_DIR . '/assets/images/' . $file ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
    if ( $size ) {
        $width  = (int) $size[0];
        $height = (int) $size[1];
    }

    return [
        'url'    => erawat_image_url( $file ),
        'width'  => $width,
        'height' => $height,
        'style'  => $style,
    ];
}

/**
 * URL of a product photo (preferred file if it exists, else the fallback).
 */
function erawat_product_image_url( $product ) {
    $image = erawat_product_image( $product );
    return $image['url'];
}
