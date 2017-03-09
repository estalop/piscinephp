<?php

function connect_db($db)
{
  $mysqli = mysqli_init();

  if (!mysqli_real_connect($mysqli, "localhost", "jbobin", "", $db)) {
      echo 'Connect Error (' . mysqli_connect_errno() . ') '.mysqli_connect_error();
      return (false);
  }
  return($mysqli);
}

?>
