<?php get_header(); ?>

<section class="section section--404">
    <div class="container error-404">
        <span class="error-404__code" aria-hidden="true">404</span>
        <h1 class="error-404__title">Page Not Found</h1>
        <p class="error-404__desc">The page you're looking for doesn't exist or has been moved.</p>
        <div class="error-404__actions">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary btn--lg">
                Back to Home <?php echo erawat_btn_circle(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
            </a>
            <a href="<?php echo esc_url( erawat_url( 'contact-us' ) ); ?>" class="btn btn--ghost btn--lg">
                Contact Us <?php echo erawat_btn_circle(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
