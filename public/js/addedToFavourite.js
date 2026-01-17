document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('favBtn');
    if (!btn) return;

    btn.addEventListener('click', function () {
        const bookId = this.dataset.bookId;
        const url = this.dataset.url;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'book_id=' + bookId
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    this.disabled = true;
                    this.classList.remove('btn-outline-danger');
                    this.classList.add('btn-danger');
                    document.getElementById('favMsg').style.display = 'inline';
                }
            });
    });
});
