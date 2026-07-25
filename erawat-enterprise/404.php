<?php get_header(); ?>

<section class="section section-404">
    <div class="container text-center">
        <div class="error-404">
            <div class="error-404__icon"><i class="fas fa-exclamation-triangle"></i></div>
            <h1 class="error-404__code">404</h1>
            <h2 class="error-404__title">Page Not Found</h2>
            <p class="error-404__desc">
                The page you're looking for doesn't exist or has been moved.
                Let's get you back to safety.
            </p>
            <div class="error-404__actions">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary btn--lg">
                    <i class="fas fa-home"></i> Back to Home
                </a>
                <a href="<?php echo esc_url(home_url('/contact-us')); ?>" class="btn btn--outline-primary btn--lg">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
