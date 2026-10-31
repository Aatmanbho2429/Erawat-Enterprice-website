<?php get_header(); ?>

<?php erawat_page_banner( 'Our Services', 'From Design to Delivery', 'banner--services' ); ?>

<?php
$services = [
    [
        'image'   => 'service-design.jpg',
        'pos'     => '50% 50%',
        'alt'     => 'Design drawing of a 2416 × 1110 mm wooden solar pallet',
        'icon'    => 'fa-pencil-ruler',
        'title'   => 'Design & Sizing',
        'text'    => 'We start from your panel dimensions, weight and stacking needs, and design a pallet that fits exactly.',
        'bullets' => [ 'Panel-size based design', 'Drawing for your approval', 'Sample pallet on request' ],
    ],
    [
        'image'   => 'service-bulk.jpg',
        'pos'     => '50% 50%',
        'alt'     => 'Stacks of finished long wooden solar pallets ready for dispatch',
        'icon'    => 'fa-industry',
        'title'   => 'Bulk Manufacturing',
        'text'    => 'Steady, repeatable production of solar pallets and box pallets, for one order or monthly supply.',
        'bullets' => [ 'Same spec every order', 'Checked before dispatch', 'Scheduled supply' ],
    ],
    [
        'image'   => 'service-heattreatment.jpg',
        'pos'     => '50% 40%',
        'alt'     => 'Operator at the control panel of a heat treatment chamber for wooden pallets',
        'icon'    => 'fa-temperature-high',
        'title'   => 'ISPM 15 Heat Treatment',
        'text'    => 'Heat treatment and IPPC marking for pallets and boxes that travel overseas.',
        'bullets' => [ '56 °C core for 30 minutes', 'IPPC mark on every unit', 'Treatment record provided' ],
    ],
    [
        'image'   => 'service-delivery.jpg',
        'pos'     => '50% 62%',
        'alt'     => 'Solar module pallets delivered to a solar power plant site',
        'icon'    => 'fa-truck-fast',
        'title'   => 'Delivery',
        'text'    => 'Dispatch to your factory, warehouse or project site anywhere in India or worldwide, including Gujarat, Rajasthan and Jaipur.',
        'bullets' => [ 'Factory & site delivery', 'Full truck loads', 'Planned to your schedule' ],
    ],
];
?>

<!-- ══════════════ SERVICES ══════════════ -->
<section class="section section--tight-bottom">
    <div class="container">
        <p class="statement statement--center services-lead" data-aos="fade-up">Everything it takes to get solar pallets and box pallets from drawing to dispatch — handled by one team.</p>

        <div class="services-grid" data-aos-stagger>
            <?php foreach ( $services as $i => $service ) : ?>
                <article class="service-card" data-aos="fade-up">
                    <div class="service-card__media photo-frame" data-aos="clip-up">
                        <?php $size = @getimagesize( ERAWAT_DIR . '/assets/images/' . $service['image'] ); // phpcs:ignore WordPress.PHP.NoSilencedErrors ?>
                        <img src="<?php echo esc_url( erawat_image_url( $service['image'] ) ); ?>"
                             alt="<?php echo esc_attr( $service['alt'] ); ?>"
                             width="<?php echo esc_attr( $size ? $size[0] : 1200 ); ?>" height="<?php echo esc_attr( $size ? $size[1] : 800 ); ?>"
                             style="object-position: <?php echo esc_attr( $service['pos'] ); ?>;"
                             loading="lazy" decoding="async">
                    </div>
                    <div class="service-card__body">
                        <div class="service-card__top">
                            <span class="index-num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
                            <span class="service-card__icon" aria-hidden="true"><i class="fas <?php echo esc_attr( $service['icon'] ); ?>"></i></span>
                        </div>
                        <h2 class="service-card__title"><?php echo esc_html( $service['title'] ); ?></h2>
                        <p><?php echo esc_html( $service['text'] ); ?></p>
                        <ul class="spec-list spec-list--sm">
                            <?php foreach ( $service['bullets'] as $bullet ) : ?>
                                <li><span class="spec-list__mark" aria-hidden="true"><i class="fas fa-check"></i></span><span><?php echo esc_html( $bullet ); ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ══════════════ HOW IT WORKS ══════════════ -->
<section class="section section--dark" aria-labelledby="how-title">
    <div class="container">
        <div class="section-head section-head--center">
            <h2 id="how-title" data-split>How It Works</h2>
        </div>
        <?php get_template_part( 'template-parts/process-steps', null, [ 'layout' => 'horizontal' ] ); ?>
    </div>
</section>

<?php get_footer(); ?>
