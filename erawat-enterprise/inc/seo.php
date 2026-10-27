<?php
/**
 * SEO: page titles, meta description / keywords, Open Graph, Twitter card, LocalBusiness JSON-LD,
 * and the favicon fallback. The meta output is skipped when Yoast SEO or Rank Math is active.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * True when a dedicated SEO plugin is handling titles/meta.
 */
function erawat_seo_plugin_active() {
    return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' );
}

/**
 * Title + meta description per page. Key is the page slug (the front page uses `home`).
 */
function erawat_seo_pages() {
    return [
        'home' => [
            'Solar Pallets & Box Pallets in Gujarat, Rajasthan & Jaipur | Erawat Enterprise',
            'Erawat Enterprise makes solar pallets, box pallets and custom wooden pallets for solar panel makers in Gujarat, Rajasthan and Jaipur. Get a fast quote.',
        ],
        'about-us' => [
            'About Erawat Enterprise | Wooden Pallet Manufacturer in Gujarat',
            'Erawat Enterprise is a Gujarat wooden pallet manufacturer making solar pallets and box pallets for solar companies in Gujarat, Rajasthan and Jaipur.',
        ],
        'product-range' => [
            'Solar Pallets, Box Pallets & Custom Wooden Pallets | Erawat Enterprise',
            'Solar pallets, box pallets, customised solar pallets and customised box pallets — wooden pallets made to size in Gujarat and supplied across Rajasthan and Jaipur.',
        ],
        'services' => [
            'Wooden Pallet Manufacturing & ISPM 15 Services | Erawat Enterprise',
            'Pallet design, bulk manufacturing, ISPM 15 heat treatment and delivery of solar pallets and box pallets across Gujarat, Rajasthan and Jaipur.',
        ],
        'ispm-15-packing' => [
            'ISPM 15 Heat Treated Pallets for Export | Erawat Enterprise',
            'ISPM 15 heat-treated solar pallets and box pallets with IPPC marking for export, made in Gujarat by Erawat Enterprise.',
        ],
        'contact-us' => [
            'Contact Erawat Enterprise | Solar Pallet Supplier in Gujarat',
            'Get a quote for solar pallets, box pallets and custom wooden pallets. Call or WhatsApp +91 99310 60355 — supplying Gujarat, Rajasthan and Jaipur.',
        ],
    ];
}

/**
 * Slug key of the page being viewed, if it is one of the six mapped pages.
 */
function erawat_seo_current_key() {
    $pages = erawat_seo_pages();

    if ( is_front_page() ) {
        return 'home';
    }
    if ( is_page() ) {
        $slug = get_post_field( 'post_name', get_queried_object_id() );
        if ( $slug && isset( $pages[ $slug ] ) ) {
            return $slug;
        }
    }
    return '';
}

/**
 * Mapped <title> for the six pages.
 */
function erawat_seo_document_title( $title ) {
    if ( erawat_seo_plugin_active() ) {
        return $title;
    }
    $key   = erawat_seo_current_key();
    $pages = erawat_seo_pages();
    if ( $key && isset( $pages[ $key ] ) ) {
        return $pages[ $key ][0];
    }
    return $title;
}
add_filter( 'pre_get_document_title', 'erawat_seo_document_title' );

/**
 * Keyword list: every base keyword, plus each with the three location suffixes.
 * "erawat enterprice" (common misspelling) is included here only — never in visible copy.
 */
function erawat_seo_keywords() {
    $bases = [
        'solar pallet', 'wooden pallet', 'box pallet', 'solar', 'pallet', 'solar box',
        'erawat', 'erawat enterprise', 'erawat enterprice',
    ];
    $places = [ 'Gujarat', 'Rajasthan', 'Jaipur' ];

    $keywords = [];
    foreach ( $bases as $base ) {
        $keywords[] = $base;
        foreach ( $places as $place ) {
            $keywords[] = $base . ' in ' . $place;
        }
    }
    return $keywords;
}

/**
 * LocalBusiness structured data (one entity, printed on every page).
 */
function erawat_seo_schema() {
    $offers = [];
    foreach ( erawat_products() as $product ) {
        $offers[] = [
            '@type'       => 'Offer',
            'itemOffered' => [
                '@type'       => 'Product',
                'name'        => $product['name'],
                'description' => $product['caption'],
                'image'       => erawat_product_image_url( $product ),
            ],
        ];
    }

    return [
        '@context'        => 'https://schema.org',
        '@type'           => 'LocalBusiness',
        'name'            => 'Erawat Enterprise',
        'url'             => home_url( '/' ),
        'logo'            => erawat_image_url( 'logo-erawat.png' ),
        'image'           => erawat_image_url( 'hero.jpg' ),
        'description'     => 'Erawat Enterprise makes solar pallets, box pallets and custom wooden pallets for solar panel makers in Gujarat, Rajasthan and Jaipur.',
        'telephone'       => '+91-99310-60355',
        'email'           => ERAWAT_EMAIL,
        'address'         => [
            '@type'           => 'PostalAddress',
            'addressRegion'   => 'Gujarat',
            'addressCountry'  => 'IN',
        ],
        'areaServed'      => [
            [ '@type' => 'State', 'name' => 'Gujarat' ],
            [ '@type' => 'State', 'name' => 'Rajasthan' ],
            [ '@type' => 'City',  'name' => 'Jaipur' ],
        ],
        'sameAs'          => [ ERAWAT_LINKEDIN ],
        'hasOfferCatalog' => [
            '@type'           => 'OfferCatalog',
            'name'            => 'Solar Pallets & Box Pallets',
            'itemListElement' => $offers,
        ],
    ];
}

/**
 * Print meta tags, Open Graph and JSON-LD.
 */
function erawat_seo_head() {
    if ( erawat_seo_plugin_active() ) {
        return;
    }

    $pages = erawat_seo_pages();
    $key   = erawat_seo_current_key();

    if ( $key && isset( $pages[ $key ] ) ) {
        $title       = $pages[ $key ][0];
        $description = $pages[ $key ][1];
        $url         = is_front_page() ? home_url( '/' ) : get_permalink();
    } else {
        $title       = wp_get_document_title();
        $description = $pages['home'][1];
        $url         = is_404() ? '' : home_url( '/' );
        if ( is_singular() ) {
            $url = get_permalink();
        }
    }

    echo "\n<!-- Erawat SEO -->\n";
    echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
    echo '<meta name="keywords" content="' . esc_attr( implode( ', ', erawat_seo_keywords() ) ) . '">' . "\n";

    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:site_name" content="Erawat Enterprise">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
    if ( $url ) {
        echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
    }
    echo '<meta property="og:image" content="' . esc_url( erawat_image_url( 'hero.jpg' ) ) . '">' . "\n";
    echo '<meta property="og:locale" content="en_IN">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";

    echo '<script type="application/ld+json">' . wp_json_encode( erawat_seo_schema() ) . '</script>' . "\n";
    echo "<!-- /Erawat SEO -->\n";
}
add_action( 'wp_head', 'erawat_seo_head', 1 );

/**
 * Favicon fallback when no Site Icon is set in Appearance → Customize.
 */
function erawat_seo_favicon() {
    if ( has_site_icon() ) {
        return;
    }
    $icon = esc_url( erawat_image_url( 'favicon.png' ) );
    echo '<link rel="icon" type="image/png" href="' . $icon . '">' . "\n";
    echo '<link rel="apple-touch-icon" href="' . $icon . '">' . "\n";
}
add_action( 'wp_head', 'erawat_seo_favicon', 2 );
