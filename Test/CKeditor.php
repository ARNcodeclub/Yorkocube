<?php
if (isset($_POST['Ok'])) {
  echo $_POST['editor1'];
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>CKEDITOR</title>
    <script src="https://cdn.ckeditor.com/4.10.0/standard/ckeditor.js"></script>
  </head>
  <body>
    <form action="" method="post">
    <textarea name="editor1"></textarea>
		<script>
			CKEDITOR.replace('editor1');
		</script>
    <input type="submit" name="Ok" value="Envoyer mon texte">
  </form>
  </body>
</html>
