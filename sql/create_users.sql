-- SQL migration to create users table with requested schema

CREATE OR REPLACE TABLE users (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(20) NOT NULL,
  surname VARCHAR(20) NOT NULL,
  city VARCHAR(50) NULL,
  PSC CHAR(5) NULL,
  street VARCHAR(50) NULL,
  e_mail VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role CHAR(1) NOT NULL DEFAULT 'U'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

