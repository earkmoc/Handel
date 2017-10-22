<?php

session_start();

$idtab=$_GET['idtab'];	// id tabeli
$id=$_GET['ID'];			// id pozycji
$r=$_GET['r'];
$c=$_GET['c'];
$str=$_GET['str'];

@ $db=mysql_connect('','guest','123');
if (!$db){
	echo 'B³±d: Po³±czenie z baz± danych nie powiod³o siê';
	exit;
}
mysql_select_db('handel');

$z='Select ID from tabeles where ID_OSOBY=';
$z.=$_SESSION['osoba_id'];
$z.=' and ID_TABELE=';
$z.=$idtab;

$w=mysql_query($z);
if ($w) {
	if (mysql_num_rows($w)>0) {

		$w=mysql_fetch_array($w);

		$z='Update tabeles';
		$z.=' set NR_STR=';
		$z.=$str;
		$z.=', NR_ROW=';
		$z.=$r;
		$z.=', NR_COL=';
		$z.=$c;
		$z.=', ID_POZYCJI=';
		$z.=$id;
		$z.=' where ID=';
		$z.=$w['ID'];
	}
	else {
		$z='Insert into tabeles (ID_OSOBY,ID_TABELE,ID_POZYCJI,NR_STR,NR_ROW,NR_COL) values (';
		$z.=$_SESSION['osoba_id'];
		$z.=',';
		$z.=$idtab;
		$z.=',';
		$z.=$id;
		$z.=',';
		$z.=$str;
		$z.=',';
		$z.=$r;
		$z.=',';
		$z.=$c;
		$z.=')';
	}
	$w=mysql_query($z);
	if ($w) {
	echo '<html><head><meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />';
	echo "<title>Praca w systemie zakoñczona</title></head><body bgcolor=white ";
	echo "<br  /><br  />Praca w systemie zakoñczona";
	echo '</body></html>';
	}
}
if (!$w) echo "$z<br  /><br  />niestety nie wysz³o !!!";
//mysql_free_result($w);
mysql_close($db);
?>