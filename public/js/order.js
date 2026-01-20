function checkOrderForm() {

    const form = document.getElementById("orderForm");
    const cityInput = document.getElementById("city");
    const cityError = document.getElementById("city-error");
    const streetInput = document.getElementById("street");
    const streetError = document.getElementById("street-error");
    const deliveryRadios = document.getElementsByName("delivery");
    const deliveryError = document.getElementById("delivery-error");
    const paymentRadios = document.getElementsByName("payment");
    const paymentError = document.getElementById("payment-error");
    const termsCheck = document.getElementById("terms");
    const termsError = document.getElementById("terms-error");
    const PSCInput = document.getElementById("PSC");
    const PSCError = document.getElementById("PSC-error");


    form.addEventListener("submit", function (e) {

        let isValid = true;

        if (PSCInput.value.trim() !== "" || PSCInput.value.trim().length !== 5) {
            isValid = false;
            PSCError.innerText = "Neplatné PSČ";
            PSCError.style.display = "block";
            PSCInput.classList.add("input-error");
        }

        if (cityInput.value.trim() === "") {
            isValid = false;
            cityError.innerText = "Mesto je povinné";
            cityError.style.display = "block";
            cityInput.classList.add("input-error");
        }

        if (streetInput.value.trim() === "") {
            isValid = false;
            streetError.innerText = "Ulica je povinná";
            streetError.style.display = "block";
            streetInput.classList.add("input-error");
        }

        let deliverySelected = false;
        let paymentSelected = false;

        for (const radio of deliveryRadios) {
            if (radio.checked) {
                deliverySelected = true;
                break;
            }
        }
        if (!deliverySelected) {
            isValid = false;
            deliveryError.innerText = "Prosím, vyberte spôsob dopravy";
            deliveryError.style.display = "block";
        }

        for (const radio of paymentRadios) {
            if (radio.checked) {
                paymentSelected = true;
                break;
            }
        }
        if (!paymentSelected) {
            isValid = false;
            paymentError.innerText = "Prosím, vyberte spôsob platby";
            paymentError.style.display = "block";
        }

        if (termsCheck.checked === false) {
            isValid = false;
            termsError.innerText = "Musíte súhlasiť s obchodnými podmienkami";
            termsError.style.display = "block";
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
    phoneInput.addEventListener("input", function () {
        // odstráni všetko, čo nie je číslo, + alebo medzera
        this.value = this.value.replace(/[^0-9+ ]/g, '');
    });

}

checkOrderForm()



