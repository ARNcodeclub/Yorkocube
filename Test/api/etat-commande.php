<?php
  include 'header.php';


  if (!empty($_POST['personne'])) {
      $nom = htmlspecialchars($_POST['personne']);
      $reqEtat = $bdd->prepare('SELECT etat FROM commande WHERE personne = ? ORDER BY id DESC LIMIT 1');
      $reqEtat->execute(array($nom));
      $retour['success'] = true;
      $retour['message'] = "La pizza a été commandée";
      $retour['results']['etat'] = $reqEtat->fetch();
  }else{
    $retour['success'] = false;
    $retour['message'] = "Pas assez d'informations";
  }

  retourJSON(true, $retour['message']);

?>
