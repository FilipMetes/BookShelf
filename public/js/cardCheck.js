function initCardCheck() {
    const paymentCards = document.getElementById("payment-k");
    const paymentCash = document.getElementById("payment-h");
    const cardPaymentFields = document.getElementById("cardPaymentFields");
    const form = document.getElementById("orderForm");

    const cardNumberInput = document.getElementById("card_number");
    const cardNumberError = document.getElementById("card_number-error");
    const cardExpiryInput = document.getElementById("card_expiry");
    const cardExpiryError = document.getElementById("card_expiry-error");
    const cardCvcInput = document.getElementById("card_cvc");
    const cardCvcError = document.getElementById("card_cvc-error");

    // Funkcia na zobrazovanie polí kariet
    function toggleCardFields() {
        if (paymentCards.checked) {
            cardPaymentFields.style.display = "block"; // Zobraz
        } else {
            cardPaymentFields.style.display = "none";  // Skry
        }
    }

    paymentCards.addEventListener("change", toggleCardFields);
    paymentCash.addEventListener("change", toggleCardFields);

    toggleCardFields();

    form.addEventListener("submit", function(e) {
        // Validáciu spúšťame iba ak je platba kartou
        if (paymentCards.checked) {

            let isValid = true;


            if (cardNumberError) cardNumberError.style.display = "none";
            if (cardExpiryError) cardExpiryError.style.display = "none";
            if (cardCvcError) cardCvcError.style.display = "none";

            // Kontrola čísla karty
            if (cardNumberInput.value.trim() === "") {
                isValid = false;
                if (cardNumberError) {
                    cardNumberError.innerText = "Číslo karty je povinné";
                    cardNumberError.style.display = "block";
                }
                cardNumberInput.classList.add("input-error");
            }

            if (cardExpiryInput.value.trim() === "") {
                isValid = false;
                if (cardExpiryError) {
                    cardExpiryError.innerText = "Dátum platnosti je povinný";
                    cardExpiryError.style.display = "block";
                }
                cardExpiryInput.classList.add("input-error");
            }else if (!/^(0[1-9]|1[0-2])\/\d{2,4}$/.test(cardExpiryInput.value.trim())) {
                isValid = false;
                cardExpiryError.innerText = "Neplatný formát (MM/YY)";
                cardExpiryError.style.display = "block";
                cardExpiryInput.classList.add("input-error");
            }

            if (cardCvcInput.value.trim() === "") {
                isValid = false;
                if (cardCvcError) {
                    cardCvcError.innerText = "CVC kód je povinný";
                    cardCvcError.style.display = "block";
                }
                cardCvcInput.classList.add("input-error");
            } else if (!/^\d{3,4}$/.test(cardCvcInput.value.trim())) {
                isValid = false;
                cardCvcError.innerText = "Neplatný CVC kód";
                cardCvcError.style.display = "block";
                cardCvcInput.classList.add("input-error");
            }

            if (!isValid) {
                e.preventDefault();
            }
        }
    });

    // Odstránenie error triedy pri písaní
    cardNumberInput.addEventListener("input", function() {
        cardNumberInput.classList.remove("input-error");
        if (cardNumberError) cardNumberError.style.display = "none";
    });
}

initCardCheck();
