<?php get_header(); ?>

<?php erawat_page_banner(); ?>

<section class="section default-page">
    <div class="container">
        <div class="default-page__content">
            <?php
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
