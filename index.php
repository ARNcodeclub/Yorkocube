<?php
session_start();
include 'user/a-function.php';
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
        <link href="templates/css/utils.css" rel="stylesheet">
        <link rel="apple-touch-icon" href="templates/images/icon-apple.png"/>
        <script type="text/javascript">
        window.addEventListener('load', function(){
          document.getElementById('loader').style.display = 'none';
        });
        </script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="templates/css/index.css" rel="stylesheet">
        <link href="templates/css/header.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
        <link href="templates/css/responsive.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Crete+Round' rel="stylesheet">
        <link rel="icon" href="templates/images/york.ico" />
        <link href="templates/css/fontawesome-all.css" rel="stylesheet">
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
  		</header>
      <div id="box1">
          <h1>Bienvenue sur Yorkocube</h1>
          <section id="video">
            <p>Site de partage de connaissances.</p>
            <video height="300" controls poster="templates/images/Poster York3.png">
              <source src="templates/videos/Explications York3.mp4" type="video/mp4">
            </video>
          </section>
      </div>
      <div id="box2">
        <ul>
          <li id="step-1">
            <i class="steps_icons fas fa-pencil-alt"></i>
            <h4>Cours</h4>
            <p>Suivez une multitude de cours du des tas de sujets divers et varié</p>
          </li>
          <li id="step-2">
            <i class="steps_icons fas fa-book"></i>
            <h4>Exercices</h4>
            <p>Exerce toi et envoie tes exercices, nous nous chargerons de les corriger ! </p>
          </li>
          <li id="step-3">
            <i class="steps_icons fas fa-rocket"></i>
            <h4>Partage</h4>
            <p>Si vous avez un problème, posez vos questions ! D'autres membres pourront vous répondre !</p>
          </li>
        </ul>
      </div>

      <div id="box3">
        <!-- Boite 1 contenat les infos sur le cours de prog -->
        <article style="background-image: url('templates/images/article-image-1.jpg');">
          <div class="infos_cours_texte">
            <h4>Programmation</h4>
            <p>Apprennez à développer un site web de A à Z ! (HTML/CSS,Javascript,...)</p>
            <a href="cours.php" class="button-2">Plus d'infos</a>
          </div>
          <!-- <img src="templates/images/article-image-1.jpg" class="infos_cours_images" alt=""> -->
        </article>
        <!-- Boite 2 contenat les infos sur le cours d'Anglais -->
        <article style="background-image: url('templates/images/article-image-2.jpg');">
          <div class="infos_cours_texte">
            <h4>Anglais</h4>
            <p>Apprennez le langue de Shakespeare de A à Z ! Devenez bilingue ! (ou en tout cas essayez)</p>
            <a href="cours.php" class="button-2">Plus d'infos</a>
          </div>
          <!-- <img src="templates/images/article-image-2.jpg" class="infos_cours_images" alt=""> -->
        </article>
      </div>
      <?php if (!isset($_SESSION['id'])):?>
      <div id="box5">
        <section id="auth">
          <a href="user/inscription.php"><button type="button" class="button_auth" name="button">S'inscrire</button></a>
          <a href="user/connexion.php"><button type="button" class="button_auth" name="button">Se connecter</button></a>
        </section>
      </div>
    <?php endif; ?>
      <div id="box4">
        <h2>L'équipe</h2>
        <div id="staff">
          <div class="staff_box" id="staff3">
            <img src="templates/images/staff-icon-3.png" alt="">
            <h3>Emma</h3>
            <p>Designer de talent ! Il ne lui faut pas grand chose pour vous dessinez un monde imaginaire</p>
          </div>
          <div class="staff_box" id="staff2">
            <img src="templates/images/staff-icon-2.png" alt="">
            <h3>Nicolas</h3>
            <p>Community Manager et Chef du projet "York3". Son charisme incroyable laisserais n'importe qui sans voix</p>
          </div>
          <div class="staff_box" id="staff1">
            <img src="templates/images/Zandies.png" style="height: 120px" alt="">
            <h3>Attilio</h3>
            <p>Le Développeur. Il vous code un site web en deux-trois coup de clavier</p>
          </div>
        </div>
      </div>

      <footer>
        <p>Copyright &copy; 2018. Tous droits réservés.</p>
    	   <p>Site web dévellopé par le FabLab de l'ARN.</p>
      </footer>

    </body>
</html>
