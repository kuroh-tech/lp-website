(() => {
  const header = document.getElementById("site-header");
  const navToggle = document.querySelector(".nav-toggle");
  const nav = document.getElementById("site-nav");
  const navBackdrop = document.querySelector(".nav-backdrop");
  const navClose = document.querySelector(".nav-close");

  const setMobileNavState = (isOpen) => {
    if (!navToggle || !nav || !navBackdrop) {
      return;
    }

    document.body.classList.toggle("nav-open", isOpen);
    navToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
    navToggle.setAttribute("aria-label", isOpen ? "メニューを閉じる" : "メニューを開く");
    navBackdrop.hidden = !isOpen;
  };

  if (navToggle && nav && navBackdrop) {
    navToggle.addEventListener("click", () => {
      const isOpen = navToggle.getAttribute("aria-expanded") === "true";
      setMobileNavState(!isOpen);
    });

    navBackdrop.addEventListener("click", () => setMobileNavState(false));

    if (navClose) {
      navClose.addEventListener("click", () => setMobileNavState(false));
    }

    nav.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => setMobileNavState(false));
    });

    window.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        setMobileNavState(false);
      }
    });

    window.addEventListener("resize", () => {
      if (window.innerWidth > 860) {
        setMobileNavState(false);
      }
    });
  }

  if (header) {
    const toggleHeader = () => {
      const isFront = document.body.classList.contains("home") || document.body.classList.contains("front-page");
      if (!isFront) {
        return;
      }

      header.classList.toggle("scrolled", window.scrollY > 60);
    };

    window.addEventListener("scroll", toggleHeader, { passive: true });
    toggleHeader();
  }

  const reveals = document.querySelectorAll(".reveal");
  if (reveals.length) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 }
    );

    reveals.forEach((el) => observer.observe(el));
  }

  const mainImage = document.querySelector("[data-main-gallery]");
  const thumbButtons = document.querySelectorAll("[data-gallery-thumb]");

  if (mainImage && thumbButtons.length) {
    thumbButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const src = button.getAttribute("data-src");
        const alt = button.getAttribute("data-alt") || "Property image";

        if (!src) {
          return;
        }

        mainImage.setAttribute("src", src);
        mainImage.setAttribute("alt", alt);

        thumbButtons.forEach((target) => target.classList.remove("active"));
        button.classList.add("active");
      });
    });
  }

  const messageField = document.getElementById("inquiry-message");
  if (messageField) {
    const params = new URLSearchParams(window.location.search);
    const propertyName = params.get("inquiry_property");

    if (propertyName && messageField.value.trim() === "") {
      messageField.value = `${propertyName}についてお問い合わせします。`;
    }
  }
})();
