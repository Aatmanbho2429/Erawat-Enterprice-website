<?php get_header(); ?>

<?php erawat_page_banner( 'About Erawat Enterprise', 'Our Story', 'banner--about' ); ?>

<!-- ══════════════ WHO WE ARE ══════════════ -->
<section class="section" aria-labelledby="who-title">
    <div class="container split">
        <figure class="split__media photo-frame" data-aos="clip-up">
            <img src="<?php echo esc_url( erawat_image_url( 'about-pallet-yard.jpg' ) ); ?>"
                 alt="Stacks of wooden pallets in a pallet yard in Gujarat"
                 width="500" height="375" loading="lazy" decoding="async">
        </figure>

        <div class="split__body">
            <span class="eyebrow" data-aos="fade-up">Who We Are</span>
            <h2 id="who-title" data-split>A Wooden Pallet Maker Focused on Solar</h2>
            <p class="statement" data-aos="fade-up">Erawat Enterprise is a wooden packaging manufacturer based in Gujarat. <span class="statement__muted">We make two things and we make them well — solar pallets and box pallets for the solar industry — plus custom versions of both, built to each customer's panel size.</span></p>
            <p data-aos="fade-up">Solar modules are large, heavy and easy to crack. Our pallets are designed around that: a firm, flat base, the right board spacing, foam where the glass needs it, and a fit that stops panels shifting in transit.</p>
            <ul class="chip-list" data-aos="fade-up">
                <li class="chip"><i class="fas fa-layer-group" aria-hidden="true"></i> Solar Pallets</li>
                <li class="chip"><i class="fas fa-box-open" aria-hidden="true"></i> Box Pallets</li>
                <li class="chip"><i class="fas fa-ruler-combined" aria-hidden="true"></i> Custom Builds</li>
            </ul>
        </div>
    </div>
</section>

<!-- ══════════════ WHAT WE STAND FOR ══════════════ -->
<section class="section section--panel" aria-labelledby="stand-title">
    <div class="container">
        <div class="section-head section-head--center">
            <h2 id="stand-title" data-split>What We Stand For</h2>
        </div>

        <div class="values-grid" data-aos-stagger>
            <article class="value-card" data-aos="fade-up">
                <span class="value-card__icon" aria-hidden="true"><i class="fas fa-ruler-combined"></i></span>
                <div class="value-card__body">
                    <h3>The right fit</h3>
                    <p>We build to your module size, not a catalogue size.</p>
                </div>
            </article>
            <article class="value-card" data-aos="fade-up">
                <span class="value-card__icon" aria-hidden="true"><i class="fas fa-hammer"></i></span>
                <div class="value-card__body">
                    <h3>A strong build</h3>
                    <p>Good timber, firm nailing and a check on every batch before dispatch.</p>
                </div>
            </article>
            <article class="value-card" data-aos="fade-up">
                <span class="value-card__icon" aria-hidden="true"><i class="fas fa-calendar-check"></i></span>
                <div class="value-card__body">
                    <h3>On-time supply</h3>
                    <p>We plan production around your dispatch dates, so pallets are ready when your panels are.</p>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- ══════════════ CUSTOMER REVIEWS ══════════════ -->
<?php get_template_part( 'template-parts/reviews' ); ?>

<?php get_footer(); ?>
