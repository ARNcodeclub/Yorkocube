<?php
session_start();
include '../../user/a-connect.php';
if (isset($_POST['submit'])) {
  $msg = htmlspecialchars($_POST['comment']);
  if (!empty($msg)) {
    $reqUser = $bdd->prepare('SELECT * FROM membre WHERE id = ?');
    $reqUser->execute(array($_SESSION['id']));
    $infoUser = $reqUser->fetch();
    $date = date("d M Y\, H\hi");
    $reqInsertComment = $bdd->prepare('INSERT INTO commentaires(auteur, contenu, date, id_tuto, id_auteur) VALUES(?,?,?,?,?) ');
    $reqInsertComment->execute(array($infoUser['pseudo'], $msg, $date, 1, $infoUser['id']));
    $reussi = True;
  }else{
    $erreur = "Vous n'avez pas écris de commentaire. Manque d'inspiration ?";
  }
}
$reqNumberComments = $bdd->prepare('SELECT COUNT(id) as nbre_comment FROM commentaires WHERE id_tuto = ?');
$reqNumberComments->execute(array(1));
$reqNumberComments = $reqNumberComments->fetch();
$nbreComments = $reqNumberComments['nbre_comment'];

$reqComments = $bdd->prepare("SELECT * FROM commentaires RIGHT JOIN membre ON commentaires.id_auteur = membre.id WHERE id_tuto=?");
$reqComments->execute(array(1));

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link href="../../templates/css/cours.css" rel="stylesheet">
        <link href="../../templates/css/header.css" rel="stylesheet">
        <link href="../../templates/css/utils.css" rel="stylesheet">
        <link href="../../templates/css/responsive.css" rel="stylesheet">
        <link href="../../templates/css/fontawesome-all.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Crete+Round' rel="stylesheet">
        <link rel="icon" href="../../templates/images/york.ico" />
        <script src="../../templates/js/jquery.js"></script>
        <script src="../../templates/js/script.js"></script>
        <title>York3</title>
    </head>

    <body>
      <header>
        <a href="../../index.php" id="link_title"><img src="../../templates/images/York_Logo.png" /></a>
        <nav>
          <?php if (isset($_SESSION['id'])): ?>
            <?php if (isset($_SESSION['pdp']) AND $_SESSION['pdp'] != "user.svg"): ?>
              <a href="../../user/profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><img src="../../templates/images/avatars/<?= $_SESSION['pdp'] ?>" id="profil-link-avatar"></i><?= $_SESSION['pseudo'] ?></a>
            <?php else: ?>
              <a href="../../user/profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><i class="fas fa-user-circle" id="profil-link-icon"></i><?= $_SESSION['pseudo'] ?></a>
            <?php endif; ?>
          <?php else: ?>
            <a href="../../user/inscription.php" class="link_button" id="login_button">S'inscrire</a>
            <a href="../../user/connexion.php" class="link_button" id="connect_button">Se connecter</a>
          <?php endif; ?>
        </nav>
  		</header>
      <?php if(isset($_SESSION['id'])){ ?>
      <article>
        <div id="auteur">
          <img src="../../templates/images/author/Nicolas Xaborov.jpg" />
          <div id="auteur_info">
            <p id="auteur_info_nom">Nicolas Xaborov</p>
            <p id="auteur_info_descri">Eleve de 5éme à L'Athénée Royal de Nivelles I ❤ Math</p>
            <p id="auteur_info_time">Jui 6, 2018<i class="fas fa-comment-dots"></i><?= $nbreComments ?> commentaires</p>
          </div>
        </div>
        <div id="cours">
          <h1>Mathématique 1ère : Les nombres relatifs</h1>
          <section id="video">
            <video height="450px" controls poster="../../templates/images/Poster York3.png">
              <source src="../../templates/videos/algebre1.mp4" />
            </video>
          </section>
          <p>Les nombres relatifs sont des nombres de la vies de tous les jours, ils sont aussi bien positifs que négatifs</p><br />
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><br />
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div>
      </article>
      <div id="end_cours">
          <div id="commentaires-publie">
            <?php
            while ($donnees = $reqComments->fetch()) {?>
              <div class="commentaire">
              <img src="../../templates/images/avatars/<?= $donnees['pdp'] ?>">
              <div class="commentaire-message">
              <p class="commentaire-message-pseudo"><?= $donnees['auteur'] ?></p>
              <p><?= $donnees['contenu'] ?></p>
              <p class="commentaire-message-date"><?= $donnees['date'] ?></p>
              </div>
              </div>
            <?php
            }
            ?>
            <?php if ($nbreComments <= 0): ?>
              <p>Il n'y a pas de commentaires pour l'instant... Soyez le premier à en faire un !</p>
              <i class="fas fa-ellipsis-h"></i>
            <?php endif; ?>
          </div>
          <aside class="comments">
            <span class="divider"></span>
            <div id="ecrire-commentaires">
              <form action="" method="post">
                <i class="fas fa-comment" id="comment-icon"></i>
                <input type="text" id="input-commentaire" name="comment" placeholder="Ecrire un commentaire...">
                <button type="submit" name="submit" id="comment-button_validate">
                  <i class="fas fa-check" id="comment-icon_validate"></i>
                </button>
              </form>
              <?php if (isset($erreur)) { ?> <span class="tips-alert error"><i class="fas fa-circle"></i><?= $erreur ?></span><?php } ?>
              <?php if ($reussi) { ?> <span class="tips-alert reussi"><i class="fas fa-circle"></i>Commentaire ajouté !</span><?php } ?>
            </div>
        </aside>
      </div>
      <?php }else{ ?>
      <div id="non_connecte">
        <strong>Veuillez-vous <a href="../../../user/connexion.php">connecter</a> pour accéder aux cours</strong><br />
      </div>
      <?php } ?>
      <footer>
        Copyright (c) 2018 Copyright ARNCodeClub All Rights Reserved.
      </footer>
    </body>
</html>
