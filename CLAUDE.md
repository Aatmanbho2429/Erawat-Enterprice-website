# Project overview

Marketing website for **Erawat Enterprise**, a Jaipur (Rajasthan, India) maker of wooden solar pallets and box
pallets for solar module manufacturers and EPC companies, delivering anywhere in India and worldwide. Gujarat /
Rajasthan / Jaipur are named **for SEO only**, always as "across India and worldwide, including Gujarat,
Rajasthan and Jaipur" — never
as a limit on where Erawat delivers, and never "based in" or "made in" Gujarat. Only four products: Solar Pallet, Box Pallet,
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
  returns a page URL with trailing slash. The address is `ERAWAT_ADDRESS_STREET` / `_CITY` / `_REGION` /
  `_POSTCODE`; `erawat_address_html()` prints it as two escaped lines (footer and contact panel, in an
  `<address>`), and the LocalBusiness schema in `inc/seo.php` reads the same constants.
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
- `inc/enquiries.php` — every submission is saved **before** the email is sent, as a private
  `erawat_enquiry` post (wp-admin → **Enquiries**; Editors/Admins only, no "Add New", no front-end URL,
  not in REST or sitemaps). Fields are post meta `_erawat_{name|phone|email|company|product|quantity|location}`,
  the message is `post_content`, and `_erawat_mail` records `sent` / `failed`. The email links to the saved
  enquiry. The form reports success if the enquiry was saved **or** emailed, so a mail outage loses nothing.
- Nav menus register as `primary` and `footer`. If no menu is assigned, `erawat_fallback_menu()` and
  `erawat_fallback_mobile_menu()` (both defined at the bottom of `header.php`) render a hardcoded list
  (Home, About Us, Products, Services, ISPM 15, Contact). Both menus work with the pill-nav indicator.
  The mobile menu takes over at **1100px** (CSS `max-width: 1100px` block and `MOBILE_BP` in the JS — change
  both together).
- Logo: `the_custom_logo()` if set in the Customizer, otherwise `assets/images/logo-erawat.png` (navy);
  `logo-erawat-white.png` is used in the footer; `favicon.png` is the browser-tab icon.
- `inc/setup-pages.php` creates the six pages on `after_switch_theme` and sets Home as the front page.

# CSS, JavaScript and dropped work

Full conventions are in `content/theme-css-js.md`. Read it before any CSS/JS change. The essentials:
- All styles live in `assets/css/erawat-style.css`; root `style.css` holds only the theme header and a reset.
- Use the `:root` design tokens only (no raw hex outside `:root`); class names are BEM-ish.
- `assets/js/erawat-script.js` is one vanilla IIFE: no jQuery. Keep the fixed element IDs it relies on.
- Scroll animation is hand-rolled `data-aos`; only the listed values have CSS (others leave elements invisible).
- Animate only `transform`, `opacity`, `clip-path`, `filter`; respect `prefers-reduced-motion`.

# Known issues

1. **`screenshot.php` is a stub** — WordPress wants a 1200x900 `screenshot.png`. Delete the `.php`
   and add a real PNG, or the theme shows no thumbnail in Appearance → Themes.
2. **Low-resolution photos** — most real product/service photos are 500px wide (IndiaMART size), so
   they look soft on large retina screens. Replace them with larger originals when the client has them.

# Placeholder content — never present this as fact

The invented filler from the first build (stats, testimonials, fake "Plot No. XX" address, fake map,
"ISO Compliant", NPPO-registered kilns) has been removed. The address is the real one from the client's
business card (Khara No. 288/2, Bawdi, Sarna Doongar, Jhotwara, Jaipur, Rajasthan 302012; added
2026-10-31) and the client does not want a map; there are no business hours and no "quote within 24 hours" promise (removed at
the client's request — don't add them back). The client delivers anywhere in India and worldwide (confirmed
2026-10-31); Gujarat, Rajasthan and Jaipur are SEO focus areas, not limits. Still unconfirmed:

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
- `wp_mail()` goes out through the **WP Mail SMTP** plugin using **Hostinger Email** (Mailer "Other SMTP",
  `smtp.hostinger.com`, SSL 465, a mailbox on erawatenterprise.com; Resend was dropped on 2026-10-31). The
  domain's MX/SPF/DKIM/DMARC are already Hostinger's. If mail fails, enquiries still land in wp-admin →
  Enquiries (marked "Not sent"). The recipient is Settings → General → Administration Email Address.
- Never commit `.DS_Store`; the deploy workflow also excludes it.

# Repository etiquette

- Work on the `Development` branch. `main` is production: merging or pushing to `main` deploys to the
  live site, so only do that deliberately.
- There is no zip to build or commit; deployment is the GitHub Action.
- Don't create extra branches or worktrees for this project unless asked.
- Never commit `.DS_Store` files (they are gitignored).
