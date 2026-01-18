document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('bookSearch');
    const searchBtn = document.getElementById('searchButton');
    const applyBtn = document.getElementById('applyFilters');
    const resetBtn = document.getElementById('resetFilters');

    const price = document.getElementById('priceRange');
    const priceText = document.getElementById('priceCurrent');

    const slots = [...document.querySelectorAll('.books-grid > div[class*="col-"]')];
    const cards = slots.map(s => s.querySelector('.book-card'));

    // cena slider
    priceText.textContent = price.value + '€';
    price.addEventListener('input', () => priceText.textContent = price.value + '€');

    function normalize(text) {
        return text
            ? text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim()
            : '';
    }

    function filterBooks() {
        const query = normalize(searchInput.value);
        const maxPrice = Number(price.value);

        const genres = [...document.querySelectorAll('input[id^="genre-"]:checked')]
            .map(i => normalize(i.value));

        const authors = [...document.querySelectorAll('input[id^="author-"]:checked')]
            .map(i => normalize(i.value));

        const formats = [...document.querySelectorAll('input[id^="format-"]:checked')]
            .map(i => normalize(i.value));

        // odstránime všetky knihy zo slotov
        slots.forEach(s => s.querySelector('.book-card')?.remove());

        let index = 0;

        cards.forEach(card => {
            const title = normalize(card.querySelector('.book-title')?.textContent);
            const author = normalize(card.dataset.author);
            const genre = normalize(card.dataset.genre);
            const format = normalize(card.dataset.format);
            const price = Number(card.dataset.price);

            if (query && !title.includes(query) && !author.includes(query)) return;
            if (genres.length && !genres.includes(genre)) return;
            if (authors.length && !authors.includes(author)) return;
            if (formats.length && !formats.includes(format)) return;
            if (price > maxPrice) return;

            if (slots[index]) {
                slots[index].appendChild(card);
                index++;
            }
        });
    }

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
        price.value = 50;
        priceText.textContent = '50€';

        slots.forEach(s => s.querySelector('.book-card')?.remove());
        cards.forEach((c, i) => slots[i]?.appendChild(c));
    });

});
