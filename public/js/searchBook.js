document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('bookSearch');
    const searchButton = document.getElementById('searchButton');
    const booksGrid = document.querySelector('.books-grid');

    if (!searchInput || !searchButton || !booksGrid) return;

    // Vytvoríme element pre hlášku "Žiadne knihy nenájdené"
    let noResultsDiv = document.createElement('p');
    noResultsDiv.className = 'text-muted mt-2';
    noResultsDiv.textContent = 'Žiadne knihy nenájdené';
    noResultsDiv.style.display = 'none';
    booksGrid.parentNode.insertBefore(noResultsDiv, booksGrid.nextSibling);

    function filterBooks() {
        const query = searchInput.value.trim().toLowerCase();
        const bookCards = Array.from(booksGrid.querySelectorAll('.book-card'));
        let found = false;

        bookCards.forEach(card => {
            const title = card.querySelector('.book-title')?.textContent.toLowerCase() || '';
            const author = card.querySelector('.book-author')?.textContent.toLowerCase() || '';

            if (title.includes(query) || author.includes(query)) {
                card.style.display = '';
                found = true;
            } else {
                card.style.display = 'none';
            }
        });

        noResultsDiv.style.display = found ? 'none' : 'block';
    }

    searchButton.addEventListener('click', filterBooks);

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterBooks();
        }
    });
});
