const form = document.getElementById("bookForm");
const titleInput = document.getElementById("title");
const titleError = document.getElementById("title-error");
const authorInput = document.getElementById("author");
const authorError = document.getElementById("author-error");
const genreSelect = document.getElementById("genre");
const genreError = document.getElementById("genre-error");
const formatRadios = document.getElementsByName("format");
const formatError = document.getElementById("format-error");
const yearInput = document.getElementById("year");
const yearError = document.getElementById("year-error");

form.addEventListener("submit", function (e) {

    let isValid = true;

    if (titleInput.value.trim() === "") {
        isValid = false;
        titleError.innerText = "Názov je povinný";
        titleError.style.display = "block";
        titleInput.classList.add("input-error");
    }

    if (authorInput.value.trim() === "") {
        isValid = false;
        authorError.innerText = "Autor je povinný";
        authorError.style.display = "block";
        authorInput.classList.add("input-error");
    }

    if (genreSelect.value === "") {
        isValid = false;
        genreError.innerText = "Prosím, vyberte žáner";
        genreError.style.display = "block";
        genreSelect.classList.add("input-error");
    }

    if (yearInput.value === "") {
        isValid = false;
        yearError.innerText = "Rok je povinný";
        yearError.style.display = "block";
        yearInput.classList.add("input-error");
    }
    let formatSelected = false;
    for (const radio of formatRadios) {
        if (radio.checked) {
            formatSelected = true;
            break;
        }
    }
    if (!formatSelected) {
        isValid = false;
        formatError.innerText = "Prosím, vyberte formát knihy";
        formatError.style.display = "block";
    }

    if (!isValid) {
        e.preventDefault();
    }
});

yearInput.addEventListener("input", function() {

    if (this.value.length > 4) {
        this.value = this.value.slice(0, 4);
    }

    yearError.style.display = "none";
});

titleInput.addEventListener("input", function () {
    emailError.style.display = "none";
    emailInput.classList.remove("input-error");
});

authorInput.addEventListener("input", function () {
    passwordError.style.display = "none";
    passwordInput.classList.remove("input-error");
});

for (const radio of formatRadios) {
    radio.addEventListener("change", function () {
        formatError.style.display = "none";
    });
}