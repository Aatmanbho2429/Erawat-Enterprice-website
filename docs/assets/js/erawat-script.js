/**
 * Erawat Enterprise — Static Preview JS
 */
(function () {
  'use strict';

  /* Sticky Header */
  const header = document.getElementById('site-header');
  if (header) window.addEventListener('scroll', () => header.classList.toggle('scrolled', window.scrollY > 60), { passive: true });

  /* Mobile Menu */
  const toggle = document.getElementById('mobile-menu-toggle');
  const mNav   = document.getElementById('mobile-nav');
  const ovl    = document.getElementById('mobile-nav-overlay');
  function openMenu()  { toggle.setAttribute('aria-expanded','true');  mNav.classList.add('is-open');    ovl.classList.add('is-visible');    document.body.style.overflow='hidden'; }
  function closeMenu() { toggle.setAttribute('aria-expanded','false'); mNav.classList.remove('is-open'); ovl.classList.remove('is-visible'); document.body.style.overflow=''; }
  if (toggle) {
    toggle.addEventListener('click', () => mNav.classList.contains('is-open') ? closeMenu() : openMenu());
    ovl.addEventListener('click', closeMenu);
    document.addEventListener('keydown', e => e.key==='Escape' && closeMenu());
  }

  /* Back to Top */
  const btt = document.getElementById('back-to-top');
  if (btt) {
    window.addEventListener('scroll', () => btt.classList.toggle('visible', window.scrollY > 400), { passive: true });
    btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }

  /* Animate on Scroll */
  if ('IntersectionObserver' in window) {
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('aos-animate'); obs.unobserve(e.target); } });
    }, { threshold: 0.08, rootMargin: '0px 0px -20px 0px' });
    document.querySelectorAll('[data-aos]').forEach(el => obs.observe(el));
  } else {
    /* Fallback: show everything if IntersectionObserver not supported */
    document.querySelectorAll('[data-aos]').forEach(el => el.classList.add('aos-animate'));
  }

  /* Stats Counter */
  if ('IntersectionObserver' in window) {
    const cObs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (!e.isIntersecting) return;
        const el = e.target, target = parseInt(el.dataset.target, 10);
        const step = Math.ceil(target / (1800 / 16)); let cur = 0;
        const t = setInterval(() => { cur = Math.min(cur + step, target); el.textContent = cur.toLocaleString('en-IN'); if (cur >= target) clearInterval(t); }, 16);
        cObs.unobserve(el);
      });
    }, { threshold: 0.5 });
    document.querySelectorAll('.stat-item__number[data-target]').forEach(c => cObs.observe(c));
  }

  /* FAQ Accordion */
  document.querySelectorAll('.faq-question').forEach(btn => {
    btn.addEventListener('click', () => {
      const isOpen = btn.classList.contains('is-open');
      document.querySelectorAll('.faq-question.is-open').forEach(q => { q.classList.remove('is-open'); q.nextElementSibling.classList.remove('is-open'); });
      if (!isOpen) { btn.classList.add('is-open'); btn.nextElementSibling.classList.add('is-open'); }
    });
  });

  /* Forms — Demo message for static preview */
  document.querySelectorAll('.enquiry-form, .contact-form').forEach(form => {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const btn = form.querySelector('[type="submit"]');
      const orig = btn.innerHTML;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending…';
      btn.disabled = true;
      setTimeout(() => {
        btn.innerHTML = orig; btn.disabled = false;
        const msg = form.querySelector('.form-message');
        if (msg) {
          msg.style.display = 'block'; msg.className = 'form-message success';
          msg.innerHTML = '✅ <strong>Preview Mode:</strong> Form is fully functional on the live WordPress site. Your enquiry will be emailed to Erawat Enterprise.';
          form.reset();
          setTimeout(() => { msg.style.display = 'none'; }, 7000);
        }
      }, 1200);
    });
  });

  /* Smooth scroll for anchor links */
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function (e) {
      const t = document.querySelector(this.getAttribute('href'));
      if (t) { e.preventDefault(); window.scrollTo({ top: t.getBoundingClientRect().top + window.scrollY - 90, behavior: 'smooth' }); }
    });
  });
})();
