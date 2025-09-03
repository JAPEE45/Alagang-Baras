const hamburgerBtn = document.getElementById("hamburgerBtn");
const sidebar = document.getElementById("sidebar");
const mainContent = document.getElementById("mainContent");
const overlay = document.getElementById("overlay");

hamburgerBtn.addEventListener("click", function () {
  sidebar.classList.toggle("show");
  overlay.classList.toggle("show");
});

overlay.addEventListener("click", function () {
  sidebar.classList.remove("show");
  overlay.classList.remove("show");
});

document.querySelectorAll(".nav-link").forEach((link) => {
  link.addEventListener("click", function () {
    if (!this.classList.contains("submenu-toggle")) {
      if (window.innerWidth <= 768) {
        sidebar.classList.remove("show");
        overlay.classList.remove("show");
      }
    }
  });
});

document.querySelectorAll(".submenu-toggle").forEach(toggle => {
  toggle.addEventListener("click", function (e) {
    e.preventDefault();
    const parent = this.parentElement;
    parent.classList.toggle("open");
  });
});

window.addEventListener("resize", function () {
  if (window.innerWidth > 768) {
    sidebar.classList.remove("show");
    overlay.classList.remove("show");
  }
});

      