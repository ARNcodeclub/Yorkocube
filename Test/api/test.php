<?php

header('Content-type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
// var_dump($request);
// $input = json_decode(file_get_contents('php://input'),true);
$bdd = new PDO('mysql:host=127.0.0.1;dbname=api;', 'root', 'Azertyuiop42');

$table = preg_replace('/[^a-z0-9_]+/i','',array_shift($request));
$key = array_shift($request)+0;
error_log("Table : " . $table);
// $columns = preg_replace('/[^a-z0-9_]+/i','',array_keys($input));
// $values = array_map(function ($value) use ($link) {
//   if ($value===null) return null;
//   return mysqli_real_escape_string($link,(string)$value);
// },array_values($input));

$set = '';
// for ($i=0;$i<count($columns);$i++) {
//   $set.=($i>0?',':'').'`'.$columns[$i].'`=';
//   $set.=($values[$i]===null?'NULL':'"'.$values[$i].'"');
// }

switch ($method) {
  case 'GET':
    $sql = "SELECT * from $table";
    break;
  case 'PUT':
    $sql = "UPDATE $table SET $set WHERE id=$key";
    break;
  case 'POST':
    $sql = "INSERT into $table SET $set";
    break;
  case 'DELETE':
    $sql = "DELETE $table WHERE id=$key";
    break;
}

$req = $bdd->prepare($sql);
$req->execute();

json_encode($req->fetchAll());

?>
