<?php
	session_start();
	include 'db.php';
	if ($mysqli = connect_db("Rush00"))
	{
		foreach ($_POST as $key => $value) {
			$_POST[$key] = mysqli_real_escape_string ($mysqli, $value);
		}
		foreach ($_GET as $key => $value) {
	 		$_GET[$key] = mysqli_real_escape_string ($mysqli, $value);
		}
		mysqli_close($mysqli);
	}
	if ($_GET['valider'])
	{
		if ($_SESSION['login'] && $mysqli = connect_db("Rush00"))
		{
			$request = "select * from basket where id_user=".$_SESSION['login'].";";
			if (!($result = mysqli_query($mysqli, $request))) {
					echo $request." : erreur : ".mysqli_error($mysqli)."\n";
			}
			else {
				$string = mysqli_fetch_all($result);
			}
			if ($string)
			{
				foreach ($string as $value) {
					$request = "insert INTO basketa (id_product, n_product, id_user) VALUES (".$value[0].", ".$value[1].", ".$value[2].");";
					if (!($result = mysqli_query($mysqli, $request))) {
							echo $request." : erreur : ".mysqli_error($mysqli)."\n";
					}
					$request = "delete from basket;";
					if (!($result = mysqli_query($mysqli, $request))) {
							echo $request." : erreur : ".mysqli_error($mysqli)."\n";
					}
				}
				header('Location: index.php');
			}
			else
				echo 'Votre panier est vide, achete ma merde !!!';
		}
		else
		{
			echo 'Vous devez vous connecter pour valider votre panier';
		}
	}
	if ($_GET['ajouter'])
	{
			$bool = -1;
			if ($_SESSION['login'] && $mysqli = connect_db("Rush00"))
			{
				$request = "select * from basket where id_user=".$_SESSION['login'].";";
				if (!($result = mysqli_query($mysqli, $request))) {
						echo $request." : erreur : ".mysqli_error($mysqli)."\n";
				}
				else {
					$string = mysqli_fetch_all($result);
				}
				if ($string)
				{
					foreach ($string as $key => $value) {
						if ($value[0] == $_GET['ajouter'])
							$bool = $key;
					}
				}
				if ($bool != -1)
				{
					$request = "UPDATE basket SET n_product=".++$string[$bool][1]." WHERE id_product=".$_GET['ajouter'].";";
					if (!($result = mysqli_query($mysqli, $request))) {
							echo $request." : erreur : ".mysqli_error($mysqli)."\n";
					}
				}
				else
				{
					$request = "insert INTO basket VALUES (".$_GET['ajouter'].", 1, ".$_SESSION['login'].");";
					if (!($result = mysqli_query($mysqli, $request))) {
							echo $request." : erreur : ".mysqli_error($mysqli)."\n";
					}
				}
				mysqli_close($mysqli);
			}
			elseif ($mysqli = connect_db("Rush00"))
			{
				if ($_SESSION['basket'])
				{
					foreach ($_SESSION['basket'] as $key => $value) {
						if ($value[0] == $_GET['ajouter'])
							$bool = $key;
					}
				}
				if ($bool != -1)
				{
					$_SESSION['basket'][$bool]['1']++;
				}
				else
				{
					$_SESSION['basket'][] = array($_GET['ajouter'], 1);
				}
			}
		}
	if ($_GET['supprimer'])
	{
		$bool = -1;
		if ($_SESSION['login'] && $mysqli = connect_db("Rush00"))
		{
			$request = "select * from basket where id_user=".$_SESSION['login'].";";
			if (!($result = mysqli_query($mysqli, $request))) {
					echo $request." : erreur : ".mysqli_error($mysqli)."\n";
			}
			else {
				$string = mysqli_fetch_all($result);
			}
			if ($string)
			{
				foreach ($string as $key => $value) {
					if ($value[0] == $_GET['supprimer'])
						$bool = $key;
				}
			}
			if ($bool != -1 && $string[$bool][1] > 1)
			{
				$request = "UPDATE basket SET n_product=".--$string[$bool][1]." WHERE id_product=".$_GET['supprimer'].";";
				if (!($result = mysqli_query($mysqli, $request))) {
						echo $request." : erreur : ".mysqli_error($mysqli)."\n";
				}
			}
			elseif ($bool != -1)
			{
				$request = "delete from basket where id_product=".$_GET['supprimer'].";";
				if (!($result = mysqli_query($mysqli, $request))) {
						echo $request." : erreur : ".mysqli_error($mysqli)."\n";
				}
			}
			mysqli_close($mysqli);
		}
		elseif ($mysqli = connect_db("Rush00"))
		{
			if ($_SESSION['basket'])
			{
				foreach ($_SESSION['basket'] as $key => $value) {
					if ($value[0] == $_GET['supprimer'])
						$bool = $key;
				}
			}
			if ($bool != -1 && $_SESSION['basket'][$bool]['1'] > 1)
			{
				$_SESSION['basket'][$bool]['1']--;
			}
			elseif ($bool != -1) {
				unset($_SESSION['basket'][$bool]);
			}
			else {
				// echo "noting to destroy";
			}
		}
	}
	if ($mysqli = connect_db("Rush00"))
	{
		if ($_SESSION['login']) {
			$message = "Connecté";
			if ($mysqli = connect_db("Rush00"))
			{
				$request = "select * from basket;";
				if (!($result = mysqli_query($mysqli, $request))) {
					echo $request." : erreur : ".mysqli_error($mysqli)."\n";
				}
				else {
					$string = mysqli_fetch_all($result);
				}
				foreach ($string as $value) {
					$nb = $nb + $value[1];
					$request = "select price from product where id_product='".$value['0']."';";
					if (!($result = mysqli_query($mysqli, $request)))
					{
						echo $request." : erreur : ".mysqli_error($mysqli)."\n";
					}
					else
					{
						$array = mysqli_fetch_all($result);
						$ob_price = $array['0']['0'] * $value['1'];
					}
					$price = $price + $ob_price;
					$all_ob_price[] = array($ob_price, $value[0]);
				}
				mysqli_close($mysqli);
			}
		}
		elseif($_SESSION['basket'])
		{
			if ($mysqli = connect_db("Rush00"))
			{
				foreach ($_SESSION['basket'] as $value)
				{
					$nb = $nb + $value[1];
					$request = "select price from product where id_product='".$value[0]."';";
					if (!($result = mysqli_query($mysqli, $request)))
					{
						echo $request." : erreur : ".mysqli_error($mysqli)."\n";
					}
					else {
						$string = mysqli_fetch_all($result);
						$ob_price = $string['0']['0'] * $value['1'];
					}
					$price = $price + $ob_price;
					$all_ob_price[] = array($ob_price, $value[0]);
				}
				mysqli_close($mysqli);
				$string = $_SESSION['basket'];
			}
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="panier.css"/>
		<link rel="stylesheet" href="main.css"/>
		<meta charset="utf-8">
		<title>Panier</title>
	</head>
	<body>
		<div id="title">
			<h1>Panier</h1>
		</div>
		<?php  ?>
		<div id="tableau">
			<?php if ($mysqli = connect_db("Rush00"))
			{
				$request = "select * from product;";
				if (!($result = mysqli_query($mysqli, $request)))
					echo $request." : erreur : ".mysqli_error($mysqli)."\n";
				else
				{
					$newstring = mysqli_fetch_all($result);
					foreach ($newstring as $key => $value)
					{
						echo 'Nombre de <strong>'.strtoupper($value[2]).'</strong> a '.$value[6].'$ dans le panier: ';
						if ($string)
						{
							foreach ($string as $supervalue) {
								if($supervalue[0] == $value[0]){echo $supervalue[1].'</br>'; $yolo = TRUE;}
							}
						}
						if (!$yolo){echo "0</br>";}$yolo = FALSE;
						echo '<form action="./panier.php" method="GET">
							<button type="submit" name="ajouter" value="'.$value[0].'">+</button>
							<button type="submit" name="supprimer" value="'.$value[0].'">-</button>
						</form>';
					}
					echo '<p>Prix Total :'.$price.'$</p>';
				}
	}?>
		</div>
		<div>
		<form action="./panier.php" method="GET">
			<input type="submit" name="valider" value="valider">
		</form>
		</div>
	<footer>
		<a href="index.php">
			<img style="height:50px; margin-left:50%; margin-top:30%" src="http://playbd24.mobie.in/icon/home.png"/>
		</a>
	</footer>
	</body>
</html>
