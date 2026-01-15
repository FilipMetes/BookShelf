document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.form-signin');
    const fields = [
        { id: 'username', message: 'E-mail je povinný.' },
        { id: 'password', message: 'Heslo je povinné.' }
    ];

    if (!form) return;

    // vytvorenie error divov
    fields.forEach(f => {
        const input = document.getElementById(f.id);
        const error = document.createElement('div');
        error.className = 'text-danger';
        error.style.fontSize = '0.875rem';
        error.id = f.id + 'Error';
        input.after(error);
    });

    form.addEventListener('submit', e => {
        let valid = true;

        fields.forEach(f => {
            const input = document.getElementById(f.id);
            const error = document.getElementById(f.id + 'Error');

            error.textContent = '';
            input.classList.remove('is-invalid');

            if (input.value.trim() === '') {
                error.textContent = f.message;
                input.classList.add('is-invalid');
                valid = false;
            }
        });

        if (!valid) e.preventDefault();
    });
});
