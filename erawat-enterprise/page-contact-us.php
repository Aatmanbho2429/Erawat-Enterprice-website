<?php get_header(); ?>

<?php erawat_page_banner('Contact Us', 'Get In Touch', 'banner--contact'); ?>

<!-- ══════════════ CONTACT INFO CARDS ══════════════ -->
<section class="section contact-info-strip">
    <div class="container">
        <div class="contact-info-grid" data-aos="fade-up">
            <div class="contact-info-card">
                <div class="contact-info-card__icon" style="background: var(--navy);">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h4>Call Us</h4>
                <p><a href="tel:+919931060355">+91 99310 60355</a></p>
            </div>
            <div class="contact-info-card">
                <div class="contact-info-card__icon" style="background: var(--forest);">
                    <i class="fas fa-envelope"></i>
                </div>
                <h4>Email Us</h4>
                <p><a href="mailto:Erawat005@gmail.com">Erawat005@gmail.com</a></p>
            </div>
            <div class="contact-info-card">
                <div class="contact-info-card__icon" style="background: var(--wood);">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h4>Visit Us</h4>
                <p>Plot No. XX, Industrial Area,<br>Gujarat — 380001, India</p>
                <span class="contact-info-card__note">Factory visits by appointment</span>
            </div>
            <div class="contact-info-card">
                <div class="contact-info-card__icon" style="background: var(--navy-dark);">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h4>WhatsApp</h4>
                <p><a href="https://wa.me/919931060355" target="_blank" rel="noopener">+91 99310 60355</a></p>
                <p>Send your specs &amp; get a quick quote</p>
                <span class="contact-info-card__note">Fast response via WhatsApp</span>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════ CONTACT FORM + MAP ══════════════ -->
<section class="section section--cream contact-main">
    <div class="container">
        <div class="contact-main__grid">

            <!-- Contact Form -->
            <div class="contact-form-wrap" data-aos="fade-right">
                <div class="section-header">
                    <span class="section-eyebrow">Send An Enquiry</span>
                    <h2 class="section-title">Get Your Free Quote</h2>
                    <div class="title-divider"></div>
                    <p>Fill in your details and our packaging engineers will contact you with a tailored quote and design recommendation.</p>
                </div>

                <form class="contact-form" id="main-contact-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cf-name">Full Name <span class="required">*</span></label>
                            <input type="text" id="cf-name" name="name" placeholder="Your full name" required>
                        </div>
                        <div class="form-group">
                            <label for="cf-company">Company Name</label>
                            <input type="text" id="cf-company" name="company" placeholder="Your company">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cf-email">Email Address <span class="required">*</span></label>
                            <input type="email" id="cf-email" name="email" placeholder="you@company.com" required>
                        </div>
                        <div class="form-group">
                            <label for="cf-phone">Phone Number</label>
                            <input type="tel" id="cf-phone" name="phone" placeholder="+91 XXXXX XXXXX">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cf-product">Product / Service Required</label>
                        <select id="cf-product" name="product">
                            <option value="">— Select a product/service —</option>
                            <option value="solar-boxes">Solar Panel Wooden Boxes</option>
                            <option value="export-pallets">Export Wooden Pallets (ISPM 15)</option>
                            <option value="industrial-crates">Industrial Wooden Crates</option>
                            <option value="plywood-boxes">Plywood Packaging Boxes</option>
                            <option value="custom">Custom Packaging Solution</option>
                            <option value="ispm15-treatment">ISPM 15 Treatment of Existing Packaging</option>
                            <option value="onsite-packing">On-Site Packing Services</option>
                            <option value="other">Other — Please Describe</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cf-quantity">Approximate Quantity</label>
                            <input type="text" id="cf-quantity" name="quantity" placeholder="e.g. 500 units/month">
                        </div>
                        <div class="form-group">
                            <label for="cf-destination">Destination (if export)</label>
                            <input type="text" id="cf-destination" name="destination" placeholder="e.g. USA, Germany">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cf-message">Your Requirements <span class="required">*</span></label>
                        <textarea id="cf-message" name="message" rows="5"
                            placeholder="Please describe the product to be packed (dimensions, weight, fragility), intended use (domestic/export), and any special requirements..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn--primary btn--lg btn--full">
                        <i class="fas fa-paper-plane"></i> Send Enquiry
                    </button>
                    <p class="form-privacy mt-3">
                        <i class="fas fa-lock"></i>
                        Your information is confidential and will only be used to respond to your enquiry.
                    </p>
                    <div class="form-message" id="contact-form-message" style="display:none;"></div>
                </form>
            </div>

            <!-- Right Panel -->
            <div class="contact-side" data-aos="fade-left">
                <!-- Map Placeholder -->
                <div class="map-container">
                    <div class="map-placeholder">
                        <i class="fas fa-map-marked-alt"></i>
                        <p>Erawat Enterprise</p>
                        <span>Industrial Area, Gujarat, India</span>
                        <a href="https://maps.google.com" target="_blank" rel="noopener" class="btn btn--outline-primary btn--sm mt-3">
                            <i class="fas fa-external-link-alt"></i> Open in Google Maps
                        </a>
                    </div>
                </div>

                <!-- Quick Contact Details -->
                <div class="contact-side__details">
                    <div class="contact-side__social mt-4">
                        <h4>Connect With Us</h4>
                        <div class="social-links-row">
                            <a href="https://www.linkedin.com/in/erawat-enterprise-145601431" target="_blank" rel="noopener" class="social-link-lg" aria-label="LinkedIn">
                                <i class="fab fa-linkedin-in"></i> LinkedIn
                            </a>
                            <a href="https://wa.me/919931060355" class="social-link-lg" aria-label="WhatsApp" target="_blank" rel="noopener">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
