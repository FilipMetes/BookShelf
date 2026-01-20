function checkRegisterForm() {

    const form = document.getElementById("registerForm");
    const passwordInput = document.getElementById("password");
    const passwordError = document.getElementById("password-error");


    form.addEventListener("submit", function (e) {

        if (passwordInput.value.trim() === "") {
            e.preventDefault();
            passwordError.innerText = "Heslo je povinné";
            passwordError.style.display = "block";
            passwordInput.classList.add("input-error");
        }
    });

}

checkRegisterForm()
