<?php

//require('skladuj_zmienne.php');
require('towary_kolumny.php');

$ipole=(($ipole<0)?(-$ipole):$ipole);

if (($_POST['idtab']==642)||($_POST['idtab']==691)) {
   $w=mysql_query("select ID_T from spec where ID=$ipole");
   if ($r=mysql_fetch_row($w)) {
   	$ipole=$r[0];
   }
}
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

		$w=mysql_query("select WARUNKI from tabeles where ID_OSOBY=$ido and ID_TABELE=".$r[0]);
		if ($r=mysql_fetch_row($w)) {
			if (strpos($r[0],'TAN <> 0')>0) {
				$_POST['ss']='STAN <> 0';
			}
		}

	}

	require("Tabela_Szukaj_Zapisz.php");
}
