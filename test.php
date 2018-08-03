<?php
$dir = dirname(__FILE__); //path of the directory to read
$iterator = new RecursiveDirectoryIterator($dir);
foreach (new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST) as  $file) {
if (!$file->isFile()) { //create hyperlink if this is a folder
echo "<a href=". $file->getPath().">" . $file->getFilename() . "\</a><br />";
}else{ //do not link if this is a file
  $file->getFilename();
  }
}
?>
