document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('favBtn');
    if (!btn) {
        return;
    }

    btn.addEventListener('click', function () {
        const bookId = this.dataset.bookId;
        const url = this.dataset.url;

        // fetch v skrátenej forme
        fetch(url, {
            method: 'POST',
            body: new URLSearchParams({ book_id: bookId })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.disabled = true;
                    this.classList.replace('btn-outline-danger', 'btn-danger');
                    document.getElementById('favMsg').style.display = 'inline';
                }
            })
            .catch(console.error);
    });
});
