<?php
session_start();
	if (isset($_SESSION['id']) && isset($_SESSION['pseudo']) && isset($_SESSION['pdp'])) {
		header('Location: profil.php?id=' . $_SESSION['id']);
	}
	include('a-connect.php');
	require_once('../templates/php/ImageManipulator.php');
	// PARTIE SELECTIONNANT L'ID MAXIMUM DANS LA BDD ET RAJOUTE 1 POUR AVOIR l'ID QUE L'USER AURA
	$reqId = $bdd->prepare('SELECT MAX(id) as max_id FROM membre');
	$reqId->execute();
	$reqId = $reqId->fetch(PDO::FETCH_ASSOC);
	$id = $reqId['max_id']+1;
	if (isset($_POST['forminscription']))
	{
		$rand = rand(1,5);
		$pseudo = htmlspecialchars($_POST['pseudo']);
		$mail = $_POST['mail'];
		$mdpClair = $_POST['mdp'];
		$mdp = sha1($_POST['mdp']);
		$mdp2 = sha1($_POST['mdp2']);
		$ok = $_POST['forminscription'];
		$sexe = $_POST['sexe'];
		if (!empty($_POST['pseudo']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2']) AND !empty($_POST['mail']))
		{
			$pseudolength = strlen($pseudo);
			if ($pseudolength <= 255)
			{
				$reqpseudo = $bdd->prepare("SELECT * FROM membre WHERE pseudo = ? ");
				$reqpseudo->execute(array($pseudo));
			  	$pseudoexist = $reqpseudo->rowCount();
				if ($pseudoexist == 0) {

							if ($mdp == $mdp2)
							{
								switch ($sexe) {
									case '1':
										$sexe ="Homme";
										break;
									case '2':
										$sexe = "Femme";
										break;
									default:
										$sexe = "Autre";
										break;
								}
									if (isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name'])) {
											$tailleMax = 2097152;
				 		         	$extensionValides = array('jpg', 'jpeg', 'gif', 'png', 'svg');
											// Je "coupe" le nom du fichier pour extraire l'extension
											$extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'] ,'.' ), 1));
											if ($_FILES['avatar']['size'] <= $tailleMax) {
												if (in_array($extensionUpload, $extensionValides)) {
													$chemin = "../templates/images/avatars";
			 		               	$fichier = $_FILES['avatar']['tmp_name'];
													$manipulator = new ImageManipulator($fichier);
									        $width  = $manipulator->getWidth();
									        $height = $manipulator->getHeight();
									        $centreX = round($width / 2);
									        $centreY = round($height / 2);
									        $x1 = $centreX - 300;
									        $y1 = $centreY - 300;
									        $x2 = $centreX + 300;
									        $y2 = $centreY + 300;
									        // $newImage = imagecrop($fichier, array($x1, $y1, 60, 60));
			 		               	$name = "$id" . "." . "$extensionUpload";
													// $manipulator->save($chemin. "/" . $name);
			 		               	$resultat = move_uploaded_file($fichier, "$chemin/$name");
													if ($resultat) {
														$pdp = $name;
														$allisok = True;
													}else{
														$erreur = "Erreur lors de l'importation";
													}
												}else{
													$erreur = "Votre photo de profil doit être au format jpg, jpeg, gif, png ou svg";
												}
											}else{
												$erreur = "Votre photo de profil ne peut dépasser les 2Mo";
											}
									}else{
										switch ($sexe) {
											case 'Homme':
												$pdp = "default/Gars_York3.png";
												break;
											case 'Femme':
												$pdp = "default/Fille_York3.png";
												break;
											default:
												$pdp = "default/user.svg";
												break;
										}
										$allisok = True;
									}
								if (isset($allisok)) {
									$insertmbr = $bdd->prepare('INSERT INTO membre(pseudo, mdp, mail, sexe, pdp) VALUES(?,?,?,?,?)');
									$insertmbr->execute(array($pseudo, $mdp, $mail, $sexe, $pdp));
									$requser = $bdd->prepare("SELECT * FROM membre WHERE pseudo = ? AND mdp = ?");
									$requser->execute(array($pseudo, $mdp));
									$userinfo = $requser->fetch();
									$_SESSION['id'] = $userinfo['id'];
									$_SESSION['pseudo'] = $userinfo['pseudo'];
									$_SESSION['mdp'] = $mdpClair;
									header("Location: connexion.php");
								}
							}
							else
							{
								$erreur = "Vos mot de passes ne correspondent pas";
							}
				}else{ $erreur = "Pseudo déja utilisé";}
			   }
			else
			{
				$erreur = "Votre pseudo est invalide";
			}
		}
		else
		{
			$erreur = "Tous les champs doivent êtres completés";
		}
	}

?>
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
						<a href="../user/profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><img src="../templates/images/avatars/<?= $_SESSION['pdp'] ?>" id="profil-link-avatar"></i><p><?= $_SESSION['pseudo'] ?></p></a>
					<?php else: ?>
						<a href="../user/profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><i class="fas fa-user-circle" id="profil-link-icon"></i><p><?= $_SESSION['pseudo'] ?></p></a>
					<?php endif; ?>
				<?php else: ?>
					<a href="../user/inscription.php" class="link_button" id="login_button">S'inscrire</a>
					<a href="../user/connexion.php" class="link_button" id="connect_button">Se connecter</a>
				<?php endif; ?>
			</nav>
		</header>
		<br>
			<div id="formulaire">
				<h1>Inscription</h1>
				<p>Inscrivez-vous maintenant !</p>
				<?php if (isset($erreur)) { ?>
					<p style="font-weight: bold; color: red; text-align:center"><?= $erreur ?></p>
				<?php } ?>
				<form action="" method="post" enctype="multipart/form-data">
					<label for="pseudo">Pseudo<br /><input id="pseudo" name="pseudo" type="text"></label>
					<label for="mdp">Mot de passe<br /><input id="mdp" name="mdp" type="password"></label>
					<label for="mdp2">Répétez mot de passe<br /><input id="mdp2" name="mdp2" type="password"></label>
					<label for="mail">E-mail<br /><input id="mail" name="mail" type="mail"></label>
					<label for="input-avatar">Avatar<br /><input type="file" id="input-avatar" name="avatar"/></label>
					<label for="sexe1"><input type="radio" id="sexe1" value="1" name="sexe" checked>Homme</label>
					<label for="sexe2"><input type="radio" id="sexe2" value="2" name="sexe">Femme</label>
					<label for="sexe3"><input type="radio" id="sexe3" value="3" name="sexe" onclick="alert('Serieux ?! Et ca se passe comment, dis ?')">Autre</label>
					<input type="submit" name="forminscription" value="S'inscrire">
				</form>
				<p>Déjà un compte ? <a href="connexion.php">Connectez-vous</a></p>
			</div>
			<!-- <footer>
				<p>Copyright &copy; 2018. Tous droits réservés.</p>
				<p>Site web dévellopé par le FabLab de l'ARN.</p>
			</footer> -->
  </body>
</html>
