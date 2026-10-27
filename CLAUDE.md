# Project overview

Marketing website for **Erawat Enterprise**, a Gujarat (India) manufacturer of ISPM 15 certified
wooden packaging — boxes, crates, and pallets for solar panel manufacturers, EPC contractors, and
industrial exporters. Six pages: Home, About Us, Product Range, Services, ISPM 15 Packing, Contact Us.

The deliverable is a **WordPress theme**. Development is local (WordPress Playground, see below);
pushing to `main` deploys the theme to Hostinger through a GitHub Action. There is no staging server.

# Repository layout

- `erawat-enterprise/` — **the WordPress theme. The only copy of the site code. Make changes here.**
- `blueprint.json` + `dev.sh` — local WordPress preview (WordPress Playground) that mounts and activates the theme.
- `.github/workflows/deploy.yml` — deploys `erawat-enterprise/` to Hostinger over FTP on push to `main`.
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
  `erawat_page_banner( $title, $subtitle, $bg_class )` (e.g. `'banner--products'`). It renders the
  page banner and then the breadcrumbs — do not add either markup by hand.
- Prefix everything with `erawat_` (functions), `erawat-` (asset handles), `ERAWAT_` (constants).
  Text domain is `erawat-enterprise`.
- Paths use the `ERAWAT_DIR` / `ERAWAT_URI` constants, defined in `functions.php`.
- Escape all output: `esc_html()`, `esc_url()`, `esc_attr()`, `sanitize_text_field()` on input.
- Enqueue assets only inside `erawat_enqueue_assets()`. Asset versions come from `filemtime()` of each
  file, so browser and Hostinger caches bust automatically — there is no version to bump by hand.
- Contact form: AJAX action `erawat_contact`, nonce `erawat_nonce`, handled by
  `erawat_handle_contact_form()`, delivered with `wp_mail()` to the site's `admin_email`.
- Nav menus register as `primary` and `footer`. If no menu is assigned, `erawat_fallback_menu()` and
  `erawat_fallback_mobile_menu()` (both defined at the bottom of `header.php`) render a hardcoded list.
- `inc/setup-pages.php` creates the six pages on `after_switch_theme` and sets Home as the front page.

# CSS

- All real styles live in `assets/css/erawat-style.css`. Root `style.css` holds only the WordPress
  theme header and a small reset — keep it that way.
- Prefer the design tokens in `:root` over raw hex values (the existing stylesheet still has some raw hex
  in gradients; don't add more). Palette: `--navy`, `--forest`, `--wood`, `--cream` plus `-dark` /
  `-light` / `-pale` / `-xlight` variants, and neutrals (`--white`, `--black`, `--gray*`). There is no
  `--teal` in the theme.
- Typography tokens: `--font-heading` (Copperplate → Cinzel fallback), `--font-body` (Open Sans),
  `--font-accent` (Playfair Display). Also `--space-*`, `--radius-*`, `--shadow-*`, `--transition`.
- Class naming is BEM-ish: `block__element--modifier` (`site-header__inner`, `btn--primary`,
  `product-card__img--solar`, `section--navy`).
- Scroll animation is hand-rolled, not the AOS library: add `data-aos="fade-up"` / `fade-left` /
  `fade-right` (optionally `data-aos-delay="100"` or `"200"`), and the JS adds `.aos-animate` to trigger
  a CSS transition. Only those values have CSS — any other `data-aos` value leaves the element invisible.
- Images: the optimised photos are real JPGs in `assets/images/`, referenced from the stylesheet
  (photography section at the bottom, `url('../images/...')`). The stylesheet no longer hotlinks Unsplash.
  Originals are in `image-originals/`.

# JavaScript

- `assets/js/erawat-script.js`, a single IIFE, `'use strict'`, vanilla DOM APIs. It is enqueued with
  a `jquery` dependency but does not actually use jQuery — do not introduce jQuery usage.
- Behavior is wired to fixed element IDs: `site-header`, `mobile-menu-toggle`, `mobile-nav`,
  `mobile-nav-overlay`, `back-to-top`. Keep these IDs when editing templates.
- Forms are wired by explicit ID pairs at the bottom of the file:
  `initForm('home-enquiry-form', 'home-form-message')` and
  `initForm('main-contact-form', 'contact-form-message')`. A new form needs both IDs on the markup
  **and** its own `initForm()` call, or it will silently do nothing.
- FAQ accordions use `.faq-question` with the answer as the immediate next sibling.

# Dropped work

An earlier static mirror (`docs/`) had extras the theme never used: a 3D rotating hero crate
(`.hero-crate` / `.crate-3d`), extra `data-aos` variants (`box-reveal`, `stamp`, `weight-drop`,
`slat-in`) and delays 150/250/300, a teal palette, and static contact-page styles. They were dropped when
`docs/` was removed and can be recovered from git history at commit `4549cf4`.

# Known issues

1. **`screenshot.php` is a stub** — WordPress wants a 1200x900 `screenshot.png`. Delete the `.php`
   and add a real PNG, or the theme shows no thumbnail in Appearance → Themes.
2. **Orphaned CSS** — the client-logos block (`.clients-logos`, `.client-logo`) has styles but no
   template outputs it any more; remove it only after grepping the templates again.

# Placeholder content — never present this as fact

Invented filler from the initial build. Do not cite it as real, and replace it with client-supplied data
before launch:

- Address "Plot No. XX, Industrial Area, Gujarat — 380001" (footer and contact page)
- Stats: "18+ years", "500+ satisfied clients", "10,000+ projects", "Trusted Since 2005"
- All three testimonials (Rajesh Kumar, Priya Mehta, Anand Shah) on the home page
- Certification claims (ISPM 15, NPPO-registered kilns, "ISO Compliant") — unverified
- The contact page map is a `.map-placeholder` div, not a real embed

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
