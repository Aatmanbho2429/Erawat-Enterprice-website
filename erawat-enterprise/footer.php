</main>

<!-- ══════════════ CTA BAND (not on the Contact page — the form is already there) ══════════════ -->
<?php if ( ! is_page( 'contact-us' ) ) : ?>
<section class="cta-band" aria-labelledby="cta-band-title">
    <div class="container">
        <div class="cta-band__inner">
            <div class="cta-band__text">
                <h2 id="cta-band-title" data-split>Need solar pallets or box pallets?</h2>
                <p data-aos="fade-up" data-aos-delay="200">Send your panel size and quantity, and we'll send you a quote.</p>
            </div>
            <div class="cta-band__actions" data-aos="fade-up" data-aos-delay="200">
                <a href="<?php echo esc_url( erawat_url( 'contact-us' ) ); ?>" class="btn btn--white btn--lg" data-magnetic>
                    Get a Quote <?php echo erawat_btn_circle(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                </a>
                <a href="tel:<?php echo esc_attr( ERAWAT_PHONE_TEL ); ?>" class="btn btn--outline-white btn--lg">
                    <i class="fas fa-phone-alt" aria-hidden="true"></i> Call Now
                </a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ══════════════ MAIN FOOTER ══════════════ -->
<footer class="site-footer">
    <div class="container">
        <div class="site-footer__grid">

            <!-- Brand -->
            <div class="footer-col footer-col--brand">
                <a class="footer-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                    <img src="<?php echo esc_url( erawat_image_url( 'logo-erawat-white.png' ) ); ?>" alt="Erawat Enterprise" width="780" height="252" loading="lazy" decoding="async">
                </a>
                <p class="footer-tagline">Erawat Enterprise makes solar pallets, box pallets and custom wooden pallets for solar module makers and EPC companies across India and worldwide, including Gujarat and Rajasthan.</p>
                <ul class="footer-badges">
                    <li><i class="fas fa-ruler-combined" aria-hidden="true"></i> Made to Size</li>
                    <li><i class="fas fa-temperature-high" aria-hidden="true"></i> ISPM 15 Heat Treatment</li>
                    <li><i class="fas fa-truck-ramp-box" aria-hidden="true"></i> Bulk Orders</li>
                </ul>
                <div class="footer-social">
                    <a href="<?php echo esc_url( ERAWAT_LINKEDIN ); ?>" aria-label="Erawat Enterprise on LinkedIn" class="social-link" target="_blank" rel="noopener"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
                    <a href="<?php echo esc_url( ERAWAT_WHATSAPP ); ?>" aria-label="Chat with Erawat Enterprise on WhatsApp" class="social-link" target="_blank" rel="noopener"><i class="fab fa-whatsapp" aria-hidden="true"></i></a>
                </div>
            </div>

            <!-- Products -->
            <div class="footer-col">
                <h3 class="footer-col__title">Products</h3>
                <ul class="footer-links">
                    <?php foreach ( erawat_products() as $slug => $product ) : ?>
                        <li><a href="<?php echo esc_url( erawat_url( 'product-range' ) . '#' . $slug ); ?>"><?php echo esc_html( $product['name'] ); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Company -->
            <div class="footer-col">
                <h3 class="footer-col__title">Company</h3>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url( erawat_url( 'about-us' ) ); ?>">About Us</a></li>
                    <li><a href="<?php echo esc_url( erawat_url( 'services' ) ); ?>">Services</a></li>
                    <li><a href="<?php echo esc_url( erawat_url( 'ispm-15-packing' ) ); ?>">ISPM 15 Packing</a></li>
                    <li><a href="<?php echo esc_url( erawat_url( 'contact-us' ) ); ?>">Contact Us</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="footer-col">
                <h3 class="footer-col__title">Contact</h3>
                <ul class="footer-contact-list">
                    <li>
                        <i class="fas fa-phone-alt" aria-hidden="true"></i>
                        <a href="tel:<?php echo esc_attr( ERAWAT_PHONE_TEL ); ?>"><?php echo esc_html( ERAWAT_PHONE ); ?></a>
                    </li>
                    <li>
                        <i class="fab fa-whatsapp" aria-hidden="true"></i>
                        <a href="<?php echo esc_url( ERAWAT_WHATSAPP ); ?>" target="_blank" rel="noopener">Chat on WhatsApp</a>
                    </li>
                    <li>
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <a href="mailto:<?php echo esc_attr( ERAWAT_EMAIL ); ?>"><?php echo esc_html( ERAWAT_EMAIL ); ?></a>
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                        <address class="footer-address"><?php echo erawat_address_html(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper ?></address>
                    </li>
                </ul>
            </div>
        </div>

        <p class="footer-areas">Solar pallet &amp; box pallet supplier across India and worldwide, including <strong>Gujarat</strong> · <strong>Rajasthan</strong> · <strong>Jaipur</strong></p>
    </div>

    <!-- Decorative wordmark -->
    <div class="footer-wordmark" aria-hidden="true">Erawat</div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container footer-bottom__inner">
            <p class="footer-bottom__copy">
                &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Erawat Enterprise. All rights reserved.
            </p>
        </div>
    </div>
</footer>

<!-- Floating actions -->
<a class="whatsapp-float" href="<?php echo esc_url( ERAWAT_WHATSAPP ); ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
    <i class="fab fa-whatsapp" aria-hidden="true"></i>
    <span class="whatsapp-float__label">Chat on WhatsApp</span>
</a>
<button class="back-to-top" id="back-to-top" type="button" aria-label="Back to top">
    <svg class="back-to-top__ring" viewBox="0 0 44 44" aria-hidden="true" focusable="false">
        <circle class="back-to-top__track" cx="22" cy="22" r="20.5"/>
        <circle class="back-to-top__bar" cx="22" cy="22" r="20.5" pathLength="100"/>
    </svg>
    <i class="fas fa-chevron-up" aria-hidden="true"></i>
</button>

<?php wp_footer(); ?>
</body>
</html>
