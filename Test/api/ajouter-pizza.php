<?php
  include 'header.php';

  if (!empty($_POST['nom']) && !empty($_POST['contenu']) && !empty($_POST['description']) && !empty($_POST['prix'])) {

    if ($_POST['prix'] >= 5) {
      $nom = htmlspecialchars($_POST['nom']);
      $contenu = htmlspecialchars($_POST['contenu']);
      $description = htmlspecialchars($_POST['description']);
      $prix = $_POST['prix'];
      $reqPizza = $bdd->prepare('INSERT INTO carte(nom, contenu, description, prix) VALUES(?,?,?,?)');
      $reqPizza->execute(array($nom, $contenu, $description, $prix));
      $retour['success'] = true;
      $retour['message'] = "La pizza a été ajoutée";
      $retour['results'] = array();
    }else{
      $retour['success'] = false;
      $retour['message'] = "Le prix doit être supérieur ou égal à 5€";
    }
  }else{
    $retour['success'] = false;
    $retour['message'] = "Pas assez d'informations";
  }

  retourJSON(true, $retour['message']);
  header('Location: retour.php?msg=' . $retour['message']);
?>
