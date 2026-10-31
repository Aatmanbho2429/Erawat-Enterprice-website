<?php
/**
 * Erawat Enterprise Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'ERAWAT_VERSION', '2.0.0' );
define( 'ERAWAT_DIR', get_template_directory() );
define( 'ERAWAT_URI', get_template_directory_uri() );

/* Contact details — single source for header, footer, contact page and schema */
define( 'ERAWAT_PHONE',     '+91 99310 60355' );
define( 'ERAWAT_PHONE_TEL', '+919931060355' );
define( 'ERAWAT_WHATSAPP',  'https://wa.me/919931060355' );
define( 'ERAWAT_EMAIL',     'Erawat005@gmail.com' );
define( 'ERAWAT_LINKEDIN',  'https://www.linkedin.com/in/erawat-enterprise-145601431' );

/* Business address (from the client's business card) — footer, contact page and schema */
define( 'ERAWAT_ADDRESS_STREET',   'Khara No. 288/2, Bawdi, Sarna Doongar, Jhotwara' );
define( 'ERAWAT_ADDRESS_CITY',     'Jaipur' );
define( 'ERAWAT_ADDRESS_REGION',   'Rajasthan' );
define( 'ERAWAT_ADDRESS_POSTCODE', '302012' );

/* Auto-create pages on theme activation */
require_once ERAWAT_DIR . '/inc/setup-pages.php';

/* Product data and SEO */
require_once ERAWAT_DIR . '/inc/products.php';
require_once ERAWAT_DIR . '/inc/seo.php';
require_once ERAWAT_DIR . '/inc/reviews.php';

/* Contact-form enquiries, saved in wp-admin → Enquiries */
require_once ERAWAT_DIR . '/inc/enquiries.php';

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
    // Google Fonts: Archivo (expanded headings) + Manrope (body) + Instrument Serif (accents)
    wp_enqueue_style(
        'erawat-google-fonts',
        'https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@100..125,500..800&family=Manrope:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap',
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
        filemtime( ERAWAT_DIR . '/assets/css/erawat-style.css' )
    );

    // Theme style.css (base reset)
    wp_enqueue_style( 'erawat-style', get_stylesheet_uri(), [ 'erawat-main' ], filemtime( ERAWAT_DIR . '/style.css' ) );

    // Main JS
    wp_enqueue_script(
        'erawat-script',
        ERAWAT_URI . '/assets/js/erawat-script.js',
        [],
        filemtime( ERAWAT_DIR . '/assets/js/erawat-script.js' ),
        true
    );

    wp_localize_script( 'erawat-script', 'erawatData', [
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'erawat_nonce' ),
    ] );
}
add_action( 'wp_enqueue_scripts', 'erawat_enqueue_assets' );

/**
 * Preload the Home hero photo — it is the LCP image and is a CSS background, so the browser
 * would not find it early on its own.
 */
function erawat_preload_hero() {
    if ( is_front_page() ) {
        printf(
            '<link rel="preload" as="image" href="%s" fetchpriority="high">' . "\n",
            esc_url( ERAWAT_URI . '/assets/images/hero.jpg' )
        );
    }
}
add_action( 'wp_head', 'erawat_preload_hero', 3 );

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

    $name     = sanitize_text_field( wp_unslash( $_POST['name']     ?? '' ) );
    $phone    = sanitize_text_field( wp_unslash( $_POST['phone']    ?? '' ) );
    $email    = sanitize_email( wp_unslash( $_POST['email']         ?? '' ) );
    $company  = sanitize_text_field( wp_unslash( $_POST['company']  ?? '' ) );
    $quantity = sanitize_text_field( wp_unslash( $_POST['quantity'] ?? '' ) );
    $location = sanitize_text_field( wp_unslash( $_POST['location'] ?? '' ) );
    $message  = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
    $product  = sanitize_key( wp_unslash( $_POST['product'] ?? '' ) );

    if ( '' === $name || '' === $phone || '' === $message ) {
        wp_send_json_error( [ 'message' => 'Please fill in your name, phone number and requirements.' ] );
    }

    // Email is optional, but if it is given it must be valid.
    $raw_email = sanitize_text_field( wp_unslash( $_POST['email'] ?? '' ) );
    if ( '' !== $raw_email && ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => 'Please enter a valid email address, or leave it blank.' ] );
    }

    // Product slug → readable name.
    $products     = erawat_products();
    $product_name = 'Not specified';
    if ( isset( $products[ $product ] ) ) {
        $product_name = $products[ $product ]['name'];
    } elseif ( 'not-sure' === $product ) {
        $product_name = 'Not sure yet';
    }

    // Save it first (wp-admin → Enquiries), so the enquiry is kept even if the email fails.
    $saved_id = erawat_save_enquiry( [
        'name'     => $name,
        'phone'    => $phone,
        'email'    => $email,
        'company'  => $company,
        'product'  => $product_name,
        'quantity' => $quantity,
        'location' => $location,
    ], $message );

    $to      = get_option( 'admin_email' );
    $subject = "New enquiry from {$name} — {$product_name}";
    $body    = "Name: {$name}\n"
             . "Phone: {$phone}\n"
             . 'Email: ' . ( $email ? $email : '—' ) . "\n"
             . 'Company: ' . ( $company ? $company : '—' ) . "\n"
             . "Product: {$product_name}\n"
             . 'Quantity: ' . ( $quantity ? $quantity : '—' ) . "\n"
             . 'Delivery location: ' . ( $location ? $location : '—' ) . "\n\n"
             . "Requirements:\n{$message}";
    if ( $saved_id ) {
        $body .= "\n\nSaved in WordPress: " . admin_url( 'post.php?post=' . $saved_id . '&action=edit' );
    }

    $headers = [];
    if ( $email && is_email( $email ) ) {
        $headers[] = "Reply-To: {$name} <{$email}>";
    }

    $sent = wp_mail( $to, $subject, $body, $headers );
    erawat_set_enquiry_mail_status( $saved_id, $sent );

    // A saved enquiry counts as received even if the email failed — it is waiting in wp-admin.
    if ( $sent || $saved_id ) {
        wp_send_json_success( [ 'message' => 'Thank you! We have received your enquiry and will reply shortly.' ] );
    } else {
        wp_send_json_error( [ 'message' => 'Could not send your enquiry. Please call or WhatsApp us on ' . ERAWAT_PHONE . '.' ] );
    }
}
add_action( 'wp_ajax_erawat_contact',        'erawat_handle_contact_form' );
add_action( 'wp_ajax_nopriv_erawat_contact', 'erawat_handle_contact_form' );

/* ──────────────────────────────────────────────
   HELPER FUNCTIONS
────────────────────────────────────────────── */

/**
 * URL of one of the six pages by slug, with a trailing slash.
 */
function erawat_url( $slug = '' ) {
    return home_url( '/' . ( $slug ? trailingslashit( $slug ) : '' ) );
}

/**
 * The business address as two escaped lines: street, then city / state / PIN / country.
 */
function erawat_address_html() {
    return esc_html( ERAWAT_ADDRESS_STREET . ',' ) . '<br>'
         . esc_html( ERAWAT_ADDRESS_CITY . ', ' . ERAWAT_ADDRESS_REGION . ' ' . ERAWAT_ADDRESS_POSTCODE . ', India' );
}

/**
 * Breadcrumb trail (printed inside the page banner).
 */
function erawat_get_breadcrumbs( $current = '' ) {
    if ( is_front_page() ) return;

    $current = $current ?: get_the_title();

    echo '<nav class="breadcrumbs" aria-label="Breadcrumb"><ol class="breadcrumbs__list">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'erawat-enterprise' ) . '</a></li>';
    if ( is_page() ) {
        $ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
        foreach ( $ancestors as $ancestor_id ) {
            echo '<li><a href="' . esc_url( get_permalink( $ancestor_id ) ) . '">' . esc_html( get_the_title( $ancestor_id ) ) . '</a></li>';
        }
    }
    echo '<li aria-current="page">' . esc_html( $current ) . '</li>';
    echo '</ol></nav>';
}

/**
 * The round arrow that ends a button (two arrows: one slides out, the other slides in on hover).
 */
function erawat_btn_circle() {
    return '<span class="btn__circle" aria-hidden="true"><i class="fas fa-arrow-right"></i><i class="fas fa-arrow-right"></i></span>';
}

/**
 * Page banner: a rounded photo panel with the breadcrumb, eyebrow ($subtitle) and the page's single <h1> ($title).
 */
function erawat_page_banner( $title = '', $subtitle = '', $bg_class = 'banner--default' ) {
    $title = $title ?: get_the_title();
    ?>
    <section class="page-banner <?php echo esc_attr( $bg_class ); ?>">
        <div class="page-banner__bg" data-parallax="0.08" aria-hidden="true"></div>
        <div class="page-banner__overlay" aria-hidden="true"></div>
        <div class="container page-banner__inner">
            <?php erawat_get_breadcrumbs( $title ); ?>
            <?php if ( $subtitle ) : ?>
                <span class="eyebrow eyebrow--light page-banner__eyebrow"><?php echo esc_html( $subtitle ); ?></span>
            <?php endif; ?>
            <h1 class="page-banner__title" data-split><?php echo esc_html( $title ); ?></h1>
        </div>
    </section>
    <?php
}
