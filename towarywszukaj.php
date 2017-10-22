<?php

require('towary_kolumny.php');

$ipole=(($ipole<0)?(-$ipole):$ipole);
$w=mysql_query("select INDEKS from towary where ID=$ipole");
if ($r=mysql_fetch_row($w)) {
	
	$_POST['INDEKS']=$r[0];
	$c=$kolumna_indeks;

	$_POST['opole']='S';
	$_POST['tabela']=$natab;
	$_POST['tabelaa']=$natab;
	$_POST['c']=$c;

	$w=mysql_query("select ID from tabele where NAZWA='$natab'");
	if ($r=mysql_fetch_row($w)) {
		$_POST['idtab']=$r[0];
	}

//	require('skladuj_zmienne.php');
	require("Tabela_Szukaj_Zapisz.php");
}
