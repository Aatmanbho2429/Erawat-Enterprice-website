# Development plan — Erawat Enterprise v3 "Premium" redesign

Goal: make the existing six-page WordPress theme look **luxurious and premium** — the feel of a high-end
architecture or design studio site — while keeping the same brand colours, the same copy, and short pages.
This is a **visual redesign**: layout, typography, edges, motion. No content or feature changes.

Read `CLAUDE.md` first. Every rule there still applies unless this plan explicitly says otherwise.

---

## 0. Ground rules (non-negotiable)

- Work in `erawat-enterprise/` only (plus `CLAUDE.md` at the end). Branch `Development`.
  **Do not commit, push, merge, or create branches/worktrees.**
- Plain PHP / CSS / vanilla JS. **No new JS or CSS libraries**, no jQuery, no build step. Changing the Google
  Fonts request in `erawat_enqueue_assets()` is allowed (and required — see §2).
- **No copy changes.** Every visible sentence, heading, label and button text stays exactly as it is now (it
  comes from `content/site-content.md`). You may split existing text into `<span>`s for styling, and you may
  add purely structural micro-labels: index numbers (`01`–`04`), `aria-*` text, and decorative `aria-hidden`
  elements (e.g. the footer wordmark "ERAWAT"). **Never add new claims or facts** — no years, stats, counts,
  certifications, "premium quality" slogans, testimonials, addresses or locations.
- Locations (Gujarat, Rajasthan, Jaipur) stay SEO-only: do not add new visible location labels anywhere.
- Do **not** touch `inc/reviews.php`, `inc/seo.php`, `inc/setup-pages.php`, `inc/products.php` data, the
  contact-form PHP handler, `content/`, or the image files. No new images.
- Keep every fixed ID: `site-header`, `scroll-progress`, `mobile-menu-toggle`, `mobile-nav`,
  `mobile-nav-overlay`, `back-to-top`, `main-contact-form`, `contact-form-message`, and the FAQ
  question → next-sibling-answer structure. Keep form field `name`s, `required`s and label `for`s.
- Keep template conventions: `get_header()` / `get_footer()`, `erawat_page_banner()` (its signature stays the
  same; its markup may change), one `<h1>` per page, `get_template_part()` for shared parts, escape all output.
- Colours: **the same palette** — navy, forest, wood, cream, neutrals. No raw hex outside `:root`. You may add
  **one** extra shade inside the wood family (`--wood-glow`, see §1) plus its `-rgb` triplet. Nothing else new.
- Keep pages short. "Premium" comes from type, framing and motion — **not** from huge empty bands.
  Sections keep `--section-y` spacing.
- Everything must still work with JS off (content fully visible), with `prefers-reduced-motion: reduce`
  (no movement), on touch screens, and at 375 px wide with no horizontal scroll.
- Accessibility: WCAG AA text contrast, visible focus rings, 44 px minimum touch targets.

---

## 1. Design direction

### What we take from each reference

| Reference | Take this |
|---|---|
| **MR L Production (nav bar)** | Floating **pill navigation**: links inside a soft frosted pill, the active item is a dark filled pill. Round icon button (phone) and a dark pill CTA that ends in a **light circle with an arrow**. |
| **Glasshaven** | Dark, moody full-bleed photo hero inside a **rounded panel**, **giant extended uppercase** headline, tiny uppercase corner labels. **Numbered 01–04 cards** with photos. **Hairline "spec table"** rows (label left, value right, thin dividers). Restrained palette, high contrast. |
| **Nura (About)** | Page content sits in **rounded panels inset from the viewport edge** (a "framed" layout). Photo banner panel with a big centred title. Small **tag labels** ("Principles") instead of loud eyebrows. **Two-tone statement paragraph** (part dark, part muted). Airy cards with a small **dark square icon tile**, title and text pushed to the bottom. |

### Extra patterns to add (common on award-level luxury sites)

- **Framed layout**: cream page canvas; hero, banners, dark sections, the CTA and the footer are rounded panels
  with a small gutter all round.
- **Masked line reveals**: heading words slide up from behind a mask instead of fading.
- **Sliding nav indicator**: the dark pill glides between nav items on hover and settles back on the active one.
- **Scroll-fill statement**: one large statement whose words go from muted to full colour as you scroll.
- **Giant footer wordmark**: "ERAWAT" set enormous and low-contrast across the bottom of the footer.
- **Arrow-circle buttons** with a smooth arrow swap on hover.
- Slow, expo-out easing everywhere; nothing bouncy, nothing fast.

### Colour roles (same palette, used more deliberately)

| Role | Token |
|---|---|
| Page canvas | `--cream` |
| Light panels / cards | `--white` with `--hairline` borders |
| Dark panels, primary buttons, headings | `--navy-dark` (body text on dark: `rgba(var(--white-rgb), .78)`) |
| Signature dark accent panel (CTA) | `--forest-dark` |
| Luxe warm accent: index numbers, serif accent words on dark, small dots, hairline highlights | `--wood-glow` (**new**, `#C9946B`, ≈5.8:1 on `--navy-dark`) and `--wood` / `--wood-light` on light backgrounds |
| Small positive accents: check icons, tag dots, WhatsApp, form success | `--forest` / `--forest-light` |

Add to `:root`: `--wood-glow: #C9946B;` and `--wood-glow-rgb: 201, 148, 107;`. Green stays in the theme as an
accent and as the CTA panel — it is no longer the default button colour.

---

## 2. Design system (do this first)

### Fonts — replace the Google Fonts request in `erawat_enqueue_assets()`

```
https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@100..125,500..800&family=Manrope:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap
```

| Token | Font | Use |
|---|---|---|
| `--font-heading` | **Archivo**, expanded (`font-stretch: 125%`) | H1, H2, index numbers, big stats, marquee, footer wordmark. **Uppercase via CSS** (`text-transform`), source text stays as-is. Weight 600–750, line-height .92–1.02, letter-spacing −0.015em. |
| `--font-body` | **Manrope** | Body, nav, buttons, H3 (sentence case, weight 600), labels. |
| `--font-accent` | **Instrument Serif** (italic) | Accent words: hero "Built Around Your Panels", one phrase in a few headings, review quotes, photo-caption titles. Never uppercase. |

Add `--heading-stretch: 125%` and use `font-stretch: var(--heading-stretch)`. Verify in the browser that the
expanded width actually renders (computed `font-stretch` and a visual check); if it doesn't, fall back to
`font-variation-settings: 'wdth' 125`. Remove Outfit, Open Sans and Playfair Display.

Extended uppercase is wide — size it carefully:

- Hero display: `clamp(2.4rem, 7vw, 6.25rem)`, line-height .92.
- Banner H1: `clamp(2rem, 5.2vw, 4.25rem)`.
- Section H2: `clamp(1.5rem, 3.4vw, 2.75rem)`.
- H2 inside half-width columns (split / product rows / custom cards / service cards): a smaller step,
  `clamp(1.3rem, 2.4vw, 2rem)`. **No H2 may wrap to more than 3 lines at any width — check every one.**

### Tokens to change / add in `:root`

- Container `max-width: 1280px`.
- `--frame: clamp(8px, 1.1vw, 16px)` — the gutter around panels and between stacked panels.
- Radii: `--radius-sm 10px`, `--radius-md 14px`, `--radius-lg 20px`, `--radius-xl 28px` (panels; drop to
  20px under 640px), `--radius-pill 999px`.
- Shadows: softer and rarer. Luxury here is hairlines plus contrast, not drop shadows. Keep `--shadow-*` names
  but lower their strength; most cards use a hairline only.
- `--hairline-light: 1px solid rgba(var(--white-rgb), .12)` for dark panels.
- Motion: `--ease: cubic-bezier(.16, 1, .3, 1)` (expo-out), `--ease-in-out: cubic-bezier(.65, 0, .35, 1)`,
  `--dur-reveal: 1.1s`, `--dur-hover: .6s`, `--transition: var(--dur-hover) var(--ease)`.

### Surfaces (panels)

Replace the current `.section--cream` / `.section--navy` with explicit panel modifiers and update the templates:

- `.section` — sits on the cream canvas, no panel.
- `.section--panel` — white rounded panel: `margin: 0 var(--frame)`, `border-radius: var(--radius-xl)`,
  hairline border.
- `.section--dark` — `--navy-dark` rounded panel, light text, hairlines `--hairline-light`. A very subtle grain
  overlay (inline SVG `feTurbulence` data URI in a `::before`, opacity ≈ .05, no colours in the SVG) adds a
  tactile finish.
- Stacked panels are separated by `--frame`. Panels never touch the viewport edge.

### Components

- **Tag (eyebrow)**: restyle `.eyebrow` as a Nura-style tag — small rounded rectangle (`--radius-sm`), faint
  fill (`rgba(var(--navy-dark-rgb), .05)`) and hairline border, Manrope 600, `.7rem`, uppercase, letter-spacing
  .16em, a 6 px dot in `--forest` before the text. `.eyebrow--light` for dark backgrounds (white 8% fill, cream
  text, `--wood-glow` dot). Drop the growing line.
- **Buttons**: pill buttons, Manrope 600. Variant with a trailing circle: `<span class="btn__circle">` holding
  the arrow icon (update every arrow button in the templates). Hover: the arrow slides out to the right while a
  second copy slides in from the left (two `<i>` inside the circle, or one icon plus a `::before`), the circle
  scales to 1.06, the pill darkens slightly. No sheen sweep, no jump.
  - `btn--primary`: `--navy-dark` pill, cream text, cream circle with a navy arrow.
  - `btn--white` (on dark panels): cream pill, navy text, navy circle with a cream arrow.
  - `btn--outline-white` / a light-background ghost variant: hairline border, transparent; on hover a fill wipes
    in from the left.
  - `btn--navy` becomes the same as primary or is removed — keep the set small and consistent.
- **Index number** `.index-num`: Archivo expanded, wood colour, used for 01–04 on product cards, service cards,
  process steps, product features and FAQ items.
- **Spec list** `.spec-list`: Glasshaven-style hairline rows — `display: grid` rows with a top hairline, small
  index or check at left, text, optional value at right. Use for product features, service bullets, vertical
  process steps and the contact details list.
- **Statement** `.statement`: large Manrope 500 text (`clamp(1.25rem, 2.4vw, 1.9rem)`, line-height 1.35,
  `--navy-dark`) where `<span class="statement__muted">` is `--gray-mid`. On dark panels: cream plus
  `rgba(white, .45)`.
- **Photo frame**: every product / service / about photo sits in a rounded frame with a `--cream-dark`
  background, `object-fit: cover`, and a very light unifying grade (e.g. `filter: saturate(.92) contrast(1.03)`).
  The product photos have mixed backgrounds (white studio, grey floor, site shot); check each card visually and
  set `object-position` per image where a crop cuts the pallet off. Most real photos are only 500–1040 px wide:
  **never show one wider than about 640 px.**
- **Glass label**: the frosted `.photo-caption` becomes a slimmer glass label — white 72% plus blur, hairline,
  `--radius-md`, title in Instrument Serif.

---

## 3. Global pieces

### Header (`header.php`)

- Sticky, on a frosted cream background; it sits **above** the hero panel, not over it (the logo is navy).
  84 px tall, shrinking to 68 px when `.scrolled`.
- Layout: logo left · **pill nav** centred · round phone button (44 px circle, icon only, `aria-label` with the
  number) · "Get a Quote" primary pill with an arrow circle.
- Pill nav: the `<ul class="nav-menu">` gets a frosted white pill container (white 60% plus blur, hairline,
  6 px padding). Links: Manrope 500, `.92rem`, pill-shaped hit areas. With JS, a `.nav-indicator` element
  (injected by JS) is a `--navy-dark` pill absolutely positioned behind the active link; on hover it **glides**
  to the hovered link (`transform` + `width`, `.5s var(--ease)`) and the link text turns cream; on
  `pointerleave` it returns to the active link. Without JS: `.current-menu-item > a` simply gets the dark pill
  background. Must work with both `wp_nav_menu()` output and `erawat_fallback_menu()`.
- Check widths 961–1200 px: nothing may wrap or overlap. If six items plus CTA don't fit, raise the mobile
  breakpoint (e.g. to 1100 px) — and **update the hardcoded `960` in the JS resize handler to match**.
- `#scroll-progress`: keep it; make it 2 px, `--wood-glow`.

### Mobile menu

- Hamburger becomes a 44 px round button (two lines morphing to an X).
- `#mobile-nav` becomes a near-full-screen **rounded navy-dark panel** inset by `--frame` below the header
  (still outside `<header>`). Links in Archivo expanded uppercase, `clamp(1.6rem, 7vw, 2.4rem)`, hairline
  dividers, staggered rise-in when it opens (CSS using `--i`). Contact link and quote button at the bottom.
  Keep the focus trap, Escape, overlay click and `aria-*` behaviour exactly.

### CTA band + footer (`footer.php`)

- CTA band: a rounded **`--forest-dark` panel** (inset by `--frame`). Heading in giant extended uppercase on the
  left; text below; actions on the right (`btn--white` with circle plus an outline "Call Now"). A slow drifting
  radial highlight (`--wood-glow` at about 18%, 20 s alternate, transform only) gives depth.
- Footer: a rounded **`--navy-dark` panel** with a bottom margin of `--frame`. Keep all columns and content. Column
  titles become small uppercase tags; links get a hover underline that draws from the left. Badges become
  hairline tags. Across the bottom, a **decorative wordmark** `ERAWAT` (`aria-hidden="true"`, Archivo expanded
  800, about `19vw`, `rgba(white, .05)` or an outlined stroke, clipped to the panel) sits above the copyright
  row. Keep `.footer-areas` (SEO line) as a quiet small line.
- WhatsApp float: keep the link and label; 52 px, forest. **Remove the infinite pulse ring** (it reads cheap) —
  one gentle pulse after load at most.
- Back-to-top: 44 px circle with a thin **progress ring** (inline SVG circle, `stroke-dashoffset` set from the
  existing rAF scroll handler). The `back-to-top` ID and behaviour stay.

---

## 4. Pages

### Home (`front-page.php`)

1. **Hero** — a rounded panel, `min-height: min(calc(100svh - var(--header-h) - 2 * var(--frame)), 860px)`, with
   `hero.jpg` (a dark warehouse with golden light — keep the content on the dark left side). Overlay: navy-dark
   gradient, heavier at the left and bottom. Slow Ken Burns (40 s, scale ≤ 1.06).
   - Top: the existing eyebrow as a light tag.
   - Title: "Solar Pallets & Box Pallets" in **giant extended uppercase cream**; "Built Around Your Panels" on
     its own line in **Instrument Serif italic**, `--wood-glow`, about 1.1× the size, sentence case. Keep
     `data-split`.
   - Bottom row (grid): left = hero text plus the two buttons (`btn--white` with circle, outline ghost);
     right = the two product cards reworked as **small glass cards side by side** (thumbnail in a rounded
     frame, name, small arrow), keeping the `[data-depth]` pointer parallax but much subtler (depth ≤ 10).
   - The three hero chips become a quiet strip of hairline-separated uppercase micro-labels along the bottom
     edge of the panel (Glasshaven corner labels). Same three texts, same icons optional.
   - Load sequence: panel `clip-path: inset(3% 3% round 40px)` → `inset(0 round var(--radius-xl))` plus the
     background scaling 1.12 → 1 (1.6 s); then title words rise; then text and buttons; then the glass cards.
     All of it under 2 s.
2. **Marquee** — no navy band. On the cream canvas: Archivo expanded uppercase, `clamp(1.5rem, 4vw, 3rem)`,
   `--navy-dark`, every second item **outlined** (`-webkit-text-stroke: 1px var(--navy-dark)`, transparent fill
   — keep a solid fallback where stroke isn't supported), separators a small `--wood` ✦ or an Instrument Serif
   "·". Slower (about 60 s). Pause on hover. Same items.
3. **What We Make** — `.section--panel`. Head row: tag plus H2 on the left, the existing one-line intro as a
   `.statement` on the right (desktop) / below (mobile). Cards: **four equal numbered cards** (Glasshaven
   services row), 4 columns ≥ 1100 px, 2 columns ≥ 640 px, 1 column (or a horizontal scroll-snap row) below.
   Each card: photo frame (aspect 4/3 or 1/1 — choose per visual check), then `01` index number, a hairline,
   the product name in small extended uppercase, the caption in gray, "View details" plus an arrow. The whole
   card is clickable (keep the cover-link pattern and focus outline). Hover: photo scales to 1.06 over 1.2 s,
   index turns `--wood`, arrow slides. **Remove the tilt, glare and sheen** from product cards; drop the
   feature/compact size difference (all four equal). Reveal: `clip-up` on the photos, staggered.
4. **Why Erawat + How It Works** — `.section--dark`. H2 "Built Strong. Sized Right. Delivered on Time." with
   "Delivered on Time." in Instrument Serif italic `--wood-glow` (span only, same text). Reasons: four dark glass
   cards (white 4% fill, `--hairline-light`, `--radius-lg`) in a 2×2 grid, each with a small **cream square icon
   tile** (Nura style) holding a navy icon, title, text. How It Works: the vertical process steps restyled as a
   **hairline spec list** (`01  Share your size — Panel dimensions…`), index numbers in `--wood-glow`, the
   hairlines draw in left → right on reveal.
5. **Reviews** — on the cream canvas. Cards: white, hairline, `--radius-lg`, the quote in **Instrument Serif**
   (`1.3rem`, `--navy-dark`), stars `--wood-light`, initials avatar navy, author in Manrope 600. Keep the
   auto-scroll / swipe behaviour and the edge fade mask. Do not change the PHP logic or data.

### Page banner (`erawat_page_banner()`, all inner pages)

Nura "About Us" style: a rounded panel, photo background with a navy-dark overlay, content **centred**:
breadcrumb as a small glass pill row, eyebrow tag, H1 in giant extended uppercase cream with `data-split`.
Height `clamp(260px, 40vh, 420px)`. On load the photo scales 1.1 → 1 (1.8 s) and the title words rise.
Light scroll parallax on the photo (§5).

### About (`page-about-us.php`)

- Who We Are: split. The photo (500 px original — cap the frame at about 520 px wide, tall rounded frame,
  `clip-up`). Body: tag, H2 (smaller step), the **first paragraph as a `.statement`** (two-tone: split the
  existing sentence, e.g. muted from "We make two things…"), the second paragraph normal. Chips become hairline
  tags.
- What We Stand For: `.section--panel`, three **Nura cards**: white on `--cream`-tinted card background
  (`rgba(var(--cream-rgb), .6)`), small `--navy-dark` square icon tile top-left with a cream icon, title and
  text pushed to the bottom (`min-height` about 220 px, flex column, `justify-content: space-between`).
- Reviews part as on Home.

### Products (`page-product-range.php`)

- Intro + jump links: the intro line as a `.statement`; the jump links as a **segmented pill bar** styled like
  the header nav pill (hairline pill container, links as pills, hover fill).
- Solar Pallet / Box Pallet rows: big `01` / `02` index number above the H2; text; **features as a hairline
  spec list** (index or check left, text); arrow-circle CTA. Photo in a rounded frame with the glass label;
  `clip-up` reveal; alternate sides as now.
- Customised cards: `.section--panel`, two cards side by side: photo frame on top, `03` / `04` index, H2
  (small step), text, options as hairline tags, arrow CTA.
- FAQ: see the FAQ pattern below.

### Services (`page-services.php`)

- Intro line as a centred `.statement`.
- Four service cards, Glasshaven numbered style, 2×2: inset photo frame (radius inside card padding), the `01`
  index number replaces the old badge, title (H2 small step), text, bullets as a compact spec list. Remove the
  overlapping icon square, or turn it into a small Nura icon tile beside the index — pick whichever looks
  cleaner.
- How It Works: horizontal steps on a `.section--dark` panel: four columns, a top hairline that draws in, big
  `01–04` index numbers in `--wood-glow`, title, text. Vertical spec list below 960 px.

### ISPM 15 (`page-ispm-15-packing.php`)

- What Is ISPM 15: split; facts become a **2×2 hairline stat grid** (no boxes; hairlines between cells),
  values in giant Archivo expanded (`56°C`, `30min`, `HT`, `IPPC`) with the unit small in `--wood`; keep the
  count-up.
- Treatment process: horizontal steps on `.section--dark` (same as Services).
- IPPC mark: refine the stamp to look **branded into wood** — `--wood-pale` field, `--wood-dark` 3 px rules,
  Archivo expanded code text; the three notes as a hairline spec list (code in an index tile, title, text).
- FAQ: see below.

### Contact (`page-contact-us.php`)

- Form card: `.section--panel` feel (white, hairline, `--radius-xl`). Fields become **underline inputs**: no box,
  a bottom hairline, label above as a small uppercase Manrope label, focus = a `--navy-dark` line that draws in
  from the left (a `::after` on a wrapper or `background-size` on the input) plus the existing focus-visible
  ring for keyboard users. `<select>` gets a custom chevron built with CSS (a rotated bordered square on a
  wrapper — **no hex inside an SVG data URI**). Submit = full-width primary pill with circle.
- Contact panel: `.section--dark` style, the contact items as a hairline spec list, links large (Archivo
  expanded or Manrope 600 at 1.2rem), hover underline draw. "We deliver to" tags as light hairline tags.

### FAQ pattern (`template-parts/faq-list.php`)

No boxed cards: a hairline-divided list. Each question: index number (`01`) in `--wood`, question in Manrope
600 `1.05rem`, a 36 px circle with a plus that rotates to an ×. The answer opens with the existing grid-rows
technique, text indented to line up with the question. Keep the button/answer sibling structure, IDs and
`aria-*`.

### 404 / `page.php` / `index.php`

404: giant outlined "404" in Archivo expanded with a navy gradient fill, title, buttons with circles.
Default page and the post list: inherit the new type and panels; keep them simple.

---

## 5. Motion system (`erawat-script.js` + CSS)

Everything stays in the single IIFE, vanilla, one rAF-throttled scroll handler. Every effect has a CSS
reduced-motion override **and** a JS `reduceMotion` guard. Fine-pointer-only effects check `finePointer`.

1. **Reveal timing**: existing `data-aos` values stay; slow them down and shorten the distances (fade-up 24 px,
   fade-left/right 28 px, zoom-in .96, blur-in 6 px), `var(--dur-reveal) var(--ease)`. Stagger 90 ms.
   `clip-up` stays as the photo reveal (inset from the bottom plus scale 1.12 → 1 over 1.4 s). Do not add new
   `data-aos` values unless needed; if you do, add the CSS, the reduced-motion override and the CLAUDE.md note.
2. **Masked word reveal** (`[data-split]`): change the split to wrap each word as
   `<span class="split-word"><span class="split-word__inner">word</span></span>`; the outer word is
   `overflow: hidden` (with a little `padding-bottom` so descenders and the serif italic aren't clipped), the
   inner word rises from `translateY(105%)`, staggered 60 ms. Hero and banner H1 reveal on load; also add
   `data-split` to the section H2s and reveal them **when they scroll into view** (observe the element itself —
   don't rely on the load-time CSS animation). Skip `.sr-only` children as now.
3. **Nav indicator** (§3): inject `.nav-indicator` into `.site-header__nav`, position it with
   `offsetLeft` / `offsetWidth` of the active / hovered link, update it on resize and after the fonts load
   (`document.fonts.ready`). No indicator if no link is current until hover; hide it again on leave.
4. **Scroll parallax** `[data-parallax]` (value = strength, e.g. `"0.08"`): applied to the hero background,
   banner backgrounds and at most a couple of split photos. Compute only for elements currently in view (track
   them with an IntersectionObserver set) inside the existing scroll handler; translateY within the frame
   (the image is scaled about 1.12 so no edges show). Off for reduced motion and below 768 px.
5. **Scroll-fill statement** `[data-scroll-fill]`: use it in exactly **one** place (Home "What We Make"
   statement or About first paragraph). JS splits words; as the element crosses the viewport, words switch from
   the muted colour to full colour in order (set a class or `--p` per word). Reduced motion / no JS: shown
   fully coloured.
6. **Buttons**: the arrow-swap hover (CSS only). Optional **magnetic** pull (≤ 6 px, fine pointer only,
   `[data-magnetic]`) on the hero and CTA-band primary buttons only.
7. **Counters**, FAQ, form, smooth anchor scroll, mobile menu: keep the behaviour; only restyle.
8. **Remove**: product-card tilt/glare/sheen CSS, and `initTilt()` if nothing uses `[data-tilt]` any more; the
   button sheen sweep; the WhatsApp infinite pulse. Update CLAUDE.md accordingly.
9. **Performance**: animate only `transform`, `opacity`, `clip-path` and `filter` (filter on small elements
   only). `will-change` only while animating. Add a `<link rel="preload" as="image">` for `hero.jpg` on the
   front page via a `wp_head` hook in `functions.php` (it's the LCP image and it's a CSS background).

---

## 6. Implementation order

Work page by page and check in the browser after each phase — don't write everything and test at the end.

1. **Design system**: fonts (functions.php), `:root` tokens, base typography, `.section` panels, tags,
   buttons with circles, spec list, statement, photo frame, reveal timing. Rewrite `erawat-style.css` in a
   clean, sectioned order (keep the existing comment style); `style.css` stays as the header plus a tiny reset.
2. **Header, mobile menu, CTA band, footer, floating buttons** (+ nav indicator JS).
3. **Home** (hero, marquee, products, why/how, reviews).
4. **Page banner** + **About**, **Products**, **Services**, **ISPM 15**, **Contact**, **404**.
5. **Motion JS**: masked split, scroll-reveal for H2s, parallax, scroll-fill, magnetic, progress ring.
6. **QA** (§7) and fix everything found.
7. **Update `CLAUDE.md`**: fonts and tokens (including `--wood-glow`, `--frame`, `--heading-stretch`, motion
   tokens), panel classes (`.section--panel`, `.section--dark` replacing `--cream` / `--navy`), new components
   (`.btn__circle`, `.index-num`, `.spec-list`, `.statement`, `.nav-indicator`), new data attributes
   (`data-parallax`, `data-scroll-fill`, `data-magnetic`), the changed `data-split` markup, removed
   features (tilt, sheen, pulse), and the mobile breakpoint if it changed. Keep CLAUDE.md's existing style.

---

## 7. QA checklist (all must pass)

Run the local site: `preview_start` with name `wordpress` (from `.claude/launch.json`, it runs `./dev.sh` →
http://127.0.0.1:9400). If the preview tools aren't available, run `./dev.sh` in the background with Bash and
use `curl` to load each page.

- [ ] All six pages + a 404 URL load with **no PHP warnings/notices/fatals** (check the server output) and **no
      console errors**.
- [ ] Screenshots look right at **1440, 1280, 1024, 768 and 375 px** for every page. At each width:
      `document.documentElement.scrollWidth <= window.innerWidth` (no horizontal scroll).
- [ ] Header at 961–1200 px: nothing wraps or overlaps. The nav indicator sits exactly behind the active link,
      glides on hover, and returns on leave. The fallback menu also works.
- [ ] Mobile menu: opens and closes, Escape closes it and returns focus, the focus trap works, overlay click
      closes it, links stagger in.
- [ ] Archivo really renders **expanded** (check the computed style and the screenshot); Instrument Serif renders
      italic; no H2 wraps to more than 3 lines.
- [ ] No product/service/about photo is shown wider than about 640 px; no crop cuts a pallet off awkwardly.
- [ ] FAQ opens/closes with correct `aria-expanded`; contact form: empty submit shows the browser's required
      validation; `?product=box-pallet` preselects the product.
- [ ] Count-ups, marquee, reviews auto-scroll (desktop) and swipe row (mobile) all work.
- [ ] JS disabled (or the `js` class removed from `<html>`): every piece of content is visible.
- [ ] Reduced motion (emulate, or reason through the CSS block + JS guards): nothing moves, everything is visible.
- [ ] Contrast spot checks: body text on cream / white / navy-dark / forest-dark, `--wood-glow` on navy-dark,
      gray captions. Focus rings visible on light and dark panels.
- [ ] `grep` the CSS: no raw hex outside `:root`; no `--teal`; no leftover selectors for removed markup.
- [ ] No visible copy changed (diff the templates' text against the current version), no new claims added.

## 8. Report back

When done, report: what changed per file, any deviation from this plan and why, anything you could not verify,
and any open questions (e.g. a photo that crops badly at every ratio). Do not commit.
