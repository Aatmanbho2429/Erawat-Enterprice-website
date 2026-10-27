<?php get_header(); ?>

<?php erawat_page_banner( 'ISPM 15 Export Packing', 'Heat Treated & IPPC Marked', 'banner--ispm' ); ?>

<?php
$treatment_steps = [
    [ 'title' => 'Build', 'text' => 'Pallets and boxes are made from debarked timber.' ],
    [ 'title' => 'Heat',  'text' => 'Loaded into the heat chamber until the wood core reaches 56 °C.' ],
    [ 'title' => 'Hold',  'text' => 'Held at temperature for at least 30 minutes, with the run logged.' ],
    [ 'title' => 'Mark',  'text' => 'Stamped with the IPPC mark and dispatched with the treatment record.' ],
];

$faqs = [
    [ 'q' => 'Do I need ISPM 15 for shipments within India?', 'a' => 'No. ISPM 15 applies to international shipments. Some customers still use treated pallets everywhere to keep one specification.' ],
    [ 'q' => 'Does plywood need ISPM 15?', 'a' => 'No. Plywood and other processed wood are exempt. Solid timber pallets and boxes need treatment for export.' ],
    [ 'q' => 'Does the treatment expire?', 'a' => 'No. If a pallet is repaired with new wood, that wood must be treated and the pallet re-marked.' ],
    [ 'q' => 'What documents do I get?', 'a' => 'Treated orders come with a heat treatment record for your shipping documents.' ],
];

$code_notes = [
    [ 'code' => 'IN',  'title' => 'Country code',     'text' => 'India' ],
    [ 'code' => '000', 'title' => 'Producer number',  'text' => "Issued by India's plant protection authority (NPPO)" ],
    [ 'code' => 'HT',  'title' => 'Treatment type',   'text' => 'Heat treatment' ],
];
?>

<!-- ══════════════ WHAT IS ISPM 15 ══════════════ -->
<section class="section" aria-labelledby="what-title">
    <div class="container split split--top">
        <div class="split__body">
            <h2 id="what-title" data-split>What Is ISPM 15?</h2>
            <p data-aos="fade-up">ISPM 15 is the international rule for wooden packaging used in export. Pallets, boxes and crates must be treated to kill pests and stamped with the IPPC mark — otherwise the shipment can be held or sent back at the destination port.</p>
            <p data-aos="fade-up">We heat-treat our solar pallets and box pallets for export orders, so your shipment leaves with packaging that meets the standard.</p>
        </div>

        <div class="facts" data-aos-stagger>
            <div class="fact" data-aos="fade-up">
                <strong class="fact__value"><span data-count-to="56">56</span><small>°C</small></strong>
                <span class="fact__label">Core temperature</span>
            </div>
            <div class="fact" data-aos="fade-up">
                <strong class="fact__value"><span data-count-to="30">30</span><small>min</small></strong>
                <span class="fact__label">Minimum hold</span>
            </div>
            <div class="fact" data-aos="fade-up">
                <strong class="fact__value">HT</strong>
                <span class="fact__label">Heat only, no chemicals</span>
            </div>
            <div class="fact" data-aos="fade-up">
                <strong class="fact__value">IPPC</strong>
                <span class="fact__label">Mark on every unit</span>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════ TREATMENT PROCESS ══════════════ -->
<section class="section section--dark" aria-labelledby="treat-title">
    <div class="container">
        <div class="section-head section-head--center">
            <h2 id="treat-title" data-split>Our Treatment Process</h2>
        </div>
        <?php get_template_part( 'template-parts/process-steps', null, [ 'steps' => $treatment_steps, 'layout' => 'horizontal' ] ); ?>
    </div>
</section>

<!-- ══════════════ READING THE IPPC MARK ══════════════ -->
<section class="section" aria-labelledby="mark-title">
    <div class="container">
        <div class="section-head section-head--center">
            <h2 id="mark-title" data-split>Reading the IPPC Mark</h2>
        </div>

        <div class="ippc" data-aos="fade-up">
            <div class="ippc__stamp" role="img" aria-label="Example IPPC mark: IPPC symbol, IN-000, HT">
                <span class="ippc__cell ippc__cell--symbol">
                    <svg viewBox="0 0 48 64" width="40" height="54" aria-hidden="true" focusable="false">
                        <path d="M24 62V14" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round"/>
                        <path d="M24 12c-5-3-5-8 0-11 5 3 5 8 0 11z" fill="currentColor"/>
                        <path d="M24 26c-9 1-14-4-14-11 8-1 14 3 14 11zM24 26c9 1 14-4 14-11-8-1-14 3-14 11z" fill="currentColor"/>
                        <path d="M24 42c-9 1-14-4-14-11 8-1 14 3 14 11zM24 42c9 1 14-4 14-11-8-1-14 3-14 11z" fill="currentColor"/>
                        <path d="M24 58c-9 1-14-4-14-11 8-1 14 3 14 11zM24 58c9 1 14-4 14-11-8-1-14 3-14 11z" fill="currentColor"/>
                    </svg>
                </span>
                <span class="ippc__cell ippc__cell--code">IN-000</span>
                <span class="ippc__cell ippc__cell--code">HT</span>
            </div>

            <ul class="spec-list ippc__notes">
                <?php foreach ( $code_notes as $note ) : ?>
                    <li class="ippc__note">
                        <span class="ippc__code"><?php echo esc_html( $note['code'] ); ?></span>
                        <div>
                            <h3><?php echo esc_html( $note['title'] ); ?></h3>
                            <p><?php echo esc_html( $note['text'] ); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <p class="ippc__footnote"><i class="fas fa-stamp" aria-hidden="true"></i> The mark is stamped or burned into the wood — never a sticker.</p>
        </div>
    </div>
</section>

<!-- ══════════════ FAQ ══════════════ -->
<section class="section section--panel" aria-labelledby="faq-title">
    <div class="container container--narrow">
        <div class="section-head section-head--center">
            <h2 id="faq-title" data-split>FAQ</h2>
        </div>
        <?php get_template_part( 'template-parts/faq-list', null, [ 'items' => $faqs, 'prefix' => 'ifaq' ] ); ?>
    </div>
</section>

<?php get_footer(); ?>
