document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('rolesForm');
    if (!form) return;

    const url = form.dataset.url;

    form.addEventListener('submit', (e) => {
        e.preventDefault(); // zabráni reloadu

        /*
            Vypracované s pomocou AI
        */
        const checkboxes = form.querySelectorAll('.role-toggle');
        const admins = [];

        checkboxes.forEach(cb => {
            if (cb.checked) {
                admins.push(parseInt(cb.dataset.userId));
            }
        });
        /*
            koniec AI
        */

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ admins })
        })
            .then(res => res.json())
            .then(data => {
                /*
                 Vypracované s pomocou AI
                 */
                if (data.success) {
                    checkboxes.forEach(cb => {
                        const row = cb.closest('tr');
                        const roleTd = row.querySelector('.role-text');
                        roleTd.textContent = cb.checked ? 'Admin' : 'Uživatel';
                    });
                }
                /*
                 koniec
                 */
            })
            .catch(err => {
                console.error(err);
            });
    });
});
