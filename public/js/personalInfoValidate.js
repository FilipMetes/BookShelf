let form = document.getElementById("registerForm");

if (!form) {
    form = document.getElementById("orderForm");
}
const nameInput = document.getElementById("name");
const nameError = document.getElementById("name-error");
const surnameInput = document.getElementById("surname");
const surnameError = document.getElementById("surname-error");
const emailInput = document.getElementById("e_mail");
const emailError = document.getElementById("e_mail-error");
const PSCInput = document.getElementById("PSC");
const PSCError = document.getElementById("PSC-error");

form.addEventListener("submit", function (e) {

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
    }else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
        e.preventDefault();
        emailError.innerText = "Neplatný formát e-mailu";
        emailError.style.display = "block";
        emailInput.classList.add("input-error");
    }

    if (PSCInput.value.trim() !== "" && PSCInput.value.trim().length !== 5) {
        e.preventDefault();
        PSCError.innerText = "PSČ musi mať 5 znakov";
        PSCError.style.display = "block";
        PSCInput.classList.add("input-error");
    }``
});

emailInput.addEventListener("input", function () {
    emailError.style.display = "none";
    emailInput.classList.remove("input-error");
});

nameInput.addEventListener("input", function () {
    nameError.style.display = "none";
    nameInput.classList.remove("input-error");
});

surnameInput.addEventListener("input", function () {
    surnameError.style.display = "none";
    surnameInput.classList.remove("input-error");
});

PSCInput.addEventListener("input", function () {
    PSCError.style.display = "none";
    PSCInput.classList.remove("input-error");

    // Obmedzenie na max 5 číslic
    if (this.value.length > 5) {
        this.value = this.value.slice(0, 5);
    }
});


