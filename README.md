O aplikácii

BookShelf je webová aplikácia, ktorá predstavuje e-shop pre nákup kníh. 

Bežný uživatelia môžu prehľadávať katalóg kníh spolu s jeho filtrovaním pre lepší zážitok. Taktiež majú možnosť
prezerať detail knihy, pridať recenziu ku knihe, pridať knihu do obľúbených a následné pridanie knihy do košíka. 
Po registrácii má uživateľ možnosť prehliadať a upravovať svoj profil.

Administrátor má rovnaké možnosti ako bežný uživateľ s tým, že môže do katalógu knihy pridávať a následne ich upravovať
alebo mazať. Taktieť má vlastnu sekciu admin, v ktorej môže spravovať práva uživateľov a vybavovať ich objednávky.

Projekt je vypracovaný vo frameworku 'vaiicko' ponúkaný fakultou FRI Žilinskej Univerzity v Žiline

Postup inštalácie

1. Spustenie aplikácii PHPStorm a Docker
2. Naklonovanie repozitára do PHPStorm: https://github.com/FilipMetes/BookShelf.git
3. Spustenie 'services' v súbore docker/docker-compose.yml nachádzajúci sa v koreňovom adresári: skript vytvorí v docker kontajner pre našu aplikáciu s týmito službami:
    1. web server (Apache) s PHP 8.3
    2. MariaDB databázový server s predvytvorenou databázou
    3. Adminer aplikáciu pre administráciu MariaDB
4. Pripojenie databázy do PHPStorm: údaje na prihlásenie sa do databázy sú v súbore docker/.env
5. Spustenie sql dotazov na vytvorenie entít v priečinku /sql, nachádzajúci sa v koreňovom adresári, v tomto poradí: 
    1. create_users.sql
    2. create_books.sql
    3. create_orders.sql
    4. create_orderItems.sql
    5. create_favourite_books.sql
    6. create_reviews.sql
6. Spustenie sql dotazov na naplnenie databázy:
    1. fillUsers.sql
    2. fillBooks.sql
7. Zadanie adresy 'http://localhost/' do webového prehliadača

Ďalšie poznámky

Adminer je dostupný http://localhost:8080/.

Webová stránka je dostupná na http://localhost/.




    


