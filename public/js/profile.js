document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('registerForm');
    if (!form) return;

    const fields = [
        { id: 'name', message: 'Meno je povinné.' },
        { id: 'surname', message: 'Priezvisko je povinné.' },
        { id: 'e_mail', message: 'Email je povinný.' }
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

    form.addEventListener('submit', e => {
        let hasError = false;

        fields.forEach(f => {
            const input = document.getElementById(f.id);
            clearError(input);

            if (input.value.trim() === '') {
                showError(input, f.message);
                hasError = true;
            }
        });

        if (hasError) e.preventDefault();
    });

});
