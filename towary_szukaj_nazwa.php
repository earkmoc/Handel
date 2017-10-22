<?php

//require('skladuj_zmienne.php');
require('towary_kolumny.php');

$ipole=(($ipole<0)?(-$ipole):$ipole);
$w=mysql_query("select NAZWA from towary where ID=$ipole");
if ($r=mysql_fetch_row($w)) {
	
	$_POST['NAZWA']=$r[0];
	$c=$kolumna_nazwa;

	$_POST['opole']='S';
	$_POST['tabela']=$natab;
	$_POST['tabelaa']=$natab;
	$_POST['c']=$c;

	$w=mysql_query("select ID from tabele where NAZWA='$natab'");
	if ($r=mysql_fetch_row($w)) {
		$_POST['idtab']=$r[0];

		$w=mysql_query("select WARUNKI from tabeles where ID_OSOBY=$ido and ID_TABELE=".$r[0]);
		if ($r=mysql_fetch_row($w)) {
			if (strpos($r[0],'TAN <> 0')>0) {
				$_POST['ss']='STAN <> 0';
			}
		}

	}

	require("Tabela_Szukaj_Zapisz.php");
}
