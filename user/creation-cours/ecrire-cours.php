<?php
session_cache_expire('private, must-revalidate');
session_start();
include '../a-connect.php';
include '../a-function.php';
if (isset($_SESSION['id'])) {
  if (isset($_POST['submitConnexion'])) {
    if (sha1($_POST['mdpConnexion']) == "b7743322967135224587dfc5e43ba8a613782f11") {
      $motDePasse = True;
    }else{
      $erreur = "Mauvais mot de passe !";
    }
  }
  $dossier = listDirectory("../../cours");
  if (isset($_POST['validation'])) {
    if (!empty($_POST['nom']) && !empty($_POST['theme'])) {
      $name = $_POST['nom'];
      $theme = $_POST['theme'];
      $sous_theme = NULL;
      if (!empty($_POST['sous-theme'])) {
        $sous_theme = $_POST['sous-theme'];
      }
      $pdp = "default_Empty.png";
      if (!empty($_POST['pdp'])) {
        $pdp = $_POST['pdp'];
      }
      $contenu = $_POST['contenu'];
      $bbcode = array('<p>[CODE]', '[/CODE]</p>');
      $bbcodeTrad = array('<div class="contenu_box-code"><pre>', '</pre></div>');
      $contenu = str_replace($bbcode, $bbcodeTrad, $contenu);
      $insertCours = $bdd->prepare('INSERT INTO cours(titre, contenu, auteur, id_auteur,theme, sous_theme, date, pdp) VALUES(?,?,?,?,?,?,?,?)');
      $insertCours->execute(array($name, $contenu, $_SESSION['pseudo'], $_SESSION['id'], $theme, $sous_theme, null, $pdp));

      $toutEstOk = True;

      $reqInfosAuteur = $bdd->prepare("SELECT * FROM auteur");
      $reqInfosAuteur->execute(array($_SESSION['id']));
      $infosAuteur = $reqInfosAuteur->fetch();

      $reqInfosUser = $bdd->prepare("SELECT * FROM membre WHERE id = ?");
      $reqInfosUser->execute(array($_SESSION['id']));
      $infosUser = $reqInfosUser->fetch();

      if ($infosUser['estAuteur'] != 1) {
        $insertAuteur = $bdd->prepare('INSERT INTO auteur(id_membre, pdp_auteur) VALUES(?,?)');
        $insertAuteur->execute(array($_SESSION['id'], $_SESSION['pdp']));
        $reqUpdate = $bdd->prepare('UPDATE membre SET estAuteur = True WHERE id = ?');
        $reqUpdate->execute(array($_SESSION['id']));
      }
    }else{
      $erreur = "Titre et theme doivent être completés !";
    }
  }
}else{
  $erreur="Vous devez être connecté pour pouvoir écrire un cours";
  header('Location: ../../index.php?tipsAlert=' . $erreur);
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
        <link href="../../templates/css/styles.css" rel="stylesheet">
        <link href="../../templates/css/cours.css" rel="stylesheet">
        <link href="../../templates/css/auth.css" rel="stylesheet">
        <link href="../../templates/css/header.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" />
        <link href="../../templates/css/all.css" rel="stylesheet">
        <link href="../../templates/css/utils.css" rel="stylesheet">
        <link href="../../templates/css/fontawesome-all.css" rel="stylesheet">
        <link rel="icon" href="../../templates/images/york.ico" />
        <link href='http://fonts.googleapis.com/css?family=Crete+Round' rel="stylesheet">
        <title>York3</title>
        <script src="../../templates/js/jquery.js"></script>
        <script src="../../templates/js/ckeditor/ckeditor.js"></script>
        <script type="text/javascript">
        window.addEventListener('load', function(){
          document.getElementById('loader').style.display = 'none';
        });
        </script>
    </head>

    <body>
      <div id="loader">
      </div>
      <header>
        <a href="../../index.php" id="link_title"><img src="../../templates/images/York_Logo.png" /></a>
        <nav>
          <?php if (isset($_SESSION['id'])): ?>
            <?php if (isset($_SESSION['pdp']) AND $_SESSION['pdp'] != "user.svg"): ?>
              <a href="../profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><img src="../../templates/images/avatars/<?= $_SESSION['pdp'] ?>" id="profil-link-avatar"></i><p><?= $_SESSION['pseudo'] ?></p></a>
            <?php else: ?>
              <a href="../profil.php?id=<?= $_SESSION['id'] ?>" id="profil-link"><i class="fas fa-user-circle" id="profil-link-icon"></i><p><?= $_SESSION['pseudo'] ?></p></a>
            <?php endif; ?>
          <?php else: ?>
            <a href="../inscription.php" class="link_button" id="login_button">S'inscrire</a>
            <a href="../connexion.php" class="link_button" id="connect_button">Se connecter</a>
          <?php endif; ?>
        </nav>
      </header>
      <div id="formulaire">
        <form action="" method="post">
          <label for="nom">Nom du cours<input type="text" id="nom" name="nom"></label><br />
          <label for="auteur">Auteur du cours<input type="text" disabled value="<?= $_SESSION['pseudo'] ?>" name="auteur"></label><br />
          <label for="theme">Theme du cours
            <select name="theme" id="theme">
              <?php foreach ($dossier as $key => $value): ?>
                <option value="<?= $value ?>"><?= $value ?></option>
              <?php endforeach; ?>
            </select>
          </label><br />
          <label for="sous-theme">Sous-theme du cours(facultatif)<input type="text" id="sous-theme" name="sous-theme"></label><br />
          <label for="contenu">Contenu:<br /><textarea name="contenu"></textarea></label><script>CKEDITOR.replace('contenu');</script>
          <p class="note">Pour ce qui est des images, vous pouvez directement en importer depuis Google (à l'aide de son URL) ou bien mettre dans votre texte le terme "[IMAGE]", ensuite dans le champ suivant vous pouvez importer les images apr lesquelles seront remplacés les "[IMAGE]"</p>
          <label for="bg-image">Image(qui est dans le dossier /templates/images/badges)<input type="text" id="bg-image" name="pdp"></label><br />
          <input type="submit" name="validation" value="Créer">
        </form>
      <?php if ($toutEstOk == True): ?>
        <span class="tips-alert reussi"><i class="fas fa-circle"></i>Cours envoyé, en attente de validation</span>
      <?php endif; ?>
    <?php if (isset($erreur)): ?>
      <span class="tips-alert error"><i class="fas fa-circle"></i><?= $erreur ?></span>
    <?php endif; ?>
  </div>
  </body>
</html>
