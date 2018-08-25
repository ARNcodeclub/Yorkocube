<?php
session_start();
include 'a-connect.php';
include 'a-function.php';
require_once('../templates/php/ImageManipulator.php');

if (isset($_GET["tipsAlert"])) {
  $message = $_GET['tipsAlert'];
  $erreur = tipsAlert($message);
}

if (!empty($_SESSION['id'])) {
  $getid = $_GET['id'];
  $id = $_SESSION['id'];
  if ($getid = $id) {
    $admin = true;
  }else{
    $admin = false;
  }
  $reqUser = $bdd->prepare('SELECT * FROM membre WHERE id = ?');
  $reqUser->execute(array($getid));
  $infoUser = $reqUser->fetch();

  // On vérifie si la personne est un Auteur de cours
  if ($infoUser['estAuteur'] == 1) {
    $estAuteur = True;
    $reqInfosAuteur = $bdd->prepare("SELECT * FROM auteur RIGHT JOIN membre ON auteur.id_membre = membre.id WHERE membre.id = ?");
    $reqInfosAuteur->execute(array($getid));
    $infosAuteur = $reqInfosAuteur->fetch();
    if ($infosAuteur['description'] != NULL) {
      $descriEcrite = True;
    }else{
      $descriEcrite = False;
      if (isset($_POST['ecrireDescription'])) {
        $descri = substr(htmlspecialchars($_POST['description']), 0, 255);
        $updateDescri = $bdd->prepare('UPDATE auteur SET description = ? WHERE id_membre=?');
        $updateDescri->execute(array($descri, $id));
        header('Location: profil.php?id=' . $getid);
      }
    }
  }
}else{
  header('Location: connexion.php');
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <link rel="stylesheet" href="../templates/css/profil.css">
    <link href="../templates/css/header.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
    <link href="../templates/css/auth.css" rel="stylesheet">
    <link href="../templates/css/utils.css" rel="stylesheet">
		<link href="../templates/css/responsive.css" rel="stylesheet">
    <link href="../templates/css/fontawesome-all.css" rel="stylesheet">
    <link href="../templates/css/all.css" rel="stylesheet">
    <title>Profil de <?= $infoUser['pseudo'] ?></title>
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
        <?php if ($admin == true): ?>
          <a href="edition-profil.php?id=<?= $id ?>" class="link_button" id="profil-link-modif"><i class="fas fa-cog" id="modif_button"></i><p>Editer profil</p></a>
          <a href="deco.php" class="link_button" id="profil-link-deco"><i class="fas fa-power-off" id="deco_button"></i><p>Se déconnecter</p></a>
        <?php else: ?>
          <?php if (isset($_SESSION['pdp']) AND $_SESSION['pdp'] != "user.svg"): ?>
            <a href="user/profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><img src="../templates/images/avatars/<?= $_SESSION['pdp'] ?>" id="profil-link-avatar"></i><p><?= $_SESSION['pseudo'] ?></p></a>
          <?php else: ?>
            <a href="user/profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><i class="fas fa-user-circle" id="profil-link-icon"></i><p><?= $_SESSION['pseudo'] ?></p></a>
          <?php endif; ?>
        <?php endif; ?>
      </nav>
		</header>
      <div id="profil" class="element-profil">
        <h1><?= $infoUser['pseudo'] ?></h1>
        <img src="../templates/images/avatars/<?= $infoUser['pdp'] ?>" alt="Photo de profil">
      </div>
      <?php if ($estAuteur): ?>
        <fieldset>
          <legend>Auteur</legend>
          <?php if (!$descriEcrite): ?>
            <form action="" method="post" enctype="multipart/form-data">
              <textarea name="description" rows="4" cols="60" placeholder="Description (250 caractères maximum)" maxlength="255"></textarea>
              <input type="submit" name="ecrireDescription" value="Valider ma description">
              <p style="color:red">Attention, votre description ne pourra plus être modifiée</p>
            </form>
          <?php else: ?>
            <p>Description: <?= $infosAuteur['description']; ?></p>
          <?php endif; ?>
        </fieldset>
      <?php endif; ?>
      <?php if ($infoUser['admin'] == 1): ?>
        <p>Donc, tu es admin... Et tant que tel tu as le droit d'accéder à la <a href="admin/gestion.php">page d'adminitration</a></p>
      <?php endif; ?>
      <?php if (isset($erreur)): ?>
        <?= $erreur ?>
      <?php endif; ?>
  </body>
</html>
