document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('bookSearch');
    const booksGrid = document.querySelector('.books-grid');
    const applyFiltersButton = document.querySelector('.btn-primary'); // "Použiť filtre"
    const resetFiltersButton = document.querySelector('.btn-link'); // "Vymazať"
    const priceSlider = document.getElementById('priceRange');
    const priceCurrent = document.getElementById('priceCurrent');

    if (!booksGrid || !priceSlider) return;

    // Aktualizácia aktuálnej hodnoty slidera
    priceCurrent.textContent = `${priceSlider.value}€`;
    priceSlider.addEventListener('input', () => {
        priceCurrent.textContent = `${priceSlider.value}€`;
    });

    // Funkcia na normalizáciu textu: lowercase, trim, odstráni medzery a bodky
    function normalize(str) {
        if (!str) return '';
        return str
            .toLowerCase()
            .normalize("NFD").replace(/[\u0300-\u036f]/g, "") // odstráni diakritiku
            .replace(/\s+/g, '')
            .replace(/\./g,'')
            .trim();
    }


    function filterBooks() {
        const query = normalize(searchInput.value);
        const bookCards = Array.from(booksGrid.querySelectorAll('.book-card'));

        const selectedGenres = Array.from(document.querySelectorAll('input[id^="genre-"]:checked'))
            .map(cb => normalize(cb.value));
        const selectedAuthors = Array.from(document.querySelectorAll('input[id^="author-"]:checked'))
            .map(cb => normalize(cb.value));
        const selectedFormats = Array.from(document.querySelectorAll('input[id^="format-"]:checked'))
            .map(cb => normalize(cb.value));
        const maxPrice = parseFloat(priceSlider.value) || 200;

        let found = false;

        bookCards.forEach(card => {
            const title = normalize(card.querySelector('.book-title')?.textContent);
            const author = normalize(card.querySelector('.book-author')?.textContent);
            const genreText = normalize(card.querySelector('.book-genre')?.textContent.replace('Žáner:', ''));
            const price = parseFloat(card.querySelector('.book-price')?.textContent.replace('€','')) || 0;
            const format = normalize(card.dataset.format);

            let matches = (title.includes(query) || author.includes(query));
            if (selectedGenres.length && !selectedGenres.some(g => genreText.includes(g))) matches = false;
            if (selectedAuthors.length && !selectedAuthors.includes(author)) matches = false;
            if (selectedFormats.length && !selectedFormats.includes(format)) matches = false;
            if (price > maxPrice) matches = false;

            card.style.display = matches ? '' : 'none';
            if (matches) found = true;
        });

        // "Žiadne knihy nenájdené"
        let noResultsDiv = booksGrid.nextElementSibling;
        if (!noResultsDiv || !noResultsDiv.classList.contains('text-muted')) {
            noResultsDiv = document.createElement('p');
            noResultsDiv.className = 'text-muted mt-2';
            noResultsDiv.textContent = 'Žiadne knihy nenájdené';
            noResultsDiv.style.display = 'none';
            booksGrid.parentNode.insertBefore(noResultsDiv, booksGrid.nextSibling);
        }
        noResultsDiv.style.display = found ? 'none' : 'block';
    }

    // Filtrovanie až po kliknutí "Použiť filtre"
    if (applyFiltersButton) {
        applyFiltersButton.addEventListener('click', filterBooks);
    }

    // Reset filtrov
    if (resetFiltersButton) {
        resetFiltersButton.addEventListener('click', () => {
            // odškrtnutie všetkých checkboxov
            document.querySelectorAll('.filter-section input[type=checkbox]').forEach(cb => cb.checked = false);

            // reset slidera a inputu
            priceSlider.value = 50;
            priceCurrent.textContent = '50€';
            searchInput.value = '';

            // zobraz všetky knihy
            booksGrid.querySelectorAll('.book-card').forEach(card => card.style.display = '');

            // skry text "Žiadne knihy nenájdené"
            const noResultsDiv = booksGrid.nextElementSibling;
            if (noResultsDiv && noResultsDiv.classList.contains('text-muted')) {
                noResultsDiv.style.display = 'none';
            }
        });
    }

});
