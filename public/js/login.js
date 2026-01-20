const form = document.getElementById("signForm");
const emailInput = document.getElementById("username");
const emailError = document.getElementById("email-error");
const passwordInput = document.getElementById("password");
const passwordError = document.getElementById("password-error");

form.addEventListener("submit", function (e) {
    emailError.style.display = "none";
    passwordError.style.display = "none";
    emailInput.classList.remove("input-error");
    passwordInput.classList.remove("input-error");

    if (emailInput.value.trim() === "") {
        e.preventDefault();
        emailError.innerText = "E-mail je povinný";
        emailError.style.display = "block";
        emailInput.classList.add("input-error");
    }else if (!emailInput.value.includes("@")) {
        e.preventDefault();
        emailError.innerText = "Neplatný formát e-mailu";
        emailError.style.display = "block";
        emailInput.classList.add("input-error");
    }

    if (passwordInput.value.trim() === "") {
        e.preventDefault();
        passwordError.innerText = "Heslo je povinné";
        passwordError.style.display = "block";
        passwordInput.classList.add("input-error");
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

