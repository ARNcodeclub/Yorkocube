<?php
  include 'header.php';

  if (!empty($_POST['nom'])) {
    $search = $_POST['nom'];
    $reqPizza = $bdd->prepare('SELECT * FROM carte WHERE nom LIKE ?');
    $reqPizza->execute(array('%' . $search . '%'));
  }else if (!empty($_GET['nom'])){
    $search = $_GET['nom'];
    $reqPizza = $bdd->prepare('SELECT * FROM carte WHERE nom LIKE ?');
    $reqPizza->execute(array('%' . $search . '%'));
  }else{
    $reqPizza = $bdd->prepare('SELECT * FROM carte');
    $reqPizza->execute();
  }


  $i=1;
  while($donnees = $reqPizza->fetch()){
    $retour[$i]['id'] = $i;
    $retour[$i]['nom'] = $donnees['nom'];
    $contenu = preg_split("/[\s,]+/", $donnees['contenu']);
    $retour[$i]['contenu'] = $contenu;
    $retour[$i]['description'] = $donnees['description'];
    $i++;
  }
  $retour["nb"] = $i-1;

  retourJSON(true, "Menu", $retour);
?>
