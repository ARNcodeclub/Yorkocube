<pre>
<?php

$array = array(
  "Nom" => "Scepoli",
  "Prénom" => "Zandies",
  "Age" => 16,
  "Classe" => "5F"
);
// header('Content-Type: application/json');
$arrayjson =  json_encode($array);
print_r(json_decode($arrayjson));
?>
</pre>
