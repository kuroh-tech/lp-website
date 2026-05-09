(() => {
  const header = document.getElementById("site-header");
  const toggle = document.querySelector(".menu-toggle");

  const updateHeader = () => {
    if (!header) {
      return;
    }

    header.classList.toggle("scrolled", window.scrollY > 40 || header.classList.contains("subpage"));
  };

  window.addEventListener("scroll", updateHeader, { passive: true });
  updateHeader();

  if (toggle && header) {
    toggle.addEventListener("click", () => {
      const isOpen = header.classList.toggle("nav-open");
      toggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
    });
  }

  const reveals = document.querySelectorAll(".reveal");
  if (reveals.length) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.18 });

    reveals.forEach((element) => observer.observe(element));
  }

  document.querySelectorAll(".accordion-toggle").forEach((button) => {
    button.addEventListener("click", () => {
      button.parentElement.classList.toggle("is-open");
    });
  });
})();
