document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('rolesForm');
    if (!form) return;

    const url = form.dataset.url;

    form.addEventListener('submit', (e) => {
        e.preventDefault(); // zabráni reloadu

        // zistíme, ktorí užívatelia sú admini
        const checkboxes = form.querySelectorAll('.role-toggle');
        let body = '';
        checkboxes.forEach(cb => {
            if (cb.checked) {
                // pridáme do POST dáta ako "admins[]=id"
                body += 'admins[]=' + cb.dataset.userId + '&';
            }
        });

        // odstránime posledné &
        body = body.slice(0, -1);

        // pošleme dáta cez fetch
        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // aktualizujeme tabuľku: prepíšeme text Admin/Uživatel
                    checkboxes.forEach(cb => {
                        const row = cb.closest('tr');
                        const roleTd = row.querySelector('.role-text');
                        roleTd.textContent = cb.checked ? 'Admin' : 'Uživatel';
                    });
                } else {
                    alert('Nepodarilo sa uložiť zmeny!');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Chyba pri ukladaní zmien!');
            });
    });
});
