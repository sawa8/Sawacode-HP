document.addEventListener("DOMContentLoaded", function () {

  // ========================================
  // Hamburger Menu / Drawer
  // ========================================
  const hamburger = document.querySelector(".c-hamburger");
  const spNav = document.querySelector(".p-header__sp-nav");
  const overlay = document.querySelector(".drawer-overlay");
  const html = document.documentElement;

  function toggleDrawer() {
    hamburger.classList.toggle("open");
    spNav.classList.toggle("open");
    overlay.classList.toggle("open");
    html.classList.toggle("is-fixed");
  }

  function closeDrawer() {
    hamburger.classList.remove("open");
    spNav.classList.remove("open");
    overlay.classList.remove("open");
    html.classList.remove("is-fixed");
  }

  if (hamburger) {
    hamburger.addEventListener("click", toggleDrawer);
  }

  if (overlay) {
    overlay.addEventListener("click", closeDrawer);
  }

  // Close drawer when SP nav link is clicked, then navigate
  const spNavLinks = document.querySelectorAll(".sp-nav__item");
  spNavLinks.forEach(function (link) {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      var href = this.getAttribute("href");
      closeDrawer();
      setTimeout(function () {
        window.location.href = href;
      }, 300);
    });
  });

  // ========================================
  // Page Top Button
  // ========================================
  const pagetop = document.querySelector(".pagetop");

  if (pagetop) {
    pagetop.style.display = "none";

    window.addEventListener("scroll", function () {
      if (window.scrollY > 200) {
        pagetop.style.display = "flex";
      } else {
        pagetop.style.display = "none";
      }
    });

    pagetop.addEventListener("click", function () {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }

  // ========================================
  // Smooth Scroll
  // ========================================
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener("click", function (e) {
      var targetId = this.getAttribute("href");
      if (targetId === "#") return;

      var target = document.querySelector(targetId);
      if (!target) return;

      e.preventDefault();
      var headerHeight = document.querySelector(".header").offsetHeight;
      var targetY = target.getBoundingClientRect().top + window.scrollY - headerHeight;

      window.scrollTo({ top: targetY, behavior: "smooth" });
    });
  });

  // ========================================
  // Header scroll effect (transparent on hero)
  // ========================================
  var header = document.querySelector(".header");
  var hero = document.querySelector(".hero");

  if (header && hero) {
    // Start transparent on home page
    header.classList.add("header--transparent");

    window.addEventListener("scroll", function () {
      if (window.scrollY > 80) {
        header.classList.remove("header--transparent");
      } else {
        header.classList.add("header--transparent");
      }
    });
  }

  // ========================================
  // Scroll Animation (Intersection Observer)
  // ========================================
  var fadeElements = document.querySelectorAll(".fade-in");

  if (fadeElements.length > 0 && "IntersectionObserver" in window) {
    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 }
    );

    fadeElements.forEach(function (el) {
      observer.observe(el);
    });
  }


  // ========================================
  // Work Card iframe Dynamic Scale
  // ========================================
  function updateIframeScales() {
    document.querySelectorAll('.work-card__image').forEach(function (container) {
      var iframe = container.querySelector('iframe');
      if (!iframe) return;
      var scale = container.offsetWidth / 1280;
      iframe.style.transform = 'scale(' + scale + ')';
    });
  }

  updateIframeScales();
  window.addEventListener('resize', updateIframeScales);

  // ========================================
  // Hero Stars（星の生成）
  // ========================================
  generateStars();

  function generateStars() {
    var container = document.querySelector(".hero__stars");
    if (!container) return;
    container.innerHTML = "";
    for (var i = 0; i < 60; i++) {
      var star = document.createElement("div");
      star.className = "hero__star";
      var size = Math.random() * 3.5 + 1;
      star.style.cssText = [
        "width:" + size + "px",
        "height:" + size + "px",
        "left:" + (Math.random() * 100) + "%",
        "top:" + (Math.random() * 100) + "%",
        "--dur:" + (Math.random() * 3 + 2) + "s",
        "--del:-" + (Math.random() * 5) + "s"
      ].join(";");
      container.appendChild(star);
    }
  }
});
