<?php
/**
 * Numbered process steps as a hairline spec list; the hairlines draw in on reveal.
 * Styled for dark panels (.section--dark).
 *
 * Args: steps (array of [ 'title' => , 'text' => ]; defaults to the four Home steps),
 *       layout ('vertical' | 'horizontal').
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$steps = $args['steps'] ?? [
    [ 'title' => 'Share your size', 'text' => 'Panel dimensions, panels per pallet and quantity.' ],
    [ 'title' => 'Get a quote',     'text' => 'We confirm the design and price. Sample pallet on request.' ],
    [ 'title' => 'We build',        'text' => 'Cut, assembled and checked — heat-treated if you export.' ],
    [ 'title' => 'We deliver',      'text' => 'To your factory or project site across Gujarat and Rajasthan.' ],
];
$layout = ( $args['layout'] ?? 'vertical' ) === 'horizontal' ? 'horizontal' : 'vertical';
?>
<ol class="process-steps process-steps--<?php echo esc_attr( $layout ); ?>" data-aos="fade-up">
    <?php foreach ( $steps as $i => $step ) : ?>
        <li class="process-steps__item" style="--i:<?php echo (int) $i; ?>">
            <span class="process-steps__num index-num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
            <div class="process-steps__body">
                <h3 class="process-steps__title"><?php echo esc_html( $step['title'] ); ?></h3>
                <p><?php echo esc_html( $step['text'] ); ?></p>
            </div>
        </li>
    <?php endforeach; ?>
</ol>
