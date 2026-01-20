document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.role-toggle');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const userId = this.dataset.userId;
            const newRole = this.checked ? 'A' : 'U';
            const url = this.dataset.url;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'user_id=' + encodeURIComponent(userId) +
                    '&role=' + encodeURIComponent(newRole)
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // nájdeme td, kde sa zobrazuje rola a prepíšeme text
                        const row = this.closest('tr');
                        const roleTd = row.querySelector('.role-text');
                        roleTd.textContent = newRole === 'A' ? 'Admin' : 'Uživatel';
                    } else {
                        alert('Nepodarilo sa zmeniť rolu!');
                        // vrátime checkbox späť do pôvodnej hodnoty
                        this.checked = !this.checked;
                    }
                })
                .catch(err => {
                    console.error(err);
                    this.checked = !this.checked;
                });
        });
    });
});
