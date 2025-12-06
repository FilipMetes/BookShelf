document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registerForm');

    const fields = [
        {id: 'name', errorId: 'nameError', requiredMessage: 'Meno je povinné.'},
        {id: 'surname', errorId: 'surnameError', requiredMessage: 'Priezvisko je povinné.'},
        {id: 'e_mail', errorId: 'emailError', requiredMessage: 'Email je povinný.', invalidMessage: 'Neplatný formát emailu.'},
        {id: 'password', errorId: 'passwordError', requiredMessage: 'Heslo je povinné.', invalidMessage: 'Heslo musí mať aspoň 6 znakov.', minLength: 6}
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
            const value = input.value.trim();

            if (value === '') {
                // prázdne pole
                errorDiv.textContent = f.requiredMessage;
                errorDiv.style.display = 'block';
                input.classList.add('is-invalid');
                hasError = true;
            } else {
                // kontrola špecifického formátu len ak pole nie je prázdne
                if (f.id === 'e_mail') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        errorDiv.textContent = f.invalidMessage;
                        errorDiv.style.display = 'block';
                        input.classList.add('is-invalid');
                        hasError = true;
                    }
                } else if (f.id === 'password' && value.length < f.minLength) {
                    errorDiv.textContent = f.invalidMessage;
                    errorDiv.style.display = 'block';
                    input.classList.add('is-invalid');
                    hasError = true;
                }
            }
        });

        if (hasError) e.preventDefault(); // zastaví odoslanie formulára
    });
});
