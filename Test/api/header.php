<?php
  header('Content-Type: application/json');

  // Connexion à ma bdd
  try {
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=api;', 'root', 'Azertyuiop42');
  } catch (\Exception $e) {
    $retour['success'] = false;
    $retour['message'] = "Connexion à la base de donnée impossible";
  }

  function retourJSON($status, $msg, $results=NULL){
    $retour['success'] = $status;
    $retour['message'] = $msg;
    $retour['results'] = $results;
    $retour['results'] = (array) $retour['results'];
    // error_log(gettype($retour['results']));
    echo json_encode($retour);
  }
?>
