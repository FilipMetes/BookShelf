const form = document.getElementById("signForm");
const emailInput = document.getElementById("username");
const emailError = document.getElementById("email-error");
const passwordInput = document.getElementById("password");
const passwordError = document.getElementById("password-error");

form.addEventListener("submit", function (e) {

    let isValid = true;

    if (emailInput.value.trim() === "") {
        isValid = false;
        emailError.innerText = "E-mail je povinný";
        emailError.style.display = "block";
        emailInput.classList.add("input-error");
    }

    if (passwordInput.value.trim() === "") {
        isValid = false;
        passwordError.innerText = "Heslo je povinné";
        passwordError.style.display = "block";
        passwordInput.classList.add("input-error");
    }

    if (!isValid) {
        e.preventDefault();
    }
});

emailInput.addEventListener("input", function () {
    emailError.style.display = "none";
    emailInput.classList.remove("input-error");
});

passwordInput.addEventListener("input", function () {
    passwordError.style.display = "none";
    passwordInput.classList.remove("input-error");
});

