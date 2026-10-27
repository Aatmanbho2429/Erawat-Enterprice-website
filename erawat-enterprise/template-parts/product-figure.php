<?php
/**
 * Product photo in a rounded frame with a glass label (title + one-liner).
 *
 * Args: product (array from erawat_products()), aos (reveal effect, default 'clip-up').
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$product = $args['product'] ?? null;
$aos     = $args['aos'] ?? 'clip-up';

if ( empty( $product ) ) {
    return;
}

$image = erawat_product_image( $product );
?>
<figure class="product-photo product-photo--<?php echo esc_attr( $product['slug'] ); ?> photo-frame" data-aos="<?php echo esc_attr( $aos ); ?>">
    <img
        src="<?php echo esc_url( $image['url'] ); ?>"
        alt="<?php echo esc_attr( $product['alt'] ); ?>"
        width="<?php echo esc_attr( $image['width'] ); ?>"
        height="<?php echo esc_attr( $image['height'] ); ?>"
        <?php if ( $image['style'] ) : ?>style="<?php echo esc_attr( $image['style'] ); ?>"<?php endif; ?>
        loading="lazy" decoding="async">
    <figcaption class="photo-caption">
        <span class="photo-caption__title"><?php echo esc_html( $product['name'] ); ?></span>
        <span class="photo-caption__text"><?php echo esc_html( $product['page_caption'] ); ?></span>
    </figcaption>
</figure>
