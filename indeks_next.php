<?php 

session_start();
$ido=$_SESSION['osoba_id'];
$tabela='dokum';

require_once('funkcje.php');
require('dbconnect.inc');

$indeks=$_GET['indeks'];

if ((strlen($indeks)>4)&&(substr($indeks,4,1)<>'-')) {
    $indeks=substr($indeks,0,4).'-'.substr($indeks,4,4);
}

if (strlen($indeks)<=4) {
	$indeks=substr($indeks.'0000',0,4).'-0000';
} elseif (strlen($indeks)>4) {
	$indeks=substr($indeks.'0000',0,9);
}

$max=0;
do {
	$max++;
	$indeks=substr($indeks,0,4).'-'.substr('0000'.(substr($indeks,5,4)*1+1),-4,4);
	$q=("select count(*) from towary where STATUS<>'S' and INDEKS='$indeks'");
	$w=mysql_query($q);
	$r=mysql_fetch_row($w);
} while ($r[0]<>0||$max>9999);

echo $indeks;