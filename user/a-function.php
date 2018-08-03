<?php
function listDirectory($dossier='cours', $checkFichier=False){

  # Fonction permettant de lister les dossiers/fichiers présent dans un autre dossier parent
  # Elle prend en paramètre le nom du dossier à analyser (string) et un boolean disant si oui on non on veut aussi les fichier
  $contenuDossier = array();
  if ($dossier = opendir($dossier)) {
    while (false !== ($fichier = readdir($dossier))) {
      if ($checkFichier == False) {
        if ($fichier != '.' && $fichier != '..' && strpos($fichier, '.') === False) {
          array_push($contenuDossier, $fichier);
        }
      }else{
        if ($fichier != '.' && $fichier != '..') {
          array_push($contenuDossier, $fichier);
        }
      }
    }
  }
  return $contenuDossier;
}

function tipsAlert($info, $color="#e74c3c"){
  if (!empty($info) && !empty($color)) {
    return '<span class="tips-alert"><i class="fas fa-circle" style="color:' . $color . '"></i>' . $info . '</span>';
  }elseif (!empty($info) && empty($color)) {
    return '<span class="tips-alert error"><i class="fas fa-circle"></i>' . $info . '</span>';
  }else{
    return '<span class="tips-alert info"><i class="fas fa-circle"></i>Oops</span>';
  }
}

if (isset($_GET["tipsAlert"])) {
  $message = $_GET['tipsAlert'];
  $erreur = tipsAlert($message);
}

?>
