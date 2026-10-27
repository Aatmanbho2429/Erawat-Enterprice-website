<?php
/**
 * Customer reviews section (Home and About).
 *
 * Renders erawat_reviews(). While there are none, it falls back to erawat_demo_reviews() where
 * erawat_show_demo_reviews() allows (local preview / logged-in editors); public visitors see nothing.
 *
 * Desktop: an auto-scrolling row (the list is rendered twice for a seamless loop; the copy is aria-hidden).
 * Phones and reduced motion: a swipeable row of the first list only.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$reviews = erawat_reviews();

// if ( empty( $reviews ) ) {
//     if ( ! erawat_show_demo_reviews() ) {
//         return;
//     }
//     $reviews = erawat_demo_reviews();
// }
?>
<section class="section reviews" aria-labelledby="reviews-title">
    <div class="container">
        <div class="section-head section-head--center" data-aos="fade-up">
            <span class="eyebrow">Customer Reviews</span>
            <h2 id="reviews-title">What Our Customers Say</h2>
        </div>
    </div>

    <div class="reviews-marquee" data-aos="fade-up">
        <div class="reviews-track">
            <?php foreach ( [ false, true ] as $is_copy ) : ?>
                <div class="reviews-list"<?php echo $is_copy ? ' aria-hidden="true"' : ''; ?>>
                    <?php foreach ( $reviews as $review ) :
                        $rating = max( 1, min( 5, (int) ( $review['rating'] ?? 5 ) ) );
                        $meta   = implode( ' · ', array_filter( [
                            trim( implode( ', ', array_filter( [ $review['role'] ?? '', $review['company'] ?? '' ] ) ) ),
                            $review['location'] ?? '',
                        ] ) );
                        ?>
                        <figure class="review-card">
                            <div class="review-card__stars" role="img" aria-label="<?php echo esc_attr( sprintf( '%d out of 5 stars', $rating ) ); ?>">
                                <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                    <i class="<?php echo $i <= $rating ? 'fas' : 'far'; ?> fa-star" aria-hidden="true"></i>
                                <?php endfor; ?>
                            </div>
                            <blockquote class="review-card__text">
                                <p><?php echo esc_html( $review['text'] ); ?></p>
                            </blockquote>
                            <figcaption class="review-card__author">
                                <span class="review-card__avatar" aria-hidden="true"><?php echo esc_html( erawat_initials( $review['name'] ) ); ?></span>
                                <span>
                                    <span class="review-card__name"><?php echo esc_html( $review['name'] ); ?></span>
                                    <?php if ( $meta ) : ?>
                                        <span class="review-card__meta"><?php echo esc_html( $meta ); ?></span>
                                    <?php endif; ?>
                                </span>
                            </figcaption>
                        </figure>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
