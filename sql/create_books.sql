CREATE OR REPLACE TABLE books (
                                     id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                     title VARCHAR(50) NOT NULL,
                                     author VARCHAR(50) NOT NULL,
                                     genre VARCHAR(20) NOT NULL,
                                     format CHAR(1) NOT NULL,
                                     year INT(11) NOT NULL,
                                     price DECIMAL(10,0) NOT NULL,
                                     number_availible INT(11) NOT NULL,
                                     pages INT(11) NOT NULL,
                                     text TEXT NULL,
                                     sample_path VARCHAR(255) NULL,
                                     cover_path VARCHAR(255) NULL,
                                     UNIQUE (title, author)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;