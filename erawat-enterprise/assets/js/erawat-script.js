/**
 * Erawat Enterprise — Main JavaScript
 */
(function($) {
  'use strict';

  /* ── Sticky Header ── */
  const header = document.getElementById('site-header');
  if (header) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 60) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
    }, { passive: true });
  }

  /* ── Mobile Menu ── */
  const toggle  = document.getElementById('mobile-menu-toggle');
  const mobileNav = document.getElementById('mobile-nav');
  const overlay = document.getElementById('mobile-nav-overlay');

  function openMenu() {
    toggle.setAttribute('aria-expanded', 'true');
    mobileNav.classList.add('is-open');
    mobileNav.setAttribute('aria-hidden', 'false');
    overlay.classList.add('is-visible');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    toggle.setAttribute('aria-expanded', 'false');
    mobileNav.classList.remove('is-open');
    mobileNav.setAttribute('aria-hidden', 'true');
    overlay.classList.remove('is-visible');
    document.body.style.overflow = '';
  }

  if (toggle && mobileNav) {
    toggle.addEventListener('click', () => {
      if (mobileNav.classList.contains('is-open')) closeMenu();
      else openMenu();
    });
    overlay.addEventListener('click', closeMenu);

    // Close on ESC key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && mobileNav.classList.contains('is-open')) closeMenu();
    });
  }

  /* ── Back to Top ── */
  const backTop = document.getElementById('back-to-top');
  if (backTop) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 400) backTop.classList.add('visible');
      else backTop.classList.remove('visible');
    }, { passive: true });

    backTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ── Animate on Scroll (AOS) ── */
  function initAOS() {
    const elements = document.querySelectorAll('[data-aos]');
    if (!elements.length) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('aos-animate');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });

    elements.forEach(el => observer.observe(el));
  }
  initAOS();

  /* ── Stats Counter Animation ── */
  function animateCounters() {
    const counters = document.querySelectorAll('.stat-item__number[data-target]');
    if (!counters.length) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const el     = entry.target;
          const target = parseInt(el.dataset.target, 10);
          const duration = 1800;
          const step  = Math.ceil(target / (duration / 16));
          let current = 0;

          const timer = setInterval(() => {
            current = Math.min(current + step, target);
            el.textContent = current.toLocaleString('en-IN');
            if (current >= target) clearInterval(timer);
          }, 16);

          observer.unobserve(el);
        }
      });
    }, { threshold: 0.5 });

    counters.forEach(c => observer.observe(c));
  }
  animateCounters();

  /* ── FAQ Accordion ── */
  function initFAQ() {
    const questions = document.querySelectorAll('.faq-question');
    if (!questions.length) return;

    questions.forEach(btn => {
      btn.addEventListener('click', () => {
        const answer   = btn.nextElementSibling;
        const isOpen   = btn.classList.contains('is-open');

        // Close all
        document.querySelectorAll('.faq-question.is-open').forEach(q => {
          q.classList.remove('is-open');
          q.nextElementSibling.classList.remove('is-open');
        });

        // Open clicked if it was closed
        if (!isOpen) {
          btn.classList.add('is-open');
          answer.classList.add('is-open');
        }
      });
    });
  }
  initFAQ();

  /* ── Contact Form AJAX (Home) ── */
  function initForm(formId, messageId) {
    const form = document.getElementById(formId);
    const msg  = document.getElementById(messageId);
    if (!form || !msg) return;

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const btn = form.querySelector('[type="submit"]');
      const orig = btn.innerHTML;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending…';
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
        .then(r => r.json())
        .then(res => {
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

  initForm('home-enquiry-form',  'home-form-message');
  initForm('main-contact-form',  'contact-form-message');

  /* ── Smooth scroll for anchor links ── */
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const offset = (header ? header.offsetHeight : 0) + 16;
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    });
  });

  /* ── Active nav highlight on scroll ── */
  const navLinks = document.querySelectorAll('.nav-menu a');
  if (navLinks.length) {
    const currentPath = window.location.pathname.replace(/\/$/, '');
    navLinks.forEach(link => {
      const linkPath = link.getAttribute('href')
        ? new URL(link.getAttribute('href'), window.location.origin).pathname.replace(/\/$/, '')
        : '';
      if (linkPath === currentPath) {
        link.closest('li').classList.add('current-menu-item');
      }
    });
  }

})(window.jQuery || { fn: {} });
