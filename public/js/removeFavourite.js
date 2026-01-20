document.addEventListener('DOMContentLoaded', () => {
    const table = document.getElementById('favouriteTable');
    if (!table) {
        return;
    }

    const url = table.dataset.removeUrl;

    table.addEventListener('click', (e) => {
        if (!e.target.classList.contains('remove-fav')) {
            return;
        }

        const bookId = e.target.dataset.bookId;

        // jednoduchý fetch
        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `book_id=${encodeURIComponent(bookId)}`
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById('fav-row-' + bookId);
                    if (row) {
                        row.remove();
                    }
                }

            })
            .catch(console.error);
    });
});
