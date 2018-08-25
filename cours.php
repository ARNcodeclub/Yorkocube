<?php
session_start();
include 'user/a-connect.php';
include 'user/a-function.php';
$dossier = listDirectory();
if (!isset($_SESSION['id']) || !isset($_SESSION['pseudo'])) {
  header('Location: index.php?tipsAlert=Vous devez vous <a href="user/connexion.php">connecter</a> pour avoir accès au cours');
}
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
        <link href="templates/css/all.css" rel="stylesheet">
        <link rel="apple-touch-icon" href="templates/images/icon-apple.png"/>
        <script type="text/javascript">
        window.addEventListener('load', function(){
          document.getElementById('loader').style.display = 'none';
        });
        </script>
        <link href="templates/css/styles.css" rel="stylesheet">
        <link href="templates/css/cours.css" rel="stylesheet">
        <link href="templates/css/header.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
        <link href="templates/css/fontawesome-all.css" rel="stylesheet">
        <link href="templates/css/list-cours.css" rel="stylesheet">
        <link rel="icon" href="templates/images/york.ico" />
        <title>York3</title>
    </head>

    <body>
      <div id="loader">
      </div>
      <header>
        <a href="index.php" id="link_title"><img src="templates/images/York_Logo.png" /></a>
        <div id="div-banner-link">
          <a href="#" class="banner-link">Radio</a>
          <a href="#" class="banner-link">Journal</a>
          <a href="cours.php" class="banner-link">Cours</a>
        </div>
        <nav>
          <?php if (isset($_SESSION['id'])): ?>
            <?php if (isset($_SESSION['pdp']) AND $_SESSION['pdp'] != "user.svg"): ?>
              <a href="user/profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><img src="templates/images/avatars/<?= $_SESSION['pdp'] ?>" id="profil-link-avatar"></i><p><?= $_SESSION['pseudo'] ?></p></a>
            <?php else: ?>
              <a href="user/profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><i class="fas fa-user-circle" id="profil-link-icon"></i><p><?= $_SESSION['pseudo'] ?></p></a>
            <?php endif; ?>
          <?php else: ?>
            <a href="user/inscription.php" class="link_button" id="login_button">S'inscrire</a>
            <a href="user/connexion.php" class="link_button" id="connect_button">Se connecter</a>
          <?php endif; ?>
        </nav>
        <!-- Barre de recherche de cours (au besoin) -->
        <form class="search-cours" action="user/recherche-cours.php" method="get">
          <input type="search" name="search" placeholder="Chercher un cours" autocomplete="off">
          <button type="submit" name="submit" id="search-button_validate">
            <i class="fas fa-search" id="search-icon_validate"></i>
          </button>
        </form>
      </header>
      <div class="center" id="list-cours">
        <section id="list-themes">
          <?php $i=0; foreach ($dossier as $key => $value): ?>
            <div class="theme">
              <a href="cours/<?= $dossier[$i] ?>/list-cours.php"><?= $value ?></a>
            </div>
          <?php $i++; endforeach; ?>
        </section>
      </div>
    </body>
</html>
