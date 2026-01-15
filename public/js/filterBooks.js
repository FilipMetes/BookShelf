document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('bookSearch');
    const searchButton = document.getElementById('searchButton');

    const grid = document.querySelector('.books-grid');
    const price = document.getElementById('priceRange');
    const priceText = document.getElementById('priceCurrent');

    const applyBtn = document.getElementById('applyFilters');
    const resetBtn = document.getElementById('resetFilters');

    if (!grid || !price || !searchInput) return;

    // --- Cena slider ---
    priceText.textContent = price.value + '€';
    price.addEventListener('input', () => {
        priceText.textContent = price.value + '€';
    });

    // --- Normalizácia textu (diakritika, lowercase) ---
    const normalize = text =>
        text
            ? text.toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .trim()
            : '';

    // --- Checkbox helper ---
    function getChecked(prefix) {
        return [...document.querySelectorAll(`input[id^="${prefix}-"]:checked`)]
            .map(cb => normalize(cb.value));
    }

    // --- Hlavná filtračná funkcia ---
    function filterBooks() {
        const query = normalize(searchInput.value);
        const genres = getChecked('genre');
        const authors = getChecked('author');
        const formats = getChecked('format');
        const maxPrice = Number(price.value);

        let visible = false;

        document.querySelectorAll('.book-card').forEach(card => {

            const title = normalize(card.querySelector('.book-title')?.textContent);
            const author = normalize(card.dataset.author);
            const genre = normalize(card.dataset.genre);
            const format = normalize(card.dataset.format);
            const bookPrice = Number(card.dataset.price);

            let show = true;

            // vyhľadávanie
            if (query && !title.includes(query) && !author.includes(query)) {
                show = false;
            }

            // filtre
            if (genres.length && !genres.includes(genre)) show = false;
            if (authors.length && !authors.includes(author)) show = false;
            if (formats.length && !formats.includes(format)) show = false;
            if (bookPrice > maxPrice) show = false;

            card.style.display = show ? '' : 'none';
            if (show) visible = true;
        });

        // správa „nič nenájdené“
        let msg = grid.nextElementSibling;
        if (!msg) {
            msg = document.createElement('p');
            msg.className = 'text-muted mt-2';
            msg.textContent = 'Žiadne knihy nenájdené';
            grid.after(msg);
        }
        msg.style.display = visible ? 'none' : 'block';
    }

    // --- Eventy ---
    searchButton.addEventListener('click', filterBooks);

    searchInput.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterBooks();
        }
    });

    applyBtn?.addEventListener('click', filterBooks);

    resetBtn?.addEventListener('click', () => {
        document.querySelectorAll('.filter-section input').forEach(i => i.checked = false);
        searchInput.value = '';
        price.value = 50;
        priceText.textContent = '50€';

        document.querySelectorAll('.book-card').forEach(c => c.style.display = '');

        const msg = grid.nextElementSibling;
        if (msg) msg.style.display = 'none';
    });


});
