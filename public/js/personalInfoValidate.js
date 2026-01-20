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
const phoneInput = document.getElementById("phone");
const phoneError = document.getElementById("phone-error");

form.addEventListener("submit", function (e) {

    let isValid = true;

    if (nameInput.value.trim() === "") {
        isValid = false;
        nameError.innerText = "Méno je povinné";
        nameError.style.display = "block";
        nameInput.classList.add("input-error");
    }

    if (surnameInput.value.trim() === "") {
        isValid = false;
        surnameError.innerText = "Priezvisko je povinné";
        surnameError.style.display = "block";
        surnameInput.classList.add("input-error");
    }

    if (emailInput.value.trim() === "") {
        isValid = false;
        emailError.innerText = "E-mail je povinný";
        emailError.style.display = "block";
        emailInput.classList.add("input-error");
    }else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
        isValid = false;
        emailError.innerText = "Neplatný formát e-mailu";
        emailError.style.display = "block";
        emailInput.classList.add("input-error");
    }

    const digits = phoneInput.value.trim().replace(/\D/g, '');

    if (phoneInput.value.trim() === "") {
        isValid = false;
        phoneError.innerText = "Telefónne číslo je povinné";
        phoneError.style.display = "block";
        phoneInput.classList.add("input-error");
    } else if (digits.length < 7 || digits.length > 15) {
        isValid = false;
        phoneError.innerText = "Telefón musí obsahovať 7 až 15 číslic";
        phoneError.style.display = "block";
        phoneInput.classList.add("input-error");
    }

    if (PSCInput.value.trim() !== "" && PSCInput.value.trim().length !== 5) {
        isValid = false;
        PSCError.innerText = "PSČ musi mať 5 znakov";
        PSCError.style.display = "block";
        PSCInput.classList.add("input-error");
    }

    if (!isValid) {
        e.preventDefault();
    }
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

phoneInput.addEventListener("input", function () {
    // odstráni všetko, čo nie je číslo, + alebo medzera
    this.value = this.value.replace(/[^0-9+ ]/g, '');
});


