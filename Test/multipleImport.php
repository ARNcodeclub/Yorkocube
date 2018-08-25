<?php

function reArrayFiles($file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

if (isset($_POST['Envoyer'])) {
    $file_ary = reArrayFiles($_FILES['upload']);

    foreach ($file_ary as $file) {
        print 'File Name: ' . $file['name'] . '<br />';
        print 'File Type: ' . $file['type'] . '<br />';
        print 'File Size: ' . $file['size'] . '<br />';
    }
}
?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <form action="" method="post" enctype="multipart/form-data">
      <input type="file" name="upload[]" multiple>
      <input type="submit" name="Envoyer" value="Envoyer">
    </form>
  </body>
</html>
