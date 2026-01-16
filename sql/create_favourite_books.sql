CREATE OR REPLACE TABLE favourite_books (
                                 id INT(11) AUTO_INCREMENT PRIMARY KEY,
                                 id_user INT(11) NOT NULL,
                                 id_book INT(11) NOT NULL,
                                 date DATE NOT NULL,

                                 UNIQUE KEY uq_order_book (id_user, id_book),

                                 CONSTRAINT fk_fav_user
                                     FOREIGN KEY (id_user) REFERENCES users(id)
                                         ON DELETE RESTRICT
                                         ON UPDATE RESTRICT,

                                 CONSTRAINT fk_fav_book
                                     FOREIGN KEY (id_book) REFERENCES books(id)
                                         ON DELETE RESTRICT
                                         ON UPDATE RESTRICT
);
