<?php
session_start();
include '../../user/a-connect.php';
$reqCours = $bdd->prepare('SELECT * FROM cours WHERE theme="42" AND estValide = 1');
$reqCours->execute();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-title" content="York³">
		<meta name="mobile-web-app-capable" content="yes">
        <link href="../../templates/css/styles.css" rel="stylesheet">
        <link href="../../templates/css/cours.css" rel="stylesheet">
        <link href="../../templates/css/header.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet">
        <link href="../../templates/css/fontawesome-all.css" rel="stylesheet">
        <link href="../../templates/css/all.css" rel="stylesheet">
        <link href="../../templates/css/list-cours.css" rel="stylesheet">
        <link rel="icon" href="../../templates/images/york.ico" />
        <title>York3</title>
    </head>

    <body>
      <header>
        <a href="../../index.php" id="link_title"><img src="../../templates/images/York_Logo.png" /></a>
        <div id="div-banner-link">
          <a href="#" class="banner-link">Radio</a>
          <a href="#" class="banner-link">Journal</a>
          <a href="../../cours.php" class="banner-link">Cours</a>
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
      <div id="list-cours">

        <table>
        <?php $i = 0; while ($donnees = $reqCours->fetch()) { ?>
        <div class="card-cours">
          <img src="../../templates/images/badges/<?= $donnees['pdp'] ?>" alt="">
            <div class="card-cours-invisible">
              <h4><?= $donnees['titre'] ?></h4>
              <p><?= $donnees['descri'] ?></p>
              <a href="../cours.php?id=<?= $donnees['id'] ?>">Voir le cours</a>
            </div>
        </div>
        </table>
        <?php $i++; } ?>
        <?php if ($i == 0): ?>
          <div id="non_connecte">
            <strong>Il n'existe actuellement aucun cours sur le sujet...</strong><br />
          </div>
        <?php endif; ?>
      </div>
    </body>
</html>
