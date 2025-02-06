CREATE TABLE users (
  id int NOT NULL AUTO_INCREMENT,
  username varchar(30) NOT NULL,
  email varchar(140) NOT NULL,
  password varchar(255) NOT NULL,

  PRIMARY KEY (id),
  UNIQUE KEY username (username),
  UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
