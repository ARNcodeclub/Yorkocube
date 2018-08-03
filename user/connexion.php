<?php
session_start();
	include('a-connect.php');
	if (isset($_POST['formconnect']))
	{
		$nameconnect = htmlspecialchars($_POST['pseudo']);
		$mdpconnect = sha1($_POST['mdp']);
		if(!empty($nameconnect) AND !empty($mdpconnect))
		{
			$requser = $bdd->prepare('SELECT * FROM membre WHERE pseudo = ? AND mdp = ?');
			$requser->execute(array($nameconnect, $mdpconnect));
			$userexist = $requser->rowCount();
			if($userexist == 1)
			{
				$userinfo = $requser->fetch();
				$_SESSION['id'] = $userinfo['id'];
				$_SESSION['pseudo'] = $userinfo['pseudo'];
				$_SESSION['pdp'] = $userinfo['pdp'];
				header("Location: profil.php?id=" . $_SESSION['id']);
				}
			else
			{
				$erreur = "Mauvais pseudo ou mot de passe";
			}
		}
		else
		{
			$erreur = "Tous les champs doivent êtres completés";
		}

	} ?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no">
    <title>Inscription - Yorkocube</title>
    <link rel="stylesheet" href="../templates/css/styles.css">
		<link rel="stylesheet" href="../templates/css/auth.css">
		<link href="../templates/css/header.css" rel="stylesheet">
		<link href="../templates/css/responsive.css" rel="stylesheet">
		<link href="../templates/css/fontawesome-all.css" rel="stylesheet">
		<link href="../templates/css/all.css" rel="stylesheet">
  </head>
  <body>
		<header>
			<a href="../index.php" id="link_title"><img src="../templates/images/York_Logo.png" /></a>
			<div id="div-banner-link">
				<a href="#" class="banner-link">Radio</a>
				<a href="#" class="banner-link">Journal</a>
				<a href="../cours.php" class="banner-link">Cours</a>
			</div>
			<nav>
				<?php if (isset($_SESSION['id'])): ?>
					<?php if (isset($_SESSION['pdp']) AND $_SESSION['pdp'] != "user.svg"): ?>
						<a href="../../user/profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><img src="../../templates/images/avatars/<?= $_SESSION['pdp'] ?>" id="profil-link-avatar"></i><p><?= $_SESSION['pseudo'] ?></p></a>
					<?php else: ?>
						<a href="../../user/profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><i class="fas fa-user-circle" id="profil-link-icon"></i><p><?= $_SESSION['pseudo'] ?></p></a>
					<?php endif; ?>
				<?php else: ?>
					<a href="../../user/inscription.php" class="link_button" id="login_button">S'inscrire</a>
					<a href="../../user/connexion.php" class="link_button" id="connect_button">Se connecter</a>
				<?php endif; ?>
			</nav>
		</header>
		<br>
			<div id="formulaire">
				<h1>Connectez-vous</h1>
				<?php if (isset($erreur)) { ?>
					<p style="font-weight: bold; color: red; text-align:center"><?= $erreur ?></p>
				<?php } ?>
				<form action="" method="post">
					<label for="pseudo">Pseudo<br /><input id="pseudo" <?php if(isset($_SESSION['pseudo'])){ echo 'value="' . $_SESSION["pseudo"] . '"'; } ?> name="pseudo" type="text"></label>
					<label for="mdp">Mot de passe<br /><input id="mdp" <?php if(isset($_SESSION['mdp'])){ echo 'value="' . $_SESSION["mdp"] . '"'; } ?> name="mdp" type="password"></label>
					<input type="submit" name="formconnect" value="Se connecter">
				</form>
        <a href="inscription.php">S'inscrire</a>
			</div>
			<!-- <footer>
				<p>Copyright &copy; 2018. Tous droits réservés.</p>
				<p>Site web dévellopé par le FabLab de l'ARN.</p>
			</footer> -->
  </body>
