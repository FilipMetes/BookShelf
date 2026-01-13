CREATE TABLE orders (
    id INT(11) NOT NULL AUTO_INCREMENT,
    id_user INT(11) NOT NULL,
    date DATE NOT NULL,
    delivery VARCHAR(15),
    state CHAR(1),
    PRIMARY KEY (id),
    INDEX idx_id_user (id_user),
    CONSTRAINT fk_id_user
        FOREIGN KEY (id_user) REFERENCES users(id)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;