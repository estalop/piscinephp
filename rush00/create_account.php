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
				header('Location: index.php');
				}
			}
			else
				echo ("Ce compte existe deja");
				mysqli_close($mysqli);
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
		<link rel="stylesheet" href="create_account.css"/>
		<link rel="stylesheet" href="main.css"/>
		<meta charset="utf-8">
		<title>Nouveau compte</title>
	</head>
	<body>
		<div id="title">
			<h1>Register</h1>
		</div>
		<div id="register">
			<form action="create_account.php" method="post">
	 			<p>Nom d'utilisateur : <input type="text" name="users" /></p>
	 			<p>Mot de passe : <input type="password" name="passwd" value=""/></p>
	 			<p><input type="submit" name="submit" value="OK"></p>
			</form>
		</div>
		<footer>
			<a href="index.php">
				<img style="height:50px; margin-left:50%; margin-top:30%" src="http://playbd24.mobie.in/icon/home.png"/>
			</a>
		</footer>
	</body>
</html>
