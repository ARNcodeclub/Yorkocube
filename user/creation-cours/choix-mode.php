<?php
session_start();
include '../a-connect.php';
include '../a-function.php';
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
    <link href="../../templates/css/profil.css">
    <link href="../../templates/css/header.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
		<link href="../../templates/css/responsive.css" rel="stylesheet">
    <link href="../../templates/css/fontawesome-all.css" rel="stylesheet">
    <link href="../../templates/css/all.css" rel="stylesheet">
    <link href="../../templates/css/creation-cours.css" rel="stylesheet">
    <title>Mode de rédaction</title>
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
        <?php if ($admin == true): ?>
          <a href="edition-profil.php?id=<?= $id ?>" class="link_button" id="profil-link-modif"><i class="fas fa-cog" id="modif_button"></i><p>Editer profil</p></a>
          <a href="deco.php" class="link_button" id="profil-link-deco"><i class="fas fa-power-off" id="deco_button"></i><p>Se déconnecter</p></a>
        <?php else: ?>
          <?php if (isset($_SESSION['pdp']) AND $_SESSION['pdp'] != "user.svg"): ?>
            <a href="../profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><img src="../../templates/images/avatars/<?= $_SESSION['pdp'] ?>" id="profil-link-avatar"></i><p><?= $_SESSION['pseudo'] ?></p></a>
          <?php else: ?>
            <a href="../profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><i class="fas fa-user-circle" id="profil-link-icon"></i><p><?= $_SESSION['pseudo'] ?></p></a>
          <?php endif; ?>
        <?php endif; ?>
      </nav>
		</header>
    <div id="choix-redaction">
      <a class="choix-mode" href="importer-cours.php" id="ecrire-cours">Importer<i class="fas fa-file"></i></a>
      <a class="choix-mode" href="ecrire-cours.php" id="importer-cours">Ecrire le cours<i class="fa fa-pencil-alt"></i></a>
    </div>
  </body>
</html>
