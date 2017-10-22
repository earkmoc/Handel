<?php

$sp=$_POST['sourpath'];
$sf=$_POST['sourfile'];
$se=$_POST['sourext'];

$dp=$_POST['destpath'];
$df=$_POST['destfile'];
$de=$_POST['destext'];

$z="$sp$sf$se";

if (!$content=file_get_contents($z)) {
	die('<font color="red"><b>PHP error: </b></font>'."file_get_contents('$z')<br>");
}

header('Location: http://77.254.127.90/Handel/Tabela.php?tabela=wzoryumow');

$dest="$dp$df$de";
file_put_contents($dest,str_replace('text_area','text_area',str_replace(chr(13),'',$_POST['content'])));

$dest="$sp$sf$se";
file_put_contents($dest,str_replace('text_area','text_area',str_replace(chr(13),'',$_POST['content'])));

$dest="$sp$sf.old";
file_put_contents($dest,str_replace('text_area','text_area',str_replace(chr(13),'',$content)));
//file_put_contents($dest,$content);

?>