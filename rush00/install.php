<?php
include "db.php";
session_start();
$_SESSION['login'] = FALSE;
$_SESSION['basket'] = FALSE;
if ($mysqli = connect_db(""))
{
  $request = "create DATABASE IF NOT EXISTS Rush00;use Rush00;
    create TABLE IF NOT EXISTS users (id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    username VARCHAR(255), pwd VARCHAR(128));
    create TABLE IF NOT EXISTS basket (id_product INT NOT NULL,
    n_product INT, id_user INT, PRIMARY KEY(id_product, id_user));
    create TABLE IF NOT EXISTS categories (id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, name VARCHAR(255));
    create TABLE IF NOT EXISTS product (id_product INT PRIMARY KEY AUTO_INCREMENT NOT NULL, category_id INT,
    name VARCHAR(255), description TEXT, img TEXT, stock INT, price INT);
    create TABLE IF NOT EXISTS basketa (id_product INT NOT NULL,
    n_product INT, id_user INT);
    insert INTO product VALUES (1, 1, 'windows 10', 'ceci est windows 10 (sans blague)',
    'http://media.begeek.fr/2014/01/windows-8-1.jpg', 99, 200);
    insert INTO product VALUES (2, 1, 'linux', 'ceci est linux (sans blague)',
    'http://www.depannage-pc-angers.fr/wp-content/uploads/2013/04/logo-linux.png', 99, 4000);
    insert INTO product VALUES (3, 2, 'patate', 'ceci est une patate (sans blague)',
    'https://blogdemaths.files.wordpress.com/2014/02/une-patate.jpg', 5, 2);
    insert INTO categories VALUES (1, 'systems');
    insert INTO categories VALUES (2, 'patates');
    insert INTO users VALUES (1, 'admin', '6a4e012bd9583858a5a6fa15f58bd86a25af266d3a4344f1ec2018b778f29ba83be86eb45e6dc204e11276f4a99eff4e2144fbe15e756c2c88e999649aae7d94');";
    if (!($result = mysqli_multi_query($mysqli, $request))) {
          echo $request." : erreur : ".mysqli_error($mysqli)."\n";
      }
    mysqli_close($mysqli);
	header('Location: index.php');
  }
else {
  echo "unable to connect to database\n";
}

?>
