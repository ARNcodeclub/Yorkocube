<?php
if (isset($_GET['id'])) {
  $getid = $_GET['id'];
}else{
  header('Location: ../cours.php');
}
// Selectionne le cours dans le bdd en fonction de l'id se trouvant dans l'URL
$reqCours = $bdd->prepare('SELECT * FROM cours WHERE id = ?');
$reqCours->execute(array($getid));
$cours = $reqCours->fetch();

// Selectionne l'id du dernier cours existant
$reqMaxCours = $bdd->query('SELECT max(id) AS max_id FROM cours');
// $reqMaxCours->execute(array());
$coursMax = $reqMaxCours->fetch();


// Si l'user a cliquer sur le bouton pour envoyer un commentaire
if (isset($_POST['submit'])) {
  $msg = htmlspecialchars($_POST['comment']);
  if (!empty($msg)) {
    $reqId = $bdd->prepare('SELECT max(id) as dernier_id FROM commentaires');
    $reqId->execute(array());
    $infoId = $reqId->fetch();
    $lastId = intval($infoId['dernier_id']) + 1;
    $date = date("d M Y\, H\hi");
    $reqInsertComment = $bdd->prepare('INSERT INTO commentaires(auteur, contenu, date, id_tuto, id_auteur, reponseComm) VALUES(?,?,?,?,?,?) ');
    $reqInsertComment->execute(array($_SESSION['pseudo'], $msg, $date, $getid, $_SESSION['id'], $lastId));
    $reussi = True;
  }else{
    $erreur = "Vous n'avez pas écrit de commentaire. Manque d'inspiration ?";
  }
}

// Sort les infos des commentaires (nombre, ...)
$reqNumberComments = $bdd->prepare('SELECT COUNT(id) as nbre_comment FROM commentaires WHERE id_tuto = ?');
$reqNumberComments->execute(array($getid));
$reqNumberComments = $reqNumberComments->fetch();
$nbreComments = $reqNumberComments['nbre_comment'];

// Sort tous les commentaires dont l'id du tuto est egal a l'id de la page
$reqComments = $bdd->prepare("SELECT * FROM membre INNER JOIN commentaires ON commentaires.id_auteur = membre.id WHERE commentaires.id_tuto = ? ORDER BY commentaires.reponseComm");
$reqComments->execute(array($getid));

// On récupère les infos concernant l'auteur_info
$reqInfosAuteur = $bdd->prepare("SELECT * FROM auteur RIGHT JOIN membre ON auteur.id_membre = membre.id WHERE membre.id = ?");
$reqInfosAuteur->execute(array($cours['id_auteur']));
$infosAuteur = $reqInfosAuteur->fetch();

if($infosAuteur['description'] != NULL){
  $descriAuteur = $infosAuteur['description'];
}else{
  $descriAuteur = "<em>" . $cours['auteur'] . " n'a pas encore écrit de description.</em>";
}

// Selectionne les cours que l'user suit (pour ensuite lui proposer de s'y inscrire si ce n'est pas déjà fait)
// $reqCoursSuivi = $bdd->prepare("SELECT * FROM cours_suivi INNER JOIN membre WHERE membre.id = ? AND cours_suivi.id_cours = ?");
// $reqCoursSuivi->execute(array($_SESSION['id'], $getid));
// $coursSuivi = array();
// while ($donnees = $reqCoursSuivi->fetch()) {
//   array_push($coursSuivi[$donnees['id_membre']] = $donnees['id_cours']);
// }

// Selectionne les cours que l'user à écrit et entre leurs id dans un tableau
$reqCommentsEcrit = $bdd->prepare("SELECT id FROM commentaires WHERE id_auteur = ?");
$reqCommentsEcrit->execute(array($_SESSION['id']));
$idCommentairesEcrits = array();
while ($donnees = $reqCommentsEcrit->fetch()) {
  array_push($idCommentairesEcrits, $donnees['id']);
}

// Si l'user à cliqué sur le bouton pour supprimer un commentaire, on le supprime
if (isset($_GET['supprimerCom']) && !empty($_GET['supprimerCom']) && $idComSupprime != $_GET['supprimerCom']) {
  $idComSupprime = $_GET['supprimerCom'];
  if (in_array($_GET['supprimerCom'], $idCommentairesEcrits)) {
    $reqRemoveComment = $bdd->prepare('DELETE FROM commentaires WHERE id = ?');
    $reqRemoveComment->execute(array($_GET['supprimerCom']));
    $erreur = "Commentaire supprimé";
    header('Location: ?id=' . $getid . '&tipsAlert=Commentaire supprimé');
  }else{
    $erreur = "Bien essayé, mais j'ai sécurisé ça ;)";
  }
}

// DEBUT DU MODULE REPONSE COMMENTAIRE //
/*
  Système qui gère les réponses aux commentaires
  Il fonctionne de la même facon que pour un commentaire normal sauf que la colonne "reponseComm" ne sera pas vide mais contiendra l'id du commentaire dont le message est la réponse
*/

// Si l'user à cliquer sur le bouton de validation pour envoyer sa réponse à un commentaire
if (isset($_POST['repondreCommentaireValider'])) {
    // Traitement des données reçue
    $idCommentaire = intval($_POST['idCommentaire']);
    $reponseCommentaire = htmlspecialchars($_POST['reponseCommentaire']);
    if (!empty($reponseCommentaire)) {
      $date = date("d M Y\, H\hi");
      // Envoi à la bdd
      $reqInsertAnswerComment = $bdd->prepare('INSERT INTO commentaires(auteur, contenu, date, id_tuto, id_auteur, reponseComm, reponseCommVrai) VALUES(?,?,?,?,?,?,?) ');
      $reqInsertAnswerComment->execute(array($_SESSION['pseudo'], $reponseCommentaire, $date, $getid, $_SESSION['id'], $idCommentaire, 1));
      $reussi = True;
      header('Location: cours.php?id=' . $getid . '&reussi=True');
    }else{
      $erreur = "Pourquoi cliquer sur 'Répondre' si vous n'avez rien à dire ?";
    }
}
// FIN DU MODULE REPONSE COMMENTAIRE //

?>
