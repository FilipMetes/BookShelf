document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');

    if (!form) return;

    const fields = [
        {id: 'title', errorId: 'titleError', message: 'Názov knihy je povinný.'},
        {id: 'author', errorId: 'authorError', message: 'Autor je povinný.'},
        {id: 'formatE', radioGroup: 'format', errorId: 'formatError', message: 'Formát knihy musí byť vybraný.'},
        {id: 'price', errorId: 'priceError', message: 'Cena musí byť číslo.'},
        {id: 'number_availible', errorId: 'numberError', message: 'Počet kusov musí byť číslo.'},
        {id: 'pages', errorId: 'pagesError', message: 'Počet strán musí byť číslo.'}
    ];

    form.addEventListener('submit', function (e) {
        let hasError = false;

        // reset errors
        fields.forEach(f => {
            let input = document.getElementById(f.id);
            if (f.radioGroup) {
                input = document.querySelectorAll(`input[name="${f.radioGroup}"]`);
            }
            let errorDiv = document.getElementById(f.errorId);
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = f.errorId;
                errorDiv.classList.add('form-text', 'text-danger');
                if (f.radioGroup) {
                    input[0].closest('div').insertAdjacentElement('afterend', errorDiv);
                } else {
                    input.insertAdjacentElement('afterend', errorDiv);
                }
            }
            errorDiv.textContent = '';
            errorDiv.style.display = 'none';
            if (f.radioGroup) {
                input.forEach(i => i.classList.remove('is-invalid'));
            } else {
                input.classList.remove('is-invalid');
            }
        });

        // check required fields
        fields.forEach(f => {
            if (f.radioGroup) {
                const radios = document.querySelectorAll(`input[name="${f.radioGroup}"]`);
                const checked = Array.from(radios).some(r => r.checked);
                if (!checked) {
                    const errorDiv = document.getElementById(f.errorId);
                    errorDiv.textContent = f.message;
                    errorDiv.style.display = 'block';
                    radios.forEach(r => r.classList.add('is-invalid'));
                    hasError = true;
                }
            } else {
                const input = document.getElementById(f.id);
                const errorDiv = document.getElementById(f.errorId);
                if (input.value.trim() === '') {
                    errorDiv.textContent = f.message;
                    errorDiv.style.display = 'block';
                    input.classList.add('is-invalid');
                    hasError = true;
                } else if (['price','number_availible','pages'].includes(f.id) && isNaN(input.value)) {
                    errorDiv.textContent = f.message;
                    errorDiv.style.display = 'block';
                    input.classList.add('is-invalid');
                    hasError = true;
                }
            }
        });

        if (hasError) e.preventDefault();
    });
});
