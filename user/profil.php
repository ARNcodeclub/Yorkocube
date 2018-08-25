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
    // On selectionne les cours VALIDES fait par l'utilistateur
    $reqCours = $bdd->prepare("SELECT * FROM cours WHERE id_auteur = ? AND estValide = 1 ORDER BY theme DESC");
    $reqCours->execute(array($_SESSION['id']));
  }

  // On selectionne les derniers commentaires fait par l'utilistateur
  $reqLastsComments = $bdd->prepare("SELECT com.contenu, c.titre, c.id, com.id as idcom, c.theme FROM commentaires as com, cours as c WHERE com.id_auteur = ? AND com.id_tuto = c.id ORDER BY com.id DESC LIMIT 0,3");
  $reqLastsComments->execute(array($_SESSION['id']));


}else{
  header('Location: connexion.php');
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-title" content="York³">
		<meta name="mobile-web-app-capable" content="yes">
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
      <div id="profil-dashboard">

        <?php if ($estAuteur): ?>
        <div id="profil-dashboard-auteur" class="profil-dashboard-element">
          <p class="profil-dashboard-element_titre">Auteur</p>
              <?php if (!$descriEcrite): ?>
                <form action="" method="post" enctype="multipart/form-data">
                  <input name="description" placeholder="Description (250 caractères max)" maxlength="255"/>
                  <input type="submit" name="ecrireDescription" value="Valider ma description">
                  <p style="color:red">Attention, votre description ne pourra plus être modifiée</p>
                </form>
              <?php else: ?>
                <p>Description: <?= $infosAuteur['description']; ?></p>
              <?php endif; ?>
          <p class="profil-dashboard-element_sous-titre">Vos cours</p>
          <?php
          // while ($donnees = $reqCours->fetch()) {
          //   echo ";
          // }

          $optGroup = "";
            while ($donnees = $reqCours->fetch()) {
              if ($donnees['theme'] != $optGroup) {
                $optGroup = $donnees['theme'];
                echo "<p class='profil-dashboard-cours_theme'>" . $optGroup . "</p>";
              }
              echo "<a class='profil-dashboard-cours_titre' href='../cours/cours.php?id=" . $donnees['id'] . "'>" . $donnees['titre'] . "</a><br />";
            }
          if ($optGroup === "") {
            echo "<em>Aucuns de vos cours n'a encore été validé...</em>";
          }
          ?>
        </div>
      <?php endif; ?>

      <?php if ($infoUser['admin'] == 1): ?>
        <div id="profil-dashboard-admin" class="profil-dashboard-element">
          <p class="profil-dashboard-element_titre">Admin</p>
            <p>Donc, tu es admin... Et tant que tel tu as le droit d'accéder à la <a href="admin/gestion.php" style="text-decoration:underline;">page d'adminitration</a></p>
        </div>
      <?php endif; ?>
        <div class="profil-dashboard-element" id="profil-dashboard-commentaires">
          <p class="profil-dashboard-element_titre">Vos derniers commentaires</p>
          <?php $i=0;
          while ($donnees = $reqLastsComments->fetch()) {?>
            <div class="profil-dashboard-commentaires_contenu">
              <a href='../cours/cours.php?id=<?=$donnees['id']?>#commentaire<?= $donnees['idcom'] ?>'>"<?=$donnees['contenu']?>"</a>
              <a href='../cours/cours.php?id=<?=$donnees['id']?>'> dans <?=$donnees['theme']?> : <?=$donnees['titre']?></a>
            </div>
          <?php
            $i++;
          }
          if ($i === 0) {
            echo "<em>Vous n'avez encore jamais commenté, qu'est-ce que cous attendez ?!</em>";
          }
          ?>
        </div>
        <!-- <div class="profil-dashboard-element">
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div> -->
    </div>
      <?php if (isset($erreur)): ?>
        <?= $erreur ?>
      <?php endif; ?>
  </body>
</html>
