<?php
	session_start();
	include "db.php";
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
	if($_SESSION['login'] == "1")
	{
		header('Location: params_admin.php');
	}
	if ($_GET['submit'] == "DESTRUCTION" && $mysqli = connect_db("Rush00"))
	{
		$request = "delete from users where id='".$_SESSION['login']."';";
		if (!($result = mysqli_query($mysqli, $request))) {
			echo $request." : erreur : ".mysqli_error($mysqli)."\n";
		}
		$_SESSION['login'] = FALSE;
		mysqli_close($mysqli);
		header('Location: index.php');
	}
	elseif ($mysqli = connect_db("Rush00"))
	{
		$passwd = hash('whirlpool', $_GET["oldpw"]);
		$request = "select * from users where id='".$_SESSION['login']."';";
		if (!($result = mysqli_query($mysqli, $request))) {
			echo $request." : erreur : ".mysqli_error($mysqli)."\n";
		}
		else {
			$string = mysqli_fetch_all($result);
		}
		if ($passwd == $string['0']['2'])
		{
			$passwd = hash('whirlpool', $_GET["newpw"]);
			$request = "UPDATE users SET pwd='".$passwd."' where id='".$string['0']['0']."';";
			if (!($result = mysqli_query($mysqli, $request))) {
				echo $request." : erreur : ".mysqli_error($mysqli)."\n";
			}
			else
			{
				header('Location: index.php');
			}
		}
		mysqli_close($mysqli);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="main.css"/>
		<title>PARAM</title>
	</head>
	<body>

		<div id="title">
		<h1>Paramettres Personnels</h1>
	</div>
	<form>
		<form action="params.php" method="post">
			<p>Nom d'utilisateur : <input type="text" name="users" value="<? echo $string['0']['1']?>"/></p>
			<p>Ancien mot de passe : <input type="password" name="oldpw" value=""/></p>
			<p>Nouveau mot de passe : <input type="password" name="newpw" value=""/></p>
			<p><input type="submit" name="submit" value="OK"></p>
		</form>
	</form>
	<hr>
	<form>
		<form action="params.php" method="post">
			<p>Destruction du compte</p>
			<p><input type="submit" name="submit" value="DESTRUCTION"></p>
		</form>
	</form>
	<footer>
		<a href="index.php">
			<img style="height:50px; margin-left:50%; margin-top:30%" src="http://playbd24.mobie.in/icon/home.png"/>
		</a>
	</footer>
	</body>
</html>
