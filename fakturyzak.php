<?php
$wynik[0]=date('Y.m');
$wynik[1]=date('Y.m');
$wynik[7]=date('Y.m.d');
$wynik[8]=date('Y.m.d');
$wynik[9]=date('Y.m.d');
$wynik[10]=date('Y.m.d');
$wynik[11]='1';

$zaznaczone=$_POST['zaznaczone'];
$x=count(explode(',',$zaznaczone));

if (!$zaznaczone) {
	$wynik[12]="Brak wybranych typ�w op�at";
	$mybgcolor="red";}
elseif ($x==1) {
	$wynik[12]="$x typ op�at o identyfikatorze: ".$zaznaczone;}
elseif (2<=$x&&$x<=4) {
	$wynik[12]="$x typy op�at o identyfikatorach: ".$zaznaczone;}
else {
	$wynik[12]="$x typ�w op�at o identyfikatorach: ".$zaznaczone;}
?>
