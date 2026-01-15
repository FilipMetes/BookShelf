CREATE OR REPLACE TABLE order_items (
                             id INT AUTO_INCREMENT PRIMARY KEY,

                             id_order INT NOT NULL,
                             id_book INT NOT NULL,
                             countItems INT NOT NULL,

                             UNIQUE KEY uq_order_book (id_order, id_book),

                             KEY idx_book (id_book),

                             CONSTRAINT fk_order_items_order
                                 FOREIGN KEY (id_order)
                                     REFERENCES orders(id)
                                     ON DELETE RESTRICT
                                     ON UPDATE RESTRICT,

                             CONSTRAINT fk_order_items_book
                                 FOREIGN KEY (id_book)
                                     REFERENCES books(id)
                                     ON DELETE RESTRICT
                                     ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
