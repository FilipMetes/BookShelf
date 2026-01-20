const form = document.getElementById("registerForm");
const nameInput = document.getElementById("name");
const nameError = document.getElementById("name-error");
const surnameInput = document.getElementById("surname");
const surnameError = document.getElementById("surname-error");
const passwordInput = document.getElementById("password");
const passwordError = document.getElementById("password-error");
const emailInput = document.getElementById("e_mail");
const emailError = document.getElementById("e_mail-error");
const PSCinput = document.getElementById("PSC");
const PSCerror = document.getElementById("PSC-error");

form.addEventListener("submit", function (e) {
    nameInput.classList.remove("input-error");
    surnameInput.classList.remove("input-error");
    emailInput.classList.remove("input-error");
    passwordInput.classList.remove("input-error");
    nameError.style.display = "none";
    surnameError.style.display = "none";
    emailError.style.display = "none";
    passwordError.style.display = "none";


    if (nameInput.value.trim() === "") {
        e.preventDefault();
        nameError.innerText = "Méno je povinné";
        nameError.style.display = "block";
        nameInput.classList.add("input-error");
    }

    if (surnameInput.value.trim() === "") {
        e.preventDefault();
        surnameError.innerText = "Priezvisko je povinné";
        surnameError.style.display = "block";
        surnameInput.classList.add("input-error");
    }

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

    if (PSCinput.value.length > 5) {
        e.preventDefault();
        PSCerror.innerText = "PSČ môže mať maximálne 5 znakov";
        PSCerror.style.display = "block";
        PSCinput.classList.add("input-error");
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

nameInput.addEventListener("input", function () {
    nameError.style.display = "none";
    nameInput.classList.remove("input-error");
});

surnameInput.addEventListener("input", function () {
    surnameError.style.display = "none";
    surnameInput.classList.remove("input-error");
});

