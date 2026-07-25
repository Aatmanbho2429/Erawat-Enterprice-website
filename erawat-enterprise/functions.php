<?php
/**
 * Erawat Enterprise Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'ERAWAT_VERSION', '1.0.0' );

/* Auto-create pages on theme activation */
require_once ERAWAT_DIR . '/inc/setup-pages.php';
define( 'ERAWAT_DIR', get_template_directory() );
define( 'ERAWAT_URI', get_template_directory_uri() );

/* ──────────────────────────────────────────────
   THEME SETUP
────────────────────────────────────────────── */
function erawat_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ] );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'custom-logo', [
        'height'      => 90,
        'width'       => 220,
        'flex-height' => true,
        'flex-width'  => true,
    ] );

    register_nav_menus( [
        'primary' => __( 'Primary Navigation', 'erawat-enterprise' ),
        'footer'  => __( 'Footer Navigation',  'erawat-enterprise' ),
    ] );
}
add_action( 'after_setup_theme', 'erawat_theme_setup' );

/* ──────────────────────────────────────────────
   ENQUEUE ASSETS
────────────────────────────────────────────── */
function erawat_enqueue_assets() {
    // Google Fonts: Cinzel (Copperplate-style) + Open Sans body
    wp_enqueue_style(
        'erawat-google-fonts',
        'https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Cinzel+Decorative:wght@400;700;900&family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap',
        [],
        null
    );

    // Font Awesome icons
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        [],
        '6.5.1'
    );

    // Main theme styles
    wp_enqueue_style(
        'erawat-main',
        ERAWAT_URI . '/assets/css/erawat-style.css',
        [ 'erawat-google-fonts', 'font-awesome' ],
        ERAWAT_VERSION
    );

    // Theme style.css (base reset)
    wp_enqueue_style( 'erawat-style', get_stylesheet_uri(), [ 'erawat-main' ], ERAWAT_VERSION );

    // Main JS
    wp_enqueue_script(
        'erawat-script',
        ERAWAT_URI . '/assets/js/erawat-script.js',
        [ 'jquery' ],
        ERAWAT_VERSION,
        true
    );

    wp_localize_script( 'erawat-script', 'erawatData', [
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'erawat_nonce' ),
    ] );
}
add_action( 'wp_enqueue_scripts', 'erawat_enqueue_assets' );

/* ──────────────────────────────────────────────
   WIDGET AREAS
────────────────────────────────────────────── */
function erawat_widgets_init() {
    register_sidebar( [
        'name'          => __( 'Footer Column 1', 'erawat-enterprise' ),
        'id'            => 'footer-1',
        'description'   => __( 'Add widgets here for the first footer column.', 'erawat-enterprise' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ] );
    register_sidebar( [
        'name'          => __( 'Footer Column 2', 'erawat-enterprise' ),
        'id'            => 'footer-2',
        'description'   => __( 'Add widgets here for the second footer column.', 'erawat-enterprise' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ] );
    register_sidebar( [
        'name'          => __( 'Sidebar', 'erawat-enterprise' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Main sidebar.', 'erawat-enterprise' ),
        'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ] );
}
add_action( 'widgets_init', 'erawat_widgets_init' );

/* ──────────────────────────────────────────────
   CONTACT FORM AJAX HANDLER
────────────────────────────────────────────── */
function erawat_handle_contact_form() {
    check_ajax_referer( 'erawat_nonce', 'nonce' );

    $name    = sanitize_text_field( $_POST['name']    ?? '' );
    $email   = sanitize_email(      $_POST['email']   ?? '' );
    $phone   = sanitize_text_field( $_POST['phone']   ?? '' );
    $company = sanitize_text_field( $_POST['company'] ?? '' );
    $message = sanitize_textarea_field( $_POST['message'] ?? '' );

    if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
        wp_send_json_error( [ 'message' => 'Please fill in all required fields.' ] );
    }

    $to      = get_option( 'admin_email' );
    $subject = "New Enquiry from {$name} — Erawat Enterprise";
    $body    = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\nCompany: {$company}\n\nMessage:\n{$message}";
    $headers = [ "Reply-To: {$email}" ];

    $sent = wp_mail( $to, $subject, $body, $headers );

    if ( $sent ) {
        wp_send_json_success( [ 'message' => 'Thank you! We will get back to you within 24 hours.' ] );
    } else {
        wp_send_json_error( [ 'message' => 'Could not send message. Please try again or call us directly.' ] );
    }
}
add_action( 'wp_ajax_erawat_contact',        'erawat_handle_contact_form' );
add_action( 'wp_ajax_nopriv_erawat_contact', 'erawat_handle_contact_form' );

/* ──────────────────────────────────────────────
   HELPER FUNCTIONS
────────────────────────────────────────────── */
function erawat_get_breadcrumbs() {
    if ( is_front_page() ) return;
    echo '<nav class="breadcrumbs" aria-label="Breadcrumb"><div class="container">';
    echo '<a href="' . esc_url( home_url('/') ) . '">Home</a>';
    if ( is_page() ) {
        global $post;
        if ( $post->post_parent ) {
            $parent = get_post( $post->post_parent );
            echo ' <span>/</span> <a href="' . esc_url( get_permalink( $parent ) ) . '">' . esc_html( $parent->post_title ) . '</a>';
        }
        echo ' <span>/</span> <span>' . esc_html( get_the_title() ) . '</span>';
    }
    echo '</div></nav>';
}

function erawat_page_banner( $title = '', $subtitle = '', $bg_class = '' ) {
    $title = $title ?: get_the_title();
    ?>
    <section class="page-banner <?php echo esc_attr( $bg_class ); ?>">
        <div class="banner-overlay"></div>
        <div class="container">
            <div class="banner-content">
                <?php if ( $subtitle ) : ?>
                    <span class="banner-eyebrow"><?php echo esc_html( $subtitle ); ?></span>
                <?php endif; ?>
                <h1 class="banner-title"><?php echo esc_html( $title ); ?></h1>
            </div>
        </div>
    </section>
    <?php
    erawat_get_breadcrumbs();
}
