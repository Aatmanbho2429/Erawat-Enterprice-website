<?php get_header(); ?>
<?php
$products = erawat_products();

$marquee_items = [
    'Solar Pallets', 'Box Pallets', 'Custom Sizes', 'ISPM 15 Heat Treated',
    'Bulk Supply', 'All-India Delivery', 'Worldwide Delivery', 'Gujarat', 'Rajasthan', 'Jaipur',
];

$reasons = [
    [ 'icon' => 'fa-ruler-combined', 'title' => 'Made to your size',    'text' => 'Every pallet is cut to your panel dimensions. No loose fit, no wasted space.' ],
    [ 'icon' => 'fa-tree',           'title' => 'Strong, seasoned wood', 'text' => 'Firm construction that takes forklift handling and long truck journeys.' ],
    [ 'icon' => 'fa-globe',          'title' => 'Export ready',          'text' => 'ISPM 15 heat treatment and IPPC marking for export orders.' ],
    [ 'icon' => 'fa-boxes-stacked',  'title' => 'Bulk supply',           'text' => 'Steady production for repeat orders, delivered anywhere in India or worldwide.' ],
];
?>

<!-- ══════════════ HERO ══════════════ -->
<section class="hero" aria-labelledby="hero-title">
    <div class="hero__bg" data-parallax="0.08" aria-hidden="true"></div>
    <div class="hero__overlay" aria-hidden="true"></div>

    <div class="hero__inner">
        <div class="hero__main">
            <span class="eyebrow eyebrow--light">Solar Pallet &amp; Box Pallet Manufacturer</span>

            <h1 class="hero__title" id="hero-title" data-split>
                Solar Pallets &amp; Box Pallets<span class="sr-only"> — </span>
                <span class="hero__accent accent">Built Around Your Panels</span>
            </h1>
        </div>

        <div class="hero__bottom">
            <div class="hero__content">
                <p class="hero__text">Strong wooden pallets and box pallets, made to the exact size of your solar modules — for manufacturers and EPC companies across India and worldwide, including Gujarat, Rajasthan and Jaipur.</p>

                <div class="hero__actions">
                    <a href="<?php echo esc_url( erawat_url( 'contact-us' ) ); ?>" class="btn btn--white btn--lg" data-magnetic>
                        Get a Quote <?php echo erawat_btn_circle(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                    </a>
                    <a href="<?php echo esc_url( erawat_url( 'product-range' ) ); ?>" class="btn btn--outline-white btn--lg">
                        View Products
                    </a>
                </div>
            </div>

            <div class="hero__visual">
                <?php
                foreach ( [ 'solar-pallet' => 8, 'box-pallet' => 5 ] as $key => $depth ) :
                    $product = $products[ $key ];
                    $image   = erawat_product_image( $product );
                    ?>
                    <a class="hero__card" href="<?php echo esc_url( erawat_url( 'product-range' ) . '#' . $key ); ?>" data-depth="<?php echo (int) $depth; ?>">
                        <span class="hero__card-anim">
                            <span class="hero__card-thumb photo-frame">
                                <img
                                    src="<?php echo esc_url( $image['url'] ); ?>"
                                    alt="<?php echo esc_attr( $product['alt'] ); ?>"
                                    width="<?php echo esc_attr( $image['width'] ); ?>"
                                    height="<?php echo esc_attr( $image['height'] ); ?>"
                                    loading="eager" decoding="async">
                            </span>
                            <span class="hero__card-row">
                                <span class="hero__card-name"><?php echo esc_html( $product['name'] ); ?></span>
                                <span class="hero__card-arrow" aria-hidden="true"><i class="fas fa-arrow-right"></i></span>
                            </span>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <ul class="hero__chips">
            <li><i class="fas fa-ruler-combined" aria-hidden="true"></i> Made to Your Size</li>
            <li><i class="fas fa-temperature-high" aria-hidden="true"></i> ISPM 15 Heat Treatment</li>
            <li><i class="fas fa-truck-ramp-box" aria-hidden="true"></i> Bulk Orders, On Time</li>
        </ul>
    </div>
</section>

<!-- ══════════════ MARQUEE ══════════════ -->
<section class="marquee" aria-label="Products and areas we serve">
    <div class="marquee__track">
        <?php for ( $loop = 0; $loop < 4; $loop++ ) : ?>
            <ul class="marquee__list"<?php echo $loop > 0 ? ' aria-hidden="true"' : ''; ?>>
                <?php foreach ( $marquee_items as $item ) : ?>
                    <li><?php echo esc_html( $item ); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endfor; ?>
    </div>
</section>

<!-- ══════════════ WHAT WE MAKE ══════════════ -->
<section class="section section--panel" aria-labelledby="make-title">
    <div class="container">
        <div class="make-head">
            <div class="section-head">
                <span class="eyebrow" data-aos="fade-up">What We Make</span>
                <h2 id="make-title" data-split>Solar Pallets, Box Pallets &amp; Custom Builds</h2>
            </div>
            <p class="statement" data-scroll-fill>Four products, one job — getting your solar panels to site without damage.</p>
        </div>

        <div class="product-grid" data-aos-stagger>
            <?php
            $n = 0;
            foreach ( $products as $slug => $product ) {
                get_template_part( 'template-parts/product-card', null, [
                    'product' => $product,
                    'index'   => ++$n,
                ] );
            }
            ?>
        </div>
    </div>
</section>

<!-- ══════════════ WHY ERAWAT + HOW IT WORKS ══════════════ -->
<section class="section section--dark" aria-labelledby="why-title">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow" data-aos="fade-up">Why Erawat</span>
            <h2 id="why-title" data-split>Built Strong. Sized Right. <span class="accent">Delivered on Time.</span></h2>
        </div>

        <div class="why-grid">
            <div class="reasons" data-aos-stagger>
                <?php foreach ( $reasons as $reason ) : ?>
                    <article class="reason" data-aos="fade-up">
                        <span class="reason__icon" aria-hidden="true"><i class="fas <?php echo esc_attr( $reason['icon'] ); ?>"></i></span>
                        <h3 class="reason__title"><?php echo esc_html( $reason['title'] ); ?></h3>
                        <p><?php echo esc_html( $reason['text'] ); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="how-card">
                <h3 class="how-card__title">How It Works</h3>
                <?php get_template_part( 'template-parts/process-steps', null, [ 'layout' => 'vertical' ] ); ?>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════ CUSTOMER REVIEWS ══════════════ -->
<?php get_template_part( 'template-parts/reviews' ); ?>

<?php get_footer(); ?>
