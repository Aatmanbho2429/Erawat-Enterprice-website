<?php
/**
 * FAQ accordion: a hairline-divided list. JS toggles .is-open on the .faq-question and its next sibling (the answer).
 *
 * Args: items (array of [ 'q' => , 'a' => ]), prefix (unique id prefix, default 'faq').
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$items  = $args['items'] ?? [];
$prefix = sanitize_html_class( $args['prefix'] ?? 'faq' );

if ( empty( $items ) ) {
    return;
}
?>
<div class="faq-list" data-aos="fade-up">
    <?php foreach ( $items as $i => $item ) : ?>
        <div class="faq-item">
            <button class="faq-question" type="button" id="<?php echo esc_attr( "{$prefix}-q-{$i}" ); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( "{$prefix}-a-{$i}" ); ?>">
                <span class="faq-question__num index-num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
                <span class="faq-question__text"><?php echo esc_html( $item['q'] ); ?></span>
                <span class="faq-question__icon" aria-hidden="true"><i class="fas fa-plus"></i></span>
            </button>
            <div class="faq-answer" id="<?php echo esc_attr( "{$prefix}-a-{$i}" ); ?>" role="region" aria-labelledby="<?php echo esc_attr( "{$prefix}-q-{$i}" ); ?>">
                <div class="faq-answer__inner">
                    <p><?php echo esc_html( $item['a'] ); ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
