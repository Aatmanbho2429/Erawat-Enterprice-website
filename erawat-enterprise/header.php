<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ══════════════ TOP BAR ══════════════ -->
<div class="top-bar">
    <div class="container top-bar__inner">
        <div class="top-bar__left">
            <a href="tel:+918849776778" class="top-bar__link">
                <i class="fas fa-phone-alt"></i>
                <span>+91 88497 76778</span>
            </a>
            <a href="mailto:Erawat005@gmail.com" class="top-bar__link">
                <i class="fas fa-envelope"></i>
                <span>Erawat005@gmail.com</span>
            </a>
            <span class="top-bar__link">
                <i class="fas fa-map-marker-alt"></i>
                <span>Gujarat, India</span>
            </span>
        </div>
        <div class="top-bar__right">
            <span class="top-bar__cert">
                <i class="fas fa-certificate"></i>
                ISPM 15 Certified
            </span>
            <div class="top-bar__social">
                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════ MAIN HEADER ══════════════ -->
<header class="site-header" id="site-header">
    <div class="container site-header__inner">

        <!-- Logo -->
        <div class="site-header__logo">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url('/') ); ?>" class="logo-text" rel="home">
                    <span class="logo-text__main">Erawat</span>
                    <span class="logo-text__sub">Enterprise</span>
                </a>
            <?php endif; ?>
        </div>

        <!-- Primary Navigation -->
        <nav class="site-header__nav" id="primary-nav" aria-label="Primary Navigation">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'menu_class'     => 'nav-menu',
                'container'      => false,
                'fallback_cb'    => 'erawat_fallback_menu',
            ] );
            ?>
        </nav>

        <!-- Header CTA -->
        <div class="site-header__cta">
            <a href="<?php echo esc_url( home_url('/contact-us') ); ?>" class="btn btn--primary btn--sm">
                <i class="fas fa-clipboard-list"></i>
                Get a Quote
            </a>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" id="mobile-menu-toggle" aria-label="Toggle Menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    <!-- Mobile Nav Drawer -->
    <div class="mobile-nav" id="mobile-nav" aria-hidden="true">
        <div class="mobile-nav__inner">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'menu_class'     => 'mobile-nav-menu',
                'container'      => false,
                'fallback_cb'    => 'erawat_fallback_mobile_menu',
            ] );
            ?>
            <div class="mobile-nav__footer">
                <a href="tel:+918849776778" class="mobile-nav__contact">
                    <i class="fas fa-phone-alt"></i> +91 88497 76778
                </a>
                <a href="<?php echo esc_url( home_url('/contact-us') ); ?>" class="btn btn--primary w-full">Get a Quote</a>
            </div>
        </div>
    </div>
    <div class="mobile-nav-overlay" id="mobile-nav-overlay"></div>
</header>

<?php

/* Fallback menus */
function erawat_fallback_menu() {
    $pages = [
        'Home'          => home_url('/'),
        'About Us'      => home_url('/about-us'),
        'Product Range' => home_url('/product-range'),
        'Services'      => home_url('/services'),
        'ISPM 15'       => home_url('/ispm-15-packing'),
        'Contact Us'    => home_url('/contact-us'),
    ];
    echo '<ul class="nav-menu">';
    foreach ( $pages as $label => $url ) {
        $active = ( rtrim( $_SERVER['REQUEST_URI'], '/' ) === parse_url( $url, PHP_URL_PATH ) ) ? ' class="current-menu-item"' : '';
        echo '<li' . $active . '><a href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
    }
    echo '</ul>';
}

function erawat_fallback_mobile_menu() {
    $pages = [
        'Home'          => home_url('/'),
        'About Us'      => home_url('/about-us'),
        'Product Range' => home_url('/product-range'),
        'Services'      => home_url('/services'),
        'ISPM 15 Packing' => home_url('/ispm-15-packing'),
        'Contact Us'    => home_url('/contact-us'),
    ];
    echo '<ul class="mobile-nav-menu">';
    foreach ( $pages as $label => $url ) {
        echo '<li><a href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
    }
    echo '</ul>';
}
?>
