<?php
session_start();
include '../user/a-connect.php';
include 'coursTraitement.php';
include '../user/a-function.php';
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
        <link href="../templates/css/cours.css" rel="stylesheet">
        <link href="../templates/css/utils.css" rel="stylesheet">
        <link href="../templates/css/responsive.css" rel="stylesheet">
        <link href="../templates/css/fontawesome-all.css" rel="stylesheet">
        <link href="../templates/css/all.css" rel="stylesheet">
        <link href="../templates/css/header.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
        <link rel="icon" href="../templates/images/york.ico" />
        <script src="../templates/js/jquery.js"></script>
        <script src="../templates/js/script.js"></script>
        <script type="text/javascript">
          function showBox(param) {
           var allBoxes = document.getElementsByClassName('repondreCommentaireBox');
           var i = allBoxes.length;
           while (i--) {
             allBoxes[i].style.display = "none";
            }
           var button = document.getElementById('repondreCommentaire'+param);
           if (button.style.display == "none") {
              button.style.display = "flex";
           }else{
              button.style.display = "none";
           }
          }
        </script>
        <link href="../templates/css/all.css" rel="stylesheet">
        <title>York3</title>
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
              <a href="../user/profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><img src="../templates/images/avatars/<?= $_SESSION['pdp'] ?>" id="profil-link-avatar"></i><?= $_SESSION['pseudo'] ?></a>
            <?php else: ?>
              <a href="../user/profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><i class="fas fa-user-circle" id="profil-link-icon"></i><?= $_SESSION['pseudo'] ?></a>
            <?php endif; ?>
          <?php else: ?>
            <a href="../user/inscription.php" class="link_button" id="login_button">S'inscrire</a>
            <a href="../user/connexion.php" class="link_button" id="connect_button">Se connecter</a>
          <?php endif; ?>
        </nav>
  		</header>
      <?php if ($getid <= $coursMax['max_id']){ ?>
      <?php if ($cours['estValide'] == 1){ ?>
        <?php if(isset($_SESSION['id'])){ ?>
      <article>

        <div id="auteur">
          <img src="../templates/images/avatars/<?= $infosAuteur['pdp_auteur'] ?>" />
          <div id="auteur_info">
            <p id="auteur_info_nom"><?= $cours['auteur'] ?></p>
            <p id="auteur_info_descri"><?= $descriAuteur ?></p>
            <p id="auteur_info_time">Jui 6, 2018<i class="fas fa-comment-dots"></i><?= $nbreComments ?> commentaires</p>
          </div>
        </div>
        <div id="cours">
          <h1><?= $cours['titre'] ?></h1>
          <?= $cours['contenu'] ?>
        </div>
        <hr>
      </article>
      <div id="end_cours">
          <?php if ($cours['commentaireActive'] == 1): ?>
          <div id="commentaires-publie">
            <?php
            while ($donnees = $reqComments->fetch()) {?>
              <!-- Si l'user connecté est l'auteur du message, on lui propose de le supprimer -->
            <?php if ($donnees['reponseCommVrai'] != 0): ?>
              <div class="commentaire sous-commentaire" id="commentaire<?= $donnees['id'] ?>">
            <?php else: ?>
              <div class="commentaire" id="commentaire<?= $donnees['id'] ?>">
            <?php endif; ?>
                <?php if ($donnees['id_auteur'] == $_SESSION['id']): ?>
                  <a href="?id=<?= $getid ?>&supprimerCom=<?= $donnees['id'] ?>"><i class="fas fa-times erase-button" alt title="Supprimer le commentaire"></i></a>
                <?php endif; ?>
                <img src="../templates/images/avatars/<?= $donnees['pdp'] ?>">
                <div class="commentaire-message">
                  <p class="commentaire-message-pseudo"><?php echo $donnees['auteur']; if ($donnees['id_auteur'] == $cours['id_auteur']){ echo '<i class="fas fa-star commentaire-message-auteur" alt title="'. $donnees['auteur'].' est l\'auteur du cours"></i>';} // Check en même temps si le commenatire n'a pas été écrit par l'auteur du cours () ?></p>
                  <p><?= $donnees['contenu'] ?></p>
                  <p class="commentaire-message-date"><?= $donnees['date'] ?></p>
                </div>
                <?php if ($donnees['reponseCommVrai'] == 0): ?>
                  <p class="repondreCommentaireButton" onclick="showBox(<?= $donnees['id'] ?>)" id="repondreCommentaireButton">Répondre</p>
                <?php endif; ?>
              </div>
              <!-- Boc contenant le formulaire permettant de répondre à des commentaires (caché par défaut)  -->
              <?php if ($donnees['reponseCommVrai'] == 0): ?>
              <div class="repondreCommentaireBox" style="display: none;" id="repondreCommentaire<?= $donnees['id'] ?>">
                <form class="repondreCommentaireForm" action="" method="post">
                  <input type="text" name="idCommentaire" class="idCommentaire" value="<?= $donnees['id'] ?>">
                  <input type="reponseCommentaire" name="reponseCommentaire" id="reponseCommentaireInput" placeholder="Votre réponse">
                  <input type="submit" name="repondreCommentaireValider" value="Répondre">
                </form>
              </div>
              <?php endif; ?>
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
              <?php if (isset($reussi) && $reussi || $_GET ['reussi']) { ?> <span class="tips-alert reussi"><i class="fas fa-circle"></i>Commentaire ajouté !</span><?php } ?>
            </div>
          <?php else: ?>
            <span class="tips-alert info"><i class="fas fa-circle"></i>Les commentaires ont étés désactivés pour ce cours</span>
          <?php endif; ?>
        </aside>
      </div>
      <?php }else{ ?>
      <div id="non_connecte">
        <strong>Veuillez-vous <a href="../user/connexion.php">connecter</a> pour accéder aux cours</strong><br />
      </div>
    <?php } ?>
  <?php }else{ ?>
          <span id="cours-bloque"><i class="fas fa-lock"></i>Ce cours n'as pas encore été validé.</span>
    <?php } ?>
    <?php }else{ ?>
      <span id="cours-bloque"><i class="fas fa-question"></i>Beaucoup de mystères entourent ce cours... Existe-t'il ? Serait-ce une invention ? Personne ne le sait</span>
    <?php } ?>
      <footer>
        Copyright (c) 2018 Copyright ARNCodeClub All Rights Reserved.
      </footer>
    </body>
</html>
