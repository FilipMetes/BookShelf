document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('orderForm');
    if (!form) return;

    const baseFields = [
        { id: 'name', message: 'Meno je povinné.' },
        { id: 'surname', message: 'Priezvisko je povinné.' },
        { id: 'email', message: 'Email je povinný.' },
        { id: 'phone', message: 'Telefón je povinný.' },
        { id: 'street', message: 'Ulica je povinná.' },
        { id: 'city', message: 'Mesto je povinné.' },
        { id: 'psc', message: 'PSČ je povinné.' }
    ];

    const cardFields = [
        { id: 'card_number', message: 'Číslo karty je povinné.' },
        { id: 'card_expiry', message: 'Platnosť karty je povinná.' },
        { id: 'card_cvc', message: 'CVC kód je povinný.' }
    ];

    function showError(input, message) {
        let error = input.nextElementSibling;

        if (!error || !error.classList.contains('text-danger')) {
            error = document.createElement('div');
            error.className = 'form-text text-danger';
            input.after(error);
        }

        error.textContent = message;
        error.style.display = 'block';
        input.classList.add('is-invalid');
    }

    function clearError(input) {
        const error = input.nextElementSibling;
        if (error && error.classList.contains('text-danger')) {
            error.textContent = '';
            error.style.display = 'none';
        }
        input.classList.remove('is-invalid');
    }

    function isPaymentCardSelected() {
        const payment = document.querySelector('input[name="payment"]:checked');
        return payment && payment.value === 'karta';
    }

    form.addEventListener('submit', e => {
        let hasError = false;

        // základné polia
        baseFields.forEach(f => {
            const input = document.getElementById(f.id);
            if (!input) return;

            clearError(input);

            if (input.value.trim() === '') {
                showError(input, f.message);
                hasError = true;
            }
        });

        // spôsob dopravy
        const delivery = document.querySelector('input[name="delivery"]:checked');
        if (!delivery) {
            hasError = true;
        }

        // spôsob platby
        const payment = document.querySelector('input[name="payment"]:checked');
        if (!payment) {
            hasError = true;
        }

        // kartové údaje len ak je zvolená karta
        if (isPaymentCardSelected()) {
            cardFields.forEach(f => {
                const input = document.getElementById(f.id);
                if (!input) return;

                clearError(input);

                if (input.value.trim() === '') {
                    showError(input, f.message);
                    hasError = true;
                }
            });
        }

        // súhlas s podmienkami
        const terms = document.getElementById('terms');
        if (!terms.checked) {
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
        }
    });
});
