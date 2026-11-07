# Theme CSS & JavaScript conventions

Moved out of `CLAUDE.md` to keep it short. Read this before editing anything in
`erawat-enterprise/assets/css/` or `erawat-enterprise/assets/js/`, or templates that use their classes / data attributes.

## CSS

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
  Erawat has permission to use them. `product-custom-box-pallet.jpg` (since 2026-10-31 the workshop image of a
  box with foam inserts — same file as `image-originals/detail-custom.png`), the `banner-*.jpg` and
  `service-heattreatment.jpg` are AI-generated. `hero.jpg` (stacks of wooden pallets in a warehouse) is a real
  photo the user supplied on 2026-11-05; its original is `image-originals/hero.webp`. It is only 1004px wide. Don't add photos copied from other companies' sites
  without the user confirming permission. Most real photos are only 500px wide — don't use them as
  full-width backgrounds.

## JavaScript

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

## Dropped work

The v2 look (Outfit / Open Sans / Playfair fonts, green primary buttons, bento product cards with 3D
tilt and glare, button sheen sweep, infinite WhatsApp pulse, navy/cream full-width sections) was replaced by
the v3 "Premium" redesign; it is in git history before that commit.

An earlier static mirror (`docs/`) had extras the theme never used: a 3D rotating hero crate
(`.hero-crate` / `.crate-3d`), extra `data-aos` variants (`box-reveal`, `stamp`, `weight-drop`,
`slat-in`) and delays 150/250/300, a teal palette, and static contact-page styles. They were dropped when
`docs/` was removed and can be recovered from git history at commit `4549cf4`. The v1 theme (six-product
range, stats, testimonials, Cinzel fonts, top bar) is in git history before the v2 redesign.
