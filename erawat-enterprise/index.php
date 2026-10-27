<?php get_header(); ?>

<?php
if ( is_search() ) {
    $index_title = sprintf( /* translators: %s: search query */ __( 'Search results for "%s"', 'erawat-enterprise' ), get_search_query() );
} elseif ( is_archive() ) {
    $index_title = wp_strip_all_tags( get_the_archive_title() );
} else {
    $index_title = __( 'Latest Updates', 'erawat-enterprise' );
}
erawat_page_banner( $index_title, 'Erawat Enterprise' );
?>

<section class="section">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="posts-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article class="post-card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-card__img">
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large' ); ?></a>
                            </div>
                        <?php endif; ?>
                        <div class="post-card__body">
                            <span class="post-card__date"><?php echo esc_html( get_the_date() ); ?></span>
                            <h2 class="post-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="post-card__excerpt"><?php the_excerpt(); ?></div>
                            <a href="<?php the_permalink(); ?>" class="btn btn--primary btn--sm">Read More <?php echo erawat_btn_circle(); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            <div class="pagination">
                <?php the_posts_pagination(); ?>
            </div>
        <?php else : ?>
            <p>No posts found.</p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
