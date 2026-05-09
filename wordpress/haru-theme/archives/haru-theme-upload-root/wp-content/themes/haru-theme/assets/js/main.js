(() => {
  const header = document.getElementById("site-header");
  const toggle = document.querySelector(".menu-toggle");
  const navLinks = document.querySelectorAll(".site-nav a");

  const updateHeader = () => {
    if (!header) {
      return;
    }

    header.classList.toggle("scrolled", window.scrollY > 40 || header.classList.contains("subpage"));
  };

  window.addEventListener("scroll", updateHeader, { passive: true });
  updateHeader();

  if (toggle && header) {
    const setNavState = (isOpen) => {
      header.classList.toggle("nav-open", isOpen);
      document.body.classList.toggle("nav-open", isOpen);
      toggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
      toggle.setAttribute("aria-label", isOpen ? "Close navigation" : "Open navigation");
    };

    toggle.addEventListener("click", () => {
      setNavState(!header.classList.contains("nav-open"));
    });

    navLinks.forEach((link) => {
      link.addEventListener("click", () => setNavState(false));
    });

    window.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        setNavState(false);
      }
    });

    window.addEventListener("resize", () => {
      if (window.innerWidth > 920) {
        setNavState(false);
      }
    });
  }

  const reveals = document.querySelectorAll(".reveal");
  if (!reveals.length) {
    return;
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.18 });

  reveals.forEach((element) => observer.observe(element));
})();
