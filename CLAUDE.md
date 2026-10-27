# Project overview

Marketing website for **Erawat Enterprise**, a Gujarat (India) maker of wooden solar pallets and box
pallets for solar module manufacturers and EPC companies. Only four products: Solar Pallet, Box Pallet,
Customised Solar Pallet, Customised Box Pallet. Six pages: Home, About Us, Product Range, Services,
ISPM 15 Packing, Contact Us. SEO targets: solar pallet, wooden pallet, box pallet, solar, pallet,
solar box, Erawat, Erawat Enterprise — each also "in Gujarat / Rajasthan / Jaipur".

The deliverable is a **WordPress theme**. Development is local (WordPress Playground, see below);
pushing to `main` deploys the theme to Hostinger through a GitHub Action. There is no staging server.

# Repository layout

- `erawat-enterprise/` — **the WordPress theme. The only copy of the site code. Make changes here.**
- `blueprint.json` + `dev.sh` — local WordPress preview (WordPress Playground) that mounts and activates the theme.
- `.github/workflows/deploy.yml` — deploys `erawat-enterprise/` to Hostinger over FTP on push to `main`.
- `content/` — `site-content.md` (the approved copy for every page; all visible text comes from here —
  lines marked with a warning sign are client-confirmation notes and are never printed on the site) and
  `implementation-plan.md` (the v2 redesign plan) and `premium-redesign-plan.md` (the v3 "Premium" visual
  redesign plan that the current look follows). **Not deployed.**
- `image-originals/` — source PNGs for the site photography. Kept in the repo, **never deployed**; the
  optimised JPGs the theme actually uses live in `erawat-enterprise/assets/images/`.
- `.claude/` — gitignored local config (e.g. `launch.json` for the desktop app's preview pane).

No build system: no `package.json`, no bundler, no tests, no linter. Plain PHP, CSS, and vanilla JS.

# Bash commands

- Local preview: `./dev.sh` — starts WordPress Playground at **http://127.0.0.1:9400**, already logged in
  as admin (wp-admin at `/wp-admin/`). It mounts `erawat-enterprise/` live, so edit a file and reload the
  browser. Needs Node + network on first run (it downloads WordPress through `npx`, pinned in `dev.sh`).
  The site is rebuilt on every start: activating the theme (see `blueprint.json`) fires
  `after_switch_theme`, which creates the six pages and sets Home as the front page. Menus, settings and
  content edits made in the local wp-admin are discarded when the server stops.
- PHP is **not installed locally**, so there is no `php -l`. Check template changes by loading the pages
  in the local Playground site and watching for fatal errors/warnings in the output.

# WordPress theme conventions

- Every template starts with `get_header();` and ends with `get_footer();`.
- Page templates resolve by **slug** through the WordPress template hierarchy (`page-{slug}.php`).
  They carry no `Template Name:` header, so the filename must match the page slug exactly
  (`page-about-us.php`, `page-product-range.php`, `page-services.php`, `page-ispm-15-packing.php`,
  `page-contact-us.php`; Home is `front-page.php`).
- Right after `get_header();`, page templates call
  `erawat_page_banner( $title, $subtitle, $bg_class )` (e.g. `'banner--products'`). It renders the page
  banner (a rounded, centred photo panel) with the breadcrumb pill **inside** it (above the eyebrow tag and
  the page's single `<h1 data-split>`) — do not add either markup by hand. The photo is a
  `.page-banner__bg[data-parallax]` element whose image is set per `banner--*` class in the photography
  section of the CSS.
- `erawat_btn_circle()` (in `functions.php`) returns the round arrow that ends a button. Use it inside every
  arrow button: `Get a Quote <?php echo erawat_btn_circle(); ?>`.
- `erawat_preload_hero()` (`wp_head`) preloads `hero.jpg` on the front page — it is the LCP image and a CSS
  background.
- `header.php` opens `<main id="main" class="site-main">` and `footer.php` closes it before the CTA band.
  The footer also holds a decorative `aria-hidden` "ERAWAT" wordmark (`.footer-wordmark`).
- Shared pieces live in `template-parts/` and are loaded with `get_template_part( 'template-parts/x', null, $args )`:
  `product-card` (Home numbered card, args `product` + `index`), `product-figure` (Product page photo +
  glass label), `process-steps` (hairline spec list with `01`–`04` index numbers, `layout` = vertical |
  horizontal; **styled for dark panels**), `faq-list` (hairline list, `01` index + plus-circle), `reviews`
  (customer reviews on Home and About; data in `inc/reviews.php`).
- `inc/reviews.php` — `erawat_reviews()` holds **real** customer reviews only (empty until the client sends
  some). While it is empty the reviews section is not rendered for visitors; users who can `edit_pages`
  see `erawat_sample_reviews()` with a "Sample" badge and a note, so the layout can be checked locally.
  Never put invented reviews in `erawat_reviews()`.
- Locations (Gujarat, Rajasthan, Jaipur) are for SEO only — titles, meta, alt text, the hero sentence,
  footer line and schema. The client does not want visible sections built around them.
- `inc/products.php` — `erawat_products()` is the single source for the four products (name, captions,
  image, fallback, alt). Home cards, Product page, footer list, contact `<select>` and the SEO schema all
  read it. `erawat_product_image()` / `erawat_product_image_url()` use the preferred photo if the file
  exists, otherwise the optional `fallback` (all four products currently have their own photo, so every
  fallback is `null`). `erawat_image_url( $file )` builds image URLs.
- `inc/seo.php` — page titles (`pre_get_document_title`), meta description/keywords, Open Graph, Twitter
  card, LocalBusiness JSON-LD and a favicon fallback (`favicon.png` when no Site Icon is set). The meta
  output is skipped when Yoast SEO or Rank Math is active. Titles/descriptions live in `erawat_seo_pages()`.
- Contact details (phone, WhatsApp, email, LinkedIn) are `ERAWAT_PHONE`, `ERAWAT_PHONE_TEL`,
  `ERAWAT_WHATSAPP`, `ERAWAT_EMAIL`, `ERAWAT_LINKEDIN` in `functions.php`; `erawat_url( $slug )`
  returns a page URL with trailing slash.
- Prefix everything with `erawat_` (functions), `erawat-` (asset handles), `ERAWAT_` (constants).
  Text domain is `erawat-enterprise`.
- Paths use the `ERAWAT_DIR` / `ERAWAT_URI` constants, defined in `functions.php`.
- Escape all output: `esc_html()`, `esc_url()`, `esc_attr()`, `sanitize_text_field()` on input.
- Enqueue assets only inside `erawat_enqueue_assets()`. Asset versions come from `filemtime()` of each
  file, so browser and Hostinger caches bust automatically — there is no version to bump by hand.
- Contact form: AJAX action `erawat_contact`, nonce `erawat_nonce`, handled by
  `erawat_handle_contact_form()`, delivered with `wp_mail()` to the site's `admin_email`. Fields: `name`,
  `phone`, `email`, `company`, `product` (a slug from `erawat_products()` or `not-sure`), `quantity`,
  `location`, `message`. **Required: name, phone, message.** Email is optional but must be valid if given
  (Reply-To is only added then). `?product=slug` on the contact page pre-selects the product. The
  `<select>` sits in a `.select-wrap` (CSS chevron).
- Nav menus register as `primary` and `footer`. If no menu is assigned, `erawat_fallback_menu()` and
  `erawat_fallback_mobile_menu()` (both defined at the bottom of `header.php`) render a hardcoded list
  (Home, About Us, Products, Services, ISPM 15, Contact). Both menus work with the pill-nav indicator.
  The mobile menu takes over at **1100px** (CSS `max-width: 1100px` block and `MOBILE_BP` in the JS — change
  both together).
- Logo: `the_custom_logo()` if set in the Customizer, otherwise `assets/images/logo-erawat.png` (navy);
  `logo-erawat-white.png` is used in the footer; `favicon.png` is the browser-tab icon.
- `inc/setup-pages.php` creates the six pages on `after_switch_theme` and sets Home as the front page.

# CSS

- All real styles live in `assets/css/erawat-style.css`. Root `style.css` holds only the WordPress
  theme header and a small reset — keep it that way.
- Use the design tokens in `:root`; no raw hex outside `:root`. Palette: `--navy`, `--forest`, `--wood`,
  `--cream` plus `-dark` / `-light` / `-pale` / `-xlight` variants, and neutrals (`--white`, `--black`,
  `--gray*`). `--wood-glow` (+ `--wood-glow-rgb`) is the one extra warm shade: index numbers, accent words
  and dots on dark panels. For transparency use the RGB tokens: `rgba(var(--navy-dark-rgb), .8)`
  (`--navy-rgb`, `--navy-dark-rgb`, `--forest-rgb`, `--forest-light-rgb`, `--wood-rgb`, `--wood-light-rgb`,
  `--wood-glow-rgb`, `--cream-rgb`, `--white-rgb`, `--black-rgb`, `--danger-rgb`). `--danger` is only for
  form errors. There is no `--teal`. Colour roles: cream page canvas, white panels/cards with hairlines,
  `--navy-dark` for dark panels / primary buttons / headings, `--forest-dark` for the CTA panel only.
- Typography tokens: `--font-heading` (Archivo, set expanded with `--heading-stretch: 125%` and uppercase
  via CSS on `h1`/`h2`; the source text stays as typed), `--font-body` (Manrope; also `h3`),
  `--font-accent` (Instrument Serif italic: `.accent` words, caption titles, review quotes). Also
  `--space-*`, `--section-y`, `--gutter`, `--frame` (gutter around and between the rounded panels),
  `--radius-*` (`--radius-xl` is the panel radius), `--shadow-*` (soft, rarely used), `--hairline` /
  `--hairline-light`, and motion: `--ease` (expo-out), `--ease-in-out`, `--dur-reveal`, `--dur-hover`,
  `--transition`. Nothing bouncy or fast.
- Panels: `.section` sits on the cream canvas; `.section--panel` is a white rounded panel and
  `.section--dark` a `--navy-dark` rounded panel with light text (they replaced `.section--cream` /
  `.section--navy`). Panels are inset from the viewport by `--frame`; stacked panels are separated by it.
  The hero, page banner, CTA band (`--forest-dark`) and footer are panels too.
- Shared components: `.btn` (+ `.btn__circle` arrow circle, variants `btn--primary`, `btn--white`,
  `btn--outline-white`, `btn--ghost`; there is no `btn--navy`), `.eyebrow` (small hairline tag with a dot),
  `.index-num` (`01`–`04`), `.spec-list` (hairline rows; check marks or index at the left), `.statement`
  (large two-tone paragraph with `.statement__muted`), `.photo-frame` (rounded photo with a light grade — keep
  photos under ~640px wide), `.photo-caption` (glass label), `.chip` (hairline tag), `.pill-bar` (segmented
  pill links), `.accent` (serif italic word inside a heading), `.nav-indicator` (the dark pill that glides
  behind the active / hovered nav link; injected by JS).
- Keep pages short: sections use `--section-y` padding; do not add large empty bands.
- Class naming is BEM-ish: `block__element--modifier` (`site-header__inner`, `btn--primary`,
  `product-card--solar-pallet`, `section--dark`).
- Scroll animation is hand-rolled, not the AOS library: add `data-aos="fade-up"` / `fade-left` /
  `fade-right` / `zoom-in` / `blur-in` / `clip-up` (clip-up wipes an `<img>` in; the element should contain
  the image), optionally `data-aos-delay="100"` or `"200"`. Put `data-aos-stagger` on a parent and its direct
  `[data-aos]` children get 90ms-step delays (set as the `--aos-d` custom property, which nested `clip-up`
  photos inherit). The JS adds `.aos-animate`, then removes the `data-aos` attribute once the entrance is
  done. Only those values have CSS — any other `data-aos` value leaves the element invisible.
- The hiding rules are gated on a `js` class that `header.php` adds to `<html>`, so content is fully
  visible without JS. Data attributes: `[data-split]` (masked word reveal: the JS wraps each word as
  `<span class="split-word"><span class="split-word__inner">word</span></span>`; hero and banner titles
  reveal on load, section `h2`s when they scroll into view), `[data-count-to]` counts up,
  `[data-parallax="0.08"]` (scroll parallax on the hero / banner background wrapper, off below 768px),
  `[data-scroll-fill]` (words go from muted to full colour while scrolling — used once, on the Home
  statement), `[data-magnetic]` (button pulled up to 6px toward a mouse pointer; hero and CTA buttons only),
  `.hero__visual [data-depth]` (subtle pointer parallax). Everything respects `prefers-reduced-motion`
  (CSS block at the end of the stylesheet; JS checks `matchMedia` once). Animate only `transform`,
  `opacity`, `clip-path` and `filter`.
- Images: the optimised photos are real JPGs in `assets/images/`. Product, service and About photos are
  `<img>` tags with alt text; only the hero and page-banner backgrounds are CSS (photography section at the
  bottom, `url('../images/...')`). Originals are in `image-originals/`.
- Photo sources: the product, service (except heat treatment) and About photos are real photos the user
  supplied in `Image products/` (sourced from IndiaMART listings); the user confirmed on 2026-10-27 that
  Erawat has permission to use them. `product-custom-box-pallet.jpg`, `hero.jpg`, the `banner-*.jpg` and
  `service-heattreatment.jpg` are AI-generated. Don't add photos copied from other companies' sites
  without the user confirming permission. Most real photos are only 500px wide — don't use them as
  full-width backgrounds.

# JavaScript

- `assets/js/erawat-script.js`, a single IIFE, `'use strict'`, vanilla DOM APIs. It has no dependencies
  (jQuery is not loaded) — do not introduce jQuery usage.
- Behavior is wired to fixed element IDs: `site-header`, `scroll-progress`, `mobile-menu-toggle`,
  `mobile-nav`, `mobile-nav-overlay`, `back-to-top`. Keep these IDs when editing templates. The mobile
  drawer and overlay sit outside `<header>` on purpose (the header's backdrop-filter would trap `fixed`).
- One rAF-throttled scroll handler drives the header `.scrolled` state, back-to-top (and its SVG progress
  ring), the progress bar, scroll parallax and the scroll-fill statement. New scroll effects push a function
  onto `scrollTasks` instead of adding another listener.
- The pill-nav indicator (`initNavIndicator()`) measures link rects, so it works for `wp_nav_menu()` output
  and the fallback menu; it re-measures on resize and after `document.fonts.ready`.
- The form is wired by an explicit ID pair at the bottom of the file:
  `initForm('main-contact-form', 'contact-form-message')`. A new form needs both IDs on the markup
  **and** its own `initForm()` call, or it will silently do nothing.
- FAQ accordions use `.faq-question` with the answer as the immediate next sibling (see
  `template-parts/faq-list.php`).

# Dropped work

The v2 look (Outfit / Open Sans / Playfair fonts, green primary buttons, bento product cards with 3D
tilt and glare, button sheen sweep, infinite WhatsApp pulse, navy/cream full-width sections) was replaced by
the v3 "Premium" redesign; it is in git history before that commit.

An earlier static mirror (`docs/`) had extras the theme never used: a 3D rotating hero crate
(`.hero-crate` / `.crate-3d`), extra `data-aos` variants (`box-reveal`, `stamp`, `weight-drop`,
`slat-in`) and delays 150/250/300, a teal palette, and static contact-page styles. They were dropped when
`docs/` was removed and can be recovered from git history at commit `4549cf4`. The v1 theme (six-product
range, stats, testimonials, Cinzel fonts, top bar) is in git history before the v2 redesign.

# Known issues

1. **`screenshot.php` is a stub** — WordPress wants a 1200x900 `screenshot.png`. Delete the `.php`
   and add a real PNG, or the theme shows no thumbnail in Appearance → Themes.
2. **Low-resolution photos** — most real product/service photos are 500px wide (IndiaMART size), so
   they look soft on large retina screens. Replace them with larger originals when the client has them.

# Placeholder content — never present this as fact

The invented filler from the first build (stats, testimonials, fake "Plot No. XX" address, fake map,
"ISO Compliant", NPPO-registered kilns) has been removed. The address reads just "Gujarat, India" and the
client does not want a map; there are no business hours and no "quote within 24 hours" promise (removed at
the client's request — don't add them back). Delivery to Gujarat, Rajasthan and Jaipur is confirmed by the
client. Still unconfirmed:

- ISPM 15 heat treatment / IPPC marking and treatment records (Home, Services, ISPM 15 page, footer badge)
- "Strong, seasoned wood" and sample pallets on request
- Customer reviews: none received yet (see `inc/reviews.php`)

The phone (+91 99310 60355), email (Erawat005@gmail.com), LinkedIn link and WhatsApp link now in the
header, footer and contact page are no longer the original invented values and appear to be real
client-supplied details — confirm with the client before relying on them.

# Deployment (Hostinger)

- Pushing to `main` with changes under `erawat-enterprise/` triggers the GitHub Action in
  `.github/workflows/deploy.yml`. It uploads the theme over FTP to a Hostinger FTP account rooted at
  `public_html/wp-content/themes/erawat-enterprise` (`server-dir: ./`), using the repo secrets
  `FTP_SERVER`, `FTP_USERNAME` and `FTP_PASSWORD`. It can also be run manually from the repo's Actions tab.
- Only theme files deploy. Pages, menus and settings live in the WordPress database and are edited in
  wp-admin on the live site — they are not part of the repo.
- First activation of the theme on the live site runs `after_switch_theme` and creates the six pages.
  After that: create a menu under Appearance → Menus and assign it to **Primary Navigation**; confirm
  Settings → Reading shows Home as the static front page.
- `wp_mail()` needs SMTP configured on Hostinger — without it the contact form reports success paths
  but no mail arrives. The recipient is Settings → General → Administration Email Address.
- Never commit `.DS_Store`; the deploy workflow also excludes it.

# Repository etiquette

- Work on the `Development` branch. `main` is production: merging or pushing to `main` deploys to the
  live site, so only do that deliberately.
- There is no zip to build or commit; deployment is the GitHub Action.
- Don't create extra branches or worktrees for this project unless asked.
- Never commit `.DS_Store` files (they are gitignored).
