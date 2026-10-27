# Implementation plan — Erawat Enterprise v2 (shorter, product-focused, modern, SEO)

Read `CLAUDE.md` and `content/site-content.md` before starting. **All visible copy comes from
`content/site-content.md`.** The ⚠️ markers there are notes for the client — never print them on the site.

## Ground rules

- Branch `Development`. Do not commit, push, or create branches/worktrees.
- Plain PHP / CSS / vanilla JS. No build tools, no new libraries, no jQuery usage.
- Follow every convention in `CLAUDE.md`: `get_header()`/`get_footer()`, `erawat_page_banner()`, `erawat_` /
  `erawat-` / `ERAWAT_` prefixes, text domain `erawat-enterprise`, escape all output, assets only in
  `erawat_enqueue_assets()`, `ERAWAT_DIR` / `ERAWAT_URI` for paths.
- Keep page slugs and template filenames. Keep IDs `site-header`, `mobile-menu-toggle`, `mobile-nav`,
  `mobile-nav-overlay`, `back-to-top`, `main-contact-form`, `contact-form-message`.
- Colours: keep the palette tokens. No new raw hex. If you need transparency, add RGB tokens to `:root`
  (e.g. `--navy-dark-rgb: 15, 36, 71`) and use `rgba(var(--navy-dark-rgb), .8)`.
- Goal of the redesign: **much shorter pages, much less white space, modern look, tasteful motion.**

## Assets already in `erawat-enterprise/assets/images/`

| File | Notes |
|---|---|
| `logo-erawat.png` | 780×252, navy (#0F2447) on transparent — header |
| `logo-erawat-white.png` | 780×252, white on transparent — footer / dark backgrounds |
| `favicon.png` | 512×512, white mark on navy |
| `product-solar-pallet.jpg` | 1040×620, client photo — Solar Pallet |
| `product-box-pallet.jpg` | 645×488, client photo — Box Pallet |
| `product-custom-solar-pallet.jpg` | **does not exist yet** (client photo) → fall back to `product-solar.jpg` |
| `product-custom-box-pallet.jpg` | **does not exist yet** (client photo) → fall back to `detail-solar.jpg` |
| `hero.jpg`, `story-factory.jpg`, `banner-*.jpg`, `service-design.jpg`, `service-manufacturing.jpg`, `service-heattreatment.jpg`, `service-logistics.jpg` | existing, keep |

The fallback must be automatic (`file_exists()` on the preferred file) so the client can drop their real photos
in later with no code change.

## 1. Product data — new `inc/products.php`

`erawat_products()` returns the four products in order, keyed by slug: `solar-pallet`, `box-pallet`,
`custom-solar-pallet`, `custom-box-pallet`. Each entry: `name`, `caption` (the one-liner from the content doc),
`image`, `fallback` (or null), `alt`. Add `erawat_product_image_url( $product )` (preferred file if it exists,
else fallback) and `erawat_image_url( $file )`. Use this data for the Home cards, Product page, footer product
list and the contact form's product select. `require_once` it from `functions.php`.

## 2. SEO — new `inc/seo.php`

- `erawat_seo_pages()`: slug → `[ title, description ]` for all six pages (front page key `home`), exactly as in
  the content doc.
- Filter `pre_get_document_title` to return the mapped title on those pages.
- On `wp_head` (priority 1) print: meta description, meta keywords (all keywords from the content doc plus each
  with "in Gujarat" / "in Rajasthan" / "in Jaipur"; include "erawat enterprice" only here), Open Graph
  (`og:type` website, `og:site_name`, `og:title`, `og:description`, `og:url`, `og:image` = absolute URL of
  `hero.jpg`, `og:locale` en_IN), `twitter:card` summary_large_image.
- JSON-LD `LocalBusiness` on every page: name, url, logo, image, telephone `+91-99310-60355`, email,
  address `{ addressRegion: Gujarat, addressCountry: IN }`, `areaServed` (State Gujarat, State Rajasthan,
  City Jaipur), `sameAs` LinkedIn, description, and `hasOfferCatalog` listing the four products. Build it as a
  PHP array and print with `wp_json_encode()`. No Product or FAQPage schema.
- Skip all of the above when Yoast (`defined( 'WPSEO_VERSION' )`) or Rank Math (`class_exists( 'RankMath' )`)
  is active.
- If `! has_site_icon()`, print `<link rel="icon">` and `<link rel="apple-touch-icon">` for `favicon.png`.
- Delete the hardcoded `<meta name="description">` line in `header.php`.
- Add `<script>document.documentElement.className += ' js';</script>` in `<head>` (before `wp_head()`), so
  scroll-reveal hiding only applies when JS runs (`.js [data-aos] { … }`). Content must be fully visible
  without JS.

## 3. Header (`header.php`)

- Remove the top bar.
- Logo: `the_custom_logo()` if set, else `<a class="site-logo" href="home" rel="home"><img src="logo-erawat.png"
  alt="Erawat Enterprise" width="780" height="252"></a>` (CSS height ~44px, 36px when scrolled / mobile).
- Fallback menus (desktop + mobile): Home · About Us · Products (`/product-range/`) · Services · ISPM 15
  (`/ispm-15-packing/`) · Contact (`/contact-us/`).
- Right side: phone link (icon + "+91 99310 60355", text hidden below 1100px) and "Get a Quote" button.
- Add a 3px scroll-progress bar `<div class="scroll-progress" id="scroll-progress" aria-hidden="true">` on the
  header's top edge.
- Header is sticky, frosted glass (`backdrop-filter: saturate(160%) blur(12px)`, white ~85%), ~76px tall,
  shrinking to ~64px with a soft shadow when `.scrolled`.

## 4. Footer (`footer.php`)

- CTA band with the content-doc copy (two buttons).
- Footer grid: brand (white logo, about line, 3 badges, LinkedIn + WhatsApp icons) · Products (loop over
  `erawat_products()`, links to `/product-range/#{slug}`) · Company · Contact. Then the areas line and the
  copyright bar (dynamic year).
- Floating WhatsApp button on every page: `<a class="whatsapp-float" href="https://wa.me/919931060355"
  target="_blank" rel="noopener" aria-label="Chat on WhatsApp">` with a soft pulse ring; keep it clear of
  `#back-to-top` (WhatsApp bottom-left or stack them on the right).

## 5. Banner helper (`functions.php`)

Change `erawat_page_banner()` so the breadcrumb sits **inside** the banner as a small line above the eyebrow
and H1 (no separate breadcrumb strip). Banner height `clamp(220px, 32vh, 320px)`, text reveal on load. Update
the `CLAUDE.md` sentence that says it renders the banner "and then the breadcrumbs".

## 6. Templates

Use `template-parts/` + `get_template_part( 'template-parts/x', null, $args )` for shared pieces:
- `template-parts/product-card.php` — args `product`, `variant` (`feature` | `compact`). Photo as `<img>`
  (width/height/alt, `loading="lazy" decoding="async"`), caption overlay in the style of the client's reference
  image: a frosted light panel at the bottom-left of the photo (`rgba(255,255,255,.85)` + blur), title in
  `--font-accent` (Playfair Display, navy-dark), one-line description below in body font, small "View details →"
  link. Whole card has `data-tilt`.
- `template-parts/process-steps.php` — args `steps` (default: the 4 Home steps), `layout` (`vertical` |
  `horizontal`). Numbered steps joined by a line that draws in on reveal.
- `template-parts/areas-served.php` — the Gujarat / Rajasthan / Jaipur cards (icon, H3, text, "Get a quote →").

### `front-page.php` (target: ~2.5 desktop screens before the CTA band)
1. **Hero** — split grid on `hero.jpg` with a navy gradient overlay and a slow Ken Burns zoom. Left: eyebrow
   chip, H1 with `data-split` (word-by-word reveal; the accent line "Built Around Your Panels" in its own span,
   coloured `--forest-light` or a forest→wood gradient text), text, two buttons, three chips. Right:
   `.hero__visual` with two floating photo cards (Solar Pallet behind, Box Pallet in front and offset), each
   with a small label, gentle float keyframes and mouse parallax (`data-depth`). `min-height: min(88vh, 760px)`
   on desktop; on mobile the visual shrinks and sits under the text. First hero image `loading="eager"
   fetchpriority="high"`.
2. **Marquee** — thin navy strip, items from the content doc separated by ✦, infinite CSS scroll, pause on
   hover; the duplicate list is `aria-hidden="true"`.
3. **What We Make** — section header + bento grid: row 1 Solar Pallet + Box Pallet (`feature`, image aspect
   16/10); row 2 the two customised products (`compact`, aspect 21/9). One column on mobile (aspect 4/3).
   Cards link to `/product-range/#{slug}`.
4. **Why Erawat + How It Works** — one cream section, two columns: left the 4 reasons as a 2×2 icon grid,
   right `process-steps` vertical.
5. **Areas We Serve** — `areas-served`.

Remove everything else from the old home page (stats, about snippet, why grid, ISPM callout, services,
testimonials, process strip). Remove the counter JS that only served the stats bar, or make it generic (see §8).

### `page-about-us.php`
Banner (`'About Erawat Enterprise', 'Our Story', 'banner--about'`) → Who We Are (split: `story-factory.jpg`
as `<img>` with `data-aos="clip-up"`, text, 3 highlight chips) → What We Stand For (3 cards) → Where We Work
(navy strip: H2, paragraph, 3 location chips).

### `page-product-range.php`
Banner (`'Solar Pallets & Box Pallets', 'Our Products', 'banner--products'`) → intro line + 4 jump-link chips →
Solar Pallet and Box Pallet as alternating image/text rows (`id="solar-pallet"`, `id="box-pallet"`; image with
caption overlay; 4 features with check icons; button to `/contact-us/?product={slug}`) → the two customised
products side by side (`id="custom-solar-pallet"`, `id="custom-box-pallet"`; image + caption, text, option
chips, button) → FAQ (4 items, `.faq-question` + next-sibling answer).

### `page-services.php`
Banner (`'Our Services', 'From Design to Delivery', 'banner--services'`) → intro line → 4 service cards in a
grid (photo top with a 01–04 badge, icon, title, text, 3 bullets; images `service-design.jpg`,
`service-manufacturing.jpg`, `service-heattreatment.jpg`, `service-logistics.jpg`) → `process-steps`
horizontal.

### `page-ispm-15-packing.php`
Banner (`'ISPM 15 Export Packing', 'Heat Treated & IPPC Marked', 'banner--ispm'`) → What Is ISPM 15 (split:
text + 2×2 fact tiles; 56 and 30 count up) → treatment process (`process-steps` horizontal with the 4 ISPM
steps) → Reading the IPPC Mark (simplified diagram: symbol | IN-000 | HT, plus the 3 code explanations and the
note) → FAQ (4 items).

### `page-contact-us.php`
Banner (`'Contact Us', 'Get a Quote', 'banner--contact'`) → two columns: form card (fields exactly as in the
content doc; product `<select>` built from `erawat_products()` plus `not-sure` "Not sure yet"; pre-select from
`?product=` using `sanitize_key()` + `selected()`) and a contact panel (Call, WhatsApp, Email, Location, Hours,
"We deliver to" chips). Delete the fake map placeholder.

### `404.php`, `index.php`, `page.php`
Make sure they still render cleanly with the new stylesheet (no references to deleted classes).

## 7. Contact handler (`erawat_handle_contact_form()`)

Read `name`, `phone`, `email`, `company`, `product`, `quantity`, `location`, `message` (sanitised).
Required: name, phone, message → error "Please fill in your name, phone number and requirements."
Email optional; if given it must pass `is_email()` (error otherwise), and only then add `Reply-To`.
Map the product slug to its name (`not-sure` → "Not sure yet"). Put every field in the mail body.
Subject: `New enquiry from {name} — {product name}`. The JS stays as it is (it already posts all fields).

## 8. Motion (CSS + `assets/js/erawat-script.js`)

All motion is CSS transitions/keyframes driven by classes; JS only toggles classes and sets custom properties.
Everything respects `prefers-reduced-motion: reduce` (JS checks `matchMedia` once; CSS has a media block that
disables animation and shows all content).

JS (inside the existing IIFE):
- One rAF-throttled scroll handler for: header `.scrolled`, `#back-to-top` visibility, scroll-progress
  (`transform: scaleX()`).
- `initSplitText()` — for `[data-split]`, wrap the words of text nodes (keep child elements such as the accent
  span) in `<span class="split-word" style="--i:N">`; CSS reveals them with `animation-delay: calc(var(--i) * 70ms)`.
- Reveal: keep the IntersectionObserver that adds `.aos-animate`. Values: existing `fade-up`, `fade-left`,
  `fade-right` plus new `zoom-in`, `blur-in`, `clip-up` (clip-path inset reveal for images).
  `data-aos-delay` 100/200 stays; add `data-aos-stagger` on a parent → its `[data-aos]` children get
  `transition-delay: i * 80ms`.
- `initTilt()` — `[data-tilt]`, only for `(pointer: fine)`; max ±6° rotateX/Y with perspective, sets `--mx` /
  `--my` for a soft radial glare; resets on leave.
- `initParallax()` — `.hero__visual [data-depth]` moves with the pointer (small translate).
- `initCountUp()` — generic `[data-count-to]` (used by the ISPM fact tiles), replaces the stats-only counter.
- Keep: mobile menu, FAQ accordion, `initForm()` calls, smooth anchor scroll, active-nav highlight.

CSS motion list: header shrink/glass, scroll-progress bar (forest → wood gradient), hero Ken Burns + word
reveal + floating cards, marquee, section reveals, eyebrow line growing in on reveal, card hover (lift, image
scale 1.05, light sheen sweep, tilt glare), button hover (lift, arrow slides right, sheen), process line
drawing, WhatsApp pulse. Durations 0.5–0.9s, `cubic-bezier(.22, 1, .36, 1)`. Nothing loops except the
marquee, the hero float, the Ken Burns and the WhatsApp pulse.

## 9. Stylesheet (`assets/css/erawat-style.css`) — rewrite

Rewrite the stylesheet around the new templates; drop rules for components no template outputs any more
(grep the templates first). Keep the palette tokens and the header comment. Then:
- Fonts: `--font-heading: 'Outfit', 'Helvetica Neue', Arial, sans-serif` (weights 600/700, letter-spacing
  ~-0.01em, normal case); `--font-body` Open Sans; `--font-accent` Playfair Display (caption titles, accent
  words). Update the Google Fonts URL in `erawat_enqueue_assets()` to Outfit 400–700, Open Sans 400/600/700,
  Playfair Display 500/600 + italic 500; drop Cinzel and Cinzel Decorative.
- Spacing: `--section-y: clamp(2.75rem, 6vw, 4.5rem)`; `.section { padding: var(--section-y) 0 }`; section
  header bottom margin `clamp(1.5rem, 3vw, 2.25rem)`; body text max ~62ch; remove `.title-divider` from the
  templates.
- Look: radius 16–24px on cards, layered soft shadows, hairline borders (`rgba(var(--navy-dark-rgb), .08)`),
  pill buttons, navy sections with a faint radial glow (forest / wood at low opacity), eyebrow = small
  uppercase label with a short leading line.
- Responsive at 1100 / 900 / 640px; no horizontal scroll at 360px; tap targets ≥ 44px.
- Keep a "PHOTOGRAPHY" block at the bottom for the CSS background images (hero and banners).
- Bump `ERAWAT_VERSION` and the `style.css` `Version:` to 2.0.0 and update the theme `Description:` to the
  four products.

## 10. Images clean-up

After the templates and CSS are done, list every file in `assets/images/` that is no longer referenced by any
template, part or CSS, and delete only those (expected: `product-pallets.jpg`, `product-crates.jpg`,
`product-custom.jpg`, `detail-crates.jpg`, `detail-custom.jpg`, `detail-customised.jpg`,
`detail-ispmpallet.jpg`, `detail-onsite.jpg`, `detail-pallets.jpg`, `detail-plywood.jpg`,
`detail-warehousing.jpg`, `service-onsite.jpg`, `about-workshop.jpg` — verify each). **Never delete**
`product-solar.jpg` or `detail-solar.jpg` (placeholders) and never touch `image-originals/`.

## 11. `CLAUDE.md`

Update it to match: new `data-aos` values and `data-aos-stagger`, `.js` gating, breadcrumbs inside the banner,
fonts, `inc/products.php`, `inc/seo.php`, `template-parts/`, logo + favicon files, contact-form fields and
required fields, `content/` folder (site copy + this plan; not deployed), the Placeholder section (stats,
testimonials and the fake address/map are gone; the address now reads "Gujarat, India"; ISPM 15 / delivery
area / 24-hour claims still unconfirmed), and Known issue 2 if the client-logo CSS is gone.

## 12. Verify before reporting

1. Start `./dev.sh` with `run_in_background`, then wait with
   `curl -s -o /dev/null --retry 40 --retry-delay 3 --retry-connrefused http://127.0.0.1:9400/` (first start
   downloads WordPress).
2. Fetch `/`, `/about-us/`, `/product-range/`, `/services/`, `/ispm-15-packing/`, `/contact-us/`,
   `/contact-us/?product=box-pallet` and a 404 URL. In each, grep for `Fatal error`, `Warning:`, `Notice:`,
   `Deprecated:`; check exactly one `<h1>`, the expected `<title>`, a meta description, and that the JSON-LD
   parses (`python3 -c json.loads`). Check the dev-server output for PHP errors.
3. Script-check that every `assets/images/…` path referenced in PHP or CSS exists.
4. Post to `admin-ajax.php` (`action=erawat_contact`, nonce taken from the page's `erawatData`) once without a
   phone (expect the validation error) and once complete (Playground has no mail, so a "Could not send" error
   is acceptable — just no PHP error).
5. `node --check assets/js/erawat-script.js`.
6. Stop the dev server you started (kill the background process) so port 9400 is free.

Report back: files created/changed/deleted, check results, and anything you skipped or did differently from
this plan and why.
