<?php
try{
	$bdd = new PDO('mysql:host=127.0.0.1;dbname=york3', 'root', 'Azertyuiop42');
}catch (Exception $e){
  	die('Erreur : ' . $e->getMessage());
}
?>
