function checkOrderForm() {

    const form = document.getElementById("orderForm");
    const phoneInput = document.getElementById("phone");
    const phoneError = document.getElementById("phone-error");
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

        if (PSCInput.value.trim() !== "" || PSCInput.value.trim().length !== 5) {
            e.preventDefault();
            PSCError.innerText = "Neplatné PSČ";
            PSCError.style.display = "block";
            PSCInput.classList.add("input-error");
        }

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

        const digits = phoneInput.value.trim().replace(/\D/g, '');

        if (phoneInput.value.trim() === "") {
            e.preventDefault();
            phoneError.innerText = "Telefónne číslo je povinné";
            phoneError.style.display = "block";
            phoneInput.classList.add("input-error");
        } else if (digits.length < 7 || digits.length > 15) {
            e.preventDefault();
            phoneError.innerText = "Telefón musí obsahovať 7 až 15 číslic";
            phoneError.style.display = "block";
            phoneInput.classList.add("input-error");
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
            e.preventDefault();
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
            e.preventDefault();
            paymentError.innerText = "Prosím, vyberte spôsob platby";
            paymentError.style.display = "block";
        }

        if (termsCheck.checked === false) {
            e.preventDefault();
            termsError.innerText = "Musíte súhlasiť s obchodnými podmienkami";
            termsError.style.display = "block";
        }
    });
    phoneInput.addEventListener("input", function () {
        // odstráni všetko, čo nie je číslo, + alebo medzera
        this.value = this.value.replace(/[^0-9+ ]/g, '');
    });

}

checkOrderForm()



