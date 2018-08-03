<?php
session_start();

  include('a-connect.php');
  if(!isset($_SESSION['id'])){
    header('Location : connexion.php');
  }
    $getid = $_GET['id'];
    $id = $_SESSION['id'];
    if ($getid = $id) {
      $admin = true;
    }else{
      $admin = false;
    }
    if (isset($_POST['quitter'])) {
       header('Location: profil.php?id=' . $_SESSION['id']);
    }

     $mdpModifier = False;
     $pdpModifier = False;
     $requser = $bdd->prepare('SELECT * FROM membre WHERE id = ?');
     $requser->execute(array($_SESSION['id']));
     $user = $requser->fetch();

     // if (isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND $_POST['newpseudo'] != $user['pseudo']){
     //   $newpseudo = htmlspecialchars($_POST['newpseudo']);
     //   $insertpseudo = $bdd->prepare('UPDATE membre SET pseudo = ? WHERE id = ?');
     //   $insertpseudo->execute(array($newpseudo, $_SESSION['id']));
     //   header('Location: profil.php?id='. $_SESSION['id']);
     // }
     if (isset($_POST['modifier'])) {
       if (isset($_POST['mdp'])) {
         if (sha1($_POST['mdp']) == $user['mdp']) {
           if (isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) AND isset($_POST['newmdp2']) AND !empty($_POST['newmdp2']))
           {
             $mdp1 = sha1($_POST['newmdp1']);
             $mdp2 = sha1($_POST['newmdp2']);

             if ($mdp1 == $mdp2)
               {
                  $insertmdp = $bdd->prepare('UPDATE membre SET mdp = ? WHERE id = ?');
                  $insertmdp->execute(array($mdp1, $_SESSION['id']));
                  $mdpModifier = True;
               }
             else
               {
                  $erreur = "Vos 2 mot de passe ne correspondent pas";
               }
           }
        }else{
          $erreur = "Votre mot de passe est incorrect";
        }
      }

        if (isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name']))
       {
           $tailleMax = 2097152;
           $extensionValides = array('jpg', 'jpeg', 'gif', 'png', 'svg');

           if($_FILES['avatar']['size'] <= $tailleMax)
           {
              $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'] ,'.' ), 1));

              if(in_array($extensionUpload, $extensionValides))
              {
                 $chemin = "../templates/images/avatars/". $_SESSION['id'].".".$extensionUpload;
                 $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
                 if($resultat)
                 {
                   $selectOldPdp = $bdd->prepare('SELECT pdp FROM membre WHERE id = ?');
                   $selectOldPdp->execute(array($_SESSION['id']));
                   $OldPdp = $selectOldPdp->fetch();
                   $OldPdpExt = strtolower(substr(strrchr($OldPdp['pdp'] ,'.'), 1));
                    if ($OldPdpExt != $extensionUpload) {
                      unlink("../templates/images/avatars/" . $OldPdp['pdp']);
                    }

                    $updateAvatar = $bdd->prepare('UPDATE membre SET pdp = :avatar WHERE id = :id');
                    $updateAvatar->execute(array(
                       'avatar' => $_SESSION['id'] . "." . $extensionUpload,
                       'id' => $_SESSION['id']
                       ));
                   if ($user['estAuteur'] == 1) {
                     $updateAvatar = $bdd->prepare('UPDATE auteur SET pdp_auteur = ? WHERE id_membre = ?');
                     $updateAvatar->execute(array($_SESSION['id'] . "." . $extensionUpload, $_SESSION['id']));
                   }
                   $pdpModifier = True;
                   $_SESSION['pdp'] = $_SESSION['id'] . "." . $extensionUpload;
                 }
                 else
                 {
                    $erreur = "Erreur durant l'importation";
                 }
              }
              else
              {
                 $erreur = "Votre photo de profil doit être au format jpeg, jpg, png, gif ou svg";
              }
           }
           else
           {
              $erreur = "Votre photo de  profil ne doit pas dépasser 2Mo";
           }
       }
     }

     // En fonction de ce qui a été modifié, cela redirige vers la page profil.php avec comme tips-Alert le(s) messages correspondant
     if ($mdpModifier && $pdpModifier) {
       header('Location: profil.php?id='. $_SESSION['id'] . '&tipsAlert=Photo de profil et mot de passe mis à jour !');
     }elseif ($mdpModifier) {
       header('Location: profil.php?id='. $_SESSION['id'] . '&tipsAlert=Mot de passe mis à jour !');
     }elseif ($pdpModifier) {
       header('Location: profil.php?id='. $_SESSION['id'] . '&tipsAlert=Photo de profil mise à jour !');
     }


?>
<html>
  <head>
    <title>Edition profil</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="../templates/css/header.css" />
    <link rel="stylesheet" type="text/css" href="../templates/css/auth.css" />
    <link rel="stylesheet" type="text/css" href="../templates/css/all.css" />
    <link rel="stylesheet" type="text/css" href="../templates/css/utils.css" />
    <link rel="stylesheet" type="text/css" href="../templates/css/edition-profil.css" />
    <link rel="stylesheet" type="text/css" href="../templates/css/fontawesome-all.css" />
  </head>
    <body>
      <header>
        <a href="../index.php" id="link_title"><img src="../templates/images/York_Logo.png" /></a>
      </header>
      <div id="title-modif">
        <i class="fas fa-cogs"></i>
      </div>
      <form method="POST" action="" enctype="multipart/form-data">
        <div id="modif-box-input">
          <div class="modif-box" id="modif-mdp">
            <p>Modifier votre mot de passe</p>
            <label>Mot de passe actuel: </label>
            <input type="password" name="mdp" placeholder="Mot de passe"/> <br /><br />
            <label>Nouveau mot de passe: </label>
            <input type="password" name="newmdp1" placeholder="Mot de passe"/> <br /><br />
            <label>Confirmer mot de passe: </label>
            <input type="password" name="newmdp2" placeholder="Confirmation du mot de passe"/> <br /><br />
          </div>
          <div class="modif-box" id="modif-avatar">
            <p>Modifier votre avatar</p>
          <label>Avatar :</label>
          <?php if ($user["pdp"] != "default.png"): ?>
            <img src="../templates/images/avatars/<?= $user['pdp'] ?>" alt title="Votre photo actuelle" />
          <?php else: ?>
            <p>Votre photo de profil n'a pas été modifiée</p>
          <?php endif; ?>
            <input type="file" name="avatar" /><br /><br />
          </div>
        </div>
        <div class="modif-box" id="modif-valider">
          <input type="submit" name="modifier" id="submit_button" value="Mettre à jour mon profil">
        </div>
          <!-- <input id="quitter" type="submit" name="quitter" value="Retour"> -->
          <button type="submit" name="quitter"><a href="profil.php?id=<?= $_SESSION['id'] ?>"><i class="fas fa-chevron-left"></i></a></button>
      </form>
      <?php if (isset($erreur)) { ?> <span class="tips-alert error"><i class="fas fa-circle"></i><?= $erreur ?></span><?php } ?>
    </body>
</html>
