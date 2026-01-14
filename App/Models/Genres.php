<?php

namespace App\Models;

class Genres {
    const HORROR = 'Horror';
    const AKCNE = 'Akčné';
    const ROMANTICKE = 'Romantické';
    const SCIFI = 'Sci‑Fi';
    const FANTASY = 'Fantasy';
    const MYSTERY = 'Mystery';
    const NONFICTION = 'Non‑fiction';
    const THRILLER = 'Thriller';
    const HISTORICAL = 'Historické';
    const BIOGRAPHY = 'Biografie';
    const COMEDY = 'Komédia';
    const DETECTIVE = 'Detektívka';
    const YOUNG_ADULT = 'Young Adult';
    const CHILDREN = 'Detské';
    const POETRY = 'Poézia';
    const ADVENTURE = 'Dobrodružné';
    const ROMANCE = 'Romantika';
    const SCI_FI_FANTASY = 'Sci-Fi/Fantasy';
    const SELF_HELP = 'Self-Help';
    const CLASSIC = 'Klasika';

    public static function all(): array {
        return [
            self::HORROR,
            self::AKCNE,
            self::ROMANTICKE,
            self::ROMANCE,
            self::SCIFI,
            self::FANTASY,
            self::SCI_FI_FANTASY,
            self::MYSTERY,
            self::THRILLER,
            self::HISTORICAL,
            self::BIOGRAPHY,
            self::COMEDY,
            self::DETECTIVE,
            self::YOUNG_ADULT,
            self::CHILDREN,
            self::POETRY,
            self::ADVENTURE,
            self::NONFICTION,
            self::SELF_HELP,
            self::CLASSIC,
        ];
    }
}
