<?php get_header(); ?>

<?php erawat_page_banner( 'Contact Us', 'Get a Quote', 'banner--contact' ); ?>

<?php
$products = erawat_products();

// Pre-select the product when arriving from a product page (?product=box-pallet).
$selected_product = isset( $_GET['product'] ) ? sanitize_key( wp_unslash( $_GET['product'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
?>

<section class="section section--contact" aria-labelledby="quote-title">
    <div class="container contact-grid">

        <!-- Enquiry form -->
        <div class="form-card" data-aos="fade-right">
            <h2 id="quote-title" data-split>Get a Quote</h2>
            <p class="form-card__intro">Tell us your panel size and quantity. We'll reply with a price and lead time.</p>

            <form class="contact-form" id="main-contact-form" method="post" action="#">
                <div class="form-row">
                    <div class="form-group">
                        <label for="cf-name">Full Name <span class="required" aria-hidden="true">*</span></label>
                        <input type="text" id="cf-name" name="name" autocomplete="name" required>
                    </div>
                    <div class="form-group">
                        <label for="cf-phone">Phone <span class="required" aria-hidden="true">*</span></label>
                        <input type="tel" id="cf-phone" name="phone" autocomplete="tel" inputmode="tel" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cf-email">Email</label>
                        <input type="email" id="cf-email" name="email" autocomplete="email">
                    </div>
                    <div class="form-group">
                        <label for="cf-company">Company</label>
                        <input type="text" id="cf-company" name="company" autocomplete="organization">
                    </div>
                </div>

                <div class="form-group">
                    <label for="cf-product">Product</label>
                    <div class="select-wrap">
                        <select id="cf-product" name="product">
                            <option value="">Select a product</option>
                            <?php foreach ( $products as $slug => $product ) : ?>
                                <option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $selected_product, $slug ); ?>><?php echo esc_html( $product['name'] ); ?></option>
                            <?php endforeach; ?>
                            <option value="not-sure" <?php selected( $selected_product, 'not-sure' ); ?>>Not sure yet</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cf-quantity">Quantity</label>
                        <input type="text" id="cf-quantity" name="quantity" placeholder="e.g. 200 pallets/month">
                    </div>
                    <div class="form-group">
                        <label for="cf-location">Delivery location</label>
                        <input type="text" id="cf-location" name="location" placeholder="e.g. Jaipur, Rajasthan">
                    </div>
                </div>

                <div class="form-group">
                    <label for="cf-message">Requirements <span class="required" aria-hidden="true">*</span></label>
                    <textarea id="cf-message" name="message" rows="4" placeholder="Panel size (L × W), panels per pallet, quantity and anything special…" required></textarea>
                </div>

                <button type="submit" class="btn btn--primary btn--lg btn--full">
                    Send Enquiry <?php echo erawat_btn_circle(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
                </button>
                <p class="form-privacy"><i class="fas fa-lock" aria-hidden="true"></i> We only use your details to reply to your enquiry.</p>
                <div class="form-message" id="contact-form-message" role="status" aria-live="polite" hidden></div>
            </form>
        </div>

        <!-- Contact panel -->
        <aside class="contact-panel" data-aos="fade-left" aria-label="Contact details">
            <ul class="contact-list spec-list">
                <li class="contact-list__item">
                    <span class="contact-list__icon" aria-hidden="true"><i class="fas fa-phone-alt"></i></span>
                    <div>
                        <h3>Call</h3>
                        <a href="tel:<?php echo esc_attr( ERAWAT_PHONE_TEL ); ?>"><?php echo esc_html( ERAWAT_PHONE ); ?></a>
                    </div>
                </li>
                <li class="contact-list__item">
                    <span class="contact-list__icon contact-list__icon--green" aria-hidden="true"><i class="fab fa-whatsapp"></i></span>
                    <div>
                        <h3>WhatsApp</h3>
                        <a href="<?php echo esc_url( ERAWAT_WHATSAPP ); ?>" target="_blank" rel="noopener">Chat with us</a>
                        <p>Send your panel size and get a quick quote</p>
                    </div>
                </li>
                <li class="contact-list__item">
                    <span class="contact-list__icon" aria-hidden="true"><i class="fas fa-envelope"></i></span>
                    <div>
                        <h3>Email</h3>
                        <a href="mailto:<?php echo esc_attr( ERAWAT_EMAIL ); ?>"><?php echo esc_html( ERAWAT_EMAIL ); ?></a>
                    </div>
                </li>
                <li class="contact-list__item">
                    <span class="contact-list__icon contact-list__icon--wood" aria-hidden="true"><i class="fas fa-map-marker-alt"></i></span>
                    <div>
                        <h3>Location</h3>
                        <address class="contact-list__address"><?php echo erawat_address_html(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in the helper ?></address>
                    </div>
                </li>
            </ul>

            <div class="contact-panel__deliver">
                <h3>We deliver to</h3>
                <ul class="chip-list chip-list--sm">
                    <li class="chip"><i class="fas fa-truck-fast" aria-hidden="true"></i> All of India</li>
                    <li class="chip"><i class="fas fa-globe" aria-hidden="true"></i> Worldwide</li>
                    <li class="chip"><i class="fas fa-location-dot" aria-hidden="true"></i> Gujarat</li>
                    <li class="chip"><i class="fas fa-location-dot" aria-hidden="true"></i> Rajasthan</li>
                    <li class="chip"><i class="fas fa-location-dot" aria-hidden="true"></i> Jaipur</li>
                </ul>
            </div>
        </aside>
    </div>
</section>

<?php get_footer(); ?>
