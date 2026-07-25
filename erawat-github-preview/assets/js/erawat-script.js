/**
 * Erawat Enterprise — Static Preview JS
 */
(function() {
  'use strict';

  /* ── Sticky Header ── */
  const header = document.getElementById('site-header');
  if (header) {
    window.addEventListener('scroll', () => {
      header.classList.toggle('scrolled', window.scrollY > 60);
    }, { passive: true });
  }

  /* ── Mobile Menu ── */
  const toggle    = document.getElementById('mobile-menu-toggle');
  const mobileNav = document.getElementById('mobile-nav');
  const overlay   = document.getElementById('mobile-nav-overlay');

  function openMenu()  {
    toggle.setAttribute('aria-expanded', 'true');
    mobileNav.classList.add('is-open');
    overlay.classList.add('is-visible');
    document.body.style.overflow = 'hidden';
  }
  function closeMenu() {
    toggle.setAttribute('aria-expanded', 'false');
    mobileNav.classList.remove('is-open');
    overlay.classList.remove('is-visible');
    document.body.style.overflow = '';
  }
  if (toggle) {
    toggle.addEventListener('click', () => mobileNav.classList.contains('is-open') ? closeMenu() : openMenu());
    overlay.addEventListener('click', closeMenu);
    document.addEventListener('keydown', e => e.key === 'Escape' && closeMenu());
  }

  /* ── Back to Top ── */
  const backTop = document.getElementById('back-to-top');
  if (backTop) {
    window.addEventListener('scroll', () => backTop.classList.toggle('visible', window.scrollY > 400), { passive: true });
    backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }

  /* ── Animate on Scroll ── */
  const aosEls = document.querySelectorAll('[data-aos]');
  if (aosEls.length && 'IntersectionObserver' in window) {
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('aos-animate'); obs.unobserve(e.target); } });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
    aosEls.forEach(el => obs.observe(el));
  }

  /* ── Stats Counter ── */
  const counters = document.querySelectorAll('.stat-item__number[data-target]');
  if (counters.length && 'IntersectionObserver' in window) {
    const cObs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (!e.isIntersecting) return;
        const el = e.target, target = parseInt(el.dataset.target, 10);
        const step = Math.ceil(target / (1800 / 16));
        let current = 0;
        const t = setInterval(() => {
          current = Math.min(current + step, target);
          el.textContent = current.toLocaleString('en-IN');
          if (current >= target) clearInterval(t);
        }, 16);
        cObs.unobserve(el);
      });
    }, { threshold: 0.5 });
    counters.forEach(c => cObs.observe(c));
  }

  /* ── FAQ Accordion ── */
  document.querySelectorAll('.faq-question').forEach(btn => {
    btn.addEventListener('click', () => {
      const isOpen = btn.classList.contains('is-open');
      document.querySelectorAll('.faq-question.is-open').forEach(q => {
        q.classList.remove('is-open');
        q.nextElementSibling.classList.remove('is-open');
      });
      if (!isOpen) {
        btn.classList.add('is-open');
        btn.nextElementSibling.classList.add('is-open');
      }
    });
  });

  /* ── Contact Forms (Demo mode for static preview) ── */
  document.querySelectorAll('.enquiry-form, .contact-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const btn = form.querySelector('[type="submit"]');
      const orig = btn.innerHTML;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending…';
      btn.disabled = true;
      setTimeout(() => {
        btn.innerHTML = orig;
        btn.disabled = false;
        const msg = form.querySelector('.form-message');
        if (msg) {
          msg.style.display = 'block';
          msg.className = 'form-message success';
          msg.innerHTML = '<i class="fas fa-check-circle"></i> <strong>Preview Mode:</strong> In the live site, your enquiry will be sent directly to Erawat Enterprise. The form is fully functional on WordPress.';
          form.reset();
          setTimeout(() => { msg.style.display = 'none'; }, 6000);
        }
      }, 1200);
    });
  });

  /* ── Smooth scroll ── */
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function(e) {
      const t = document.querySelector(this.getAttribute('href'));
      if (t) { e.preventDefault(); window.scrollTo({ top: t.getBoundingClientRect().top + window.scrollY - 90, behavior: 'smooth' }); }
    });
  });

})();
