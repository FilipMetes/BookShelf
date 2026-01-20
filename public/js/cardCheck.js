function initCardCheck() {
    const paymentCards = document.getElementById("payment-k"); // Platba kartou
    const paymentCash = document.getElementById("payment-h");  // Hotovosť
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

    toggleCardFields(); // inicializácia pri načítaní

    // Validácia polí kariet pri submit
    form.addEventListener("submit", function(e) {
        // Validáciu spúšťame iba ak je platba kartou
        if (paymentCards.checked) {
            // Reset chýb
            cardNumberInput.classList.remove("input-error");
            cardExpiryInput.classList.remove("input-error");
            cardCvcInput.classList.remove("input-error");

            if (cardNumberError) cardNumberError.style.display = "none";
            if (cardExpiryError) cardExpiryError.style.display = "none";
            if (cardCvcError) cardCvcError.style.display = "none";

            // Kontrola čísla karty
            if (cardNumberInput.value.trim() === "") {
                e.preventDefault(); // Zastaví odoslanie formulára
                if (cardNumberError) {
                    cardNumberError.innerText = "Číslo karty je povinné";
                    cardNumberError.style.display = "block";
                }
                cardNumberInput.classList.add("input-error");
            }

            if (cardExpiryInput.value.trim() === "") {
                e.preventDefault();
                if (cardExpiryError) {
                    cardExpiryError.innerText = "Dátum platnosti je povinný";
                    cardExpiryError.style.display = "block";
                }
                cardExpiryInput.classList.add("input-error");
            }

            if (cardCvcInput.value.trim() === "") {
                e.preventDefault();
                if (cardCvcError) {
                    cardCvcError.innerText = "CVC kód je povinný";
                    cardCvcError.style.display = "block";
                }
                cardCvcInput.classList.add("input-error");
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
