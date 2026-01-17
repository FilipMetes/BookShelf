document.addEventListener('DOMContentLoaded', () => {

    const table = document.getElementById('favouriteTable');
    if (!table) return;

    const removeUrl = table.dataset.removeUrl;

    table.addEventListener('click', (e) => {

        if (!e.target.classList.contains('remove-fav')) return;

        const btn = e.target;
        const bookId = btn.dataset.bookId;

        fetch(removeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'book_id=' + encodeURIComponent(bookId)
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById('fav-row-' + bookId);
                    if (row) row.remove();
                }
            })
            .catch(err => console.error(err));
    });
});
