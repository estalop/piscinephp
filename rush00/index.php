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
	$message = '';
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
	if ($_GET['deconnection'] == "deconnection")
	{
		$_SESSION['login'] = FALSE;
		$_SESSION['basket'] = FALSE;
		if ($mysqli = connect_db("Rush00"))
		{
			$request = "delete from basket;";
			if (!($result = mysqli_query($mysqli, $request))) {
						echo $request." : erreur : ".mysqli_error($mysqli)."\n";
			}
			mysqli_close($mysqli);
		}
	}
	if ($_SESSION['login'] == FALSE && $_POST['submit'] == "OK")
	{
		$passwd = hash("whirlpool", $_POST['passwd']);
		if ($mysqli = connect_db("Rush00"))
		{
			$request = "select * from users where username='".$_POST['login']."';";
			if (!($result = mysqli_query($mysqli, $request))) {
						echo $request." : erreur : ".mysqli_error($mysqli)."\n";
		}
		else {
			$string = mysqli_fetch_all($result);
		}
		if ($string['0']['1'] == $_POST['login'] && $string[0]['2'] == $passwd)
		{
			$_SESSION['login'] = $string['0']['0'];
			if (is_array($_SESSION['basket']) || is_object($_SESSION['basket']))
			{
			foreach ($_SESSION['basket'] as $key => $value) {
				$request = "insert INTO basket VALUES (".$value['0'].", ".$value['1'].", ".$_SESSION['login'].");";
				if (!($result = mysqli_query($mysqli, $request))) {
							echo $request." : erreur : ".mysqli_error($mysqli)."\n";
					}
			}
			}
			$_SESSION['basket'] = FALSE;
		}
		else {
			$message = "Non Connecté";
		}
		mysqli_close($mysqli);
		}
	}
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
	}
	foreach ($string as $value) {
		$nb = $nb + $value[1];
		$request = "select price from product where id_product='".$value['0']."';";
		if (!($result = mysqli_query($mysqli, $request))) {
			echo $request." : erreur : ".mysqli_error($mysqli)."\n";
		}
		else {
			$array = mysqli_fetch_all($result);
			$ob_price = $array['0']['0'] * $value['1'];
		}
		$price = $price + $ob_price;
	}
	mysqli_close($mysqli);
}
elseif($_SESSION['basket'])
{
	if ($mysqli = connect_db("Rush00"))
	{
		foreach ($_SESSION['basket'] as $value) {
			$nb = $nb + $value[1];
			$request = "select price from product where id_product='".$value['0']."';";
			if (!($result = mysqli_query($mysqli, $request))) {
				echo $request." : erreur : ".mysqli_error($mysqli)."\n";
			}
			else {
				$string = mysqli_fetch_all($result);
				$ob_price = $string['0']['0'] * $value['1'];
			}
				$price = $price + $ob_price;
		}
	}
	mysqli_close($mysqli);
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<link rel="stylesheet" href="main.css"/>
		<title>Rush00</title>
	</head>
	<body>
		<div id="title">
			<h1>Achete mon Swag</h1>
		</div>
		<div id="left_tab">
			<center>
			</br>
			<div id="panier">
				<center>
				<a href="panier.php">
					<img style="width: 75%; height: auto;" src="http://s3-eu-west-1.amazonaws.com/websites-s3-offload/wp-content/uploads/sites/2/2015/01/panier-moyen.png" alt="panier" title="Voir panier"/>
				</a>
				</br>
				<table><tr><td>Nb articles:</td><td><?php if(!$nb){echo '0';}else{echo $nb;}?></td></tr></br>
						<tr><td>Prix:</td><td><?php if(!$price){echo '0$';}else{echo $price.'$';}?></td></tr>
				</table>
				</center>
			</div>
			<div id="identification">
				<img style = "width: 50%; height: auto;" src="http://image.flaticon.com/icons/png/128/149/149068.png" alt="identification" title="Identification"/>
				<?php if(!empty($message) && $message == "Connecté") : ?>
					<p><?php echo $message."<form action=\"index.php\" method=\"\">
					<input type=\"submit\" name=\"deconnection\" value=\"deconnection\" />
					</form>
					<form action=\"params.php\">
					<input type=\"submit\" name=\"Parametre Personnel\" value=\"Paramettre Personnel\"/></form>"?></p>
				<?php else : ?>
				<p><?php echo $message."\n<form action=\"index.php\" method=\"POST\">
					Identifiant:<br/> <input type=\"text\" name=\"login\" size=\"15\" value=\"\"/>
					<br/>
					Mot de passe:</br> <input type=\"password\" name=\"passwd\" size=\"15\" value=\"\"/>
					<br/>
					<input type=\"submit\" name=\"submit\" value=\"OK\" />
				</form>
				</div>
				<div id =\"creation_compte\">
					<a href=\"./create_account.php\">
						<img style = \"width: 70%; height: auto;\" src=\"http:\/\/www.synergymaxx.com/images/signup_icon.png\" alt=\"nouveau compte\" title=\"Nouveau compte\"/>
					</a>
				</div>"?></p>
				<?php endif; ?>
			</center>
		</div>
		<div id="items">
			<table>
				<tr>
					<?php if ($mysqli = connect_db("Rush00"))
					{
						$request = "select * from product;";
						if (!($result = mysqli_query($mysqli, $request)))
							echo $request." : erreur : ".mysqli_error($mysqli)."\n";
						else
						{
							$string = mysqli_fetch_all($result);
							foreach ($string as $key => $value)
							{
								echo '<td id="show">';
								echo '<p>Categorie:  ';
								$request2 = "select name from categories where id=".$value[1].";";
								$result2 = mysqli_query($mysqli, $request2);
								$string2 = mysqli_fetch_all($result2);
								echo strtoupper($string2[0][0]);
								echo '<img style="height=auto; width:200px;" src="'.$value[4].'" />';
								echo 'Stock: '.$value[5].'<br/>Prix: '.$value[6].'$<br/>
								<form action="index.php" method="GET">
								<button type="submit" name="ajouter" value="'.$value[0].'">ajouter</button>
								</form>
								<form action="index.php" method="GET">
								<button type="submit" name="supprimer" value="'.$value[0].'">supprimer</button
								</form>';
							}
						}
					}
					?>
				</tr>
			</table>
		</div>
	</body>
</html>
