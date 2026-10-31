<?php get_header(); ?>

<?php erawat_page_banner( 'Solar Pallets & Box Pallets', 'Our Products', 'banner--products' ); ?>

<?php
$products = erawat_products();

/* Product-page copy that is specific to this page (names, images and captions come from erawat_products()). */
$details = [
    'solar-pallet' => [
        'text'     => 'A long, flat wooden pallet built to carry a full stack of solar modules. Wide top boards spread the load across the panel frames, and strong blocks give clean forklift and pallet-jack entry.',
        'features' => [
            'Sized to your module length and width',
            'Wide, evenly spaced top boards',
            'Forklift and pallet-jack entry',
            'ISPM 15 heat treatment for export (optional)',
        ],
        'button'   => 'Get a Solar Pallet Quote',
    ],
    'box-pallet' => [
        'text'     => 'A wooden box built directly on a pallet base. Panels stand upright inside, separated by foam dividers, with a hinged lid and braced side walls that protect the glass from knocks, dust and weather.',
        'features' => [
            'Braced side walls with steel corner brackets',
            'Hinged or removable lid',
            'Foam dividers between panels',
            'Pallet base for forklift handling',
        ],
        'button'   => 'Get a Box Pallet Quote',
    ],
    'custom-solar-pallet' => [
        'text'    => 'Need a different length, more panels per stack or four-way entry? We build solar pallets to your drawing or your sample — once, or for every shipment.',
        'options' => [ 'Custom length & width', 'Panels per stack', '2-way / 4-way entry', 'Top frame & corner guards', 'Strap slots', 'Your logo stencilled' ],
        'button'  => 'Discuss a Custom Solar Pallet',
    ],
    'custom-box-pallet' => [
        'text'    => 'Shipping odd-sized panels, inverters or a mixed load? We size the box, partitions and lid to your product, and add foam lining or lifting points where you need them.',
        'options' => [ 'Any box size', 'Panel partitions', 'Foam / EPE lining', 'Hinged or bolt-on lid', 'Lifting points', 'Export marking' ],
        'button'  => 'Discuss a Custom Box Pallet',
    ],
];

$faqs = [
    [ 'q' => 'What is a solar pallet?', 'a' => 'A solar pallet is a long wooden pallet made to carry stacked solar modules. Its size matches the panel, so the stack does not overhang or shift during transport.' ],
    [ 'q' => 'What is the difference between a solar pallet and a box pallet?', 'a' => 'A solar pallet is an open, flat base. A box pallet adds walls and a lid that fully enclose the panels — better for long journeys, rough roads or export.' ],
    [ 'q' => 'Do you make pallets in custom sizes?', 'a' => "Yes. Most of our pallets are made to size. Share your panel dimensions and quantity and we'll quote for your exact build." ],
    [ 'q' => 'Do you deliver to Rajasthan and Jaipur?', 'a' => 'Yes. We deliver solar pallets and box pallets anywhere in India and worldwide — including Gujarat, Rajasthan and Jaipur.' ],
];
?>

<!-- ══════════════ INTRO + JUMP LINKS ══════════════ -->
<section class="section section--tight product-intro">
    <div class="container product-intro__inner" data-aos="fade-up">
        <p class="statement">Every product is made to order and sized to your solar modules.</p>
        <ul class="pill-bar" aria-label="Jump to a product">
            <?php foreach ( $products as $slug => $product ) : ?>
                <li><a href="#<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $product['name'] ); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<!-- ══════════════ SOLAR PALLET + BOX PALLET ══════════════ -->
<section class="section section--flush-top">
    <div class="container product-rows">
        <?php foreach ( [ 'solar-pallet', 'box-pallet' ] as $n => $slug ) :
            $product = $products[ $slug ];
            $detail  = $details[ $slug ];
            ?>
            <article class="product-row<?php echo 1 === $n ? ' product-row--reverse' : ''; ?>" id="<?php echo esc_attr( $slug ); ?>">
                <?php get_template_part( 'template-parts/product-figure', null, [ 'product' => $product, 'aos' => 'clip-up' ] ); ?>
                <div class="product-row__body" data-aos="<?php echo 1 === $n ? 'fade-right' : 'fade-left'; ?>">
                    <span class="index-num index-num--lg" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $n + 1 ) ); ?></span>
                    <h2><?php echo esc_html( $product['name'] ); ?></h2>
                    <p><?php echo esc_html( $detail['text'] ); ?></p>
                    <ul class="spec-list">
                        <?php foreach ( $detail['features'] as $feature ) : ?>
                            <li><span class="spec-list__mark" aria-hidden="true"><i class="fas fa-check"></i></span><span><?php echo esc_html( $feature ); ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                    <a class="btn btn--primary" href="<?php echo esc_url( add_query_arg( 'product', $slug, erawat_url( 'contact-us' ) ) ); ?>">
                        <?php echo esc_html( $detail['button'] ); ?> <?php echo erawat_btn_circle(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<!-- ══════════════ CUSTOMISED PRODUCTS ══════════════ -->
<section class="section section--panel">
    <div class="container">
        <div class="custom-grid" data-aos-stagger>
            <?php foreach ( [ 'custom-solar-pallet', 'custom-box-pallet' ] as $n => $slug ) :
                $product = $products[ $slug ];
                $detail  = $details[ $slug ];
                ?>
                <article class="custom-card" id="<?php echo esc_attr( $slug ); ?>" data-aos="fade-up">
                    <?php get_template_part( 'template-parts/product-figure', null, [ 'product' => $product, 'aos' => 'clip-up' ] ); ?>
                    <div class="custom-card__body">
                        <span class="index-num index-num--lg" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $n + 3 ) ); ?></span>
                        <h2><?php echo esc_html( $product['name'] ); ?></h2>
                        <p><?php echo esc_html( $detail['text'] ); ?></p>
                        <ul class="chip-list chip-list--sm" aria-label="<?php echo esc_attr( $product['name'] . ' options' ); ?>">
                            <?php foreach ( $detail['options'] as $option ) : ?>
                                <li class="chip"><?php echo esc_html( $option ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <a class="btn btn--primary" href="<?php echo esc_url( add_query_arg( 'product', $slug, erawat_url( 'contact-us' ) ) ); ?>">
                            <?php echo esc_html( $detail['button'] ); ?> <?php echo erawat_btn_circle(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ══════════════ FAQ ══════════════ -->
<section class="section" aria-labelledby="faq-title">
    <div class="container container--narrow">
        <div class="section-head section-head--center">
            <h2 id="faq-title" data-split>Questions</h2>
        </div>
        <?php get_template_part( 'template-parts/faq-list', null, [ 'items' => $faqs, 'prefix' => 'pfaq' ] ); ?>
    </div>
</section>

<?php get_footer(); ?>
