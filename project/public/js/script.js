document.addEventListener("DOMContentLoaded", () => {
  
  const alert = document.querySelector(".alert-success");
  if (alert) {
    setTimeout(() => {
      alert.style.transition = "opacity 0.6s ease";
      alert.style.opacity = "0";
      setTimeout(() => alert.remove(), 600);
    }, 3000);
  }

  
  const footerLinks = document.querySelectorAll("footer a[href^='#']");
  footerLinks.forEach(link => {
    link.addEventListener("click", e => {
      e.preventDefault();
      const target = document.querySelector(link.getAttribute("href"));
      if (target) {
        target.scrollIntoView({ behavior: "smooth" });
      }
    });
  });

  
  document.querySelectorAll(".form-control").forEach(input => {
    input.addEventListener("focus", () => {
      input.style.boxShadow = "0 0 0 3px rgba(47,129,247,0.3)";
    });
    input.addEventListener("blur", () => {
      input.style.boxShadow = "none";
    });
  });
});
