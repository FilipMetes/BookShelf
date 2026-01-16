const paymentRadios = document.querySelectorAll('input[name="payment"]');
const cardFields = document.getElementById('cardPaymentFields');

paymentRadios.forEach(radio => {
    radio.addEventListener('change', () => {
        if (radio.value === 'karta' && radio.checked) {
            cardFields.style.display = 'block';
        } else {
            cardFields.style.display = 'none';
        }
    });
});