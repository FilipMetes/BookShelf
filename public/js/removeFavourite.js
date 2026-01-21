document.addEventListener('DOMContentLoaded', () => {
    const table = document.getElementById('favouriteTable');
    if (!table) {
        return;
    }

    const url = table.dataset.removeUrl;

    table.addEventListener('click', (e) => {
        if (!e.target.classList.contains('remove-fav')) return;

        const bookId = e.target.dataset.bookId;
        if (!bookId) return;

        /*
            Vypracované s pomocou AI
        */
        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ book_id: bookId })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Odstránime riadok z tabuľky
                    const row = document.getElementById('fav-row-' + bookId);
                    row?.remove();
                }
            })
            .catch(err => console.error('Chyba pri fetch:', err));
        /*
            koniec AI
        */
    });

});
