<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>document.documentElement.className += ' js';</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'erawat-enterprise' ); ?></a>

<!-- ══════════════ MAIN HEADER ══════════════ -->
<header class="site-header" id="site-header">
    <div class="scroll-progress" id="scroll-progress" aria-hidden="true"></div>

    <div class="container site-header__inner">

        <!-- Logo -->
        <div class="site-header__logo">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                    <img src="<?php echo esc_url( erawat_image_url( 'logo-erawat.png' ) ); ?>" alt="Erawat Enterprise" width="780" height="252">
                </a>
            <?php endif; ?>
        </div>

        <!-- Primary Navigation -->
        <nav class="site-header__nav" id="primary-nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'erawat-enterprise' ); ?>">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'menu_class'     => 'nav-menu',
                'container'      => false,
                'fallback_cb'    => 'erawat_fallback_menu',
            ] );
            ?>
        </nav>

        <!-- Phone + CTA -->
        <div class="site-header__cta">
            <a href="tel:<?php echo esc_attr( ERAWAT_PHONE_TEL ); ?>" class="site-header__phone" aria-label="<?php echo esc_attr( 'Call ' . ERAWAT_PHONE ); ?>">
                <i class="fas fa-phone-alt" aria-hidden="true"></i>
            </a>
            <a href="<?php echo esc_url( erawat_url( 'contact-us' ) ); ?>" class="btn btn--primary btn--sm site-header__quote">
                <?php esc_html_e( 'Get a Quote', 'erawat-enterprise' ); ?>
                <?php echo erawat_btn_circle(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
            </a>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" id="mobile-menu-toggle" type="button" aria-label="<?php esc_attr_e( 'Toggle Menu', 'erawat-enterprise' ); ?>" aria-expanded="false" aria-controls="mobile-nav">
            <span></span>
            <span></span>
        </button>
    </div>
</header>

<!-- Mobile drawer (outside the header so the frosted-glass header does not trap its fixed positioning) -->
<div class="mobile-nav-overlay" id="mobile-nav-overlay"></div>
<div class="mobile-nav" id="mobile-nav" aria-hidden="true">
    <div class="mobile-nav__inner">
        <nav aria-label="<?php esc_attr_e( 'Mobile Navigation', 'erawat-enterprise' ); ?>">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'menu_class'     => 'mobile-nav-menu',
                'container'      => false,
                'fallback_cb'    => 'erawat_fallback_mobile_menu',
            ] );
            ?>
        </nav>
        <div class="mobile-nav__footer">
            <a href="tel:<?php echo esc_attr( ERAWAT_PHONE_TEL ); ?>" class="mobile-nav__contact">
                <i class="fas fa-phone-alt" aria-hidden="true"></i> <?php echo esc_html( ERAWAT_PHONE ); ?>
            </a>
            <a href="<?php echo esc_url( erawat_url( 'contact-us' ) ); ?>" class="btn btn--white btn--lg btn--full"><?php esc_html_e( 'Get a Quote', 'erawat-enterprise' ); ?> <?php echo erawat_btn_circle(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
        </div>
    </div>
</div>

<main id="main" class="site-main">
<?php

/**
 * Hardcoded menu used until a menu is assigned to "Primary Navigation".
 * Returns [ label, url, is_current ] rows.
 */
function erawat_fallback_menu_items() {
    $items = [
        [ 'Home',     '',                'home' ],
        [ 'About Us', 'about-us',        'about-us' ],
        [ 'Products', 'product-range',   'product-range' ],
        [ 'Services', 'services',        'services' ],
        [ 'ISPM 15',  'ispm-15-packing', 'ispm-15-packing' ],
        [ 'Contact',  'contact-us',      'contact-us' ],
    ];

    $rows = [];
    foreach ( $items as $item ) {
        $is_current = ( 'home' === $item[2] ) ? is_front_page() : is_page( $item[2] );
        $rows[]     = [ $item[0], erawat_url( $item[1] ), $is_current ];
    }
    return $rows;
}

/* Fallback menus */
function erawat_fallback_menu() {
    echo '<ul class="nav-menu">';
    foreach ( erawat_fallback_menu_items() as $row ) {
        $class = $row[2] ? ' class="current-menu-item"' : '';
        $aria  = $row[2] ? ' aria-current="page"' : '';
        echo '<li' . $class . '><a href="' . esc_url( $row[1] ) . '"' . $aria . '>' . esc_html( $row[0] ) . '</a></li>';
    }
    echo '</ul>';
}

function erawat_fallback_mobile_menu() {
    echo '<ul class="mobile-nav-menu">';
    foreach ( erawat_fallback_menu_items() as $row ) {
        $class = $row[2] ? ' class="current-menu-item"' : '';
        $aria  = $row[2] ? ' aria-current="page"' : '';
        echo '<li' . $class . '><a href="' . esc_url( $row[1] ) . '"' . $aria . '>' . esc_html( $row[0] ) . '</a></li>';
    }
    echo '</ul>';
}
?>
