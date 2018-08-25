<?php
  include 'header.php';


  if (!empty($_POST['personne']) && !empty($_POST['produit'])) {
      $nom = htmlspecialchars($_POST['personne']);
      $produit = htmlspecialchars($_POST['produit']);
      $date = date("d/m/Y H:i:s");
      $etat = "Envoi de la commande";
      $reqPizza = $bdd->prepare('INSERT INTO commande(personne, produit, date, etat) VALUES(?,?,?,?)');
      $reqPizza->execute(array($nom, $produit, $date, $etat));
      $retour['success'] = true;
      $retour['message'] = "La pizza a été commandée";
      $retour['results'] = array();
  }else{
    $retour['success'] = false;
    $retour['message'] = "Pas assez d'informations";
  }

  retourJSON(true, $retour['message']);

  header('Location: retour.php?msg=' . $retour['message']);
?>
