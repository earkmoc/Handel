<?php

//Z|Zerowe|PlikPHP('Tabela_SQL.php','','select ID from tabele where NAZWA=`towary`;select [0], WARUNKI from tabeles where ID_TABELE=[0] and ID_OSOBY=osoba_id limit 1;update tabeles SET WARUNKI=if(`[1]`<>``,concat(WARUNKI,` and STAN<>0`),`STAN<>0`) where ID_TABELE=[0] and ID_OSOBY=osoba_id limit 1')||Filtr na stany zerowe/niezerowe w magazynie (prze³±cznik)|
//require('skladuj_zmienne.php');
require('towary_kolumny.php');

$ipole=(($ipole<0)?(-$ipole):$ipole);

if ($_POST['idtab']==642) {
   $w=mysql_query("select ID_T from spec where ID=$ipole");
   if ($r=mysql_fetch_row($w)) {
   	$ipole=$r[0];
   }
}
$w=mysql_query("select INDEKS, NAZWA, DOSTAWCA from towary where ID=$ipole");
if ($r=mysql_fetch_row($w)) {
	
	$indeks=$r[0];
	$nazwa=$r[1];
	$dostawca=$r[2];
	
	$_POST['INDEKS']=$indeks;
	$c=$kolumna_indeks;

	$_POST['opole']='S';
	$_POST['tabela']=$natab;
	$_POST['tabelaa']=$natab;
	$_POST['c']=$c;
	$_POST['ss']='STAN <> 0';

	$w=mysql_query("select ID from tabele where NAZWA='$natab'");
	if ($r=mysql_fetch_row($w)) {
		$_POST['idtab']=$r[0];

		$w=mysql_query("select WARUNKI, SORTOWANIE from tabeles where ID_OSOBY=$ido and ID_TABELE=".$r[0]);
		if ($r=mysql_fetch_row($w)) {
			if (strpos($r[0],'TAN <> 0')>0) {
				$_POST['ss']='';				//wy³±cz filtr na zerowe
			}
			if (strpos($r[1],'owary.NAZWA')==1) {
				$_POST['NAZWA']=$nazwa;
				$_POST['c']=$kolumna_nazwa;
			}
			if (strpos($r[1],'owary.DOSTAWCA')==1) {
				$_POST['DOSTAWCA']=$dostawca;
				$_POST['NAZWA']=$nazwa;
				$_POST['c']=$kolumna_dostawca;
				$_POST['cc']=$kolumna_nazwa;
			}
		}
	}

	require("Tabela_Szukaj_Zapisz.php");
}
