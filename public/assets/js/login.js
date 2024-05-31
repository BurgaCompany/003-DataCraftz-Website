document.addEventListener("DOMContentLoaded", function () {
    const togglePassword = document.getElementById("togglePassword");
    const password = document.getElementById("password");

    if (togglePassword && password) {
        togglePassword.addEventListener("click", function () {
            const type =
                password.getAttribute("type") === "password"
                    ? "text"
                    : "password";
            password.setAttribute("type", type);
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    }
});

document.getElementById("email").addEventListener("input", function () {
    var emailInput = this.value;
    var emailPattern = /\b[A-Za-z0-9._%+-]+@gmail\.com\b/;
    var errorMessage = document.getElementById("email-error-message");

    if (!emailPattern.test(emailInput)) {
        errorMessage.style.display = "block";
    } else {
        errorMessage.style.display = "none";
    }
});
