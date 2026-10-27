<?php
/**
 * Product card (Home) — photo, index number, hairline, name, caption and "View details".
 * The whole card is clickable through the cover link.
 *
 * Args: product (array from erawat_products()), index (1-based position, shown as 01–04).
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$product = $args['product'] ?? null;
$index   = (int) ( $args['index'] ?? 1 );

if ( empty( $product ) ) {
    return;
}

$image = erawat_product_image( $product );
$href  = erawat_url( 'product-range' ) . '#' . $product['slug'];
?>
<article class="product-card product-card--<?php echo esc_attr( $product['slug'] ); ?>" data-aos="fade-up">
    <div class="product-card__media photo-frame" data-aos="clip-up">
        <img
            src="<?php echo esc_url( $image['url'] ); ?>"
            alt="<?php echo esc_attr( $product['alt'] ); ?>"
            width="<?php echo esc_attr( $image['width'] ); ?>"
            height="<?php echo esc_attr( $image['height'] ); ?>"
            <?php if ( $image['style'] ) : ?>style="<?php echo esc_attr( $image['style'] ); ?>"<?php endif; ?>
            loading="lazy" decoding="async">
    </div>
    <a class="product-card__cover" href="<?php echo esc_url( $href ); ?>" tabindex="-1" aria-hidden="true"></a>
    <div class="product-card__body">
        <div class="product-card__index"><span class="index-num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $index ) ); ?></span></div>
        <h3 class="product-card__title"><?php echo esc_html( $product['name'] ); ?></h3>
        <p class="product-card__text"><?php echo esc_html( $product['caption'] ); ?></p>
        <a class="product-card__link" href="<?php echo esc_url( $href ); ?>">
            View details<span class="sr-only"> — <?php echo esc_html( $product['name'] ); ?></span>
            <i class="fas fa-arrow-right" aria-hidden="true"></i>
        </a>
    </div>
</article>
