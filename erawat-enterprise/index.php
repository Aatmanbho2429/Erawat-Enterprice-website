<?php get_header(); ?>

<section class="section">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="posts-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article class="post-card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-card__img">
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
                            </div>
                        <?php endif; ?>
                        <div class="post-card__body">
                            <span class="post-card__date"><?php the_date(); ?></span>
                            <h2 class="post-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="post-card__excerpt"><?php the_excerpt(); ?></div>
                            <a href="<?php the_permalink(); ?>" class="btn btn--outline-primary btn--sm">Read More</a>
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
