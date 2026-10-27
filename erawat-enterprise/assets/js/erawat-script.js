/**
 * Erawat Enterprise — Main JavaScript
 *
 * Vanilla JS only. Motion is CSS (transitions / keyframes) driven by classes and custom properties;
 * this file toggles classes, splits text, and runs one rAF-throttled scroll handler.
 * Every effect checks reduceMotion (and finePointer where it needs a mouse).
 */
(function () {
  'use strict';

  const MOBILE_BP    = 1100; // keep in sync with the header breakpoint in erawat-style.css
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const finePointer  = window.matchMedia('(pointer: fine)').matches;
  const hasObserver  = 'IntersectionObserver' in window;

  const header   = document.getElementById('site-header');
  const progress = document.getElementById('scroll-progress');
  const backTop  = document.getElementById('back-to-top');
  const backBar  = backTop ? backTop.querySelector('.back-to-top__bar') : null;

  /* ── Scroll-driven effects registered by the sections below ── */
  const scrollTasks = [];

  /* ── Scroll handler: header state, back-to-top + ring, progress bar, parallax, scroll-fill ── */
  let scrollTicking = false;

  function updateOnScroll() {
    const y = window.scrollY;
    const max = document.documentElement.scrollHeight - window.innerHeight;
    const ratio = max > 0 ? Math.min(Math.max(y / max, 0), 1) : 0;

    if (header) header.classList.toggle('scrolled', y > 40);
    if (backTop) backTop.classList.toggle('visible', y > 400);
    if (progress) progress.style.transform = 'scaleX(' + ratio.toFixed(4) + ')';
    if (backBar) backBar.style.strokeDashoffset = (100 - ratio * 100).toFixed(2);

    scrollTasks.forEach((task) => task());
    scrollTicking = false;
  }

  function requestScrollUpdate() {
    if (scrollTicking) return;
    scrollTicking = true;
    window.requestAnimationFrame(updateOnScroll);
  }

  window.addEventListener('scroll', requestScrollUpdate, { passive: true });
  window.addEventListener('resize', requestScrollUpdate);

  if (backTop) {
    backTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
    });
  }

  /* ── Mobile Menu ── */
  const toggle    = document.getElementById('mobile-menu-toggle');
  const mobileNav = document.getElementById('mobile-nav');
  const overlay   = document.getElementById('mobile-nav-overlay');

  function isMenuOpen() {
    return mobileNav.classList.contains('is-open');
  }

  function openMenu() {
    toggle.setAttribute('aria-expanded', 'true');
    mobileNav.classList.add('is-open');
    mobileNav.setAttribute('aria-hidden', 'false');
    overlay.classList.add('is-visible');
    document.body.classList.add('menu-open');
    const first = mobileNav.querySelector('a, button');
    if (first) window.setTimeout(() => first.focus({ preventScroll: true }), 50);
  }

  function closeMenu(returnFocus) {
    toggle.setAttribute('aria-expanded', 'false');
    mobileNav.classList.remove('is-open');
    mobileNav.setAttribute('aria-hidden', 'true');
    overlay.classList.remove('is-visible');
    document.body.classList.remove('menu-open');
    if (returnFocus) toggle.focus({ preventScroll: true });
  }

  if (toggle && mobileNav && overlay) {
    toggle.addEventListener('click', () => {
      if (isMenuOpen()) closeMenu(false);
      else openMenu();
    });
    overlay.addEventListener('click', () => closeMenu(false));

    mobileNav.addEventListener('click', (e) => {
      if (e.target.closest('a')) closeMenu(false);
    });

    document.addEventListener('keydown', (e) => {
      if (!isMenuOpen()) return;

      if (e.key === 'Escape') {
        closeMenu(true);
        return;
      }

      // Keep keyboard focus inside the open drawer and its toggle: toggle → links … → toggle.
      if (e.key === 'Tab') {
        const items = [toggle].concat(Array.from(mobileNav.querySelectorAll('a[href], button')));
        const first = items[0];
        const last  = items[items.length - 1];
        if (e.shiftKey && document.activeElement === first) {
          e.preventDefault();
          last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
          e.preventDefault();
          first.focus();
        }
      }
    });

    // Close the drawer if the viewport grows past the mobile breakpoint.
    window.addEventListener('resize', () => {
      if (window.innerWidth > MOBILE_BP && isMenuOpen()) closeMenu(false);
    });
  }

  /* ── Split text: [data-split] words are masked, then rise. Hero / banner titles reveal on load; any other
        split heading reveals when it scrolls into view. Markup: .split-word > .split-word__inner ── */
  function initSplitText() {
    if (reduceMotion) return;

    const items = Array.from(document.querySelectorAll('[data-split]'));
    if (!items.length) return;

    items.forEach((el) => {
      let index = 0;

      (function walk(node) {
        Array.from(node.childNodes).forEach((child) => {
          if (child.nodeType === 3) {
            const frag = document.createDocumentFragment();
            child.textContent.split(/(\s+)/).forEach((part) => {
              if (!part) return;
              if (/^\s+$/.test(part)) {
                frag.appendChild(document.createTextNode(' '));
              } else {
                const outer = document.createElement('span');
                const inner = document.createElement('span');
                outer.className = 'split-word';
                outer.style.setProperty('--i', index++);
                inner.className = 'split-word__inner';
                inner.textContent = part;
                outer.appendChild(inner);
                frag.appendChild(outer);
              }
            });
            node.replaceChild(frag, child);
          } else if (child.nodeType === 1 && !child.classList.contains('sr-only')) {
            walk(child);
          }
        });
      })(el);

      el.classList.add('is-split');
    });

    const onLoad = items.filter((el) => el.closest('.hero, .page-banner'));
    const onView = items.filter((el) => onLoad.indexOf(el) === -1);

    // Two frames so the hidden start state is painted before the reveal begins.
    window.requestAnimationFrame(() => window.requestAnimationFrame(() => {
      onLoad.forEach((el) => el.classList.add('is-in'));
    }));

    if (!hasObserver) {
      onView.forEach((el) => el.classList.add('is-in'));
      return;
    }
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting || entry.boundingClientRect.top < 0) {
          entry.target.classList.add('is-in');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.2, rootMargin: '0px 0px -8% 0px' });
    onView.forEach((el) => observer.observe(el));
  }
  initSplitText();

  /* ── Scroll reveal: adds .aos-animate when [data-aos] elements enter the viewport ── */
  function initReveal() {
    const elements = Array.from(document.querySelectorAll('[data-aos]'));
    if (!elements.length) return;

    // data-aos-stagger on a parent: its direct [data-aos] children get 90ms-step delays (CSS var --aos-d).
    document.querySelectorAll('[data-aos-stagger]').forEach((parent) => {
      Array.from(parent.children)
        .filter((child) => child.hasAttribute('data-aos'))
        .forEach((child, i) => {
          child.style.setProperty('--aos-d', (i * 90) + 'ms');
          child.dataset.aosStaggerDelay = String(i * 90);
        });
    });

    function reveal(el) {
      el.classList.add('aos-animate');

      // Once the entrance has finished, drop the attribute so the element returns to its normal
      // CSS (hover transitions) without the reveal's delay/transform rules getting in the way.
      const delay = parseInt(el.dataset.aosStaggerDelay || el.getAttribute('data-aos-delay') || '0', 10) || 0;
      window.setTimeout(() => {
        el.removeAttribute('data-aos');
        el.style.removeProperty('--aos-d');
      }, delay + 2600);
    }

    if (reduceMotion || !hasObserver) {
      elements.forEach((el) => el.classList.add('aos-animate'));
      return;
    }

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        // Also reveal anything already scrolled past (e.g. after jumping to an #anchor).
        if (entry.isIntersecting || entry.boundingClientRect.top < 0) {
          reveal(entry.target);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });

    elements.forEach((el) => observer.observe(el));
  }
  initReveal();

  /* ── Count-up numbers: [data-count-to] ── */
  function initCountUp() {
    const counters = Array.from(document.querySelectorAll('[data-count-to]'));
    if (!counters.length || reduceMotion || !hasObserver) return;

    counters.forEach((el) => { el.textContent = '0'; });

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const el     = entry.target;
        const target = parseInt(el.getAttribute('data-count-to'), 10) || 0;
        const start  = performance.now();
        const duration = 1600;

        (function tick(now) {
          const t = Math.min((now - start) / duration, 1);
          const eased = 1 - Math.pow(1 - t, 4);
          el.textContent = Math.round(target * eased).toLocaleString('en-IN');
          if (t < 1) window.requestAnimationFrame(tick);
        })(start);

        observer.unobserve(el);
      });
    }, { threshold: 0.5 });

    counters.forEach((el) => observer.observe(el));
  }
  initCountUp();

  /* ── Pointer parallax: [data-depth] layers in the hero drift very slightly with the mouse ── */
  function initPointerParallax() {
    const visual = document.querySelector('.hero__visual');
    if (!visual || !finePointer || reduceMotion) return;

    const hero   = visual.closest('.hero') || visual;
    const layers = Array.from(visual.querySelectorAll('[data-depth]'));
    let frame = null;

    hero.addEventListener('pointermove', (e) => {
      if (e.pointerType && e.pointerType !== 'mouse') return;
      const rect = hero.getBoundingClientRect();
      const nx = ((e.clientX - rect.left) / rect.width - 0.5) * 2;  // -1 .. 1
      const ny = ((e.clientY - rect.top) / rect.height - 0.5) * 2;

      if (frame) window.cancelAnimationFrame(frame);
      frame = window.requestAnimationFrame(() => {
        layers.forEach((layer) => {
          const depth = parseFloat(layer.getAttribute('data-depth')) || 0;
          layer.style.transform = 'translate3d(' + (-nx * depth).toFixed(1) + 'px,' + (-ny * depth).toFixed(1) + 'px,0)';
        });
      });
    });

    hero.addEventListener('pointerleave', () => {
      if (frame) window.cancelAnimationFrame(frame);
      layers.forEach((layer) => { layer.style.transform = ''; });
    });
  }
  initPointerParallax();

  /* ── Scroll parallax: [data-parallax="0.08"] backgrounds drift inside their panel (in-view elements only).
        Off for reduced motion and below 768px. ── */
  function initScrollParallax() {
    const items = Array.from(document.querySelectorAll('[data-parallax]')).map((el) => ({
      el: el,
      panel: el.parentElement,
      strength: parseFloat(el.getAttribute('data-parallax')) || 0,
      visible: false,
    }));
    if (!items.length || reduceMotion) return;

    const enabled = () => window.innerWidth >= 768;

    function apply(item) {
      if (!enabled()) {
        item.el.style.transform = '';
        return;
      }
      const rect  = item.panel.getBoundingClientRect();
      const mid   = rect.top + rect.height / 2;
      const limit = rect.height * 0.06; // the bg element overshoots its panel by 7% top and bottom
      const shift = Math.max(-limit, Math.min(limit, (window.innerHeight / 2 - mid) * item.strength));
      item.el.style.transform = 'translate3d(0,' + shift.toFixed(1) + 'px,0)';
    }

    if (hasObserver) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          const item = items.find((it) => it.panel === entry.target);
          if (item) item.visible = entry.isIntersecting;
        });
      });
      items.forEach((item) => observer.observe(item.panel));
    } else {
      items.forEach((item) => { item.visible = true; });
    }

    scrollTasks.push(() => items.forEach((item) => { if (item.visible) apply(item); }));
    items.forEach(apply);
  }
  initScrollParallax();

  /* ── Scroll-fill statement: [data-scroll-fill] words go from muted to full colour as the block crosses the screen ── */
  function initScrollFill() {
    const blocks = Array.from(document.querySelectorAll('[data-scroll-fill]'));
    if (!blocks.length || reduceMotion) return;

    const prepared = blocks.map((el) => {
      (function walk(node) {
        Array.from(node.childNodes).forEach((child) => {
          if (child.nodeType === 3) {
            const frag = document.createDocumentFragment();
            child.textContent.split(/(\s+)/).forEach((part) => {
              if (!part) return;
              if (/^\s+$/.test(part)) {
                frag.appendChild(document.createTextNode(' '));
              } else {
                const word = document.createElement('span');
                word.className = 'fill-word';
                word.textContent = part;
                frag.appendChild(word);
              }
            });
            node.replaceChild(frag, child);
          } else if (child.nodeType === 1) {
            walk(child);
          }
        });
      })(el);
      return { el: el, words: Array.from(el.querySelectorAll('.fill-word')) };
    });

    function update() {
      const vh = window.innerHeight;
      prepared.forEach((item) => {
        const rect = item.el.getBoundingClientRect();
        if (rect.bottom < -50 || rect.top > vh + 50) return;
        // starts when the block's top reaches 85% of the viewport, finishes when its bottom reaches 45%
        const p = Math.min(Math.max((vh * 0.85 - rect.top) / (vh * 0.4 + rect.height), 0), 1);
        const lit = Math.round(p * item.words.length);
        item.words.forEach((word, i) => word.classList.toggle('is-lit', i < lit));
      });
    }

    scrollTasks.push(update);
    update();
  }
  initScrollFill();

  /* ── Magnetic buttons: [data-magnetic] pulls up to 6px toward the pointer (mouse only) ── */
  function initMagnetic() {
    if (!finePointer || reduceMotion) return;

    document.querySelectorAll('[data-magnetic]').forEach((el) => {
      el.addEventListener('pointermove', (e) => {
        if (e.pointerType && e.pointerType !== 'mouse') return;
        const rect = el.getBoundingClientRect();
        const dx = (e.clientX - (rect.left + rect.width / 2)) / rect.width;
        const dy = (e.clientY - (rect.top + rect.height / 2)) / rect.height;
        el.style.transform = 'translate3d(' + (dx * 12).toFixed(1) + 'px,' + (dy * 12).toFixed(1) + 'px,0)';
      });
      el.addEventListener('pointerleave', () => { el.style.transform = ''; });
    });
  }
  initMagnetic();

  /* ── FAQ Accordion ── */
  function initFAQ() {
    const questions = document.querySelectorAll('.faq-question');
    if (!questions.length) return;

    function setOpen(btn, open) {
      const answer = btn.nextElementSibling;
      btn.classList.toggle('is-open', open);
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (answer) answer.classList.toggle('is-open', open);
    }

    questions.forEach((btn) => {
      btn.addEventListener('click', () => {
        const wasOpen = btn.classList.contains('is-open');

        // Close the other items in the same list.
        const list = btn.closest('.faq-list') || document;
        list.querySelectorAll('.faq-question.is-open').forEach((q) => setOpen(q, false));

        if (!wasOpen) setOpen(btn, true);
      });
    });
  }
  initFAQ();

  /* ── Enquiry form (AJAX) ── */
  function initForm(formId, messageId) {
    const form = document.getElementById(formId);
    const msg  = document.getElementById(messageId);
    if (!form || !msg) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const btn  = form.querySelector('[type="submit"]');
      const orig = btn.innerHTML;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Sending…';
      btn.disabled = true;
      msg.style.display = 'none';

      const data = new FormData(form);
      data.append('action', 'erawat_contact');

      // Use nonce if available (WP context)
      if (typeof erawatData !== 'undefined') {
        data.append('nonce', erawatData.nonce);
      }

      const url = (typeof erawatData !== 'undefined')
        ? erawatData.ajaxurl
        : '/wp-admin/admin-ajax.php';

      fetch(url, { method: 'POST', body: data })
        .then((r) => r.json())
        .then((res) => {
          msg.style.display = 'block';
          if (res.success) {
            msg.className = 'form-message success';
            msg.textContent = res.data.message || 'Thank you! We will get back to you soon.';
            form.reset();
          } else {
            msg.className = 'form-message error';
            msg.textContent = (res.data && res.data.message) || 'Something went wrong. Please try again.';
          }
        })
        .catch(() => {
          msg.style.display = 'block';
          msg.className = 'form-message error';
          msg.textContent = 'Network error. Please try calling us directly.';
        })
        .finally(() => {
          btn.innerHTML = orig;
          btn.disabled = false;
        });
    });
  }

  initForm('main-contact-form', 'contact-form-message');

  /* ── Smooth scroll for same-page anchor links ── */
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function (e) {
      const hash = this.getAttribute('href');
      if (!hash || hash.length < 2) return;

      let target = null;
      try { target = document.querySelector(hash); } catch (err) { target = null; }

      if (target) {
        e.preventDefault();
        const offset = (header ? header.offsetHeight : 0) + 16;
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: reduceMotion ? 'auto' : 'smooth' });
        if (window.history && window.history.replaceState) window.history.replaceState(null, '', hash);
      }
    });
  });

  /* ── Active nav highlight (for menus assigned in wp-admin) ── */
  const navLinks = document.querySelectorAll('.nav-menu a');
  if (navLinks.length) {
    const currentPath = window.location.pathname.replace(/\/$/, '');
    navLinks.forEach((link) => {
      const href = link.getAttribute('href');
      const linkPath = href
        ? new URL(href, window.location.origin).pathname.replace(/\/$/, '')
        : '';
      if (linkPath === currentPath) {
        link.closest('li').classList.add('current-menu-item');
        link.setAttribute('aria-current', 'page');
      }
    });
  }

  /* ── Nav indicator: a dark pill behind the active link that glides to the hovered / focused link
        and settles back on leave. Works for wp_nav_menu() output and the fallback menu. ── */
  function initNavIndicator() {
    const nav  = document.querySelector('.site-header__nav');
    const menu = nav ? nav.querySelector('.nav-menu') : null;
    if (!menu) return;

    const links  = Array.from(menu.querySelectorAll('a'));
    const active = links.find((a) => a.getAttribute('aria-current') === 'page' ||
      (a.closest('li') && /current[-_](menu|page)[-_]item/.test(a.closest('li').className))) || null;

    const indicator = document.createElement('span');
    indicator.className = 'nav-indicator';
    indicator.setAttribute('aria-hidden', 'true');
    nav.insertBefore(indicator, nav.firstChild);
    nav.classList.add('has-indicator');

    let target = null;

    function setHot(link) {
      links.forEach((a) => a.classList.toggle('is-hot', a === link));
    }

    function place(link, animate) {
      target = link;
      setHot(link);
      if (!link || !nav.offsetWidth) {
        indicator.classList.remove('is-on');
        return;
      }
      const navRect  = nav.getBoundingClientRect();
      const linkRect = link.getBoundingClientRect();
      const x = linkRect.left - navRect.left - nav.clientLeft;

      const appearing = !indicator.classList.contains('is-on');
      if (!animate || appearing) indicator.classList.add('no-anim');
      indicator.style.width = linkRect.width + 'px';
      indicator.style.transform = 'translate3d(' + x.toFixed(1) + 'px,0,0)';
      indicator.classList.add('is-on');
      if (!animate || appearing) {
        void indicator.offsetWidth; // flush so the jump is not animated
        indicator.classList.remove('no-anim');
      }
    }

    links.forEach((link) => {
      link.addEventListener('pointerenter', () => place(link, true));
      link.addEventListener('focus', () => place(link, true));
    });
    nav.addEventListener('pointerleave', () => place(active, true));
    nav.addEventListener('focusout', (e) => {
      if (!nav.contains(e.relatedTarget)) place(active, true);
    });

    const relayout = () => place(target || active, false);
    window.addEventListener('resize', relayout);
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(relayout);

    place(active, false);
  }
  initNavIndicator();

  updateOnScroll();

})();
