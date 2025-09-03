
document.getElementById("loginForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;

  if (username.trim() === "" || password.trim() === "") {
    alert("Please enter both username and password.");
    return;
  }

  if (username === "vet" && password === "vet") {
    window.location.href = "./veterinarian/dashboard.html";
  } else {
    alert("Invalid username or password.");
  }
});

const passwordInput = document.getElementById("password");
const togglePassword = document.createElement("span");
togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
togglePassword.style.cssText = `
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
        `;

passwordInput.parentElement.style.position = "relative";
passwordInput.parentElement.appendChild(togglePassword);

togglePassword.addEventListener("click", function () {
  const type =
    passwordInput.getAttribute("type") === "password" ? "text" : "password";
  passwordInput.setAttribute("type", type);
  this.innerHTML =
    type === "password"
      ? '<i class="fas fa-eye"></i>'
      : '<i class="fas fa-eye-slash"></i>';
});

document.addEventListener("keydown", function (e) {
  if (e.key === "Enter" && e.ctrlKey) {
    document.getElementById("loginForm").dispatchEvent(new Event("submit"));
  }
});
