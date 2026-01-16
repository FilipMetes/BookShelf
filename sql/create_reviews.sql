CREATE OR REPLACE TABLE reviews (
                         id INT(11) NOT NULL AUTO_INCREMENT,
                         id_book INT(11) NOT NULL,
                         id_user INT(11) NOT NULL,
                         rating INT(11) NOT NULL,
                         review TEXT,
                         date DATE NOT NULL,

                         PRIMARY KEY (id),

                         UNIQUE KEY uq_order_book (id_book, id_user),

                         CONSTRAINT fk_reviews_book
                             FOREIGN KEY (id_book)
                                 REFERENCES books(id)
                                 ON DELETE RESTRICT
                                 ON UPDATE RESTRICT,

                         CONSTRAINT fk_reviews_user
                             FOREIGN KEY (id_user)
                                 REFERENCES users(id)
                                 ON DELETE RESTRICT
                                 ON UPDATE RESTRICT
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;
