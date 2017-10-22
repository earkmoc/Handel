<?php
//require_once('skladuj_zmienna.php');
//skladuj($z);
function skladuj($z) {
//$tmpfname=date('Y.m.d_H.i.s_').(1*microtime()).'.txt';
$tmpfname="Login".$_SESSION['osoba_id'].".htm";
$file=fopen($tmpfname,"w");
fputs($file,"$z");
fclose($file);
}
?>