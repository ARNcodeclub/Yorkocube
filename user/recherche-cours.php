<?php
session_start();
include 'a-connect.php';
if (isset($_GET['search']) && isset($_GET['submit']) && !empty($_GET['search'])) {
  $search = $_GET['search'];
  // echo "Vous avez cherché: " . $search . "<br />";
  $reqSearch = $bdd->prepare("SELECT * FROM cours WHERE titre or contenu LIKE ?");
  $reqSearch->execute(array('%' . $search . '%'));
  $nbreResult = $reqSearch->rowcount();
  if ($nbreResult === 0) {
    $nbreResult = "Aucun résultats pour votre recherche";
  }else{
    $nbreResult = "Nombre de résultats: " . $nbreResult;
  }
  // echo "Nombre de résultats: " . $nbreResult . "<br />";
}else{
  header('Location: ../cours.php');
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
        <link href="../templates/css/all.css" rel="stylesheet">
        <link href="../templates/css/cours.css" rel="stylesheet">
        <link href="../templates/css/list-cours.css" rel="stylesheet">
        <link href="../templates/css/utils.css" rel="stylesheet">
        <link rel="apple-touch-icon" href="../templates/images/icon-apple.png"/>
        <script type="text/javascript">
        window.addEventListener('load', function(){
          document.getElementById('loader').style.display = 'none';
        });
        </script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../templates/css/header.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
        <link href="../templates/css/responsive.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Crete+Round' rel="stylesheet">
        <link rel="icon" href="../templates/images/york.ico" />
        <link href="../templates/css/fontawesome-all.css" rel="stylesheet">
        <title>York3</title>
    </head>

    <body>
      <div id="loader">
      </div>
      <?php
        if (isset($erreur)) {
          echo $erreur;
        }
      ?>
      <header>
  			<a href="index.php" id="link_title"><img src="../templates/images/York_Logo.png" /></a>
  			<div id="div-banner-link">
  				<a href="#" class="banner-link">Radio</a>
  				<a href="#" class="banner-link">Journal</a>
  				<a href="../cours.php" class="banner-link">Cours</a>
  			</div>
  			<nav>
  				<?php if (isset($_SESSION['id'])): ?>
  					<?php if (isset($_SESSION['pdp']) AND $_SESSION['pdp'] != "user.svg"): ?>
  						<a href="profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><img src="../templates/images/avatars/<?= $_SESSION['pdp'] ?>" id="profil-link-avatar"></i><p><?= $_SESSION['pseudo'] ?></p></a>
  					<?php else: ?>
  						<a href="profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><i class="fas fa-user-circle" id="profil-link-icon"></i><p><?= $_SESSION['pseudo'] ?></p></a>
  					<?php endif; ?>
  				<?php else: ?>
  					<a href="inscription.php" class="link_button" id="login_button">S'inscrire</a>
  					<a href="connexion.php" class="link_button" id="connect_button">Se connecter</a>
  				<?php endif; ?>
  			</nav>
  		</header>

      <div id="box-result_search">
        <p>Vous avez cherché: <?= $search ?></p>
        <p><?= $nbreResult ?></p>
        <div id="box-result_search-box">
        <?php
          while ($donnees = $reqSearch->fetch()) {
            echo "<div class='box-result_search-box-link'><img src='../templates/images/badges/" . $donnees['pdp'] . "' />";
            echo "<a href='../cours/cours.php?id=" . $donnees['id'] . "'>" . $donnees['titre'] . "</a></div>";
          }
        ?>
        </div>
        <a href="../cours.php">Retour</a>
      </div>

    </body>
</html>
