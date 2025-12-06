document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registerForm');

    const fields = [
        {id: 'name', errorId: 'nameError', message: 'Meno je povinné.'},
        {id: 'surname', errorId: 'surnameError', message: 'Priezvisko je povinné.'},
        {id: 'e_mail', errorId: 'emailError', message: 'Email je povinný.'},
    ];

    if (!form) return;

    form.addEventListener('submit', function (e) {
        let hasError = false;

        // reset errors
        fields.forEach(f => {
            const input = document.getElementById(f.id);
            let errorDiv = document.getElementById(f.errorId);
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = f.errorId;
                errorDiv.classList.add('form-text', 'text-danger');
                input.insertAdjacentElement('afterend', errorDiv);
            }
            errorDiv.textContent = '';
            errorDiv.style.display = 'none';
            input.classList.remove('is-invalid');
        });

        // check each field
        fields.forEach(f => {
            const input = document.getElementById(f.id);
            const errorDiv = document.getElementById(f.errorId);
            if (input.value.trim() === '') {
                errorDiv.textContent = f.message;
                errorDiv.style.display = 'block';
                input.classList.add('is-invalid');
                hasError = true;
            }
        });

        if (hasError) e.preventDefault(); // stop form submission
    });
});
