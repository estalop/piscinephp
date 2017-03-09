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
	if ($_GET['effacer_factures'] && $mysqli = connect_db("Rush00"))
	{
		$request = "delete from basketa;";
		if (!($result = mysqli_query($mysqli, $request))) {
			echo $request." : erreur : ".mysqli_error($mysqli)."\n";
		}
		mysqli_close($mysqli);
	}
	if ($_GET['effacer'] == "effacer" && $mysqli = connect_db("Rush00"))
	{
		foreach ($_GET as $key => $value) {
			if ($key > 0)
				$tokill = (str_replace("effacer" , "" ,$value));
		}
		if ($tokill)
		$request = "delete from users where id='".$tokill."';";
		if (!($result = mysqli_query($mysqli, $request))) {
			echo $request." : erreur : ".mysqli_error($mysqli)."\n";
		}
		mysqli_close($mysqli);
		header('Location: params_admin.php');
	}
	if ($_POST['users'] && $_POST['passwd'] && $_POST['submit'] == "OK")
	{
		if ($mysqli = connect_db("Rush00"))
		{
			$request = "select * from users where username='".$_POST['users']."';";
			if (!($result = mysqli_query($mysqli, $request))) {
				echo $request." : erreur : ".mysqli_error($mysqli)."\n";
			}
			else {
				$string = mysqli_fetch_all($result);
			}
			if ($string['0']['1'] != $_POST['users'] && $string[0]['2'] != $_POST['passwd'])
			{
				$passwd = hash('whirlpool', $_POST['passwd']);
				$request = "insert INTO users (username, pwd) VALUES ('".$_POST['users']."', '".$passwd."')";
				if (!($result = mysqli_query($mysqli, $request))) {
					echo $request." : erreur : ".mysqli_error($mysqli)."\n";
				}
				else
				{
					$request = "select * from users where username='".$_POST['users']."';";
					if (!($result = mysqli_query($mysqli, $request)))
					{
						echo $request." : erreur : ".mysqli_error($mysqli)."\n";
					}
					else
					{
						$string = mysqli_fetch_all($result);
					}
					$_SESSION['login'] = $string['0']['0'];
					if ($_SESSION['basket'])
					{
						foreach ($_SESSION['basket'] as $key => $value) {
							$request = "insert INTO basket VALUES (".$value['0'].", ".$value['1'].", ".$_SESSION['login'].");";
							if (!($result = mysqli_query($mysqli, $request))) {
									echo $request." : erreur : ".mysqli_error($mysqli)."\n";
						}
					}
				}
				header('Location: params_admin.php');
				}
			}
			else{
				echo ("Ce compte existe deja");
				mysqli_close($mysqli);}
		}
	}
	else if ($_POST['submit'] != "OK")
	{
	}
	else
	{
		echo ("Vous devez remplir les cases");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="main.css"/>
		<meta charset="utf-8">
		<title>Admin</title>
	</head>
	<body>
		<div id="title">
			<h1>Super Admin Page</h1>
		</div>
		<h2>Gestion utilisateurs</h2>
		<div>
			<?php if ($mysqli = connect_db("Rush00"))
			{
				$request = "select * from users;";
				if (!($result = mysqli_query($mysqli, $request)))
					echo $request." : erreur : ".mysqli_error($mysqli)."\n";
				else
				{
					$string = mysqli_fetch_all($result);
					foreach ($string as $key => $value) {
						if($key != 0)
						{
							echo $value[1].'<form action="params_admin.php" method="get">
							<input type="submit" name="effacer" value="effacer">
							<input type"hidden" name="'.($value[0]).'" maxlength="0" value="'.($value[0]).'">';
							echo '<hr style="width:200px; margin-left: 0px;"></form>';
						}
					}
				}
			}?>
		</div>
		<h2>Ajout d'utilisateur</h2>
		<form action="params_admin.php" method="post">
			<p>Nom d'utilisateur : <input type="text" name="users" /></p>
			<p>Mot de passe : <input type="password" name="passwd" value=""/></p>
			<p><input type="submit" name="submit" value="OK"></p>
		</form>
		<h2>Gestion base de donnee</h2>
		<h2>Gestion commandes clients</h2>
		<?php
			if ($mysqli = connect_db("Rush00"))
			{
				$request = "select * from basketa;";
				if (!($result = mysqli_query($mysqli, $request)))
					echo $request." : erreur : ".mysqli_error($mysqli)."\n";
				else
				{
					$string = mysqli_fetch_all($result);
					foreach ($string as $value) {
						$request = "select username from users where id=".$value[2].";";
						if (!($result = mysqli_query($mysqli, $request)))
							echo $request." : erreur : ".mysqli_error($mysqli)."\n";
						else
						{
							$yolostring = mysqli_fetch_all($result);
							echo "user=".$yolostring[0][0].": ";
						}
						$request = "select name from product where id_product=".$value[0].";";
						if (!($result = mysqli_query($mysqli, $request)))
							echo $request." : erreur : ".mysqli_error($mysqli)."\n";
						else
						{
							$yolostring = mysqli_fetch_all($result);
							echo "product=".$yolostring[0][0].": number=".$value[1]."<br/>";
						}
					}
					echo '<form action="params_admin.php" method="GET">
						<p><input type="submit" name="effacer_factures" value="effacer factures"></p>
					</form>';
				}
			}
		 ?>
		<footer>
			<a href="index.php">
				<img style="height:50px; margin-left:50%; margin-top:30%" src="http://playbd24.mobie.in/icon/home.png"/>
			</a>
		</footer>
	</body>
</html>
