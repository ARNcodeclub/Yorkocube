<?php
session_start();
include '../a-connect.php';
if (!empty($_SESSION['id'])) {
  $reqUser = $bdd->prepare('SELECT * FROM membre WHERE id = ?');
  $reqUser->execute(array($_SESSION['id']));
  $reqUser = $reqUser->fetch();
  if ($reqUser['admin']) {
    $reqCours = $bdd->prepare('SELECT * FROM cours ORDER BY theme DESC');
    $reqCours->execute(array());
    $reqMembres = $bdd->prepare('SELECT * FROM membre');
    $reqMembres->execute(array());
  }else{
    $pasAdmin = True;
  }
}else{
  $pasAdmin = True;
}

if (isset($_POST['setCours'])) {
  if (isset($_POST['cours']) && isset($_POST['action'])) {
    if ($_POST['action'] == "validerCours") {
      $reqUpdate = $bdd->prepare('UPDATE cours SET estValide = True WHERE titre = ?');
      $reqUpdate->execute(array($_POST['cours']));
      echo "Ok ! Tout c'est bien déroulé !";
    }
  }else{
    echo "Erreur";
  }
}

if (isset($_POST['setMembre'])) {
  if (isset($_POST['membre']) && isset($_POST['action'])) {
    if ($_POST['action'] == "updateAuthor") {
      $reqUpdate = $bdd->prepare('UPDATE membre SET estAuteur = True WHERE id = ?');
      $reqUpdate->execute(array($_POST['membre']));
      echo "Ok ! Tout c'est bien déroulé !";
    }
  }else{
    echo "Erreur";
  }
}

if (isset($pasAdmin)) {
  header('Location: /index.php?tipsAlert=Tu t\'es pris pour qui ?');
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Gestion administrative des cours</title>
    <link rel="stylesheet" href="../../templates/css/auth.css">
    <style>
      *{
        margin: 0;
        padding: 0;
      }
      html{
        height: 100%;
      }
      body{
        min-height: 100vh;
        display: flex;
        align-items: center;
        flex-direction: column;
      }
      #div{
        display: none;
      }
    </style>
  </head>
  <body>
    <h3>Bienvenue sur l'interface d'administration des différents cours présents sur York³</h3>
    <p>Cours existants</p>
    <form action="" method="post">
      <select name="cours">
        <?php $optGroup = "";
          while ($donnees = $reqCours->fetch()) {
            if ($donnees['theme'] != $optGroup) {
              $optGroup = $donnees['theme'];
              echo "<optgroup label='$optGroup'>";
            }
        ?>
          <option value="<?= $donnees['titre'] ?>"><?= $donnees['titre'] ?></option>
        <?php
          if ($optGroup != $donnees['theme']) {
            echo "</optgroup>";
          }
       } ?>
      </select>
      <p>Que voulez vous faire ?</p>
      <select name="action">
        <option disabled value="" selected>Rien</option>
        <option value="supprimer">Supprimer</option>
        <option value="desactiverCommentaire">Désactiver commentaires</option>
        <option value="activerCommentaire">Activer commentaires</option>
        <option value="validerCours">Valider le cours</option>
      </select><br />
      <input type="submit" name="setCours" value="Valider modifications sur cours">
    </form>

      <form action="" method="post">
        <select name="membre">
          <?php while ($donnees = $reqMembres->fetch()) { ?>
            <option value="<?= $donnees['id'] ?>"><?= $donnees['pseudo'] ?></option>
          <?php } ?>
        </select>
        <p>Que voulez vous faire ?</p>
        <select name="action">
          <option disabled value="" selected>Rien</option>
          <option disabled value="bannir">Bannir</option>
          <option value="updateAuthor">Promouvoir Auteur</option>
        </select><br>
        <input type="submit" name="setMembre" value="Valider modifications sur membre">
      </form>

      <a href="../profil.php?id=<?= $_SESSION['id'] ?>"><button type="button" name="button"><-Retour</button></a>
  </body>
</html>
