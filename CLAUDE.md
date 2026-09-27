# Project overview

Marketing website for **Erawat Enterprise**, a Gujarat (India) manufacturer of ISPM 15 certified
wooden packaging — boxes, crates, and pallets for solar panel manufacturers, EPC contractors, and
industrial exporters. Six pages: Home, About Us, Product Range, Services, ISPM 15 Packing, Contact Us.

The deliverable is a **WordPress theme**, installed on Hostinger WordPress hosting as a theme zip.
Development is local; there is no staging server and no CI.

# Repository layout

- `erawat-enterprise/` — **the WordPress theme. This is the source of truth. Make changes here.**
- `docs/` — standalone static HTML mirror (6 `.html` files, no PHP). Useful for previewing markup in a
  browser without WordPress. Its CSS and JS are **ahead of the theme** — see "Porting from docs/".
- `erawat-github-preview/` — stale leftover, assets only, no HTML. Do not edit it or add files to it.
- `erawat-enterprise-theme.zip` — build output. Regenerate it; never hand-edit it.

No build system: no `package.json`, no bundler, no tests, no linter. Plain PHP, CSS, and vanilla JS.

# Bash commands

- Build the theme zip (run from the repo root):
  `zip -r "erawat-enterprise-theme.zip" "erawat-enterprise/" -x "*.DS_Store"`
- Preview the static mirror: `cd docs && python3 -m http.server 8000`
- Lint PHP syntax after editing a template: `php -l erawat-enterprise/<file>.php`

# WordPress theme conventions

- Every template starts with `get_header();` and ends with `get_footer();`.
- Page templates resolve by **slug** through the WordPress template hierarchy (`page-{slug}.php`).
  They carry no `Template Name:` header, so the filename must match the page slug exactly.
- Right after `get_header();`, page templates call
  `erawat_page_banner( $title, $subtitle, $bg_class )` (e.g. `'banner--products'`). It renders the
  page banner and then the breadcrumbs — do not add either markup by hand.
- Prefix everything with `erawat_` (functions), `erawat-` (asset handles), `ERAWAT_` (constants).
  Text domain is `erawat-enterprise`.
- Paths use the `ERAWAT_DIR` / `ERAWAT_URI` constants, defined in `functions.php`.
- Escape all output: `esc_html()`, `esc_url()`, `esc_attr()`, `sanitize_text_field()` on input.
- Enqueue assets only inside `erawat_enqueue_assets()`. Bump `ERAWAT_VERSION` in `functions.php`
  after changing CSS or JS so Hostinger's cache and browsers pick it up.
- Contact form: AJAX action `erawat_contact`, nonce `erawat_nonce`, handled by
  `erawat_handle_contact_form()`, delivered with `wp_mail()` to the site's `admin_email`.
- Nav menus register as `primary` and `footer`. If no menu is assigned, `erawat_fallback_menu()` and
  `erawat_fallback_mobile_menu()` (both defined at the bottom of `header.php`) render a hardcoded list.
- `inc/setup-pages.php` creates the six pages on `after_switch_theme` and sets Home as the front page.

# CSS

- All real styles live in `assets/css/erawat-style.css`. Root `style.css` holds only the WordPress
  theme header and a small reset — keep it that way.
- Use the design tokens in `:root`; never write a raw hex value. Brand palette:
  `--navy`, `--forest`, `--teal`, `--wood`, `--cream`, plus `-dark` / `-light` / `-pale` variants.
- Typography tokens: `--font-heading` (Copperplate → Cinzel fallback), `--font-body` (Open Sans),
  `--font-accent` (Playfair Display). Also `--space-*`, `--radius-*`, `--shadow-*`, `--transition`.
- Class naming is BEM-ish: `block__element--modifier` (`site-header__inner`, `btn--primary`,
  `product-card__img--solar`, `section--navy`).
- Scroll animation is hand-rolled, not the AOS library: add `data-aos="fade-up"` (optionally
  `data-aos-delay="100"`), and the JS adds `.aos-animate` to trigger a CSS transition.
- There are **no local image files** — `assets/images/` is empty and section backgrounds hotlink
  Unsplash URLs from the stylesheet. Replace these with real photos of Erawat's products before launch.

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

# Porting from docs/

`docs/assets/` is newer than the theme's copy and diverged (CSS: 2950 vs 2596 lines). The static
mirror has extra work that the theme is missing:

- Extra `data-aos` variants: `box-reveal`, `stamp`, `weight-drop`, `slat-in`, and `data-aos-delay`
  values `150` / `250` / `300`. Using these attributes in PHP without porting the CSS leaves elements
  stuck invisible.
- A 3D rotating crate on the hero (`.hero-crate` / `.crate-3d`) and several keyframe animations.
- Rewritten product-card image styles (`.product-card__img` base class plus modifiers, replacing
  per-variant rules).

When changing shared CSS or JS, apply the change to the theme copy; port the corresponding `docs/`
work over rather than letting the two drift further apart.

# Known issues — fix these before deploying

1. **`functions.php` fatal on PHP 8**: line 11 does `require_once ERAWAT_DIR . '/inc/setup-pages.php';`
   but `ERAWAT_DIR` is not defined until line 12. Move the two `define()` calls above the `require_once`.
2. **Four of five page templates never load.** WordPress looks for `page-{slug}.php`, and the slugs
   created by `inc/setup-pages.php` don't match the filenames:
   - `page-about.php` → needs `page-about-us.php`
   - `page-products.php` → needs `page-product-range.php`
   - `page-contact.php` → needs `page-contact-us.php`
   - `page-ispm15.php` → needs `page-ispm-15-packing.php`
   - `page-services.php` is the only one that resolves today.
   Rename them, or add a `Template Name:` header to each and assign it per page in the editor.
   Until then those pages fall through to `page.php` and render a banner with empty content.
3. **`screenshot.php` is a stub** — WordPress wants a 1200x900 `screenshot.png`. Delete the `.php`
   and add a real PNG, or the theme shows no thumbnail in Appearance → Themes.

# Placeholder content — never present this as fact

All of the following is invented filler from the initial build. Do not cite it as real, and replace it
with client-supplied data before launch:

- Phone `+91 98765 43210` / `+91 98765 43211`; emails `info@` and `sales@erawatenterprise.com`
- Address "Plot No. XX, Industrial Area, Gujarat — 380001"
- Stats: "18+ years", "500+ satisfied clients", "10,000+ projects", "Trusted Since 2005"
- All three testimonials (Rajesh Kumar, Priya Mehta, Anand Shah) and all eight client logos
- Certification claims (ISPM 15, NPPO-registered kilns, "ISO Compliant") — unverified
- Social links are all `href="#"`; the contact page map is a `.map-placeholder` div, not a real embed

# Hostinger deployment

- Upload via wp-admin → Appearance → Themes → Add New → Upload Theme → `erawat-enterprise-theme.zip`
  (or hPanel's WordPress → Themes). Activating it runs `after_switch_theme` and creates the six pages.
- After activation: create a menu under Appearance → Menus and assign it to **Primary Navigation**;
  confirm Settings → Reading shows Home as the static front page.
- `wp_mail()` needs SMTP configured on Hostinger — without it the contact form reports success paths
  but no mail arrives. The recipient is Settings → General → Administration Email Address.
- Only `erawat-enterprise/` belongs on the server. Never upload `docs/` or `erawat-github-preview/`.

# Repository etiquette

- Work locally on `main`. Do not create branches or worktrees for this project.
- Regenerate `erawat-enterprise-theme.zip` after theme changes and commit it alongside the source.
- Never commit `.DS_Store` files.
