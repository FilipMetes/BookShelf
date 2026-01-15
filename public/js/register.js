document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registerForm');
    if (!form) return;

    const fields = [
        { id: 'name', message: 'Meno je povinné.' },
        { id: 'surname', message: 'Priezvisko je povinné.' },
        {
            id: 'e_mail',
            message: 'Email je povinný.',
            invalid: 'Neplatný formát emailu.',
            pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        },
        {
            id: 'password',
            message: 'Heslo je povinné.',
            invalid: 'Heslo musí mať aspoň 6 znakov.',
            minLength: 6
        }
    ];

    form.addEventListener('submit', e => {
        let hasError = false;

        fields.forEach(f => {
            const input = document.getElementById(f.id);
            let error = document.getElementById(f.id + 'Error');

            if (!error) {
                error = document.createElement('div');
                error.id = f.id + 'Error';
                error.className = 'form-text text-danger';
                input.after(error);
            }

            error.textContent = '';
            error.style.display = 'none';
            input.classList.remove('is-invalid');

            const value = input.value.trim();

            if (!value) {
                error.textContent = f.message;
                error.style.display = 'block';
                input.classList.add('is-invalid');
                hasError = true;
                return;
            }

            if (f.pattern && !f.pattern.test(value)) {
                error.textContent = f.invalid;
                error.style.display = 'block';
                input.classList.add('is-invalid');
                hasError = true;
            }

            if (f.minLength && value.length < f.minLength) {
                error.textContent = f.invalid;
                error.style.display = 'block';
                input.classList.add('is-invalid');
                hasError = true;
            }
        });

        if (hasError) e.preventDefault();
    });
});
