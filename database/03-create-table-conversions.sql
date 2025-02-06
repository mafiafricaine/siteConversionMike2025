CREATE TABLE conversions (
  id int(11) NOT NULL AUTO_INCREMENT,
  currency_from char(3) DEFAULT NULL,
  currency_to char(3) DEFAULT NULL,
  amount float NOT NULL,
  user_id int(11) NOT NULL,

  PRIMARY KEY (id),
  KEY fk_user_id (user_id),
  CONSTRAINT fk_user_id FOREIGN KEY(user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
