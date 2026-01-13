CREATE TABLE order_items (
                             id_order INT(11) NOT NULL,
                             id_book INT(11) NOT NULL,
                             countItems INT(11) NOT NULL,
                             PRIMARY KEY (id_order, id_book),
                             INDEX idx_id_book (id_book),
                             CONSTRAINT fk_order_items_order
                                 FOREIGN KEY (id_order) REFERENCES orders(id)
                                     ON DELETE RESTRICT
                                     ON UPDATE RESTRICT,
                             CONSTRAINT fk_order_items_book
                                 FOREIGN KEY (id_book) REFERENCES books(id)
                                     ON DELETE RESTRICT
                                     ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;