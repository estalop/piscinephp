create DATABASE IF NOT EXIST Rush00;
use Rush00;
create TABLE IF NOT EXIST users (
  id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  username VARCHAR(255),
  pwd VARCHAR(128)
);
create TABLE IF NOT EXIST basket (
  id_user INT NOT NULL,
  id_product INT NOT NULL,
  n_product INT,
  PRIMARY KEY(id_user, id_product)
);
create TABLE IF NOT EXIST product(
  id_product INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  name VARCHAR(255),
  description TEXT,
  img TEXT,
  stock INT
);
