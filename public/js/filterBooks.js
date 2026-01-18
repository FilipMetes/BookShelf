document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('bookSearch');
    const searchBtn = document.getElementById('searchButton');
    const applyBtn = document.getElementById('applyFilters');
    const resetBtn = document.getElementById('resetFilters');

    const priceRange = document.getElementById('priceRange');
    const priceText = document.getElementById('priceCurrent');

    const cards = [...document.querySelectorAll('.book-card')];

    // ===== Cena =====
    priceText.textContent = priceRange.value + '€';
    priceRange.addEventListener('input', () => {
        priceText.textContent = priceRange.value + '€';
    });

    function normalize(text) {
        return text
            ? text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim()
            : '';
    }

    function getChecked(prefix) {
        return [...document.querySelectorAll(`input[id^="${prefix}"]:checked`)]
            .map(i => normalize(i.value));
    }

    function filterBooks() {
        const query = normalize(searchInput.value);
        const maxPrice = Number(priceRange.value);

        const genres  = getChecked('genre-');
        const authors = getChecked('author-');
        const formats = getChecked('format-');

        cards.forEach(card => {
            const title  = normalize(card.querySelector('.book-title')?.textContent);
            const author = normalize(card.dataset.author);
            const genre  = normalize(card.dataset.genre);
            const format = normalize(card.dataset.format);
            const price  = Number(card.dataset.price);

            let visible = true;

            if (query && !title.includes(query) && !author.includes(query)) visible = false;
            if (genres.length && !genres.includes(genre)) visible = false;
            if (authors.length && !authors.includes(author)) visible = false;
            if (formats.length && !formats.includes(format)) visible = false;
            if (price > maxPrice) visible = false;

            card.style.display = visible ? '' : 'none';
        });
    }

    // ===== Eventy =====
    searchBtn.addEventListener('click', filterBooks);
    applyBtn?.addEventListener('click', filterBooks);

    searchInput.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterBooks();
        }
    });

    resetBtn?.addEventListener('click', () => {
        document.querySelectorAll('.filter-section input').forEach(i => i.checked = false);
        searchInput.value = '';
        priceRange.value = 50;
        priceText.textContent = '50€';

        cards.forEach(card => card.style.display = '');
    });

});

