function checkOrderForm() {

    const form = document.getElementById("orderForm");
    const cityInput = document.getElementById("city");
    const cityError = document.getElementById("city-error");
    const streetInput = document.getElementById("street");
    const streetError = document.getElementById("street-error");


    form.addEventListener("submit", function (e) {

        if (cityInput.value.trim() === "") {
            e.preventDefault();
            cityError.innerText = "Mesto je povinné";
            cityError.style.display = "block";
            cityInput.classList.add("input-error");
        }

        if (streetInput.value.trim() === "") {
            e.preventDefault();
            streetError.innerText = "Ulica je povinná";
            streetError.style.display = "block";
            streetInput.classList.add("input-error");
        }
    });

}

checkOrderForm()



